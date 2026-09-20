import re

with open('resources/views/admin/wilayah/index.blade.php', 'r') as f:
    content = f.read()

# 1. Fix Modal Overflow & rounded corners
content = content.replace('overflow-hidden shadow-xl transform transition-all', 'overflow-visible shadow-xl transform transition-all')
content = content.replace('<div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">', '<div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100 rounded-b-2xl">')

# 2. Update JS logic to disable/enable and show loading state
js_logic_old = re.search(r'<script>.*?</script>', content, flags=re.DOTALL).group(0)

js_logic_new = """
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

        const tsConfig = {
            valueField: 'id',
            labelField: 'name',
            searchField: 'name',
        };

        tsProv = new TomSelect(elProv, {
            ...tsConfig,
            load: function(query, callback) {
                this.settings.placeholder = "Memuat Provinsi...";
                this.updatePlaceholders();
                fetch(`${apiUrl}/provinces.json`)
                    .then(response => response.json())
                    .then(json => {
                        this.settings.placeholder = "Pilih Provinsi...";
                        this.updatePlaceholders();
                        callback(json);
                    })
                    .catch(() => {
                        this.settings.placeholder = "Gagal memuat";
                        this.updatePlaceholders();
                        callback();
                    });
            },
            onChange: function(value) {
                if (!value) {
                    tsKota.disable(); tsKota.clearOptions(); tsKota.clear();
                    tsKec.disable(); tsKec.clearOptions(); tsKec.clear();
                    tsKel.disable(); tsKel.clearOptions(); tsKel.clear();
                    return;
                }
                hidProv.value = this.options[value].name;
                
                tsKota.clearOptions(); tsKota.clear(); tsKota.disable();
                tsKec.clearOptions(); tsKec.clear(); tsKec.disable();
                tsKel.clearOptions(); tsKel.clear(); tsKel.disable();
                
                tsKota.settings.placeholder = "Sedang mengambil data...";
                tsKota.updatePlaceholders();

                fetch(`${apiUrl}/regencies/${value}.json`)
                    .then(response => response.json())
                    .then(json => {
                        tsKota.settings.placeholder = "Pilih Kota/Kabupaten...";
                        tsKota.updatePlaceholders();
                        tsKota.addOption(json);
                        tsKota.enable();
                    });
            }
        });

        tsKota = new TomSelect(elKota, {
            ...tsConfig,
            onChange: function(value) {
                if (!value) {
                    tsKec.disable(); tsKec.clearOptions(); tsKec.clear();
                    tsKel.disable(); tsKel.clearOptions(); tsKel.clear();
                    return;
                }
                hidKota.value = this.options[value].name;

                tsKec.clearOptions(); tsKec.clear(); tsKec.disable();
                tsKel.clearOptions(); tsKel.clear(); tsKel.disable();

                tsKec.settings.placeholder = "Sedang mengambil data...";
                tsKec.updatePlaceholders();

                fetch(`${apiUrl}/districts/${value}.json`)
                    .then(response => response.json())
                    .then(json => {
                        tsKec.settings.placeholder = "Pilih Kecamatan...";
                        tsKec.updatePlaceholders();
                        tsKec.addOption(json);
                        tsKec.enable();
                    });
            }
        });

        tsKec = new TomSelect(elKec, {
            ...tsConfig,
            onChange: function(value) {
                if (!value) {
                    tsKel.disable(); tsKel.clearOptions(); tsKel.clear();
                    return;
                }
                hidKec.value = this.options[value].name;

                tsKel.clearOptions(); tsKel.clear(); tsKel.disable();
                
                tsKel.settings.placeholder = "Sedang mengambil data...";
                tsKel.updatePlaceholders();

                fetch(`${apiUrl}/villages/${value}.json`)
                    .then(response => response.json())
                    .then(json => {
                        tsKel.settings.placeholder = "Pilih Kelurahan/Desa...";
                        tsKel.updatePlaceholders();
                        tsKel.addOption(json);
                        tsKel.enable();
                    });
            }
        });

        tsKel = new TomSelect(elKel, {
            ...tsConfig,
            onChange: function(value) {
                if (!value) return;
                hidKel.value = this.options[value].name;
            }
        });

        // Initialize state
        tsKota.disable();
        tsKec.disable();
        tsKel.disable();
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
            editSelects.tsKota.enable();
            editSelects.tsKota.addOption({id: kota, name: kota});
            editSelects.tsKota.setValue(kota, true);
        }
        if (kecamatan) {
            editSelects.tsKec.enable();
            editSelects.tsKec.addOption({id: kecamatan, name: kecamatan});
            editSelects.tsKec.setValue(kecamatan, true);
        }
        if (kelurahan) {
            editSelects.tsKel.enable();
            editSelects.tsKel.addOption({id: kelurahan, name: kelurahan});
            editSelects.tsKel.setValue(kelurahan, true);
        }

        document.getElementById('modal-edit').classList.remove('hidden');
    }
</script>
"""

content = content.replace(js_logic_old, js_logic_new)

with open('resources/views/admin/wilayah/index.blade.php', 'w') as f:
    f.write(content)
