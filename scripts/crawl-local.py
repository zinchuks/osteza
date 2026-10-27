"""Read-only crawl of published local content. No form submissions or external requests."""
import concurrent.futures, json, re, sys, urllib.request, urllib.parse
from html.parser import HTMLParser
class Page(HTMLParser):
    def __init__(self):
        super().__init__(); self.h1=0; self.links=[]; self.ids=set(); self.meta={}; self.can=[]; self.lang=''; self.visible=[]; self.skip=0
    def handle_starttag(self,tag,attrs):
        a=dict(attrs)
        if tag=='h1': self.h1+=1
        if a.get('id'): self.ids.add(a['id'])
        if tag=='a': self.links.append(a.get('href',''))
        if tag=='html': self.lang=a.get('lang','')
        if tag=='meta': self.meta[a.get('name',a.get('property',''))]=a.get('content','')
        if tag=='link' and a.get('rel')=='canonical': self.can.append(a.get('href'))
        if tag in ('script','style'): self.skip+=1
    def handle_endtag(self,tag):
        if tag in ('script','style'): self.skip=max(0,self.skip-1)
    def handle_data(self,data):
        if not self.skip: self.visible.append(data)
def crawl(row):
    url=row['url']
    if urllib.parse.urlparse(url).netloc!='localhost:8080': raise ValueError('Local URLs only')
    p=Page()
    try:
        with urllib.request.urlopen(url,timeout=40) as r: html=r.read().decode(); status=r.status
        p.feed(html)
        return dict(id=row['id'],url=url,status=status,h1=p.h1,lang=p.lang,description=p.meta.get('description',''),canonical=p.can,robots=p.meta.get('robots',''),hash_links=p.links.count('#'),missing_local_anchors=[x for x in p.links if x.startswith('#') and len(x)>1 and urllib.parse.unquote(x[1:]) not in p.ids],placeholders=re.findall(r'\[(?:CONFIRM[^\]]*|УТОЧ[^\]]*|Nombre|Name|Ім[^\]]*|Имя|Imię|TELÉFONO|EMAIL|DIRECCIÓN)\]',' '.join(p.visible)),php_errors=bool(re.search(r'(?:Fatal error|Warning:|Notice:)',html)))
    except Exception as e: return dict(id=row['id'],url=url,error=str(e))
rows=json.load(open(sys.argv[1]))
with concurrent.futures.ThreadPoolExecutor(max_workers=4) as pool: results=list(pool.map(crawl,rows))
json.dump(results,open(sys.argv[2],'w'),ensure_ascii=False,indent=2)
print(json.dumps(dict(pages=len(results),errors=[r for r in results if r.get('error') or r.get('php_errors') or r.get('h1')!=1 or not r.get('description')],missing_anchors=[dict(url=r['url'],anchors=r['missing_local_anchors']) for r in results if r.get('missing_local_anchors')]),ensure_ascii=False))
