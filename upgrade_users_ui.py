import re

with open('resources/views/admin/users/index.blade.php', 'r') as f:
    content = f.read()

# 1. Add animations to modals
content = content.replace('class="fixed inset-0 z-50 hidden"', 'class="fixed inset-0 z-50 hidden animate-backdrop"')

content = re.sub(r'(<div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full)', r'\1 animate-modal relative z-10', content, flags=re.DOTALL)
content = re.sub(r'(<div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-visible shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full)', r'\1 animate-modal relative z-10', content, flags=re.DOTALL)

# 2. Add premium CSS (if not present)
premium_styles = """
<style>
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
</style>
"""
if "modalFadeIn" not in content:
    content = content.replace("@section('content')", "@section('content')\n" + premium_styles)

# 3. Upgrade input styles
old_input = 'class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#5C52E7] focus:border-transparent transition-all text-sm"'
new_input = 'class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors"'
content = content.replace(old_input, new_input)

old_select = 'class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#5C52E7] focus:border-transparent transition-all text-sm appearance-none bg-white"'
new_select = 'class="mt-1 focus:ring-[#5C52E7] focus:border-[#5C52E7] block w-full shadow-sm sm:text-sm border-gray-300 rounded-xl px-4 py-2 border outline-none transition-colors bg-white"'
content = content.replace(old_select, new_select)

# 4. Fix layout spacing
content = content.replace('class="mt-4 space-y-4"', 'class="mt-4 flex flex-col gap-4"')

with open('resources/views/admin/users/index.blade.php', 'w') as f:
    f.write(content)
