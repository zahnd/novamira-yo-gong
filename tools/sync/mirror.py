"""Mirror WPCode snippets and Novamira memories from JSON dumps into this repo.

Usage: python3 -I tools/sync/mirror.py <dump_dir> <repo_root>

<dump_dir> must contain snippets.json and memories.json: the return_value of the two
execute-php calls in tools/sync/README.md. The script rewrites wpcode-snippets/ (code files and
manifest.json; README.md is kept) and docs/novamira-memories/ (README.md is kept). Files of
snippets or memories that no longer exist on the site are deleted, so git shows the removal.
"""
import hashlib, json, os, re, sys, unicodedata

dump, repo = sys.argv[1], sys.argv[2]


def slug(s):
    s = unicodedata.normalize('NFKD', s.replace('ß', 'ss')).encode('ascii', 'ignore').decode()
    return re.sub(r'[^a-z0-9]+', '-', s.lower()).strip('-')[:70]


def write(path, text):
    with open(path, 'w', newline='\n', encoding='utf-8') as f:
        f.write(text)


def clear(d):
    for name in os.listdir(d):
        if name != 'README.md' and os.path.isfile(os.path.join(d, name)):
            os.remove(os.path.join(d, name))


# --- WPCode snippets ---------------------------------------------------------------------------
EXT = {'php': 'php', 'js': 'js', 'scss': 'scss', 'css': 'css', 'html': 'html', 'text': 'txt',
       'universal': 'txt', 'blocks': 'html'}
out = os.path.join(repo, 'wpcode-snippets')
os.makedirs(out, exist_ok=True)
clear(out)
manifest = []
for s in json.load(open(os.path.join(dump, 'snippets.json'), encoding='utf-8')):
    t = (s['terms'].get('wpcode_type') or ['text'])[0]
    loc = (s['terms'].get('wpcode_location') or ['(shortcode/manual)'])[0]
    fn = f"{s['id']}-{slug(s['title'])}.{EXT.get(t, 'txt')}"
    code = s['content'].replace('\r\n', '\n')
    if t == 'php' and not code.lstrip().startswith('<?php'):
        code = '<?php\n' + code  # WPCode stores PHP without the open tag and eval()s it
    write(os.path.join(out, fn), code.rstrip('\n') + '\n')
    m = s['meta']
    compiled = None
    if t == 'scss' and m.get('_wpcode_compiled_code'):
        compiled = fn[:-5] + '.compiled.css'
        write(os.path.join(out, compiled), m['_wpcode_compiled_code'].replace('\r\n', '\n').rstrip('\n') + '\n')
    manifest.append(dict(
        id=s['id'], title=s['title'], status=s['status'], active=s['status'] == 'publish', type=t,
        location=loc, tags=s['terms'].get('wpcode_tags', []),
        auto_insert=m.get('_wpcode_auto_insert'), priority=m.get('_wpcode_priority'),
        conditional_logic_enabled=bool(m.get('_wpcode_conditional_logic_enabled')),
        conditional_logic=m.get('_wpcode_conditional_logic') or None,
        library_id=m.get('_wpcode_library_id'), note=m.get('_wpcode_note') or None,
        load_as_file=m.get('_wpcode_load_as_file'), created=s['date'], modified=s['modified'],
        file=fn, compiled_css=compiled,
        live_sha1=hashlib.sha1(s['content'].encode('utf-8')).hexdigest(),
        live_has_crlf='\r\n' in s['content']))
write(os.path.join(out, 'manifest.json'), json.dumps(manifest, indent=2, ensure_ascii=False) + '\n')

# --- Novamira memories -------------------------------------------------------------------------
md = os.path.join(repo, 'docs', 'novamira-memories')
os.makedirs(md, exist_ok=True)
clear(md)
mems = json.load(open(os.path.join(dump, 'memories.json'), encoding='utf-8'))
for m in mems:
    meta = m['meta'] if isinstance(m['meta'], dict) else {}
    typ = meta.get('_novamira_memory_type', '')
    desc = meta.get('_novamira_memory_description', m.get('excerpt', ''))
    fn = f"{m['id']}-{slug(m['title'])}.md"
    write(os.path.join(md, fn),
          f"---\nnovamira_id: {m['id']}\ntitle: {json.dumps(m['title'], ensure_ascii=False)}\n"
          f"type: {typ}\ndescription: {json.dumps(desc, ensure_ascii=False)}\n"
          f"created: {m['date']}\nupdated: {m['modified']}\n---\n\n# {m['title']}\n\n{m['content'].strip()}\n")

print(f"{len(manifest)} snippets, {len(mems)} memories")
for e in manifest:
    print(f"  wpcode {e['id']:>5} {e['status']:<8} {e['file']}")
for m in mems:
    print(f"  memory {m['id']:>5} {m['title']}")
