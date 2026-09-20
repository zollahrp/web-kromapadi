import re

with open('app/Http/Controllers/ApiController.php', 'r') as f:
    content = f.read()

# 1. Update login to accept petani
content = content.replace("""
            // Only KTD users can use the mobile app
            if ($user->role !== 'ktd') {
                return response()->json(['error' => 'Akses ditolak. Aplikasi mobile hanya untuk Kelompok Tani Desa.'], 403);
            }
""", """
            // Only Petani (or KTD for backward compatibility) users can use the mobile app
            if (!in_array($user->role, ['petani', 'ktd'])) {
                return response()->json(['error' => 'Akses ditolak. Aplikasi mobile hanya untuk Petani atau Kelompok Tani.'], 403);
            }
""")

# 2. Update getLahan to fetch using kelompok_tani_id for petani
content = content.replace("""
        // Cari KelompokTani (entitas) yang berhubungan dengan User (KTD) ini
        // Kita menggunakan name dan wilayah_id karena waktu store di KelompokTaniController kita menyamakan nama.
        $ktd = \App\Models\KelompokTani::where('name', $user->name)
                                        ->where('wilayah_id', $user->wilayah_id)
                                        ->first();
        
        if (!$ktd) {
            return response()->json(['data' => []]);
        }

        $lahans = \App\Models\Lahan::where('kelompok_tani_id', $ktd->id)->get();
""", """
        if ($user->role === 'petani' && $user->kelompok_tani_id) {
            $ktd_id = $user->kelompok_tani_id;
        } else {
            // Backward compatibility for old 'ktd' role accounts
            $ktd = \App\Models\KelompokTani::where('name', $user->name)
                                            ->where('wilayah_id', $user->wilayah_id)
                                            ->first();
            
            if (!$ktd) {
                return response()->json(['data' => []]);
            }
            $ktd_id = $ktd->id;
        }

        $lahans = \App\Models\Lahan::where('kelompok_tani_id', $ktd_id)->get();
""")

with open('app/Http/Controllers/ApiController.php', 'w') as f:
    f.write(content)

print("Updated ApiController")
