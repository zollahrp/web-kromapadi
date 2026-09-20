import re

file_path = 'resources/views/admin/petani/index.blade.php'
with open(file_path, 'r') as f:
    content = f.read()

# Remove the whole add_wilayah_container block
content = re.sub(r'<div id="add_wilayah_container">.*?</div>\s*</div>', '</div>', content, flags=re.DOTALL)

# Remove the whole edit_wilayah_container block 
content = re.sub(r'<div id="edit_wilayah_container">.*?</div>\s*</div>', '</div>', content, flags=re.DOTALL)

# Let's also check if there is any JS that might be referencing add_wilayah_container
content = re.sub(r'document\.getElementById\(\'add_wilayah_container\'\)\.classList\..*?;', '', content)
content = re.sub(r'document\.getElementById\(\'edit_wilayah_container\'\)\.classList\..*?;', '', content)

with open(file_path, 'w') as f:
    f.write(content)

print("Fixed")
