import re

with open('resources/views/admin/wilayah/index.blade.php', 'r') as f:
    content = f.read()

# Replace the simple style block with a more premium one
premium_styles = """<style>
/* Premium TomSelect UI */
.ts-control { 
    border-radius: 0.75rem !important; 
    padding: 0.625rem 1rem !important; 
    border: 1px solid #e5e7eb !important; 
    background-color: #f9fafb !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
}
.ts-control.focus { 
    border-color: #5C52E7 !important; 
    background-color: #ffffff !important;
    box-shadow: 0 0 0 4px rgba(92, 82, 231, 0.15) !important; 
}
.ts-dropdown {
    border-radius: 0.75rem !important;
    border: 1px solid #e5e7eb !important;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
    margin-top: 0.5rem !important;
    animation: dropdownSlide 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards !important;
    overflow: hidden !important;
}
.ts-dropdown .option {
    padding: 0.625rem 1rem !important;
    transition: all 0.2s ease !important;
}
.ts-dropdown .active {
    background-color: #f3f2ff !important;
    color: #5C52E7 !important;
    font-weight: 500 !important;
}
@keyframes dropdownSlide {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Smooth Modal Animation */
@keyframes modalFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
@keyframes modalScaleIn {
    from { opacity: 0; transform: translateY(20px) scale(0.95); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
.animate-backdrop {
    animation: modalFadeIn 0.3s ease-out forwards;
}
.animate-modal {
    animation: modalScaleIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>"""

# Replace old style
content = re.sub(r'<style>.*?</style>', premium_styles, content, flags=re.DOTALL)

# Add animation classes to modal Tambah
# 1. Backdrop
content = content.replace('id="modal-tambah" class="fixed inset-0 z-50 hidden"', 'id="modal-tambah" class="fixed inset-0 z-50 hidden animate-backdrop"')
content = content.replace('bg-gray-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById(\'modal-tambah\').classList.add(\'hidden\')', 'bg-gray-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById(\'modal-tambah\').classList.add(\'hidden\')')
# 2. Modal Content (Tambah)
content = re.sub(r'(<div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10")(.*?<form action="{{ route\(\'wilayah.store\'\) }})', r'\1 animate-modal"\2', content, flags=re.DOTALL)

# Add animation classes to modal Edit
content = content.replace('id="modal-edit" class="fixed inset-0 z-50 hidden"', 'id="modal-edit" class="fixed inset-0 z-50 hidden animate-backdrop"')
# Modal Content (Edit)
content = re.sub(r'(<div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10")(.*?<form id="form-edit")', r'\1 animate-modal"\2', content, flags=re.DOTALL)

with open('resources/views/admin/wilayah/index.blade.php', 'w') as f:
    f.write(content)
