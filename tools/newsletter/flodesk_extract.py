"""Print the text of a Flodesk web-view email as lightly marked-up plain text.

Usage: curl -sSL -A "Mozilla/5.0" -o <dir>/email.html "https://view.flodesk.com/emails/<id>"
       python3 -I tools/newsletter/flodesk_extract.py <dir>/email.html

Output: <title>, then the body in reading order with **bold**, _italic_, [link text](href),
[IMG src alt=...] markers and <br> for in-paragraph line breaks. Formatting markers can be
split oddly where Flodesk splits a word across tags (e.g. "E**in"): fix by hand.
"""
import sys, re, html
from html.parser import HTMLParser
src = open(sys.argv[1], encoding='utf-8').read()
m = re.search(r'<title>(.*?)</title>', src, re.S); print('TITLE:', html.unescape(m.group(1)) if m else None)
for k in ['og:title','og:description','description']:
    mm = re.search(r'<meta[^>]+(?:property|name)="%s"[^>]+content="([^"]*)"' % k, src)
    if mm: print(k.upper()+':', html.unescape(mm.group(1)))
class P(HTMLParser):
    def __init__(s): super().__init__(); s.out=[]; s.skip=0; s.href=[]
    def handle_starttag(s, t, a):
        a=dict(a)
        if t in ('style','script','head','title'): s.skip+=1
        if t=='img': s.out.append(f"\n[IMG {a.get('src')} alt={a.get('alt')!r}]\n")
        if t in ('p','div','tr','h1','h2','h3','li','table'): s.out.append('\n')
        if t=='br': s.out.append('<br>')
        if t in ('strong','b'): s.out.append('**')
        if t in ('em','i'): s.out.append('_')
        if t=='a': s.href.append(a.get('href')); s.out.append('[')
    def handle_endtag(s, t):
        if t in ('style','script','head','title'): s.skip-=1
        if t in ('strong','b'): s.out.append('**')
        if t in ('em','i'): s.out.append('_')
        if t=='a': s.out.append(f"]({s.href.pop() if s.href else ''})")
        if t in ('p','h1','h2','h3','li'): s.out.append('\n')
    def handle_data(s, d):
        if not s.skip: s.out.append(d)
p=P(); p.feed(src)
t=''.join(p.out)
t=re.sub(r'[ \t\xa0]*\n[ \t\xa0\n]*', '\n', t)
print(t)
