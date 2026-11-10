# ⚽ Website Statistik Pemain Bola — Editorial Sports Analytics

Aplikasi web modern berbasis **CodeIgniter 4** & **MySQL** untuk mencatat, mengevaluasi, dan menampilkan statistik performa para pemain sepak bola secara visual, transparan, presisi, dan profesional (mengikuti standar analitik olahraga modern seperti *Opta Sports* dan *Wyscout*).

---

## 🌟 Fitur Utama & Arsitektur Desain

### 1. 🛡️ Portal Pemain & Ketua Pemain (Publik — Tanpa Perlu Login)
Sesuai instruksi proyek, pemain **tidak perlu melakukan login** dan tugas utamanya adalah memantau statistik mereka:
- **Editorial Sports Roster Grid**: Menampilkan ringkasan kelompok atribut berlabel Inggris dan singkat: SHO, PAS, CON, AGI, PHY, dan DEF. Kiper menampilkan DIV, HAN, POS, REF, REA, dan KIC.
- **Spotlight Ketua Pemain (Jose - No. 10)**:
  - Tampil terdepan dengan lencana resmi Kapten Tim (**C**).
  - Memiliki nilai evaluasi **Minggu 1** sempurna bernilai 100 pada seluruh stat sehingga **Overall: 100**.
- **Dossier Detail Pemain**:
  - **Diagram Radar (Spider Chart)** untuk melihat atribut sesuai posisi pemain.
  - **Grafik Tren Garis (Line Chart)** perkembangan performa minggu ke minggu.
  - Lembar evaluasi kronologis dan catatan khusus pelatih.
  - **Fitur Cetak / Simpan PDF**: Tersedia tombol cetak langsung yang sudah dioptimalkan dengan CSS `@media print`.
- **🏆 Papan Peringkat (Leaderboard)**:
  - Peringkat atlet teratas dalam kategori *Overall*, SHO, PAS, CON, AGI, PHY, dan DEF.
- **⚔️ Komparasi Head-to-Head**:
  - Matriks perbandingan 2 atlet dengan diagram radar ganda (*dual-overlay*) dan atribut sesuai posisi.
- **Filter Fleksibel**: Filter berdasarkan periode minggu (*Minggu 1*, *Minggu 2*), posisi bermain, serta pencarian instan nama atau nomor punggung.

---

### 2. 🔐 Panel Pelatih
Pelatih dapat mengelola data skuad dan menginput penilaian berkala:
- **Autentikasi Aman**: Dilindungi middleware filter `adminAuth`, session management, dan enkripsi kata sandi `password_hash` (bcrypt).
- **Dashboard Pelatih**: Ringkasan skuad, rata-rata tim, dan evaluasi terbaru.
- **Manajemen Pemain (CRUD)**:
  - Tambah, edit, dan hapus pemain.
  - Unggah foto profil pemain dengan *live preview*.
  - Pengaturan status **Ketua Pemain / Kapten Tim (C)**.
- **Workstation Input Statistik (Kalkulasi Otomatis)**:
  - Input indikator (misal: *Minggu 1*, *Minggu 2*) dan tanggal evaluasi.
  - Input hanya stat berbahasa Inggris dan singkat: SHO, PAS, CON, AGI, PHY, DEF. Kiper memakai DIV, HAN, POS, REF, REA, KIC.
  - Posisi spesifik menggunakan bobot tiap peran (ST, CF, RW/LW, CAM, RM/LM, CM, CDM, RWB/LWB, RB/LB, CB). Baris bobot 99% dinormalisasi dengan jumlah bobotnya.
  - Kiper menggunakan Diving, Handling, Positioning, Reflexes, Reactions, dan Kicking dengan bobot 24%, 22%, 22%, 22%, 6%, dan 4%.
  - **Real-Time Live Calculator** menampilkan skor Overall dari stat dan bobot posisi; nilai akhir dibulatkan ke bilangan bulat.
  - Rumus outfield: `Overall = jumlah(stat × bobot posisi)`. Untuk bobot berjumlah 99%, hasil dibagi dengan jumlah bobot aktual.

| Posisi | SHO | PAS | CON | AGI | PHY | DEF |
|---|---:|---:|---:|---:|---:|---:|
| Penyerang utama (ST) | 58% | 0% | 29% | 10% | 3% | 0% |
| Penyerang tengah (CF) | 46% | 12% | 34% | 8% | 0% | 0% |
| Sayap kanan/kiri (RW/LW) | 32% | 27% | 32% | 8% | 0% | 0% |
| Gelandang serang (CAM) | 28% | 32% | 32% | 8% | 0% | 0% |
| Gelandang kanan/kiri (RM/LM) | 7% | 42% | 33% | 13% | 5% | 0% |
| Gelandang tengah (CM) | 13% | 40% | 27% | 0% | 6% | 14% |
| Gelandang bertahan (CDM) | 0% | 32% | 18% | 0% | 17% | 32% |
| Bek sayap (RWB/LWB) | 0% | 20% | 25% | 7% | 8% | 40% |
| Bek kanan/kiri (RB/LB) | 0% | 14% | 16% | 5% | 14% | 51% |
| Bek tengah (CB) | 0% | 6% | 11% | 0% | 24% | 59% |

RW/LW dan CDM masing-masing berjumlah 99%; perhitungan membagi dengan total bobot aktual. Untuk GK, Overall memakai DIV 24%, HAN 22%, POS 22%, REF 22%, REA 6%, dan KIC 4%.
  - **Status Tingkatan Performa (Tier)**: Menentukan klasifikasi atlet secara otomatis (*S-Tier Elite*, *A-Tier*, *B-Tier*, *C-Tier*, *D-Tier*).
  - **Live Radar Preview**: Grafik diagram radar ikut menyesuaikan secara dinamis sebelum disimpan.
  - Catatan evaluasi / rekomendasi pelatih untuk pemain.

---

## 🔑 Akun Pelatih

- **URL Akses**: `http://localhost:8080/login`
- **Username**: `admin`
- **Password**: `admin123`

---

## 🚀 Panduan Menjalankan Aplikasi

### Menjalankan via CodeIgniter Spark:
1. Pastikan servis MySQL aktif di XAMPP.
2. Buka Terminal / CMD pada direktori proyek (`C:\xampp\htdocs\Statistik_pemain_bola`).
3. Jalankan server:
   ```bash
   php spark serve --port 8080
   ```
4. Buka browser ke: **[http://localhost:8080](http://localhost:8080)**

Jika database diimpor dari dump lama, jalankan migrasi untuk menambahkan field stat serta memetakan posisi lama ke peran spesifik. Data GK lama tidak memiliki rincian enam atribut kiper dan perlu dinilai ulang:

```bash
php spark migrate
```

File dump database SQL tersedia di: `db_statistik_pemain.sql`.
