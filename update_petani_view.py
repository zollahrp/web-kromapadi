import re

file_path = 'resources/views/admin/petani/index.blade.php'
with open(file_path, 'r') as f:
    content = f.read()

# Replace Header Title
content = re.sub(
    r'<h1 class="text-2xl font-bold text-gray-900">Data Petani</h1>\s*<p class="text-gray-500 mt-1 text-sm">Kelola akun Petani untuk masing-masing KTD.</p>',
    r'''<div class="flex items-center gap-3">
            <a href="{{ route('ktd.index') }}" class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Petani: {{ $ktd->name }}</h1>
                <p class="text-gray-500 mt-1 text-sm">Kelola akun Petani khusus untuk Kelompok Tani Dewasa ini.</p>
            </div>
        </div>''',
    content
)

# Replace filter form action
content = re.sub(
    r'<form action="{{ route\(\'petani\.index\'\) }}" method="GET"',
    r'<form action="{{ route(\'ktd.petani.index\', $ktd->id) }}" method="GET"',
    content
)

# Remove KTD Filter block
content = re.sub(
    r'<!-- Filter KTD -->.*?</div>\s*@endif',
    '',
    content,
    flags=re.DOTALL
)

# Fix Reset Button URL
content = re.sub(
    r'<a href="{{ route\(\'petani\.index\'\) }}" class="px-4 py-2 bg-red-50 text-red-600',
    r'<a href="{{ route(\'ktd.petani.index\', $ktd->id) }}" class="px-4 py-2 bg-red-50 text-red-600',
    content
)
content = re.sub(
    r"@if\(request\(\)->hasAny\(\['search', 'ktd_id'\]\)\)",
    r"@if(request()->has('search'))",
    content
)

# Remove KTD column from table
content = re.sub(r'<th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">KTD & Wilayah</th>', '', content)
content = re.sub(r'<td class="py-4 px-6">\s*<div class="text-sm font-medium text-gray-900">{{ \$petani->kelompokTani \? \$petani->kelompokTani->name : \'-\' }}</div>\s*<div class="text-xs text-gray-500 mt-1">{{ \$petani->kelompokTani && \$petani->kelompokTani->wilayah \? \$petani->kelompokTani->wilayah->name : \'-\' }}</div>\s*</td>', '', content)

# Fix edit button data-ktd
content = re.sub(r'data-ktd="{{ \$petani->kelompok_tani_id }}"', '', content)

# Fix delete form route
content = re.sub(
    r"action=\"\{\{ route\('petani\.destroy', \$petani->id\) \}\}\"",
    r"action=\"{{ route('ktd.petani.destroy', ['ktd' => $ktd->id, 'petani' => $petani->id]) }}\"",
    content
)

# Update Modal Store route
content = re.sub(
    r"action=\"\{\{ route\('petani\.store'\) \}\}\"",
    r"action=\"{{ route('ktd.petani.store', $ktd->id) }}\"",
    content
)

# Remove Kelompok Tani Select from Add Modal
content = re.sub(
    r'<div id="add_ktd_container">.*?</div>\s*</div>\s*</div>\s*</div>\s*<div class="bg-gray-50',
    r'</div>\s*</div>\s*</div>\s*</div>\s*<div class="bg-gray-50',
    content,
    flags=re.DOTALL
)

# Remove Kelompok Tani Select from Edit Modal
content = re.sub(
    r'<div id="edit_ktd_container">.*?</div>\s*</div>\s*</div>\s*</div>\s*<div class="bg-gray-50',
    r'</div>\s*</div>\s*</div>\s*</div>\s*<div class="bg-gray-50',
    content,
    flags=re.DOTALL
)

# Update Javascript form action
content = re.sub(
    r"document\.getElementById\('form-edit'\)\.action = '/petani/' \+ id;",
    r"document.getElementById('form-edit').action = '/ktd/{{ $ktd->id }}/petani/' + id;",
    content
)

# Remove ktd JS line
content = re.sub(r"var ktd = button\.getAttribute\('data-ktd'\);\n", "", content)

# Remove any remaining TomSelect block
content = re.sub(
    r'<script>\s*let tsFilterKtd, tsAddKtd, tsEditKtd;.*?</script>',
    '',
    content,
    flags=re.DOTALL
)


with open(file_path, 'w') as f:
    f.write(content)

print("Updated view")
