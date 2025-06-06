Aplikasi sistem daftar hadir rapat MAN 1 Jember lengkap menggunakan PHP, MySQL, dan Tailwind CSS dengan spesifikasi berikut:

1. Basis Data:
   - Rancang struktur database MySQL yang efisien untuk menyimpan data rapat, peserta, dan kehadiran
   - Buat tabel untuk pengguna (admin, pimpinan, staff), rapat, departemen, dan rekaman kehadiran
   - Sertakan query SQL lengkap untuk membuat semua tabel dengan relasi yang tepat
   - Buat diagram ER (Entity Relationship) untuk visualisasi struktur database

2. Tampilan Antarmuka:
   - Desain antarmuka responsif dan modern menggunakan Tailwind CSS
   - Buat halaman login yang aman dan menarik
   - Rancang dashboard khusus untuk setiap level pengguna
   - Implementasikan form untuk menambah/mengedit rapat dan mencatat kehadiran
   - Buat tampilan laporan yang informatif dan mudah dibaca

3. Fitur Utama:
   - Sistem login multi-level (superadmin, admin, notulen)
   - Peserta rapat tidak perlu login, cukun scan QR atau masukkan PIN
   - Manajemen data rapat (judul, tanggal, waktu, lokasi, agenda, peserta)
   - Pencatatan kehadiran dengan berbagai metode (manual, QR code, atau PIN)
   - Fitur absensi dengan tanda tangan digital
   - Laporan kehadiran yang dapat difilter (per tanggal, departemen, atau peserta)
   - Ekspor data ke PDF, Excel, dan CSV
   - Notifikasi email untuk pengingat rapat

4. Pengembangan Kode:
   - Gunakan PHP Prosedural, namun aman dari serangan
   - Implementasikan pengamanan dasar (anti SQL injection, validasi form, sanitasi data)
   - Buat kode yang modular dan mudah dipelihara
   - Tulis dokumentasi kode yang jelas dan lengkap
   - Sertakan petunjuk instalasi step-by-step

5. Fitur Tambahan:
   - Pencarian dan filter data yang cepat
   - Dashboard dengan grafik dan statistik kehadiran
   - Pencatatan notulen rapat
   - Unggah lampiran dokumen rapat
   - Sistem pengingat otomatis
   - Mode gelap/terang
