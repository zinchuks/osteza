"""Compare installed files with public WordPress.org checksums. Does not modify files."""
from pathlib import Path
import hashlib,json,re,urllib.request,urllib.parse
root=Path(__file__).resolve().parent.parent
results=[]
core_version=re.search(r"\$wp_version = '([^']+)'",(root/'wp-includes/version.php').read_text())[1]
components=[('core',core_version,root)]
for slug,entry in [('polylang','polylang.php'),('ai-provider-for-openai','plugin.php'),('ai-provider-for-anthropic','plugin.php')]:
 base=root/'wp-content/plugins'/slug
 version=re.search(r'Version:\s+([0-9.]+)',(base/entry).read_text())[1]
 components.append((slug,version,base))
for slug,version,base in components:
 url='https://api.wordpress.org/core/checksums/1.0/?'+urllib.parse.urlencode({'version':version,'locale':'en_US'}) if slug=='core' else 'https://downloads.wordpress.org/plugin-checksums/'+slug+'/'+version+'.json'
 result={'component':slug,'version':version}
 try:
  with urllib.request.urlopen(url,timeout=20) as response:data=json.load(response)
  sums=data.get('checksums') if slug=='core' else data.get('files')
  if not isinstance(sums,dict) or not sums:raise ValueError('No authoritative checksums for this version')
  mismatches=[];missing=[];count=0
  for name,expected in sums.items():
   p=(base/name).resolve()
   if not p.is_relative_to(base.resolve()):raise ValueError('Invalid checksum path')
   if slug=='core' and (name.startswith('wp-content/') or name=='wp-config-sample.php'):continue
   if not p.is_file():missing.append(name);continue
   digest=expected if isinstance(expected,str) else expected.get('md5')
   if not digest:continue
   count+=1
   if hashlib.md5(p.read_bytes()).hexdigest()!=digest:mismatches.append(name)
  result.update(checked=count,mismatches=mismatches,missing=missing)
 except Exception as error:result['unverified']=str(error)
 results.append(result)
print(json.dumps(results,indent=2))
