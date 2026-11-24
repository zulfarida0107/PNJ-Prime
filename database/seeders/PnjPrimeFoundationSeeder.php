<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PortalUser;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class PnjPrimeFoundationSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password123');

        /*
        |--------------------------------------------------------------------------
        | 1. Akun Demo 6 Role (Tabel `users` + Sinkronisasi ke Tabel `user` Auth)
        |--------------------------------------------------------------------------
        */
        $demoUsers = [
            [
                'name' => 'Bagas Pratama',
                'email' => 'bagas@pnj.ac.id',
                'role' => 'mahasiswa',
                'auth_role' => 'mhs',
                'auth_provider' => 'sso_pnj',
                'sso_id' => 'SSO-MHS-2107411058',
                'nim_nip' => '2107411058',
                'prodi_unit' => 'Teknik Elektro - Smt 7',
                'phone' => '081234567890',
                'address' => 'Kampus Baru UI, Jl. Prof. Dr. G.A. Siwabessy, Depok',
                'company_name' => null,
                'nib' => null,
                'npwp' => null,
                'kyc_status' => 'approved',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@inovasiteknologi.co.id',
                'role' => 'mitra',
                'auth_role' => 'mitra',
                'auth_provider' => 'manual',
                'sso_id' => null,
                'nim_nip' => null,
                'prodi_unit' => null,
                'phone' => '081298765432',
                'address' => 'Jl. Raya Industri No. 88, Kawasan Jababeka, Cikarang',
                'company_name' => 'PT Inovasi Teknologi Nusantara',
                'nib' => '1234567890123',
                'npwp' => '0123456789012345',
                'kyc_status' => 'approved',
            ],
            [
                'name' => 'Dr. Rina Wijaya, M.T.',
                'email' => 'admin.p3m@pnj.ac.id',
                'role' => 'admin_p3m',
                'auth_role' => 'p3m',
                'auth_provider' => 'sso_pnj',
                'sso_id' => 'SSO-P3M-001',
                'nim_nip' => '198204152008012002',
                'prodi_unit' => 'P3M Politeknik Negeri Jakarta',
                'phone' => '081211112222',
                'address' => 'Gedung Direktorat Lt. 2, Kampus PNJ Depok',
                'company_name' => null,
                'nib' => null,
                'npwp' => null,
                'kyc_status' => 'approved',
            ],
            [
                'name' => 'Hendra Kusuma, S.T.',
                'email' => 'admin.rtpu@pnj.ac.id',
                'role' => 'admin_rtpu',
                'auth_role' => 'rtpu',
                'auth_provider' => 'sso_pnj',
                'sso_id' => 'SSO-RTPU-001',
                'nim_nip' => '198509202010121003',
                'prodi_unit' => 'UPA-RTPU Politeknik Negeri Jakarta',
                'phone' => '081233334444',
                'address' => 'Gedung RTPU, Kampus PNJ Depok',
                'company_name' => null,
                'nib' => null,
                'npwp' => null,
                'kyc_status' => 'approved',
            ],
            [
                'name' => 'Ir. Agus Setiawan, M.Eng.',
                'email' => 'pimpinan.rtpu@pnj.ac.id',
                'role' => 'pimpinan_rtpu',
                'auth_role' => 'pimpinan',
                'auth_provider' => 'sso_pnj',
                'sso_id' => 'SSO-PIMP-001',
                'nim_nip' => '197503112001121001',
                'prodi_unit' => 'Kepala UPA-RTPU PNJ',
                'phone' => '081255556666',
                'address' => 'Gedung RTPU Lt. 2, Kampus PNJ Depok',
                'company_name' => null,
                'nib' => null,
                'npwp' => null,
                'kyc_status' => 'approved',
            ],
            [
                'name' => 'Prof. Dr. Syamsurizal, S.E., M.M.',
                'email' => 'direktur@pnj.ac.id',
                'role' => 'direktur',
                'auth_role' => 'pimpinan',
                'auth_provider' => 'sso_pnj',
                'sso_id' => 'SSO-DIR-001',
                'nim_nip' => '196807101994031001',
                'prodi_unit' => 'Direktorat Politeknik Negeri Jakarta',
                'phone' => '081277778888',
                'address' => 'Gedung Direktorat, Kampus PNJ Depok',
                'company_name' => null,
                'nib' => null,
                'npwp' => null,
                'kyc_status' => 'approved',
            ],
        ];

        $portalUsers = [];
        foreach ($demoUsers as $item) {
            $portalUsers[$item['role']] = PortalUser::updateOrCreate(
                ['email' => $item['email']],
                [
                    'name' => $item['name'],
                    'password' => $password,
                    'role' => $item['role'],
                    'nim_nip' => $item['nim_nip'],
                    'prodi_unit' => $item['prodi_unit'],
                    'phone' => $item['phone'],
                    'address' => $item['address'],
                    'company_name' => $item['company_name'],
                    'nib' => $item['nib'],
                    'npwp' => $item['npwp'],
                    'kyc_status' => $item['kyc_status'],
                    'email_verified_at' => now(),
                ]
            );

            // Sinkronisasi ke tabel `user` agar akun demo juga bisa langsung dipakai login di AuthController
            User::updateOrCreate(
                ['email' => $item['email']],
                [
                    'sso_id' => $item['sso_id'],
                    'auth_provider' => $item['auth_provider'],
                    'saic_id' => $item['nim_nip'],
                    'password' => $password,
                    'nama_lengkap' => $item['company_name'] ?? $item['name'],
                    'no_telepon' => $item['phone'],
                    'role' => $item['auth_role'],
                    'status_akun' => 'aktif',
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Master Data P08: Units, 3 Kategori Baku, dan Sub-Kategori
        |--------------------------------------------------------------------------
        */
        $unitsData = [
            ['code' => 'TIK', 'name' => 'Teknik Informatika & Komputer'],
            ['code' => 'MESIN', 'name' => 'Teknik Mesin'],
            ['code' => 'SIPIL', 'name' => 'Teknik Sipil'],
            ['code' => 'ELEKTRO', 'name' => 'Teknik Elektro'],
            ['code' => 'P3M', 'name' => 'Pusat Penelitian & Pengabdian Masyarakat (P3M)'],
        ];

        $units = [];
        foreach ($unitsData as $u) {
            $units[$u['code']] = Unit::updateOrCreate(['code' => $u['code']], $u);
        }

        $categoriesData = [
            ['code' => 'BRG', 'name' => 'Barang'],
            ['code' => 'JSA', 'name' => 'Jasa'],
            ['code' => 'LAB', 'name' => 'Ruangan/Lab'],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[$c['code']] = Category::updateOrCreate(['code' => $c['code']], $c);
        }

        $subCategoriesData = [
            ['code' => 'PENELITIAN', 'name' => 'Produk Penelitian', 'category_code' => 'BRG'],
            ['code' => 'INOVASI', 'name' => 'Inovasi', 'category_code' => 'BRG'],
            ['code' => 'PENGUJIAN', 'name' => 'Layanan Pengujian', 'category_code' => 'JSA'],
            ['code' => 'FABRIKASI', 'name' => 'Layanan Fabrikasi & Lab CNC', 'category_code' => 'LAB'],
        ];

        $subCategories = [];
        foreach ($subCategoriesData as $sc) {
            $subCategories[$sc['code']] = SubCategory::updateOrCreate(
                ['code' => $sc['code']],
                [
                    'category_id' => $categories[$sc['category_code']]->id,
                    'name' => $sc['name'],
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Produk Contoh (Ready Stock, Pre-Order, & Lab CNC 5-Axis)
        |--------------------------------------------------------------------------
        */
        $adminP3m = $portalUsers['admin_p3m'];

        $productsData = [
            [
                'code' => 'PRD-ELK-001',
                'unit_id' => $units['ELEKTRO']->id,
                'category_id' => $categories['BRG']->id,
                'sub_category_id' => $subCategories['PENELITIAN']->id,
                'submitted_by' => $adminP3m->id,
                'name' => 'Microcontroller IoT Starter Kit PNJ',
                'description' => 'Kit praktikum dan prototipe IoT berbasis ESP32 lengkap dengan modul sensor suhu, kelembapan, relay, dan panduan riset terapan PNJ.',
                'order_type' => 'ready_stock',
                'hpp' => 220000,
                'price_b2c' => 350000,
                'price_b2b' => 315000,
                'stock' => 25,
                'image_path' => 'products/iot-starter-kit.png',
                'status' => 'tayang',
                'curation_notes' => 'Lolos kurasi teknis RTPU dan disetujui tayang.',
            ],
            [
                'code' => 'PRD-TIK-002',
                'unit_id' => $units['TIK']->id,
                'category_id' => $categories['BRG']->id,
                'sub_category_id' => $subCategories['INOVASI']->id,
                'submitted_by' => $adminP3m->id,
                'name' => 'Sensor Lingkungan Presisi Tinggi (PNJ-Air)',
                'description' => 'Perangkat pemantau kualitas udara PM2.5, CO2, dan suhu berbasis LoRaWAN hasil inovasi dosen dan mahasiswa TIK PNJ.',
                'order_type' => 'pre_order',
                'hpp' => 310000,
                'price_b2c' => 480000,
                'price_b2b' => 430000,
                'stock' => 10,
                'image_path' => 'products/pnj-air-sensor.png',
                'status' => 'tayang',
                'curation_notes' => 'Estimasi perakitan pre-order 7 hari kerja.',
            ],
            [
                'code' => 'PRD-MSN-003',
                'unit_id' => $units['MESIN']->id,
                'category_id' => $categories['LAB']->id,
                'sub_category_id' => $subCategories['FABRIKASI']->id,
                'submitted_by' => $adminP3m->id,
                'name' => 'Lab CNC 5-Axis & Fabrikasi Presisi',
                'description' => 'Reservasi mesin CNC Milling 5-Axis lengkap dengan operator bersertifikat di Workshop Teknik Mesin PNJ untuk pembuatan komponen presisi.',
                'order_type' => 'lab_reservation',
                'hpp' => 1500000,
                'price_b2c' => 2250000,
                'price_b2b' => 2000000,
                'stock' => 4,
                'image_path' => 'products/lab-cnc-5axis.png',
                'status' => 'tayang',
                'curation_notes' => 'Slot tersedia per sesi 4 jam (pagi / siang).',
            ],
            [
                'code' => 'PRD-SPL-004',
                'unit_id' => $units['SIPIL']->id,
                'category_id' => $categories['JSA']->id,
                'sub_category_id' => $subCategories['PENGUJIAN']->id,
                'submitted_by' => $adminP3m->id,
                'name' => 'Jasa Uji Kuat Tekan Beton & Material Komposit',
                'description' => 'Layanan pengujian kuat tekan beton silinder dan material komposit berstandar SNI/ASTM di Laboratorium Struktur Teknik Sipil PNJ.',
                'order_type' => 'lab_reservation',
                'hpp' => 800000,
                'price_b2c' => 1250000,
                'price_b2b' => 1150000,
                'stock' => 6,
                'image_path' => 'products/uji-beton.png',
                'status' => 'menunggu_kurasi_rtpu',
                'curation_notes' => 'Menunggu verifikasi tarif BLU dan kelengkapan SOP lab.',
            ],
            [
                'code' => 'PRD-P3M-005',
                'unit_id' => $units['P3M']->id,
                'category_id' => $categories['BRG']->id,
                'sub_category_id' => $subCategories['PENELITIAN']->id,
                'submitted_by' => $adminP3m->id,
                'name' => 'Modul Spektrofotometri UV-Vis Portable',
                'description' => 'Instrumen analisis spektrofotometri portabel hasil riset unggulan P3M untuk kebutuhan praktikum dan riset bioteknologi.',
                'order_type' => 'pre_order',
                'hpp' => 850000,
                'price_b2c' => 1250000,
                'price_b2b' => 1100000,
                'stock' => 5,
                'image_path' => 'products/uv-vis-module.png',
                'status' => 'menunggu_sk_pimpinan',
                'curation_notes' => 'Sudah dikurasi RTPU, menunggu pengesahan SK tarif Pimpinan.',
            ],
        ];

        $products = [];
        foreach ($productsData as $p) {
            $products[$p['code']] = Product::updateOrCreate(['code' => $p['code']], $p);
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Contoh Pesanan & Invoice (100% Lunas Tanpa DP)
        |--------------------------------------------------------------------------
        */
        $mahasiswa = $portalUsers['mahasiswa'];
        $mitra = $portalUsers['mitra'];

        // Order 1: B2C Ready Stock (Paid, Diproses)
        $order1 = Order::updateOrCreate(
            ['order_code' => 'ORD-20261124-0001'],
            [
                'user_id' => $mahasiswa->id,
                'segment' => 'b2c',
                'order_type' => 'ready_stock',
                'status' => 'diproses',
                'booking_date' => null,
                'booking_slot' => null,
                'attachment_path' => null,
                'notes' => 'Diambil langsung di loket Lab RTPU Gedung Elektro.',
                'shipping_address' => $mahasiswa->address,
                'estimasi_hari' => 1,
            ]
        );

        OrderItem::updateOrCreate(
            ['order_id' => $order1->id, 'product_id' => $products['PRD-ELK-001']->id],
            [
                'quantity' => 2,
                'unit_price' => 350000,
                'subtotal' => 700000,
            ]
        );

        Invoice::updateOrCreate(
            ['invoice_number' => 'INV-PNJ-20261124-0001'],
            [
                'order_id' => $order1->id,
                'dpp' => 700000,
                'discount_b2b' => 0,
                'shipping_cost' => 0,
                'ppn_amount' => 77000,
                'pph23_amount' => 0,
                'emeterai_amount' => 0,
                'grand_total' => 777000,
                'va_number' => null,
                'payment_method' => 'qris',
                'payment_status' => 'paid',
                'due_at' => Carbon::now()->addDays(1),
                'paid_at' => Carbon::now()->subHours(2),
                'qr_pass_code' => 'QR-PNJ-PRIME-0001',
            ]
        );

        // Order 2: B2B Lab CNC 5-Axis Reservation (Unpaid, Menunggu Pembayaran)
        $order2 = Order::updateOrCreate(
            ['order_code' => 'ORD-20261124-0002'],
            [
                'user_id' => $mitra->id,
                'segment' => 'b2b',
                'order_type' => 'lab_reservation',
                'status' => 'menunggu_pembayaran',
                'booking_date' => Carbon::now()->addDays(5)->toDateString(),
                'booking_slot' => '08:00 - 12:00 WIB',
                'attachment_path' => 'orders/cad-bracket-aluminium.pdf',
                'notes' => 'Fabrikasi 4 unit bracket aluminium 6061 sesuai gambar kerja CAD terlampir.',
                'shipping_address' => $mitra->address,
                'estimasi_hari' => 3,
            ]
        );

        OrderItem::updateOrCreate(
            ['order_id' => $order2->id, 'product_id' => $products['PRD-MSN-003']->id],
            [
                'quantity' => 2,
                'unit_price' => 2000000,
                'subtotal' => 4000000,
            ]
        );

        // Perhitungan B2B: DPP 4.000.000 - Diskon 200.000 = 3.800.000 + PPN 11% (418.000) - PPh 23 2% (76.000) + e-Meterai (10.000) = 4.152.000
        Invoice::updateOrCreate(
            ['invoice_number' => 'INV-PNJ-20261124-0002'],
            [
                'order_id' => $order2->id,
                'dpp' => 4000000,
                'discount_b2b' => 200000,
                'shipping_cost' => 0,
                'ppn_amount' => 418000,
                'pph23_amount' => 76000,
                'emeterai_amount' => 10000,
                'grand_total' => 4152000,
                'va_number' => '8801202611240002',
                'payment_method' => 'va_mandiri_blu',
                'payment_status' => 'unpaid',
                'due_at' => Carbon::now()->addDays(2),
                'paid_at' => null,
                'qr_pass_code' => null,
            ]
        );

        // Order 3: B2C Pre-Order (Paid, Selesai)
        $order3 = Order::updateOrCreate(
            ['order_code' => 'ORD-20261124-0003'],
            [
                'user_id' => $mahasiswa->id,
                'segment' => 'b2c',
                'order_type' => 'pre_order',
                'status' => 'selesai',
                'booking_date' => null,
                'booking_slot' => null,
                'attachment_path' => null,
                'notes' => 'Pesanan perangkat riset tugas akhir semester 7.',
                'shipping_address' => $mahasiswa->address,
                'estimasi_hari' => 7,
            ]
        );

        OrderItem::updateOrCreate(
            ['order_id' => $order3->id, 'product_id' => $products['PRD-TIK-002']->id],
            [
                'quantity' => 1,
                'unit_price' => 480000,
                'subtotal' => 480000,
            ]
        );

        Invoice::updateOrCreate(
            ['invoice_number' => 'INV-PNJ-20261124-0003'],
            [
                'order_id' => $order3->id,
                'dpp' => 480000,
                'discount_b2b' => 0,
                'shipping_cost' => 15000,
                'ppn_amount' => 52800,
                'pph23_amount' => 0,
                'emeterai_amount' => 0,
                'grand_total' => 547800,
                'va_number' => '8801202611240003',
                'payment_method' => 'va_mandiri_blu',
                'payment_status' => 'paid',
                'due_at' => Carbon::now()->subDays(5),
                'paid_at' => Carbon::now()->subDays(6),
                'qr_pass_code' => 'QR-PNJ-PRIME-0003',
            ]
        );
    }
}
