import re

with open('resources/views/admin/wilayah/index.blade.php', 'r') as f:
    content = f.read()

# 2. Update JS logic to remove updatePlaceholders and simplify fetching
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
                
                tsKota.control_input.placeholder = "Sedang mengambil data...";
                
                fetch(`${apiUrl}/regencies/${value}.json`)
                    .then(response => response.json())
                    .then(json => {
                        tsKota.control_input.placeholder = "Pilih Kota/Kabupaten...";
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

                tsKec.control_input.placeholder = "Sedang mengambil data...";

                fetch(`${apiUrl}/districts/${value}.json`)
                    .then(response => response.json())
                    .then(json => {
                        tsKec.control_input.placeholder = "Pilih Kecamatan...";
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
                
                tsKel.control_input.placeholder = "Sedang mengambil data...";

                fetch(`${apiUrl}/villages/${value}.json`)
                    .then(response => response.json())
                    .then(json => {
                        tsKel.control_input.placeholder = "Pilih Kelurahan/Desa...";
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
        tsProv.disable();
        tsProv.control_input.placeholder = "Sedang mengambil data...";
        tsKota.disable();
        tsKec.disable();
        tsKel.disable();
        
        // Fetch provinces immediately
        fetch(`${apiUrl}/provinces.json`)
            .then(res => res.json())
            .then(json => {
                tsProv.addOption(json);
                tsProv.control_input.placeholder = "Pilih Provinsi...";
                tsProv.enable();
            })
            .catch(() => {
                tsProv.control_input.placeholder = "Gagal memuat";
            });

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
