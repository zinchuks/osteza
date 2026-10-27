"""Focused HTTP checks after launch-related code changes. Never prints response bodies."""
import json,urllib.request,urllib.error
from html.parser import HTMLParser
class Head(HTMLParser):
 def __init__(self): super().__init__();self.meta={};self.canonical=[];self.langs=[];self.jsonld=[];self.in_json=False
 def handle_starttag(self,t,attrs):
  a=dict(attrs)
  if t=='meta':self.meta[a.get('name',a.get('property',''))]=a.get('content','')
  if t=='link' and a.get('rel')=='canonical':self.canonical.append(a.get('href'))
  if t=='link' and a.get('hreflang'):self.langs.append(a['hreflang'])
  if t=='script' and a.get('type')=='application/ld+json':self.in_json=True
 def handle_endtag(self,t):
  if t=='script':self.in_json=False
 def handle_data(self,d):
  if self.in_json:self.jsonld.append(json.loads(d))
def get(path):
 try:
  with urllib.request.urlopen('http://localhost:8080'+path,timeout=30) as r:return r.status,r.read().decode(errors='replace')
 except urllib.error.HTTPError as e:return e.code,''
for path in ['/.env','/wp-config.php','/wp-content/uploads/security-probe.php','/wp-content/uploads/security-probe.phtml','/.git/config','/compose.yaml','/CLAUDE.md','/scripts/audit-content.php','/tests/launch-readiness.php','/docs/launch-readiness.md','/readme.html','/license.txt','/wp-config-sample.php','/xmlrpc.php','/wp-comments-post.php','/wp-trackback.php','/wp-json/wp/v2/users','/wp-sitemap-users-1.xml']:
 status,_=get(path);assert status in (403,404),(path,status);print('PASS private file blocked:',path,status)
for path in ['/','/en/','/uk/','/ru/','/pl/','/blog/page/2/','/proyectos/']:
 status,html=get(path);assert status==200,(path,status);p=Head();p.feed(html)
 assert len(p.canonical)==1,(path,p.canonical)
 if path=='/blog/page/2/':assert p.canonical[0].endswith(path),(path,p.canonical)
 assert p.meta.get('og:image'),path
 assert p.meta.get('og:url')==p.canonical[0],(path,'OG/canonical mismatch')
 assert p.jsonld,path
 assert 'noindex' in p.meta.get('robots',''),(path,'local indexing unexpectedly enabled')
 if path in ['/','/en/','/uk/','/ru/','/pl/']:assert 'x-default' in p.langs,(path,'x-default missing')
 print('PASS metadata:',path)
assert get('/osteza-test-page-does-not-exist/')[0]==404
print('PASS missing page returns HTTP 404')

request=urllib.request.Request('http://localhost:8080/wp-json/osteza/v1/chat?id=0')
try:
 urllib.request.urlopen(request,timeout=20)
 raise AssertionError('Anonymous history request unexpectedly succeeded')
except urllib.error.HTTPError as e:
 assert e.code==404,e.code
 assert 'no-store' in e.headers.get('Cache-Control',''),e.headers.get('Cache-Control')
 assert 'noindex' in e.headers.get('X-Robots-Tag','')
 print('PASS private REST errors are not cacheable')
with urllib.request.urlopen('http://localhost:8080/',timeout=20) as response:
 assert response.headers.get('X-Content-Type-Options')=='nosniff'
 assert response.headers.get('X-Frame-Options')=='SAMEORIGIN'
 assert response.headers.get('Referrer-Policy')=='strict-origin-when-cross-origin'
 assert 'geolocation=(self)' in response.headers.get('Permissions-Policy','')
 assert 'X-Powered-By' not in response.headers and "frame-ancestors 'self'" in response.headers.get('Content-Security-Policy','')
 print('PASS baseline security headers')
