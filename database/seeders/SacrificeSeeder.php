<?php

namespace Database\Seeders;

use App\Models\Sacrifice;
use Illuminate\Database\Seeder;

class SacrificeSeeder extends Seeder
{
    public function run(): void
    {
        $sacrifices = [

            // ── Budi Santoso: 3 kurban berbeda (demo multi-kurban 1 donatur) ─

            [
                'reference_code'              => 'NPC-2024-001',
                'sacrifice_type'              => 'palestina',
                'donor_name'                  => 'Budi Santoso',
                'donor_email'                 => 'budi@example.com',
                'donor_phone'                 => '081234567890',
                'animal_type'                 => 'unta',
                'animal_price'                => 120000000.00,
                'sharing_type'                => 'full',
                'share_ratio'                 => 1,
                'purchase_date'               => '2024-06-08',
                'purchase_location'           => 'Peternak Unta via Mitra Jordania',
                'slaughter_location'          => 'Zona Penyembelihan Gaza — Program NPC Palestina',
                'beneficiary_name'            => 'Keluarga Pengungsi Gaza Utara',
                'beneficiary_address'         => 'Kamp Pengungsi Gaza Utara, Palestina',
                'beneficiary_type'            => 'Pengungsi',
                'status_purchase'             => 'completed',
                'date_purchase_completed'     => '2024-06-08',
                'status_slaughter'            => 'completed',
                'date_slaughter_completed'    => '2024-06-17',
                'status_on_way'               => 'completed',
                'date_on_way_completed'       => '2024-06-18',
                'status_distribution'         => 'completed',
                'date_distribution_completed' => '2024-06-19',
                'status_report'               => 'completed',
                'date_report_completed'       => '2024-06-22',
                'certificate_generated_at'    => '2024-06-22 10:00:00',
                'notes'                       => 'Unta penuh Palestina. 1 unta = manfaat untuk ~70 keluarga. Dilaksanakan oleh mitra terpercaya di Gaza.',
            ],

            [
                'reference_code'              => 'NPC-2024-002',
                'sacrifice_type'              => 'palestina',
                'donor_name'                  => 'Budi Santoso',        // SAME DONOR
                'donor_email'                 => 'budi@example.com',
                'donor_phone'                 => '081234567890',
                'animal_type'                 => 'sapi',
                'animal_price'                => 70000000.00,
                'sharing_type'                => 'collective',
                'share_ratio'                 => 1,                     // 1/7 sapi
                'purchase_date'               => '2024-06-10',
                'purchase_location'           => 'Mitra Peternak Tepi Barat',
                'slaughter_location'          => 'Zona Distribusi Palestina — Program NPC',
                'beneficiary_name'            => 'Warga Sipil Gaza Selatan',
                'beneficiary_address'         => 'Gaza Selatan, Palestina',
                'beneficiary_type'            => 'Warga Sipil',
                'status_purchase'             => 'completed',
                'date_purchase_completed'     => '2024-06-10',
                'status_slaughter'            => 'completed',
                'date_slaughter_completed'    => '2024-06-17',
                'status_on_way'               => 'completed',
                'date_on_way_completed'       => '2024-06-19',
                'status_distribution'         => 'completed',
                'date_distribution_completed' => '2024-06-20',
                'status_report'               => 'completed',
                'date_report_completed'       => '2024-06-23',
                'certificate_generated_at'    => '2024-06-23 09:00:00',
                'notes'                       => 'Kurban 1/7 sapi Palestina. Kolektif bersama 6 donatur lainnya.',
            ],

            [
                'reference_code'              => 'NPC-2024-003',
                'sacrifice_type'              => 'nusantara',
                'donor_name'                  => 'Budi Santoso',        // SAME DONOR
                'donor_email'                 => 'budi@example.com',
                'donor_phone'                 => '081234567890',
                'animal_type'                 => 'domba',
                'animal_price'                => 9000000.00,
                'sharing_type'                => 'full',
                'share_ratio'                 => 1,
                'purchase_date'               => '2024-06-11',
                'purchase_location'           => 'Peternak Lokal Bantul, Yogyakarta',
                'slaughter_location'          => 'Masjid Al-Ikhlas, Bantul',
                'beneficiary_name'            => 'Panti Asuhan Nurul Huda',
                'beneficiary_address'         => 'Jl. Imogiri Barat Km 5, Bantul, Yogyakarta',
                'beneficiary_type'            => 'Panti Asuhan',
                'status_purchase'             => 'completed',
                'date_purchase_completed'     => '2024-06-11',
                'status_slaughter'            => 'completed',
                'date_slaughter_completed'    => '2024-06-17',
                'status_on_way'               => 'completed',
                'date_on_way_completed'       => '2024-06-18',
                'status_distribution'         => 'completed',
                'date_distribution_completed' => '2024-06-18',
                'status_report'               => 'pending',
                'date_report_completed'       => null,
                'certificate_generated_at'    => null,
                'notes'                       => 'Domba penuh Nusantara. Daging sudah diserahkan ke panti.',
            ],

            // ── Donatur lain: selesai ────────────────────────────────────────

            [
                'reference_code'              => 'NPC-2024-004',
                'sacrifice_type'              => 'nusantara',
                'donor_name'                  => 'Ahmad Wijaya',
                'donor_email'                 => 'ahmad@example.com',
                'donor_phone'                 => '082345678901',
                'animal_type'                 => 'sapi',
                'animal_price'                => 28000000.00,
                'sharing_type'                => 'full',
                'share_ratio'                 => 1,
                'purchase_date'               => '2024-06-10',
                'purchase_location'           => 'Pasar Ternak Jawa Timur',
                'slaughter_location'          => 'Rumah Pemotongan Hewan NPC Jakarta',
                'beneficiary_name'            => 'Anak-Anak Yatim Piatu',
                'beneficiary_address'         => 'Panti Asuhan Al-Ikhlas Jakarta Timur',
                'beneficiary_type'            => 'Yatim Piatu',
                'status_purchase'             => 'completed',
                'date_purchase_completed'     => '2024-06-10',
                'status_slaughter'            => 'completed',
                'date_slaughter_completed'    => '2024-06-17',
                'status_on_way'               => 'completed',
                'date_on_way_completed'       => '2024-06-17',
                'status_distribution'         => 'completed',
                'date_distribution_completed' => '2024-06-18',
                'status_report'               => 'completed',
                'date_report_completed'       => '2024-06-20',
                'certificate_generated_at'    => '2024-06-20 08:00:00',
                'notes'                       => 'Kurban berjalan lancar. Daging dibagikan ke 52 anak yatim piatu.',
            ],

            [
                'reference_code'              => 'NPC-2024-005',
                'sacrifice_type'              => 'nusantara',
                'donor_name'                  => 'Siti Nurhaliza',
                'donor_email'                 => 'siti@example.com',
                'donor_phone'                 => '083456789012',
                'animal_type'                 => 'domba',
                'animal_price'                => 2800000.00,
                'sharing_type'                => 'full',
                'share_ratio'                 => 1,
                'purchase_date'               => '2024-06-11',
                'purchase_location'           => 'Pasar Hewan Godean, Sleman',
                'slaughter_location'          => 'Masjid Baitul Muttaqin, Depok Sleman',
                'beneficiary_name'            => 'Masyarakat Desa Maguwoharjo',
                'beneficiary_address'         => 'Desa Maguwoharjo, Depok, Sleman, Yogyakarta',
                'beneficiary_type'            => 'Masyarakat Umum',
                'status_purchase'             => 'completed',
                'date_purchase_completed'     => '2024-06-11',
                'status_slaughter'            => 'completed',
                'date_slaughter_completed'    => '2024-06-17',
                'status_on_way'               => 'completed',
                'date_on_way_completed'       => '2024-06-17',
                'status_distribution'         => 'completed',
                'date_distribution_completed' => '2024-06-17',
                'status_report'               => 'completed',
                'date_report_completed'       => '2024-06-19',
                'certificate_generated_at'    => '2024-06-19 09:00:00',
                'notes'                       => 'Pelaksanaan berjalan sesuai rencana. Dokumentasi lengkap.',
            ],

            // ── Sedang berjalan ──────────────────────────────────────────────

            [
                'reference_code'              => 'NPC-2024-006',
                'sacrifice_type'              => 'nusantara',
                'donor_name'                  => 'Dewi Kurniasih',
                'donor_email'                 => 'dewi.k@example.com',
                'donor_phone'                 => '084567890123',
                'animal_type'                 => 'sapi',
                'animal_price'                => 28000000.00,
                'sharing_type'                => 'collective',
                'share_ratio'                 => 1,                     // 1/7 sapi
                'purchase_date'               => '2024-06-09',
                'purchase_location'           => 'Pasar Hewan Ngawi, Jawa Timur',
                'slaughter_location'          => 'Rumah Pemotongan Hewan Kota Madiun',
                'beneficiary_name'            => 'Yayasan Rumah Zakat Madiun',
                'beneficiary_address'         => 'Jl. Pahlawan No. 22, Madiun, Jawa Timur',
                'beneficiary_type'            => 'Yayasan',
                'status_purchase'             => 'completed',
                'date_purchase_completed'     => '2024-06-09',
                'status_slaughter'            => 'completed',
                'date_slaughter_completed'    => '2024-06-17',
                'status_on_way'               => 'pending',
                'date_on_way_completed'       => null,
                'status_distribution'         => 'pending',
                'date_distribution_completed' => null,
                'status_report'               => 'pending',
                'date_report_completed'       => null,
                'certificate_generated_at'    => null,
                'notes'                       => 'Kurban 1/7 sapi kolektif. Hewan sudah disembelih, distribusi daging sedang berlangsung.',
            ],

            [
                'reference_code'              => 'NPC-2024-007',
                'sacrifice_type'              => 'palestina',
                'donor_name'                  => 'Eko Prasetyo',
                'donor_email'                 => 'eko.prasetyo@example.com',
                'donor_phone'                 => '085678901234',
                'animal_type'                 => 'unta',
                'animal_price'                => 80000000.00,
                'sharing_type'                => 'collective',
                'share_ratio'                 => 1,                     // 1/10 unta
                'purchase_date'               => '2024-06-14',
                'purchase_location'           => 'Mitra Peternak Jordania',
                'slaughter_location'          => 'Zona Penyembelihan Gaza — Program NPC Palestina',
                'beneficiary_name'            => 'Warga Sipil Gaza Utara',
                'beneficiary_address'         => 'Gaza Utara, Palestina',
                'beneficiary_type'            => 'Warga Sipil',
                'status_purchase'             => 'completed',
                'date_purchase_completed'     => '2024-06-14',
                'status_slaughter'            => 'pending',
                'date_slaughter_completed'    => null,
                'status_on_way'               => 'pending',
                'date_on_way_completed'       => null,
                'status_distribution'         => 'pending',
                'date_distribution_completed' => null,
                'status_report'               => 'pending',
                'date_report_completed'       => null,
                'certificate_generated_at'    => null,
                'notes'                       => 'Kurban 1/10 unta Palestina. Hewan sudah diamankan, menunggu jadwal penyembelihan.',
            ],

            // ── Menunggu ─────────────────────────────────────────────────────

            [
                'reference_code'              => 'NPC-2024-008',
                'sacrifice_type'              => 'nusantara',
                'donor_name'                  => 'Fitri Handayani',
                'donor_email'                 => 'fitri.h@example.com',
                'donor_phone'                 => '086789012345',
                'animal_type'                 => 'domba',
                'animal_price'                => 3200000.00,
                'sharing_type'                => 'full',
                'share_ratio'                 => 1,
                'purchase_date'               => null,
                'purchase_location'           => 'Peternak Gunung Kidul, Yogyakarta (Rencana)',
                'slaughter_location'          => 'Masjid Agung Wonosari, Gunung Kidul (Rencana)',
                'beneficiary_name'            => 'Komunitas Petani Dlingo',
                'beneficiary_address'         => 'Kecamatan Dlingo, Bantul, Yogyakarta',
                'beneficiary_type'            => 'Komunitas',
                'status_purchase'             => 'pending',
                'date_purchase_completed'     => null,
                'status_slaughter'            => 'pending',
                'date_slaughter_completed'    => null,
                'status_on_way'               => 'pending',
                'date_on_way_completed'       => null,
                'status_distribution'         => 'pending',
                'date_distribution_completed' => null,
                'status_report'               => 'pending',
                'date_report_completed'       => null,
                'certificate_generated_at'    => null,
                'notes'                       => 'Menunggu konfirmasi jadwal dari panitia.',
            ],

            [
                'reference_code'              => 'NPC-2024-009',
                'sacrifice_type'              => 'palestina',
                'donor_name'                  => 'Gunawan Setiawan',
                'donor_email'                 => 'gunawan.s@example.com',
                'donor_phone'                 => '087890123456',
                'animal_type'                 => 'sapi',
                'animal_price'                => 35000000.00,
                'sharing_type'                => 'collective',
                'share_ratio'                 => 1,                     // 1/7 sapi
                'purchase_date'               => null,
                'purchase_location'           => null,
                'slaughter_location'          => null,
                'beneficiary_name'            => 'Keluarga Terdampak Konflik Palestina',
                'beneficiary_address'         => 'Gaza, Palestina',
                'beneficiary_type'            => 'Warga Terdampak',
                'status_purchase'             => 'pending',
                'date_purchase_completed'     => null,
                'status_slaughter'            => 'pending',
                'date_slaughter_completed'    => null,
                'status_on_way'               => 'pending',
                'date_on_way_completed'       => null,
                'status_distribution'         => 'pending',
                'date_distribution_completed' => null,
                'status_report'               => 'pending',
                'date_report_completed'       => null,
                'certificate_generated_at'    => null,
                'notes'                       => 'Dana sudah masuk untuk 1/7 sapi program Palestina. Proses koordinasi dengan mitra.',
            ],

            // ── Spesial: sapi kolektif penuh (semua 7 slot terisi) ──────────

            [
                'reference_code'              => 'NPC-2024-010',
                'sacrifice_type'              => 'nusantara',
                'donor_name'                  => 'Joko Wibowo (Kurban Kolektif RT 05)',
                'donor_email'                 => 'joko.wibowo@example.com',
                'donor_phone'                 => '081098765432',
                'animal_type'                 => 'sapi',
                'animal_price'                => 35000000.00,
                'sharing_type'                => 'collective',
                'share_ratio'                 => 1,                     // 1/7 sapi
                'purchase_date'               => '2024-06-08',
                'purchase_location'           => 'Pasar Hewan Ambarawa, Semarang',
                'slaughter_location'          => 'Lapangan RT 05 Perumahan Griya Asri, Ngaliyan Semarang',
                'beneficiary_name'            => 'Warga RT 05 & Sekitar',
                'beneficiary_address'         => 'Perumahan Griya Asri RT 05/08, Ngaliyan, Semarang',
                'beneficiary_type'            => 'Kurban Kolektif',
                'status_purchase'             => 'completed',
                'date_purchase_completed'     => '2024-06-08',
                'status_slaughter'            => 'completed',
                'date_slaughter_completed'    => '2024-06-17',
                'status_on_way'               => 'completed',
                'date_on_way_completed'       => '2024-06-17',
                'status_distribution'         => 'completed',
                'date_distribution_completed' => '2024-06-17',
                'status_report'               => 'completed',
                'date_report_completed'       => '2024-06-20',
                'certificate_generated_at'    => '2024-06-20 14:00:00',
                'notes'                       => 'Kurban kolektif 1/7 sapi dari 7 kepala keluarga RT 05. Sapi terpilih dengan berat terbaik. Daging dibagikan ke 60 keluarga termasuk 15 keluarga kurang mampu di sekitar perumahan. Dokumentasi foto dan video lengkap tersedia.',
            ],
        ];

        $this->command->info('Seeding sacrifices...');
        $this->command->newLine();

        $counts = ['created' => 0, 'updated' => 0];

        foreach ($sacrifices as $data) {
            $sacrifice = Sacrifice::updateOrCreate(
                ['reference_code' => $data['reference_code']],
                $data
            );

            $label   = $sacrifice->wasRecentlyCreated ? '<fg=green>CREATED</>' : '<fg=yellow>UPDATED</>';
            $price   = number_format($data['animal_price'] / 1_000_000, 1) . 'jt';
            $prog    = $data['sacrifice_type'] === 'palestina' ? 'Palestina' : 'Nusantara';
            $sharing = $data['sharing_type'] === 'collective'
                ? "1/" . \App\Models\Sacrifice::getShareRatioForAnimal($data['animal_type']) . " kolektif"
                : 'penuh';

            $this->command->line("  [{$label}] {$data['reference_code']} — {$data['donor_name']} ({$data['animal_type']} {$prog}, {$sharing}, Rp {$price})");
            $sacrifice->wasRecentlyCreated ? $counts['created']++ : $counts['updated']++;
        }

        $this->command->newLine();
        $this->command->info("Done. Created: {$counts['created']}, Updated: {$counts['updated']}.");
        $this->command->newLine();
        $this->command->comment('Demo: Budi Santoso memiliki 3 kurban (NPC-2024-001, 002, 003) — 1 unta penuh + 1 sapi kolektif + 1 domba penuh.');
    }
}
