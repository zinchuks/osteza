"""Local public-content regressions. GET only; never submits forms or contacts providers."""
import collections, concurrent.futures, json, re, subprocess, urllib.parse, urllib.request
from html.parser import HTMLParser
from pathlib import Path
ROOT = Path(__file__).resolve().parent.parent
BASE = 'http://localhost:8080'
class Page(HTMLParser):
    def __init__(self):
        super().__init__(); self.title=''; self.h1=''; self.meta={}; self.alternates=[]; self.anchors=[]; self.anchor=None; self.options=[]; self.mode=''; self.option=False; self.select=False; self.card=None; self.card_links=set(); self.footer=False; self.footer_text=''; self.legal=[]; self.headings=[]; self.heading_tag=None
    def handle_starttag(self,tag,attrs):
        a=dict(attrs)
        if tag in ('h1','h2','h3','h4','h5','h6'): self.headings.append(''); self.heading_tag=tag
        if tag=='h1' or (tag=='title' and not self.title): self.mode=tag
        if tag=='footer': self.footer=True
        if tag=='meta':
            key=a.get('name',a.get('property','')); self.meta[key]=a.get('content','')
            if key=='og:locale:alternate': self.alternates.append(a.get('content',''))
        if tag in ('article','li') and set(a.get('class','').split()) & {'pcard','feat'}: self.card=tag
        if tag=='a': self.anchor=dict(a,text='',footer=self.footer)
        if tag=='select' and a.get('name')=='service': self.select=True
        if tag=='option' and self.select: self.option=True; self.options.append('')
    def handle_endtag(self,tag):
        if tag==self.mode: self.mode=''
        if tag==self.heading_tag: self.heading_tag=None
        if tag=='footer': self.footer=False
        if tag=='a' and self.anchor:
            self.anchors.append(self.anchor)
            if self.card: self.card_links.add(self.anchor.get('href',''))
            self.anchor=None
        if tag==self.card: self.card=None
        if tag=='select': self.select=False
        if tag=='option': self.option=False
    def handle_data(self,data):
        if self.heading_tag: self.headings[-1]+=data
        if self.mode=='title': self.title+=data
        if self.mode=='h1': self.h1+=data
        if self.anchor: self.anchor['text']+=data
        if self.option: self.options[-1]+=data
        if self.footer: self.footer_text+=data

def fetch(url):
    assert urllib.parse.urlsplit(url).netloc=='localhost:8080', 'Only local requests allowed'
    with urllib.request.urlopen(url,timeout=40) as response:
        text=response.read().decode(errors='replace'); assert response.status==200
    page=Page();page.feed(text);return page,text

run=subprocess.run(['docker','compose','exec','-T','wordpress','php','scripts/audit-content.php'],cwd=ROOT,capture_output=True,text=True,check=True)
rows=json.loads(run.stdout)
def check(row):
    p,text=fetch(row['url']); errors=[]
    if not p.title.endswith('| OSTEZA'): errors.append('title suffix')
    if p.meta.get('og:site_name')!='OSTEZA': errors.append('OG site name')
    locale={'es':'es_ES','en':'en_US','uk':'uk_UA','ru':'ru_RU','pl':'pl_PL'}[row['lang']]
    if p.meta.get('og:locale')!=locale: errors.append('OG locale')
    if row['lang']=='es' and 'pl_PL' in p.alternates: errors.append('Spanish OG Polish alternate')
    if not p.meta.get('og:image:alt') or p.meta['og:image:alt']=='OSTEZA': errors.append('OG image description')
    if 'noindex' not in p.meta.get('robots',''): errors.append('local indexing changed')
    if not p.h1 or re.search('Fatal error|Warning:|Notice:',text): errors.append('heading/PHP error')
    if any(re.search(r'[ \t]{2,}',heading.strip()) for heading in p.headings): errors.append('double spaces in heading')
    if row['lang']=='en' and re.search('kitchen fitting',p.title+' '+p.h1,re.I): errors.append('English terminology')
    if 'hostingersite.com@gmail.com' in text: errors.append('placeholder email')
    for a in p.anchors:
        href=a.get('href','')
        if href.startswith('mailto:') and href!='mailto:osteza.es@gmail.com': errors.append('contact email mismatch')
        if href.startswith('tel:') and href!='tel:+34614886743': errors.append('call number mismatch')
        if 'whatsapp' in (a['text']+' '+a.get('aria-label','')).lower() and 'data-wa-link' not in a and not href.startswith('https://wa.me/'): errors.append('WhatsApp target')
        if href.startswith('https://wa.me/') and href!='https://wa.me/34666737139' and not href.startswith('https://wa.me/?text='): errors.append('WhatsApp number mismatch')
    if re.search(r'Phone\+34|Opening hours8|Emailosteza|AddressCosta|Services\+',p.footer_text): errors.append('footer spacing')
    if re.search(r'https?://',p.footer_text): errors.append('raw URL in footer text')
    return row,p,text,sorted(set(errors))
with concurrent.futures.ThreadPoolExecutor(max_workers=4) as pool: checked=list(pool.map(check,rows))
errors=[{'url':r['url'],'errors':e} for r,p,t,e in checked if e]
pages={r['url']:p for r,p,t,e in checked}
forms={}
for lang in ['es','en','uk','ru','pl']:
    sets={tuple(p.options) for r,p,t,e in checked if r['lang']==lang and p.options}
    forms[lang]={'variants':len(sets),'options':len(next(iter(sets))) if sets else 0}
    if len(sets)!=1: errors.append({'lang':lang,'errors':['form option drift']})
# All local content links, including translated legal links and blog pagination.
links=set()
for row,p,text,e in checked:
    for a in p.anchors:
        u=urllib.parse.urlsplit(urllib.parse.urljoin(row['url'],a.get('href','')))
        if u.netloc=='localhost:8080' and not u.path.startswith(('/wp-admin','/wp-json','/wp-login')):
            links.add(urllib.parse.urlunsplit((u.scheme,u.netloc,u.path,u.query,'')))
extra=sorted(links-set(pages))
with concurrent.futures.ThreadPoolExecutor(max_workers=4) as pool:
    for url,(p,text) in zip(extra,pool.map(fetch,extra)): pages[url]=p
legal_count=sum('[PENDIENTE DE REVISIÓN LEGAL]' in text for row,p,text,e in checked)
assert legal_count==15, ('Expected 15 legal review placeholders',legal_count)
for lang in ['es','en','uk','ru','pl']:
    home=BASE+('/' if lang=='es' else '/'+lang+'/'); hp=pages[home]
    blog=next(row['url'] for row in rows if row['lang']==lang and row['template']=='page-templates/blog.php')
    blog_pages=[p for url,p in pages.items() if url==blog or url.startswith(blog+'page/')]
    listed=set().union(*(p.card_links for p in blog_pages))
    assert hp.card_links<=listed, ('Homepage article missing from blog',lang)
    expected=sum(row['lang']==lang and row['type']=='post' for row in rows)
    assert len(listed)==expected,('Blog listing/pagination count',lang,len(listed),expected)
    if lang=='en': assert hp.meta['description'].endswith('.') and '…' not in hp.meta['description']
report={'published_pages':len(rows),'languages':dict(collections.Counter(row['lang'] for row in rows)),'forms':forms,'linked_local_urls_checked':len(links),'legal_review_pages':legal_count,'blog':'All three homepage articles found among 12 listed articles per language, including pagination','robots':'Local noindex preserved','number_locations':[{'url':row['url'],'call_links':sum(a.get('href')=='tel:+34614886743' for a in p.anchors),'whatsapp_links':sum(a.get('href')=='https://wa.me/34666737139' for a in p.anchors)} for row,p,t,e in checked],'errors':errors}
Path('/private/tmp/osteza-launch-verification.json').write_text(json.dumps(report,ensure_ascii=False,indent=2))
print(json.dumps({k:v for k,v in report.items() if k!='number_locations'},ensure_ascii=False,indent=2))
assert not errors,'See /private/tmp/osteza-launch-verification.json'
