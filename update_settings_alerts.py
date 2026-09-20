import re

view_path = 'resources/views/admin/settings/index.blade.php'
with open(view_path, 'r') as f:
    view_content = f.read()

old_alerts = """@if(session('success'))
<div id="success-alert" class="mb-6 p-4 bg-green-50 border border-green-100 rounded-2xl flex items-start gap-3">
    <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    <div>
        <h3 class="text-sm font-medium text-green-800">Berhasil</h3>
        <p class="text-sm text-green-600 mt-1">{{ session('success') }}</p>
    </div>
</div>
@endif

@if($errors->any())
<div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-start gap-3">
    <svg class="w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    <div>
        <h3 class="text-sm font-medium text-red-800">Terdapat Kesalahan</h3>
        <ul class="text-sm text-red-600 mt-1 list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif"""

new_alerts = """@if(session('success'))
<div id="success-alert" class="fixed top-6 right-6 z-[100] max-w-sm w-full bg-white rounded-2xl shadow-xl border border-green-100 p-4 flex items-start gap-3 transform transition-all duration-500 translate-y-0 opacity-100" style="animation: slideInRight 0.5s cubic-bezier(0.16, 1, 0.3, 1);">
    <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    </div>
    <div class="flex-1">
        <h3 class="text-sm font-bold text-gray-900">Berhasil!</h3>
        <p class="text-sm text-gray-500 mt-0.5">{{ session('success') }}</p>
    </div>
    <button onclick="closeAlert()" class="text-gray-400 hover:text-gray-600 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>
</div>
<style>
@keyframes slideInRight {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
</style>
<script>
    function closeAlert() {
        const el = document.getElementById('success-alert');
        if (el) {
            el.style.transform = 'translateX(100%)';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        }
    }
    setTimeout(closeAlert, 5000);
</script>
@endif

@if($errors->any())
<div id="error-alert" class="fixed top-6 right-6 z-[100] max-w-sm w-full bg-white rounded-2xl shadow-xl border border-red-100 p-4 flex items-start gap-3 transform transition-all duration-500 translate-y-0 opacity-100" style="animation: slideInRight 0.5s cubic-bezier(0.16, 1, 0.3, 1);">
    <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    </div>
    <div class="flex-1">
        <h3 class="text-sm font-bold text-gray-900">Gagal!</h3>
        <ul class="text-sm text-gray-500 mt-0.5 list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    <button onclick="closeErrorAlert()" class="text-gray-400 hover:text-gray-600 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>
</div>
<script>
    function closeErrorAlert() {
        const el = document.getElementById('error-alert');
        if (el) {
            el.style.transform = 'translateX(100%)';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        }
    }
    setTimeout(closeErrorAlert, 5000);
</script>
@endif"""

view_content = view_content.replace(old_alerts, new_alerts)

with open(view_path, 'w') as f:
    f.write(view_content)
