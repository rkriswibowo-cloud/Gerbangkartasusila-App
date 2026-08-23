<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DestinationCategory;
use App\Models\Destination;
use App\Models\Event;
use App\Models\CulinaryPlace;
use App\Models\Hotel;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Categories
        $catAlam = DestinationCategory::create([
            'name' => 'Wisata Alam',
            'slug' => 'wisata-alam',
            'icon' => 'heroicon-o-tree',
        ]);
        
        $catSejarah = DestinationCategory::create([
            'name' => 'Wisata Sejarah',
            'slug' => 'wisata-sejarah',
            'icon' => 'heroicon-o-building-library',
        ]);

        $catIkon = DestinationCategory::create([
            'name' => 'Ikon Kota',
            'slug' => 'ikon-kota',
            'icon' => 'heroicon-o-map-pin',
        ]);

        // 2. Destinations
        Destination::create([
            'category_id' => $catIkon->id,
            'name' => 'Monumen Simpang Lima Gumul (SLG)',
            'slug' => 'monumen-simpang-lima-gumul',
            'description' => 'Monumen yang menjadi ikon Kabupaten Kediri yang bentuknya menyerupai Arc de Triomphe di Paris, Prancis.',
            'address' => 'Tugurejo, Kec. Ngasem, Kabupaten Kediri',
            'latitude' => -7.8267,
            'longitude' => 112.0626,
            'ticket_price' => 0,
            'opening_hours' => '24 Jam',
            'thumbnail' => 'destinations/slg.png',
            'is_featured' => true,
        ]);

        Destination::create([
            'category_id' => $catAlam->id,
            'name' => 'Gunung Kelud',
            'slug' => 'gunung-kelud',
            'description' => 'Gunung berapi aktif dengan kawah yang indah. Tersedia fasilitas wisata dan pemandangan alam yang memukau.',
            'address' => 'Ngancar, Kabupaten Kediri',
            'latitude' => -7.9333,
            'longitude' => 112.3083,
            'ticket_price' => 10000,
            'opening_hours' => '07:00 - 16:00',
            'thumbnail' => 'destinations/kelud.png',
            'is_featured' => true,
        ]);

        Destination::create([
            'category_id' => $catAlam->id,
            'name' => 'Air Terjun Dolo',
            'slug' => 'air-terjun-dolo',
            'description' => 'Air terjun bertingkat yang berlokasi di kawasan pegunungan Wilis, dengan hawa sejuk dan pemandangan hutan pinus.',
            'address' => 'Jugo, Kec. Mojo, Kabupaten Kediri',
            'latitude' => -7.8864,
            'longitude' => 111.8384,
            'ticket_price' => 5000,
            'opening_hours' => '08:00 - 16:00',
            'thumbnail' => 'destinations/dolo.png',
            'is_featured' => false,
        ]);

        Destination::create([
            'category_id' => $catSejarah->id,
            'name' => 'Goa Selomangleng',
            'slug' => 'goa-selomangleng',
            'description' => 'Goa bersejarah peninggalan Kerajaan Kediri yang terletak di kaki Gunung Klotok.',
            'address' => 'Jl. Selomangleng, Pojok, Kec. Mojoroto, Kota Kediri',
            'latitude' => -7.8077,
            'longitude' => 111.9806,
            'ticket_price' => 4000,
            'opening_hours' => '07:00 - 16:00',
            'thumbnail' => 'destinations/selomangleng.png',
            'is_featured' => false,
        ]);

        // 3. Events
        Event::create([
            'title' => 'Kediri Nite Carnival',
            'slug' => 'kediri-nite-carnival',
            'description' => 'Karnaval malam hari dalam rangka hari jadi Kota Kediri yang menampilkan berbagai pertunjukan seni dan budaya.',
            'location' => 'Sepanjang Jalan Utama Kota Kediri',
            'banner' => 'events/carnival.png',
            'start_date' => now()->addDays(20),
            'end_date' => now()->addDays(20),
        ]);

        Event::create([
            'title' => 'Festival Kelud',
            'slug' => 'festival-kelud',
            'description' => 'Festival tahunan di kawasan Gunung Kelud yang menghadirkan pagelaran seni tradisional, pameran produk UMKM, dan upacara adat.',
            'location' => 'Kawasan Wisata Gunung Kelud',
            'banner' => 'destinations/kelud.png',
            'start_date' => now()->addMonths(2),
            'end_date' => now()->addMonths(2)->addDays(2),
        ]);

        // 4. Culinary
        CulinaryPlace::create([
            'name' => 'Soto Ayam Bok Ijo',
            'slug' => 'soto-ayam-bok-ijo',
            'description' => 'Soto khas Kediri yang terkenal dengan kuah santannya yang gurih, dipadukan dengan ayam kampung dan tauge.',
            'address' => 'Tamanan, Kec. Mojoroto, Kota Kediri',
            'latitude' => -7.8188,
            'longitude' => 111.9961,
            'thumbnail' => 'culinary/soto_bok_ijo.png',
        ]);

        CulinaryPlace::create([
            'name' => 'Nasi Pecel Tumpang Bu Ani',
            'slug' => 'nasi-pecel-tumpang-bu-ani',
            'description' => 'Warung pecel legendaris dengan sambal tumpang berbahan dasar tempe bosok (tempe fermentasi) khas Kediri.',
            'address' => 'Jl. Dhoho, Kota Kediri',
            'latitude' => -7.8228,
            'longitude' => 112.0125,
            'thumbnail' => 'culinary/pecel_tumpang.png',
        ]);

        // 5. Hotels
        Hotel::create([
            'name' => 'Grand Surya Hotel Kediri',
            'slug' => 'grand-surya-hotel',
            'description' => 'Hotel bintang 4 mewah yang terletak strategis di pusat kota Kediri dekat dengan jalan Dhoho.',
            'address' => 'Jl. Dhoho No.95, Kota Kediri',
            'latitude' => -7.8184,
            'longitude' => 112.0135,
            'phone' => '0354-686000',
            'thumbnail' => 'hotels/grand_surya.png',
        ]);

        Hotel::create([
            'name' => 'Bukit Daun Hotel & Resort',
            'slug' => 'bukit-daun-hotel',
            'description' => 'Hotel berkonsep resort alam dengan suasana tenang dan pemandangan hijau di pinggiran Kediri.',
            'address' => 'Jl. Argowilis No.777, Semen, Kediri',
            'latitude' => -7.8173,
            'longitude' => 111.9708,
            'phone' => '0354-772000',
            'thumbnail' => 'hotels/bukit_daun.png',
        ]);

        // 6. Users & Articles
        $userRony = User::firstOrCreate(
            ['email' => 'rkriswibowo@gmail.com'],
            [
                'name' => 'Rony Kriswibowo',
                'password' => bcrypt('password'),
                'phone' => '081234567890',
                'status' => 'active',
            ]
        );

        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Gerbangkartasusila',
                'password' => bcrypt('password'),
            ]
        );
        
        if ($user) {
            Article::create([
                'title' => 'Menikmati Sejuknya Udara di Air Terjun Dolo',
                'slug' => 'menikmati-sejuknya-udara-di-air-terjun-dolo',
                'content' => '<p>Jika Anda mencari tempat melarikan diri dari hiruk-pikuk perkotaan, <strong>Air Terjun Dolo</strong> adalah jawabannya. Terletak di lereng Gunung Wilis, air terjun ini menawarkan suasana yang sangat sejuk dan damai...</p>',
                'thumbnail' => 'articles/dolo_article.png',
                'author_id' => $user->id,
                'published_at' => now(),
            ]);

            Article::create([
                'title' => '5 Makanan Khas Kediri yang Wajib Dicoba',
                'slug' => '5-makanan-khas-kediri',
                'content' => '<p>Kediri tidak hanya kaya akan sejarah, tapi juga memanjakan lidah. Dari <em>Pecel Tumpang</em> hingga sate bekicot, berikut daftar makanan khas Kediri yang tidak boleh Anda lewatkan...</p>',
                'thumbnail' => 'culinary/pecel_tumpang.png',
                'author_id' => $user->id,
                'published_at' => now()->subDays(2),
            ]);
        }

        // 7. Destination Images (Gallery)
        $slgDest = Destination::where('slug', 'monumen-simpang-lima-gumul')->first();
        if ($slgDest) {
            \App\Models\DestinationImage::create([
                'destination_id' => $slgDest->id,
                'image' => 'destinations/slg.png',
            ]);
        }

        $keludDest = Destination::where('slug', 'gunung-kelud')->first();
        if ($keludDest) {
            \App\Models\DestinationImage::create([
                'destination_id' => $keludDest->id,
                'image' => 'destinations/kelud.png',
            ]);
        }

        $doloDest = Destination::where('slug', 'air-terjun-dolo')->first();
        if ($doloDest) {
            \App\Models\DestinationImage::create([
                'destination_id' => $doloDest->id,
                'image' => 'destinations/dolo.png',
            ]);
        }

        $seloDest = Destination::where('slug', 'goa-selomangleng')->first();
        if ($seloDest) {
            \App\Models\DestinationImage::create([
                'destination_id' => $seloDest->id,
                'image' => 'destinations/selomangleng.png',
            ]);
        }

        // ============================================================
        // GERBANGKARTASUSILA DATA
        // (Gresik, Bangkalan, Mojokerto, Surabaya, Sidoarjo, Lamongan)
        // ============================================================

        // 8. Kategori Tambahan
        $catReligi = DestinationCategory::create([
            'name' => 'Wisata Religi',
            'slug' => 'wisata-religi',
            'icon' => 'heroicon-o-star',
        ]);

        $catEdukasi = DestinationCategory::create([
            'name' => 'Wisata Edukasi',
            'slug' => 'wisata-edukasi',
            'icon' => 'heroicon-o-academic-cap',
        ]);

        // 9. Destinations - Gerbangkartasusila
        // --- SURABAYA ---
        Destination::create([
            'category_id' => $catSejarah->id,
            'name' => 'Tugu Pahlawan',
            'slug' => 'tugu-pahlawan',
            'description' => 'Monumen ikonik setinggi 41,15 meter yang menjadi simbol perjuangan arek-arek Suroboyo dalam pertempuran 10 November 1945. Di dalamnya terdapat museum yang menyimpan berbagai koleksi sejarah perjuangan kemerdekaan.',
            'address' => 'Jl. Pahlawan, Alun-alun Contong, Kec. Bubutan, Surabaya',
            'latitude' => -7.2458,
            'longitude' => 112.7378,
            'ticket_price' => 0,
            'opening_hours' => '08:00 - 16:00',
            'thumbnail' => 'destinations/tugu_pahlawan.png',
            'is_featured' => true,
        ]);

        Destination::create([
            'category_id' => $catEdukasi->id,
            'name' => 'House of Sampoerna',
            'slug' => 'house-of-sampoerna',
            'description' => 'Museum yang menampilkan sejarah keluarga Sampoerna dan industri rokok kretek di Indonesia. Bangunan bergaya kolonial Belanda ini juga menawarkan tur kota Surabaya Heritage Track.',
            'address' => 'Jl. Taman Sampoerna No.6, Krembangan Utara, Surabaya',
            'latitude' => -7.2306,
            'longitude' => 112.7347,
            'ticket_price' => 0,
            'opening_hours' => '09:00 - 18:00',
            'thumbnail' => 'destinations/house_of_sampoerna.png',
            'is_featured' => true,
        ]);

        Destination::create([
            'category_id' => $catAlam->id,
            'name' => 'Kebun Binatang Surabaya',
            'slug' => 'kebun-binatang-surabaya',
            'description' => 'Kebun binatang tertua dan terluas di Asia Tenggara yang menampung lebih dari 350 spesies satwa. Tempat favorit keluarga untuk rekreasi dan edukasi tentang keanekaragaman hayati.',
            'address' => 'Jl. Setail No.1, Darmo, Kec. Wonokromo, Surabaya',
            'latitude' => -7.2939,
            'longitude' => 112.7362,
            'ticket_price' => 15000,
            'opening_hours' => '08:00 - 16:00',
            'thumbnail' => 'destinations/kbs.png',
            'is_featured' => false,
        ]);

        // --- GRESIK ---
        Destination::create([
            'category_id' => $catReligi->id,
            'name' => 'Makam Sunan Giri',
            'slug' => 'makam-sunan-giri',
            'description' => 'Kompleks makam salah satu Wali Songo yang terletak di bukit Giri, Gresik. Sunan Giri dikenal sebagai penyebar agama Islam di Jawa Timur dan pendiri Kerajaan Giri Kedaton.',
            'address' => 'Jl. Sunan Giri, Gresik',
            'latitude' => -7.1544,
            'longitude' => 112.6371,
            'ticket_price' => 0,
            'opening_hours' => '24 Jam',
            'thumbnail' => 'destinations/sunan_giri.png',
            'is_featured' => true,
        ]);

        Destination::create([
            'category_id' => $catAlam->id,
            'name' => 'Bukit Jamur Batu',
            'slug' => 'bukit-jamur-batu',
            'description' => 'Formasi batu kapur unik berbentuk jamur yang terbentuk secara alami oleh proses erosi selama ribuan tahun. Lokasi ini menawarkan pemandangan eksotis dan spot foto yang instagramable.',
            'address' => 'Desa Sekapuk, Kec. Ujungpangkah, Gresik',
            'latitude' => -7.0187,
            'longitude' => 112.5533,
            'ticket_price' => 10000,
            'opening_hours' => '08:00 - 17:00',
            'thumbnail' => 'destinations/bukit_jamur.png',
            'is_featured' => false,
        ]);

        // --- SIDOARJO ---
        Destination::create([
            'category_id' => $catEdukasi->id,
            'name' => 'Museum Lumpur Sidoarjo',
            'slug' => 'museum-lumpur-sidoarjo',
            'description' => 'Kawasan wisata edukasi yang dibangun di area terdampak semburan lumpur Lapindo. Menyajikan informasi geologi dan dampak bencana lumpur serta menjadi pengingat sejarah bagi masyarakat.',
            'address' => 'Porong, Kec. Porong, Sidoarjo',
            'latitude' => -7.5270,
            'longitude' => 112.7123,
            'ticket_price' => 5000,
            'opening_hours' => '08:00 - 16:00',
            'thumbnail' => 'destinations/lumpur_sidoarjo.png',
            'is_featured' => false,
        ]);

        Destination::create([
            'category_id' => $catSejarah->id,
            'name' => 'Candi Pari',
            'slug' => 'candi-pari',
            'description' => 'Candi peninggalan masa Kerajaan Majapahit yang dibangun pada abad ke-14. Candi berbahan bata merah ini merupakan bukti kejayaan arsitektur masa lalu di wilayah Sidoarjo.',
            'address' => 'Ds. Candi Pari, Kec. Porong, Sidoarjo',
            'latitude' => -7.4486,
            'longitude' => 112.7272,
            'ticket_price' => 3000,
            'opening_hours' => '07:00 - 17:00',
            'thumbnail' => 'destinations/candi_pari.png',
            'is_featured' => false,
        ]);

        // --- LAMONGAN ---
        Destination::create([
            'category_id' => $catAlam->id,
            'name' => 'Wisata Bahari Lamongan (WBL)',
            'slug' => 'wisata-bahari-lamongan',
            'description' => 'Taman rekreasi bertema bahari terbesar di Jawa Timur. Menyediakan berbagai wahana permainan air, pertunjukan lumba-lumba, akuarium raksasa, dan pantai buatan.',
            'address' => 'Jl. Raya Paciran, Kec. Paciran, Lamongan',
            'latitude' => -6.8740,
            'longitude' => 112.3870,
            'ticket_price' => 50000,
            'opening_hours' => '09:00 - 16:30',
            'thumbnail' => 'destinations/wbl.png',
            'is_featured' => true,
        ]);

        Destination::create([
            'category_id' => $catReligi->id,
            'name' => 'Makam Sunan Drajat',
            'slug' => 'makam-sunan-drajat',
            'description' => 'Kompleks pemakaman Sunan Drajat, salah satu dari Wali Songo yang dikenal dengan ajaran sosialnya. Terdapat museum yang menyimpan peninggalan bersejarah sang wali.',
            'address' => 'Ds. Drajat, Kec. Paciran, Lamongan',
            'latitude' => -6.8803,
            'longitude' => 112.3308,
            'ticket_price' => 0,
            'opening_hours' => '24 Jam',
            'thumbnail' => 'destinations/sunan_drajat.png',
            'is_featured' => false,
        ]);

        // --- MOJOKERTO ---
        Destination::create([
            'category_id' => $catSejarah->id,
            'name' => 'Candi Tikus',
            'slug' => 'candi-tikus',
            'description' => 'Candi petirtaan (pemandian suci) peninggalan Kerajaan Majapahit yang ditemukan pada tahun 1914. Dinamakan Candi Tikus karena saat ditemukan dipenuhi oleh tikus-tikus liar.',
            'address' => 'Ds. Temon, Kec. Trowulan, Mojokerto',
            'latitude' => -7.6108,
            'longitude' => 112.4485,
            'ticket_price' => 5000,
            'opening_hours' => '07:00 - 16:00',
            'thumbnail' => 'destinations/candi_tikus.png',
            'is_featured' => false,
        ]);

        Destination::create([
            'category_id' => $catEdukasi->id,
            'name' => 'Museum Trowulan',
            'slug' => 'museum-trowulan',
            'description' => 'Museum arkeologi yang menyimpan ribuan artefak peninggalan Kerajaan Majapahit. Koleksi meliputi arca, gerabah, prasasti, dan replika kehidupan masyarakat Majapahit.',
            'address' => 'Jl. Pendopo Agung, Trowulan, Mojokerto',
            'latitude' => -7.5622,
            'longitude' => 112.3882,
            'ticket_price' => 5000,
            'opening_hours' => '08:00 - 16:00',
            'thumbnail' => 'destinations/museum_trowulan.png',
            'is_featured' => true,
        ]);

        // --- BANGKALAN ---
        Destination::create([
            'category_id' => $catIkon->id,
            'name' => 'Mercusuar Bangkalan',
            'slug' => 'mercusuar-bangkalan',
            'description' => 'Mercusuar peninggalan era Belanda yang terletak di pesisir utara Pulau Madura. Menawarkan pemandangan laut yang memukau dan menjadi spot favorit untuk menikmati sunset.',
            'address' => 'Kec. Kamal, Bangkalan, Madura',
            'latitude' => -7.0350,
            'longitude' => 112.7440,
            'ticket_price' => 5000,
            'opening_hours' => '06:00 - 18:00',
            'thumbnail' => 'destinations/mercusuar_bangkalan.png',
            'is_featured' => false,
        ]);

        // 10. Hotels - Gerbangkartasusila
        Hotel::create([
            'name' => 'Hotel Majapahit Surabaya',
            'slug' => 'hotel-majapahit-surabaya',
            'description' => 'Hotel bersejarah bintang 5 yang dibangun sejak tahun 1910. Terkenal sebagai lokasi insiden perobekan bendera Belanda pada tahun 1945. Menawarkan kemewahan klasik dengan sentuhan modern.',
            'address' => 'Jl. Tunjungan No.65, Genteng, Surabaya',
            'latitude' => -7.2625,
            'longitude' => 112.7508,
            'phone' => '031-5454333',
            'thumbnail' => 'hotels/hotel_majapahit.png',
        ]);

        Hotel::create([
            'name' => 'JW Marriott Hotel Surabaya',
            'slug' => 'jw-marriott-surabaya',
            'description' => 'Hotel mewah bintang 5 di jantung kota Surabaya dengan fasilitas lengkap termasuk spa, kolam renang infinity, dan restoran fine dining.',
            'address' => 'Jl. Embong Malang No.85-89, Surabaya',
            'latitude' => -7.2612,
            'longitude' => 112.7417,
            'phone' => '031-5458888',
            'thumbnail' => 'hotels/jw_marriott.png',
        ]);

        Hotel::create([
            'name' => 'Vasa Hotel Surabaya',
            'slug' => 'vasa-hotel-surabaya',
            'description' => 'Hotel modern bintang 5 di kawasan bisnis MERR Surabaya. Menawarkan kamar-kamar mewah dengan pemandangan kota dan fasilitas meeting premium.',
            'address' => 'Jl. HR Muhammad No.31, Surabaya',
            'latitude' => -7.2915,
            'longitude' => 112.7753,
            'phone' => '031-7300888',
            'thumbnail' => 'hotels/vasa_hotel.png',
        ]);

        Hotel::create([
            'name' => 'Santika Hotel Gresik',
            'slug' => 'santika-hotel-gresik',
            'description' => 'Hotel bintang 3 yang nyaman di pusat kota Gresik dengan akses mudah ke berbagai destinasi wisata religi dan industri.',
            'address' => 'Jl. Harun Thohir No.1, Gresik',
            'latitude' => -7.1622,
            'longitude' => 112.6531,
            'phone' => '031-3970707',
            'thumbnail' => 'hotels/santika_gresik.png',
        ]);

        Hotel::create([
            'name' => 'Fave Hotel Sidoarjo',
            'slug' => 'fave-hotel-sidoarjo',
            'description' => 'Hotel budget-friendly modern di pusat kota Sidoarjo. Cocok untuk wisatawan bisnis maupun keluarga dengan fasilitas yang memadai.',
            'address' => 'Jl. Raya Surabaya-Malang, Sidoarjo',
            'latitude' => -7.4530,
            'longitude' => 112.7180,
            'phone' => '031-8957888',
            'thumbnail' => 'hotels/fave_sidoarjo.png',
        ]);

        Hotel::create([
            'name' => 'Hotel & Resort WBL Lamongan',
            'slug' => 'hotel-resort-wbl',
            'description' => 'Hotel resort yang terintegrasi dengan Wisata Bahari Lamongan. Menawarkan suasana pantai yang asri dengan akses langsung ke wahana wisata bahari.',
            'address' => 'Jl. Raya Paciran, Kec. Paciran, Lamongan',
            'latitude' => -6.8762,
            'longitude' => 112.3850,
            'phone' => '0322-661777',
            'thumbnail' => 'hotels/hotel_wbl.png',
        ]);

        Hotel::create([
            'name' => 'Hotel Raden Wijaya Mojokerto',
            'slug' => 'hotel-raden-wijaya',
            'description' => 'Hotel bertema kerajaan Majapahit yang terletak strategis di pusat Kota Mojokerto. Dekat dengan situs-situs bersejarah Trowulan.',
            'address' => 'Jl. Raya Mojopahit No.260, Mojokerto',
            'latitude' => -7.4713,
            'longitude' => 112.4338,
            'phone' => '0321-322888',
            'thumbnail' => 'hotels/raden_wijaya.png',
        ]);

        Hotel::create([
            'name' => 'Hotel & Resort Camplong Bangkalan',
            'slug' => 'hotel-camplong-bangkalan',
            'description' => 'Resort di tepi pantai Camplong, Bangkalan Madura. Menawarkan pengalaman menginap dengan suasana pesisir yang tenang dan pemandangan Selat Madura.',
            'address' => 'Pantai Camplong, Kec. Camplong, Bangkalan',
            'latitude' => -7.0445,
            'longitude' => 112.7350,
            'phone' => '031-3095777',
            'thumbnail' => 'hotels/camplong_resort.png',
        ]);

        // 11. Culinary - Gerbangkartasusila
        CulinaryPlace::create([
            'name' => 'Rawon Setan Surabaya',
            'slug' => 'rawon-setan-surabaya',
            'description' => 'Warung rawon legendaris di Surabaya yang buka dari malam hingga subuh. Rawon dengan kuah hitam pekat dari kluwek yang khas dan daging empuk menjadi favorit warga Surabaya.',
            'address' => 'Jl. Embong Malang, Genteng, Surabaya',
            'latitude' => -7.2567,
            'longitude' => 112.7522,
            'thumbnail' => 'culinary/rawon_setan.png',
        ]);

        CulinaryPlace::create([
            'name' => 'Lontong Balap Pak Gendut',
            'slug' => 'lontong-balap-pak-gendut',
            'description' => 'Kuliner khas Surabaya berupa lontong dengan kuah kaldu gurih, taoge, tahu goreng, lentho, dan sambal petis. Warung legendaris yang sudah beroperasi puluhan tahun.',
            'address' => 'Jl. Kranggan, Pabean Cantian, Surabaya',
            'latitude' => -7.2346,
            'longitude' => 112.7395,
            'thumbnail' => 'culinary/lontong_balap.png',
        ]);

        CulinaryPlace::create([
            'name' => 'Nasi Krawu Gresik',
            'slug' => 'nasi-krawu-gresik',
            'description' => 'Makanan khas Gresik berupa nasi putih dengan lauk serundeng daging sapi yang dimasak dengan bumbu rempah khas. Disajikan dengan sambal terasi yang pedas.',
            'address' => 'Jl. Gubernur Suryo, Gresik',
            'latitude' => -7.1575,
            'longitude' => 112.6494,
            'thumbnail' => 'culinary/nasi_krawu.png',
        ]);

        CulinaryPlace::create([
            'name' => 'Otak-Otak Bandeng Gresik',
            'slug' => 'otak-otak-bandeng-gresik',
            'description' => 'Oleh-oleh khas Gresik berupa ikan bandeng yang diambil dagingnya, dicampur bumbu rempah, lalu dimasukkan kembali ke dalam kulitnya. Rasanya gurih dan kaya rempah.',
            'address' => 'Jl. Usman Sadar, Gresik',
            'latitude' => -7.1553,
            'longitude' => 112.6509,
            'thumbnail' => 'culinary/otak_otak_bandeng.png',
        ]);

        CulinaryPlace::create([
            'name' => 'Bandeng Presto Sidoarjo',
            'slug' => 'bandeng-presto-sidoarjo',
            'description' => 'Olahan ikan bandeng khas Sidoarjo yang dimasak dengan teknik presto sehingga tulangnya menjadi lunak dan dapat dimakan. Sidoarjo dikenal sebagai kota bandeng terbaik di Jawa Timur.',
            'address' => 'Jl. Gajah Mada, Sidoarjo',
            'latitude' => -7.4547,
            'longitude' => 112.7140,
            'thumbnail' => 'culinary/bandeng_presto.png',
        ]);

        CulinaryPlace::create([
            'name' => 'Soto Lamongan Cak Har',
            'slug' => 'soto-lamongan-cak-har',
            'description' => 'Soto ayam khas Lamongan dengan kuah kuning bening dan koya (campuran kerupuk dan bawang putih goreng). Disajikan dengan nasi, telur, dan sambal.',
            'address' => 'Jl. Lamongrejo, Lamongan',
            'latitude' => -6.8801,
            'longitude' => 112.4185,
            'thumbnail' => 'culinary/soto_lamongan.png',
        ]);

        CulinaryPlace::create([
            'name' => 'Rujak Cingur Bu Haji Mojokerto',
            'slug' => 'rujak-cingur-bu-haji',
            'description' => 'Rujak cingur khas Jawa Timur dengan irisan cingur (mulut sapi), lontong, tahu, tempe, dan berbagai sayuran. Bumbu petis yang khas menjadi ciri utamanya.',
            'address' => 'Jl. Mojopahit, Mojokerto',
            'latitude' => -7.4735,
            'longitude' => 112.4356,
            'thumbnail' => 'culinary/rujak_cingur.png',
        ]);

        CulinaryPlace::create([
            'name' => 'Bebek Sinjay Bangkalan',
            'slug' => 'bebek-sinjay-bangkalan',
            'description' => 'Warung bebek goreng legendaris dari Madura yang terkenal dengan bumbu rempah yang meresap dan tekstur daging bebek yang empuk. Disajikan dengan sambal dan lalapan.',
            'address' => 'Jl. Halim Perdana Kusuma, Bangkalan',
            'latitude' => -7.0430,
            'longitude' => 112.7362,
            'thumbnail' => 'culinary/bebek_sinjay.png',
        ]);
    }
}
