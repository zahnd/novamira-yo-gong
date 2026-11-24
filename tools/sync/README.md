# Re-syncing the mirrors from the live site

`wpcode-snippets/` and `docs/novamira-memories/` are **read-only mirrors** of what is stored on
www.yo-gong.com. Re-sync after every session that changed a snippet or a Novamira memory, then commit.

## 1. Dump from the site

MCP `novamira-yo-gong-com`, ability `novamira/execute-php`, parameter `code`.

Snippets (all statuses except trash):

```php
$r=[]; foreach (get_posts(['post_type'=>['wpcode'],'numberposts'=>-1,'post_status'=>'any','orderby'=>'ID','order'=>'ASC']) as $p) { $m=get_post_meta($p->ID); $meta=[]; foreach($m as $k=>$v){ if (strpos($k,'_edit_')===0) continue; $meta[$k]=maybe_unserialize($v[0]); } $terms=[]; foreach(get_object_taxonomies($p->post_type) as $tx){ $t=wp_get_object_terms($p->ID,$tx,['fields'=>'names']); if($t && !is_wp_error($t)) $terms[$tx]=$t; } $r[]=['id'=>$p->ID,'type'=>$p->post_type,'title'=>$p->post_title,'status'=>$p->post_status,'date'=>$p->post_date,'modified'=>$p->post_modified,'author'=>$p->post_author,'terms'=>$terms,'meta'=>$meta,'content'=>$p->post_content,'sha1'=>sha1($p->post_content)]; } return $r;
```

Memories:

```php
$r=[]; foreach (get_posts(['post_type'=>'novamira_memory','numberposts'=>-1,'post_status'=>'any','orderby'=>'ID','order'=>'ASC']) as $p) { $m=get_post_meta($p->ID); $meta=[]; foreach($m as $k=>$v){ $meta[$k]=maybe_unserialize($v[0]); } $r[]=['id'=>$p->ID,'title'=>$p->post_title,'date'=>$p->post_date,'modified'=>$p->post_modified,'excerpt'=>$p->post_excerpt,'meta'=>$meta,'content'=>$p->post_content]; } return $r;
```

Put each `data.return_value` into `snippets.json` / `memories.json` in a scratch directory. If the result
was saved to a tool-results file: `jq '.data.return_value' <file> > <dump>/snippets.json`. If it came back
inline, write it with a quoted heredoc (`<<'EOF'`), **not** with the Write tool, because that tool strips
trailing whitespace and changes the bytes.

## 2. Check the transcription and write the mirror

```bash
# every snippet's content must hash to the sha1 the server reported
python3 -I -c "import json,hashlib,sys; [print(s['id'], hashlib.sha1(s['content'].encode()).hexdigest()==s['sha1']) for s in json.load(open(sys.argv[1]))]" <dump>/snippets.json
python3 -I tools/sync/mirror.py <dump> .
git status && git diff --stat
```

`mirror.py` rewrites both directories (their `README.md` files are kept) and deletes files of snippets or
memories that no longer exist on the site. Update the tables in `wpcode-snippets/README.md`,
`docs/custom-code.md` and `docs/novamira-memories/README.md` by hand when the set of snippets or memories changes.

## 3. Then

Update the matching Basic Memory note in project `yo-gong` (folder `Website/`), and commit.
