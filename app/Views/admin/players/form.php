<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<?php 
    $isEdit = !empty($player);
    $actionUrl = $isEdit ? site_url('admin/players/update/' . $player['id']) : site_url('admin/players/store');
    $fotoCurrent = $isEdit && $player['foto'] ? 'uploads/players/' . $player['foto'] : 'uploads/players/default.svg';
?>

<div class="max-w-3xl mx-auto space-y-6">
    
    <div>
        <a href="<?= site_url('admin/players') ?>" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-300/80 hover:text-white transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Manajemen Pemain
        </a>
    </div>

    <!-- Form Panel (Emerald Pitch) -->
    <div class="p-6 sm:p-8 rounded-2xl bg-pitch-900 border border-pitch-750 space-y-6">
        
        <div class="border-b border-pitch-800 pb-4">
            <h3 class="font-display font-black text-xl text-white">
                <?= $isEdit ? 'EDIT PROFIL PEMAIN' : 'TAMBAH PEMAIN BARU' ?>
            </h3>
            <p class="text-xs text-emerald-200/70 mt-0.5">Masukkan data profil atlet untuk database tim</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="p-3.5 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 text-xs space-y-1">
                <?php foreach ($errors as $err): ?>
                    <p><i class="fa-solid fa-circle-xmark mr-1"></i> <?= esc($err) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?= $actionUrl ?>" method="POST" enctype="multipart/form-data" class="space-y-5">
            <?= csrf_field() ?>

            <!-- Nama & No. Punggung -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Nama Lengkap Atlet <span class="text-red-400">*</span></label>
                    <input type="text" name="nama" value="<?= old('nama', $player['nama'] ?? '') ?>" required placeholder="Contoh: Jose" class="w-full px-3.5 py-2.5 bg-pitch-950 border border-pitch-750 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:border-emerald-400 transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Nomor Punggung</label>
                    <input type="number" name="nomor_punggung" value="<?= old('nomor_punggung', $player['nomor_punggung'] ?? '') ?>" min="1" max="99" placeholder="10" class="w-full px-3.5 py-2.5 bg-pitch-950 border border-pitch-750 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:border-emerald-400 font-mono transition-colors">
                </div>
            </div>

            <!-- Posisi, Kaki & Usia -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Posisi Bermain <span class="text-red-400">*</span></label>
                    <select name="posisi" required class="w-full px-3.5 py-2.5 bg-pitch-950 border border-pitch-750 rounded-lg text-xs text-white focus:outline-none focus:border-emerald-400 transition-colors">
                        <?php 
                            $currPos = old('posisi', $player['posisi'] ?? ($positions[0] ?? ''));
                        ?>
                        <?php foreach ($positions as $pos): ?>
                            <option value="<?= esc($pos) ?>" <?= $currPos === $pos ? 'selected' : '' ?>><?= esc($pos) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Kaki Dominan</label>
                    <select name="kaki_terkuat" class="w-full px-3.5 py-2.5 bg-pitch-950 border border-pitch-750 rounded-lg text-xs text-white focus:outline-none focus:border-emerald-400 transition-colors">
                        <?php $currKaki = old('kaki_terkuat', $player['kaki_terkuat'] ?? 'Kanan'); ?>
                        <option value="Kanan" <?= $currKaki === 'Kanan' ? 'selected' : '' ?>>Kanan</option>
                        <option value="Kiri" <?= $currKaki === 'Kiri' ? 'selected' : '' ?>>Kiri</option>
                        <option value="Keduanya" <?= $currKaki === 'Keduanya' ? 'selected' : '' ?>>Keduanya (Both)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Usia (Tahun)</label>
                    <input type="number" name="usia" value="<?= old('usia', $player['usia'] ?? '') ?>" min="10" max="55" placeholder="22" class="w-full px-3.5 py-2.5 bg-pitch-950 border border-pitch-750 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:border-emerald-400 font-mono transition-colors">
                </div>
            </div>

            <!-- Captain Toggle -->
            <div class="p-3.5 rounded-xl bg-pitch-950 border border-pitch-750 flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-amber-400 block">Jadikan Sebagai Ketua Pemain (Kapten Tim)</span>
                    <span class="text-[11px] text-slate-400 block mt-0.5">Akan mendapatkan penanda Kapten "C" resmi dan disorot di portal.</span>
                </div>

                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_captain" value="1" class="sr-only peer" <?= old('is_captain', $player['is_captain'] ?? 0) ? 'checked' : '' ?>>
                    <div class="w-10 h-5 bg-pitch-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-amber-500"></div>
                </label>
            </div>

            <!-- Foto -->
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Foto Profil</label>
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-lg bg-pitch-950 border border-pitch-750 p-1 flex-shrink-0 flex items-center justify-center">
                        <img id="imagePreview" src="<?= base_url($fotoCurrent) ?>" class="w-full h-full object-contain">
                    </div>
                    <div class="flex-1">
                        <input type="file" name="foto" id="fotoInput" accept="image/*" class="w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-pitch-800 file:text-emerald-300 hover:file:bg-pitch-750 transition-colors cursor-pointer">
                        <p class="text-[11px] text-slate-500 mt-1">Format: JPG, PNG, WEBP, atau SVG. Maksimal 3MB.</p>
                    </div>
                </div>
            </div>

            <!-- Deskripsi Singkat -->
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Catatan Peran Atlet / Biografi</label>
                <textarea name="bio" rows="2" placeholder="Catatan kelebihan atlet, taktik bermain, atau instruksi posisi..." class="w-full px-3.5 py-2.5 bg-pitch-950 border border-pitch-750 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:border-emerald-400 transition-colors"><?= old('bio', $player['bio'] ?? '') ?></textarea>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-pitch-800">
                <a href="<?= site_url('admin/players') ?>" class="px-4 py-2 rounded-lg bg-pitch-800 text-slate-300 hover:bg-pitch-750 text-xs font-semibold transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs uppercase tracking-wider transition-colors flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span><?= $isEdit ? 'Simpan Perubahan' : 'Tambahkan Pemain' ?></span>
                </button>
            </div>

        </form>

    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.getElementById('fotoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(evt) {
            document.getElementById('imagePreview').src = evt.target.result;
        };
        reader.readAsDataURL(file);
    }
});
</script>
<?= $this->endSection() ?>
