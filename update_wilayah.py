import re

with open('resources/views/admin/wilayah/index.blade.php', 'r') as f:
    content = f.read()

# 1. Add tom-select CSS and JS
ts_includes = """
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<style>
.ts-control { border-radius: 0.75rem !important; padding: 0.5rem 1rem !important; border-color: #d1d5db !important; }
.ts-control.focus { border-color: #5C52E7 !important; box-shadow: 0 0 0 1px #5C52E7 !important; }
</style>
"""
if "tom-select" not in content:
    content = content.replace("@section('content')", "@section('content')" + ts_includes)

# 2. Fix layout spacing: mt-4 grid grid-cols-1 gap-y-4 gap-x-4 sm:grid-cols-2 -> mt-4 flex flex-col gap-4
content = content.replace('mt-4 grid grid-cols-1 gap-y-4 gap-x-4 sm:grid-cols-2', 'mt-4 flex flex-col gap-4')
content = content.replace('<div class="sm:col-span-2">', '<div>')

def replace_input_with_select(html, field_name, label, id_prefix=""):
    pattern = r'<div>\s*<label for="'+id_prefix+field_name+r'".*?>(.*?)</label>\s*<input type="text" name="'+field_name+r'" id="'+id_prefix+field_name+r'".*?>\s*</div>'
    replacement = f"""<div>
    <label for="{id_prefix}{field_name}" class="block text-sm font-medium text-gray-700">{label}</label>
    <input type="hidden" name="{field_name}" id="{id_prefix}{field_name}_name">
    <select id="{id_prefix}{field_name}" class="mt-1 block w-full" placeholder="Pilih {label}..."></select>
</div>"""
    return re.sub(pattern, replacement, html, flags=re.DOTALL)

content = replace_input_with_select(content, "provinsi", "Provinsi")
content = replace_input_with_select(content, "kota", "Kota/Kabupaten")
content = replace_input_with_select(content, "kecamatan", "Kecamatan")
content = replace_input_with_select(content, "kelurahan", "Kelurahan/Desa")

content = replace_input_with_select(content, "provinsi", "Provinsi", "edit_")
content = replace_input_with_select(content, "kota", "Kota/Kabupaten", "edit_")
content = replace_input_with_select(content, "kecamatan", "Kecamatan", "edit_")
content = replace_input_with_select(content, "kelurahan", "Kelurahan/Desa", "edit_")

js_logic = """
<script>
    const apiUrl = 'https://www.emsifa.com/api-wilayah-indonesia/api';

    function setupCascadingDropdown(prefix) {
        let tsProv, tsKota, tsKec, tsKel;

        const elProv = document.getElementById(prefix + 'provinsi');
        const elKota = document.getElementById(prefix + 'kota');
        const elKec = document.getElementById(prefix + 'kecamatan');
        const elKel = document.getElementById(prefix + 'kelurahan');

        const hidProv = document.getElementById(prefix + 'provinsi_name');
        const hidKota = document.getElementById(prefix + 'kota_name');
        const hidKec = document.getElementById(prefix + 'kecamatan_name');
        const hidKel = document.getElementById(prefix + 'kelurahan_name');

        tsProv = new TomSelect(elProv, {
            valueField: 'id',
            labelField: 'name',
            searchField: 'name',
            load: function(query, callback) {
                fetch(`${apiUrl}/provinces.json`)
                    .then(response => response.json())
                    .then(json => callback(json))
                    .catch(() => callback());
            },
            onChange: function(value) {
                if (!value) return;
                hidProv.value = this.options[value].name;
                
                tsKota.clearOptions();
                tsKota.clear();
                tsKec.clearOptions();
                tsKec.clear();
                tsKel.clearOptions();
                tsKel.clear();

                fetch(`${apiUrl}/regencies/${value}.json`)
                    .then(response => response.json())
                    .then(json => tsKota.addOption(json));
            }
        });

        tsKota = new TomSelect(elKota, {
            valueField: 'id',
            labelField: 'name',
            searchField: 'name',
            onChange: function(value) {
                if (!value) return;
                hidKota.value = this.options[value].name;

                tsKec.clearOptions();
                tsKec.clear();
                tsKel.clearOptions();
                tsKel.clear();

                fetch(`${apiUrl}/districts/${value}.json`)
                    .then(response => response.json())
                    .then(json => tsKec.addOption(json));
            }
        });

        tsKec = new TomSelect(elKec, {
            valueField: 'id',
            labelField: 'name',
            searchField: 'name',
            onChange: function(value) {
                if (!value) return;
                hidKec.value = this.options[value].name;

                tsKel.clearOptions();
                tsKel.clear();

                fetch(`${apiUrl}/villages/${value}.json`)
                    .then(response => response.json())
                    .then(json => tsKel.addOption(json));
            }
        });

        tsKel = new TomSelect(elKel, {
            valueField: 'id',
            labelField: 'name',
            searchField: 'name',
            onChange: function(value) {
                if (!value) return;
                hidKel.value = this.options[value].name;
            }
        });

        tsProv.load('');

        return { tsProv, tsKota, tsKec, tsKel, hidProv, hidKota, hidKec, hidKel };
    }

    let tambahSelects, editSelects;

    document.addEventListener("DOMContentLoaded", function() {
        tambahSelects = setupCascadingDropdown('');
        editSelects = setupCascadingDropdown('edit_');
    });

    function openEditModal(id, nama, provinsi, kota, kecamatan, kelurahan) {
        document.getElementById('form-edit').action = '/wilayah/' + id;
        document.getElementById('edit_name').value = nama;
        
        editSelects.hidProv.value = provinsi || '';
        editSelects.hidKota.value = kota || '';
        editSelects.hidKec.value = kecamatan || '';
        editSelects.hidKel.value = kelurahan || '';

        // Add dummy options so TomSelect shows the current names if available
        if (provinsi) {
            editSelects.tsProv.addOption({id: provinsi, name: provinsi});
            editSelects.tsProv.setValue(provinsi, true);
        }
        if (kota) {
            editSelects.tsKota.addOption({id: kota, name: kota});
            editSelects.tsKota.setValue(kota, true);
        }
        if (kecamatan) {
            editSelects.tsKec.addOption({id: kecamatan, name: kecamatan});
            editSelects.tsKec.setValue(kecamatan, true);
        }
        if (kelurahan) {
            editSelects.tsKel.addOption({id: kelurahan, name: kelurahan});
            editSelects.tsKel.setValue(kelurahan, true);
        }

        document.getElementById('modal-edit').classList.remove('hidden');
    }
</script>
"""

content = re.sub(r'<script>.*?</script>', js_logic, content, flags=re.DOTALL)

with open('resources/views/admin/wilayah/index.blade.php', 'w') as f:
    f.write(content)
