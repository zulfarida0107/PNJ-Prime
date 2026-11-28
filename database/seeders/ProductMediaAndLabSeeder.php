<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\PortalUser;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class ProductMediaAndLabSeeder extends Seeder
{
    /**
     * Sinkronisasi media gambar produk (public/images/products/*.png) dan
     * 6 fasilitas Sewa Lab secara idempotent tanpa mereset tabel atau merusak relasi order_items.
     */
    public function run(): void
    {
        $units = [
            'TIK' => Unit::firstOrCreate(['code' => 'TIK'], ['name' => 'Teknik Informatika & Komputer']),
            'MESIN' => Unit::firstOrCreate(['code' => 'MESIN'], ['name' => 'Teknik Mesin']),
            'SIPIL' => Unit::firstOrCreate(['code' => 'SIPIL'], ['name' => 'Teknik Sipil']),
            'ELEKTRO' => Unit::firstOrCreate(['code' => 'ELEKTRO'], ['name' => 'Teknik Elektro']),
            'P3M' => Unit::firstOrCreate(['code' => 'P3M'], ['name' => 'Pusat Penelitian & Pengabdian Masyarakat (P3M)']),
        ];

        $categories = [
            'BRG' => Category::firstOrCreate(['code' => 'BRG'], ['name' => 'Barang']),
            'JSA' => Category::firstOrCreate(['code' => 'JSA'], ['name' => 'Jasa']),
            'LAB' => Category::firstOrCreate(['code' => 'LAB'], ['name' => 'Ruangan/Lab']),
        ];

        $subCategories = [
            'PENELITIAN' => SubCategory::firstOrCreate(
                ['code' => 'PENELITIAN'],
                ['category_id' => $categories['BRG']->id, 'name' => 'Produk Penelitian']
            ),
            'INOVASI' => SubCategory::firstOrCreate(
                ['code' => 'INOVASI'],
                ['category_id' => $categories['BRG']->id, 'name' => 'Inovasi']
            ),
            'PENGUJIAN' => SubCategory::firstOrCreate(
                ['code' => 'PENGUJIAN'],
                ['category_id' => $categories['JSA']->id, 'name' => 'Layanan Pengujian']
            ),
            'FABRIKASI' => SubCategory::firstOrCreate(
                ['code' => 'FABRIKASI'],
                ['category_id' => $categories['LAB']->id, 'name' => 'Layanan Fabrikasi & Lab CNC']
            ),
        ];

        $submittedBy = PortalUser::where('email', 'admin.p3m@pnj.ac.id')->value('id');

        /*
        |--------------------------------------------------------------------------
        | 12 Item Aktif Katalog (status = 'tayang'):
        | - 3 Produk Barang Ready Stock
        | - 3 Produk Barang Pre-Order
        | - 6 Sewa Fasilitas Lab (tarif per jam, grid 3x2 simetris)
        |
        | Catatan: Kode PRD-ELK-001, PRD-TIK-002, dan PRD-MSN-003 dipertahankan
        | agar ID produk eksisting & relasi order_items tetap utuh.
        | 3 file galeri/poster CNC (mesin-cnc-galeri-1.png, mesin-cnc-galeri-2.png,
        | mesin-cnc-poster.png) sengaja TIDAK dibuatkan record produk.
        |--------------------------------------------------------------------------
        */
        $activeCatalogItems = [
            // =================================================================
            // 1. PRODUK BARANG — READY STOCK (3 ITEM)
            // =================================================================
            [
                'code' => 'PRD-ELK-001',
                'unit_id' => $units['ELEKTRO']->id,
                'category_id' => $categories['BRG']->id,
                'sub_category_id' => $subCategories['PENELITIAN']->id,
                'submitted_by' => $submittedBy,
                'name' => 'Modul Smart Sensor IoT untuk Agrikultur (V2.0)',
                'description' => 'Perangkat pemantauan lahan pertanian presisi berbasis mikrokontroler ESP32 & LoRaWAN generasi kedua. Dilengkapi sensor kelembapan tanah, pH, konduktivitas (EC), suhu, dan casing tahan cuaca IP65 hasil riset terapan Teknik Elektro PNJ.',
                'order_type' => 'ready_stock',
                'hpp' => 240000,
                'price_b2c' => 385000,
                'price_b2b' => 350000,
                'show_price' => true,
                'stock' => 30,
                'image_path' => 'images/products/smart-sensor-iot-agrikultur.png',
                'status' => 'tayang',
                'curation_notes' => 'Lolos kurasi teknis UPA-RTPU PNJ. Ready stock siap kirim dengan dokumentasi integrasi API dan garansi modul 6 bulan.',
            ],
            [
                'code' => 'PRD-ELK-002',
                'unit_id' => $units['ELEKTRO']->id,
                'category_id' => $categories['BRG']->id,
                'sub_category_id' => $subCategories['INOVASI']->id,
                'submitted_by' => $submittedBy,
                'name' => 'Autonomous Guided Vehicle (AGV) Logistik Gudang',
                'description' => 'Kendaraan pemindah material otonom kapasitas angkut 250 kg dengan navigasi LiDAR SLAM, sensor pengaman anti-tabrakan 360 derajat, baterai LiFePO4 tahan lama, serta konektivitas sistem manajemen gudang (WMS) industri.',
                'order_type' => 'ready_stock',
                'hpp' => 28500000,
                'price_b2c' => 38500000,
                'price_b2b' => 35000000,
                'show_price' => true,
                'stock' => 4,
                'image_path' => 'images/products/agv-logistik-gudang.png',
                'status' => 'tayang',
                'curation_notes' => 'Tervalidasi pada lingkungan gudang industri (TRL 7). Termasuk kalibrasi rute awal dan pelatihan operator.',
            ],
            [
                'code' => 'PRD-TIK-003',
                'unit_id' => $units['TIK']->id,
                'category_id' => $categories['BRG']->id,
                'sub_category_id' => $subCategories['INOVASI']->id,
                'submitted_by' => $submittedBy,
                'name' => 'Sistem Deteksi Anomali Kualitas Produk Berbasis Vision AI',
                'description' => 'Unit inspeksi visual otomatis berbasis Edge AI dan kamera industri berkecepatan tinggi untuk mendeteksi cacat permukaan, dimensi, maupun kelengkapan produk di lini konveyor secara real-time dengan akurasi di atas 98%.',
                'order_type' => 'ready_stock',
                'hpp' => 14500000,
                'price_b2c' => 19500000,
                'price_b2b' => 17500000,
                'show_price' => true,
                'stock' => 8,
                'image_path' => 'images/products/vision-ai-anomali.png',
                'status' => 'tayang',
                'curation_notes' => 'Lolos uji operasional lini manufaktur PNJ. Siap instalasi plug-and-play dengan dasbor pelaporan defect otomatis.',
            ],

            // =================================================================
            // 2. PRODUK BARANG — PRE-ORDER (3 ITEM)
            // =================================================================
            [
                'code' => 'PRD-TIK-002',
                'unit_id' => $units['TIK']->id,
                'category_id' => $categories['BRG']->id,
                'sub_category_id' => $subCategories['INOVASI']->id,
                'submitted_by' => $submittedBy,
                'name' => 'Sistem Monitoring IoT untuk Efisiensi Energi Pabrik',
                'description' => 'Platform pemantauan konsumsi daya listrik 3-fasa berbasis gateway Modbus/MQTT dan dasbor analitik beban puncak untuk mendeteksi anomali pemakaian energi mesin pabrik serta mendukung audit ISO 50001.',
                'order_type' => 'pre_order',
                'hpp' => 8500000,
                'price_b2c' => 12000000,
                'price_b2b' => 10500000,
                'show_price' => true,
                'stock' => 10,
                'image_path' => 'images/products/iot-energi-pabrik.png',
                'status' => 'tayang',
                'curation_notes' => 'Estimasi perakitan dan kalibrasi panel sensor pre-order 7 hari kerja. Mendukung integrasi SCADA dan alert Telegram/email.',
            ],
            [
                'code' => 'PRD-MSN-004',
                'unit_id' => $units['MESIN']->id,
                'category_id' => $categories['BRG']->id,
                'sub_category_id' => $subCategories['PENELITIAN']->id,
                'submitted_by' => $submittedBy,
                'name' => 'Material Komposit Serat Alam Ringan Otomotif',
                'description' => 'Panel material komposit ramah lingkungan berbahan penguat serat alam rami/kenaf dan matriks epoksi berkekuatan tarik tinggi dengan bobot 35% lebih ringan untuk komponen interior, penutup baterai, dan bodi kendaraan listrik.',
                'order_type' => 'pre_order',
                'hpp' => 1800000,
                'price_b2c' => 2750000,
                'price_b2b' => 2450000,
                'show_price' => true,
                'stock' => 15,
                'image_path' => 'images/products/komposit-serat-alam.png',
                'status' => 'tayang',
                'curation_notes' => 'Produksi pre-order sesuai spesifikasi dimensi cetakan mitra (estimasi 7 hari kerja), dilengkapi hasil uji tarik laboratorium.',
            ],
            [
                'code' => 'PRD-MSN-005',
                'unit_id' => $units['MESIN']->id,
                'category_id' => $categories['BRG']->id,
                'sub_category_id' => $subCategories['INOVASI']->id,
                'submitted_by' => $submittedBy,
                'name' => 'Mesin CNC 5-Axis Presisi Tinggi Skala UMKM',
                'description' => 'Mesin milling CNC 5-sumbu simultan rancangan Jurusan Teknik Mesin PNJ dengan kepresisian posisi ±0,02 mm, rangka rigid berperedam getaran, dan kontroler industri kompak untuk manufaktur komponen kompleks skala UMKM.',
                'order_type' => 'pre_order',
                'hpp' => 62000000,
                'price_b2c' => 82000000,
                'price_b2b' => 75000000,
                'show_price' => true,
                'stock' => 3,
                'image_path' => 'images/products/mesin-cnc-5axis.png',
                'status' => 'tayang',
                'curation_notes' => 'Pre-order perakitan & uji akurasi geometris 14 hari kerja. Termasuk instalasi, training CAM dasar, dan garansi servis 1 tahun.',
            ],

            // =================================================================
            // 3. SEWA FASILITAS LAB (6 ITEM — GRID 3x2 SIMETRIS, TARIF PER JAM)
            // =================================================================
            [
                'code' => 'LAB-SPL-001',
                'unit_id' => $units['SIPIL']->id,
                'category_id' => $categories['LAB']->id,
                'sub_category_id' => $subCategories['FABRIKASI']->id,
                'submitted_by' => $submittedBy,
                'name' => 'Lab Uji Material Terpadu',
                'description' => 'Fasilitas pengujian mekanik dan karakterisasi material berstandar SNI/ASTM di Kampus PNJ Depok. Dilengkapi Universal Testing Machine (UTM), alat uji tekan beton, uji tarik logam/komposit, uji kekerasan, dan preparasi metalografi.',
                'order_type' => 'lab_reservation',
                'hpp' => 240000,
                'price_b2c' => 350000,
                'price_b2b' => 350000,
                'show_price' => true,
                'stock' => 8,
                'image_path' => 'images/products/lab-uji-material.png',
                'status' => 'tayang',
                'curation_notes' => 'Tarif resmi BLU Rp 350.000/jam termasuk pendampingan laboran tersertifikasi dan lembar data hasil pengujian.',
            ],
            [
                'code' => 'LAB-TIK-002',
                'unit_id' => $units['TIK']->id,
                'category_id' => $categories['LAB']->id,
                'sub_category_id' => $subCategories['FABRIKASI']->id,
                'submitted_by' => $submittedBy,
                'name' => 'Studio Desain Grafis',
                'description' => 'Studio produksi kreatif dan komunikasi visual profesional dilengkapi workstation layar warna terkalibrasi sRGB/DCI-P3, pen display grafis, perangkat lunak desain industri, serta area dokumentasi foto prototipe produk.',
                'order_type' => 'lab_reservation',
                'hpp' => 135000,
                'price_b2c' => 200000,
                'price_b2b' => 200000,
                'show_price' => true,
                'stock' => 10,
                'image_path' => 'images/products/studio-desain-grafis.png',
                'status' => 'tayang',
                'curation_notes' => 'Tarif resmi BLU Rp 200.000/jam. Tersedia slot harian dengan dukungan konektivitas jaringan berkecepatan tinggi.',
            ],
            [
                'code' => 'LAB-MSN-003',
                'unit_id' => $units['MESIN']->id,
                'category_id' => $categories['LAB']->id,
                'sub_category_id' => $subCategories['FABRIKASI']->id,
                'submitted_by' => $submittedBy,
                'name' => 'Workshop Mesin CNC',
                'description' => 'Workshop pemesinan CNC Milling 3–5 Axis dan CNC Turning presisi tinggi di Jurusan Teknik Mesin PNJ. Cocok untuk pembuatan mold, dies, jig, dan komponen prototipe logam maupun polimer teknik sesuai desain CAD/CAM.',
                'order_type' => 'lab_reservation',
                'hpp' => 350000,
                'price_b2c' => 500000,
                'price_b2b' => 500000,
                'show_price' => true,
                'stock' => 6,
                'image_path' => 'images/products/workshop-mesin-cnc.png',
                'status' => 'tayang',
                'curation_notes' => 'Tarif resmi BLU Rp 500.000/jam termasuk operator CNC bersertifikat. Unggah file CAD (.STEP/.IGES/.DXF) saat reservasi.',
            ],
            [
                'code' => 'LAB-ELK-004',
                'unit_id' => $units['ELEKTRO']->id,
                'category_id' => $categories['LAB']->id,
                'sub_category_id' => $subCategories['FABRIKASI']->id,
                'submitted_by' => $submittedBy,
                'name' => 'Laboratorium Robotika & Otomasi Industri',
                'description' => 'Laboratorium otomasi terpadu yang dilengkapi lengan robot industri 6-DOF, stasiun PLC/SCADA, sistem pneumatik-elektropneumatik, serta meja pengujian integrasi sensor dan kontrol aktuator industri.',
                'order_type' => 'lab_reservation',
                'hpp' => 275000,
                'price_b2c' => 400000,
                'price_b2b' => 400000,
                'show_price' => true,
                'stock' => 6,
                'image_path' => 'images/products/lab-robotika-otomasi.png',
                'status' => 'tayang',
                'curation_notes' => 'Tarif resmi BLU Rp 400.000/jam. Ideal untuk pengujian integrasi otomasi, validasi PLC, dan uji coba prototipe robotika.',
            ],
            [
                'code' => 'LAB-TIK-005',
                'unit_id' => $units['TIK']->id,
                'category_id' => $categories['LAB']->id,
                'sub_category_id' => $subCategories['FABRIKASI']->id,
                'submitted_by' => $submittedBy,
                'name' => 'Laboratorium Komputer Terpadu (HPC & AI Workstation)',
                'description' => 'Fasilitas High-Performance Computing (HPC) dan klaster GPU AI Workstation untuk pelatihan model Deep Learning, analitik big data, simulasi numerik rekayasa (CFD/FEA), serta rendering visualisasi 3D skala industri.',
                'order_type' => 'lab_reservation',
                'hpp' => 390000,
                'price_b2c' => 560000,
                'price_b2b' => 560000,
                'show_price' => true,
                'stock' => 6,
                'image_path' => 'images/products/lab-komputer-hpc.png',
                'status' => 'tayang',
                'curation_notes' => 'Tarif resmi BLU Rp 560.000/jam termasuk akses lingkungan komputasi GPU terisolasi dan pendampingan admin lab TIK.',
            ],
            [
                'code' => 'PRD-MSN-003',
                'unit_id' => $units['MESIN']->id,
                'category_id' => $categories['LAB']->id,
                'sub_category_id' => $subCategories['FABRIKASI']->id,
                'submitted_by' => $submittedBy,
                'name' => 'Laboratorium Fabrikasi & Manufaktur Presisi',
                'description' => 'Fasilitas fabrikasi logam terintegrasi meliputi pemesinan presisi, pengelasan TIG/MIG, fabrikasi lembaran logam (sheet metal), serta inspeksi dimensi komponen menggunakan alat ukur metrologi presisi di Teknik Mesin PNJ.',
                'order_type' => 'lab_reservation',
                'hpp' => 300000,
                'price_b2c' => 437500,
                'price_b2b' => 437500,
                'show_price' => true,
                'stock' => 8,
                'image_path' => 'images/products/workshop-mesin-cnc.png',
                'status' => 'tayang',
                'curation_notes' => 'Tarif resmi BLU Rp 437.500/jam lengkap dengan teknisi pendamping bersertifikat. Mendukung fabrikasi prototipe dan batch kecil.',
            ],
        ];

        foreach ($activeCatalogItems as $item) {
            Product::updateOrCreate(
                ['code' => $item['code']],
                $item
            );
        }

        // Perbaiki image_path pada data proposal non-tayang lama agar tidak menunjuk ke file fiktif
        Product::where('code', 'PRD-SPL-004')
            ->where('status', '!=', 'tayang')
            ->update(['image_path' => 'images/products/lab-uji-material.png']);

        Product::where('code', 'PRD-P3M-005')
            ->where('status', '!=', 'tayang')
            ->update(['image_path' => 'images/products/smart-sensor-iot-agrikultur.png']);
    }
}
