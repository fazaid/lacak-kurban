# NPC Kurban Tracker

Platform pelacakan kurban yang transparan dan terpercaya untuk **Nusantara Palestina Center (NPC)**. Donatur dapat memantau setiap tahapan kurban mereka secara real-time — mulai dari pembelian hewan hingga distribusi daging dan laporan akhir.

---

## Fitur Utama

### Publik (Donatur)
- **Lacak Kurban** — cari data kurban menggunakan kode referensi atau email
- **Profil Kurban** — detail donatur, jenis hewan, tipe kurban (Palestina / Nusantara), dan porsi
- **Progress** — pantau 5 tahap: Pembelian → Penyembelihan → Menuju Distribusi → Distribusi → Laporan
- **Galeri Foto** — dokumentasi foto per kategori tahapan
- **Sertifikat** — unduh atau cetak sertifikat kurban (PDF)

### Admin Panel (`/admin`)
| Fitur | Admin | Staff | Viewer |
|---|:---:|:---:|:---:|
| Lihat data kurban & statistik | ✓ | ✓ | ✓ |
| Tambah / edit / hapus kurban | ✓ | ✓ | — |
| Import CSV (bulk) | ✓ | ✓ | — |
| Export CSV | ✓ | ✓ | — |
| Upload foto galeri | ✓ | ✓ | — |
| Generate sertifikat | ✓ | ✓ | — |
| Manajemen pengguna | ✓ | — | — |
| Pengaturan tanda tangan | ✓ | — | — |

---

## Tech Stack

- **Backend**: PHP 8.3 + Laravel 13
- **Frontend**: Tailwind CSS + Vite + Tabler Icons
- **PDF**: barryvdh/laravel-dompdf
- **Database**: SQLite (default) / MySQL

---

## Instalasi

```bash
# 1. Clone repo
git clone <repo-url>
cd Lacak-Kurban

# 2. Setup otomatis (install, migrate, build)
composer run setup

# 3. Buat user admin pertama
php artisan tinker
> User::create(['name'=>'Admin','email'=>'admin@example.com','password'=>bcrypt('password'),'role'=>'admin'])
```

### Jalankan development server

```bash
composer run dev
```

Perintah ini menjalankan secara bersamaan: Laravel server, queue worker, log viewer (Pail), dan Vite dev server.

---

## Tipe Kurban & Hewan

| Tipe | Hewan Tersedia | Porsi Kolektif |
|---|---|---|
| Palestina | Unta, Sapi, Domba | Unta 1/10 · Sapi 1/7 |
| Nusantara | Sapi, Domba | Sapi 1/7 |

> Domba hanya tersedia sebagai kurban penuh (tidak bisa kolektif).

---

## Tahapan Progress

1. **Pembelian Hewan** — hewan kurban telah dibeli
2. **Penyembelihan** — hewan telah disembelih sesuai syariat
3. **Menuju Distribusi** — daging dalam perjalanan ke lokasi distribusi
4. **Distribusi Daging** — daging telah didistribusikan ke penerima manfaat
5. **Laporan** — laporan pelaksanaan selesai dibuat

---

## Struktur Role

- **admin** — akses penuh termasuk manajemen pengguna dan pengaturan
- **staff** — kelola data kurban, import/export, upload foto, generate sertifikat
- **viewer** — hanya bisa melihat data dan statistik

---

## Perintah Berguna

```bash
# Jalankan test
composer run test

# Generate sertifikat PDF
php artisan tinker
> $s = Sacrifice::find(1); app(App\Http\Controllers\SacrificeAdminController::class)->generateCertificate(request(), $s);
```

---

## Lisensi

Aplikasi ini dikembangkan untuk keperluan internal NPC (Nusantara Palestina Center).
