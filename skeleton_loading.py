import re

with open('resources/views/admin/wilayah/index.blade.php', 'r') as f:
    content = f.read()

# First, let's inject a CSS rule for the skeleton loading so it applies to tom-select inputs.
# I'll put it right before </style> or right after <style>
skeleton_style = """
.ts-wrapper.skeleton-loading .ts-control {
    background-color: #e5e7eb !important;
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    pointer-events: none;
}
.ts-wrapper.skeleton-loading .ts-control input::placeholder {
    color: transparent !important;
}
.ts-wrapper.skeleton-loading .ts-control .item {
    opacity: 0;
}
"""

if '<style>' in content:
    content = content.replace('<style>', '<style>\n' + skeleton_style)
else:
    content = '<style>\n' + skeleton_style + '\n</style>\n' + content


# Now let's update the fetch blocks in setupCascadingDropdown
# 1. For Kota
old_fetch_kota = """                tsKota.control_input.placeholder = "Sedang mengambil data...";
                
                fetch(`${apiUrl}/regencies/${value}.json`)
                    .then(response => response.json())
                    .then(json => {
                        tsKota.control_input.placeholder = "Pilih Kota/Kabupaten...";
                        tsKota.addOption(json);
                        tsKota.enable();
                    });"""

new_fetch_kota = """                tsKota.wrapper.classList.add('skeleton-loading');
                
                fetch(`${apiUrl}/regencies/${value}.json`)
                    .then(response => response.json())
                    .then(json => {
                        tsKota.wrapper.classList.remove('skeleton-loading');
                        tsKota.addOption(json);
                        tsKota.enable();
                    })
                    .catch(() => tsKota.wrapper.classList.remove('skeleton-loading'));"""

content = content.replace(old_fetch_kota, new_fetch_kota)

# 2. For Kecamatan
old_fetch_kec = """                tsKec.control_input.placeholder = "Sedang mengambil data...";

                fetch(`${apiUrl}/districts/${value}.json`)
                    .then(response => response.json())
                    .then(json => {
                        tsKec.control_input.placeholder = "Pilih Kecamatan...";
                        tsKec.addOption(json);
                        tsKec.enable();
                    });"""

new_fetch_kec = """                tsKec.wrapper.classList.add('skeleton-loading');

                fetch(`${apiUrl}/districts/${value}.json`)
                    .then(response => response.json())
                    .then(json => {
                        tsKec.wrapper.classList.remove('skeleton-loading');
                        tsKec.addOption(json);
                        tsKec.enable();
                    })
                    .catch(() => tsKec.wrapper.classList.remove('skeleton-loading'));"""

content = content.replace(old_fetch_kec, new_fetch_kec)

# 3. For Kelurahan
old_fetch_kel = """                tsKel.control_input.placeholder = "Sedang mengambil data...";

                fetch(`${apiUrl}/villages/${value}.json`)
                    .then(response => response.json())
                    .then(json => {
                        tsKel.control_input.placeholder = "Pilih Kelurahan/Desa...";
                        tsKel.addOption(json);
                        tsKel.enable();
                    });"""

new_fetch_kel = """                tsKel.wrapper.classList.add('skeleton-loading');

                fetch(`${apiUrl}/villages/${value}.json`)
                    .then(response => response.json())
                    .then(json => {
                        tsKel.wrapper.classList.remove('skeleton-loading');
                        tsKel.addOption(json);
                        tsKel.enable();
                    })
                    .catch(() => tsKel.wrapper.classList.remove('skeleton-loading'));"""

content = content.replace(old_fetch_kel, new_fetch_kel)


# 4. For Provinces initial load
old_fetch_prov = """        fetch(`${apiUrl}/provinces.json`)
            .then(response => response.json())
            .then(json => {
                tsProv.addOption(json);
                tsProv.enable();
            });"""

new_fetch_prov = """        tsProv.wrapper.classList.add('skeleton-loading');
        fetch(`${apiUrl}/provinces.json`)
            .then(response => response.json())
            .then(json => {
                tsProv.wrapper.classList.remove('skeleton-loading');
                tsProv.addOption(json);
                tsProv.enable();
            })
            .catch(() => tsProv.wrapper.classList.remove('skeleton-loading'));"""

content = content.replace(old_fetch_prov, new_fetch_prov)

with open('resources/views/admin/wilayah/index.blade.php', 'w') as f:
    f.write(content)
