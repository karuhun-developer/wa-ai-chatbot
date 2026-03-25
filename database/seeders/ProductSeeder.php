<?php

namespace Database\Seeders;

use App\Models\Catalog\Product;
use App\Models\Catalog\ProductCategory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed the application's database with products and categories.
     */
    public function run(): void
    {
        $categories = [
            'Elektronik',
            'Fashion Pria',
            'Fashion Wanita',
            'Makanan & Minuman',
            'Kesehatan',
            'Peralatan Rumah',
            'Olahraga',
            'Aksesoris',
            'Gadget',
            'Kecantikan',
        ];

        $createdCategories = collect();
        foreach ($categories as $name) {
            $createdCategories->push(ProductCategory::create([
                'name' => $name,
                'status' => true,
            ]));
        }

        $products = [
            ['name' => 'Earphone Bluetooth Sport Pro', 'price' => 149000, 'stock' => 120, 'rating' => 4.5, 'categories' => ['Elektronik', 'Gadget'], 'description' => 'Earphone bluetooth tahan air dengan bass yang kuat, cocok untuk olahraga.'],
            ['name' => 'Charger Fast Charging 33W', 'price' => 89000, 'stock' => 200, 'rating' => 4.3, 'categories' => ['Elektronik', 'Gadget'], 'description' => 'Charger cepat mendukung berbagai protokol fast charging.'],
            ['name' => 'Kabel Data USB-C 1 Meter', 'price' => 35000, 'stock' => 500, 'rating' => 4.0, 'categories' => ['Elektronik', 'Gadget'], 'description' => 'Kabel data USB-C berkualitas tinggi, tahan lama.'],
            ['name' => 'Mouse Wireless Ergonomic', 'price' => 125000, 'stock' => 80, 'rating' => 4.6, 'categories' => ['Elektronik', 'Gadget'], 'description' => 'Mouse wireless ergonomis dengan DPI bisa diatur.'],
            ['name' => 'Keyboard Mechanical RGB', 'price' => 450000, 'stock' => 45, 'rating' => 4.8, 'categories' => ['Elektronik', 'Gadget'], 'description' => 'Keyboard mechanical full RGB dengan switch blue.'],
            ['name' => 'Webcam HD 1080p', 'price' => 275000, 'stock' => 60, 'rating' => 4.2, 'categories' => ['Elektronik', 'Gadget'], 'description' => 'Webcam HD 1080p dengan built-in microphone.'],
            ['name' => 'Power Bank 10000mAh', 'price' => 185000, 'stock' => 150, 'rating' => 4.4, 'categories' => ['Elektronik', 'Gadget'], 'description' => 'Power bank slim dengan kapasitas 10000mAh, dual port USB.'],
            ['name' => 'Smart Watch Band Fitness', 'price' => 350000, 'stock' => 75, 'rating' => 4.3, 'categories' => ['Elektronik', 'Gadget', 'Olahraga'], 'description' => 'Smart watch band dengan fitur monitor detak jantung dan langkah.'],
            ['name' => 'Speaker Bluetooth Mini', 'price' => 199000, 'stock' => 90, 'rating' => 4.1, 'categories' => ['Elektronik', 'Gadget'], 'description' => 'Speaker bluetooth mini portable, suara jernih dan bass mantap.'],
            ['name' => 'Lampu LED Meja Belajar', 'price' => 95000, 'stock' => 110, 'rating' => 4.5, 'categories' => ['Elektronik', 'Peralatan Rumah'], 'description' => 'Lampu LED meja belajar dengan 3 mode cahaya.'],
            ['name' => 'Kaos Polos Premium Cotton', 'price' => 79000, 'stock' => 300, 'rating' => 4.6, 'categories' => ['Fashion Pria'], 'description' => 'Kaos polos bahan cotton combed 30s, nyaman dipakai sehari-hari.'],
            ['name' => 'Celana Chino Slim Fit', 'price' => 189000, 'stock' => 100, 'rating' => 4.4, 'categories' => ['Fashion Pria'], 'description' => 'Celana chino slim fit bahan stretch, cocok untuk casual dan semi formal.'],
            ['name' => 'Kemeja Flannel Kotak', 'price' => 145000, 'stock' => 85, 'rating' => 4.3, 'categories' => ['Fashion Pria'], 'description' => 'Kemeja flannel motif kotak, bahan tebal dan hangat.'],
            ['name' => 'Jaket Hoodie Fleece', 'price' => 175000, 'stock' => 120, 'rating' => 4.7, 'categories' => ['Fashion Pria'], 'description' => 'Jaket hoodie bahan fleece tebal, nyaman dipakai di cuaca dingin.'],
            ['name' => 'Topi Baseball Cap Polos', 'price' => 45000, 'stock' => 250, 'rating' => 4.2, 'categories' => ['Fashion Pria', 'Aksesoris'], 'description' => 'Topi baseball cap polos adjustable, bahan katun.'],
            ['name' => 'Dress Casual Wanita Motif Bunga', 'price' => 165000, 'stock' => 70, 'rating' => 4.5, 'categories' => ['Fashion Wanita'], 'description' => 'Dress casual motif bunga, bahan adem dan nyaman.'],
            ['name' => 'Blouse Lengan Panjang Satin', 'price' => 135000, 'stock' => 90, 'rating' => 4.4, 'categories' => ['Fashion Wanita'], 'description' => 'Blouse satin lengan panjang elegan, cocok untuk kerja.'],
            ['name' => 'Rok Plisket Midi', 'price' => 120000, 'stock' => 100, 'rating' => 4.3, 'categories' => ['Fashion Wanita'], 'description' => 'Rok plisket midi all size, bahan premium.'],
            ['name' => 'Hijab Pashmina Diamond', 'price' => 55000, 'stock' => 200, 'rating' => 4.6, 'categories' => ['Fashion Wanita', 'Aksesoris'], 'description' => 'Hijab pashmina bahan diamond italiano, lembut dan tidak mudah kusut.'],
            ['name' => 'Cardigan Rajut Oversize', 'price' => 155000, 'stock' => 65, 'rating' => 4.5, 'categories' => ['Fashion Wanita'], 'description' => 'Cardigan rajut oversize, cocok untuk layering.'],
            ['name' => 'Kopi Arabica Toraja 250g', 'price' => 85000, 'stock' => 150, 'rating' => 4.8, 'categories' => ['Makanan & Minuman'], 'description' => 'Kopi arabica single origin Toraja, roast medium.'],
            ['name' => 'Teh Hijau Organik 100 Sachet', 'price' => 65000, 'stock' => 180, 'rating' => 4.4, 'categories' => ['Makanan & Minuman', 'Kesehatan'], 'description' => 'Teh hijau organik dalam kemasan sachet praktis.'],
            ['name' => 'Madu Murni Hutan 500ml', 'price' => 125000, 'stock' => 80, 'rating' => 4.7, 'categories' => ['Makanan & Minuman', 'Kesehatan'], 'description' => 'Madu murni asli hutan, tanpa campuran.'],
            ['name' => 'Keripik Singkong Pedas Level 5', 'price' => 25000, 'stock' => 400, 'rating' => 4.3, 'categories' => ['Makanan & Minuman'], 'description' => 'Keripik singkong renyah dengan level pedas mantap.'],
            ['name' => 'Granola Bar Oats & Honey', 'price' => 45000, 'stock' => 200, 'rating' => 4.2, 'categories' => ['Makanan & Minuman', 'Kesehatan'], 'description' => 'Granola bar sehat dengan oat dan madu alami.'],
            ['name' => 'Susu Almond Original 1 Liter', 'price' => 55000, 'stock' => 120, 'rating' => 4.5, 'categories' => ['Makanan & Minuman', 'Kesehatan'], 'description' => 'Susu almond tanpa gula tambahan, kaya nutrisi.'],
            ['name' => 'Vitamin C 1000mg 30 Tablet', 'price' => 75000, 'stock' => 250, 'rating' => 4.6, 'categories' => ['Kesehatan'], 'description' => 'Suplemen vitamin C dosis tinggi untuk daya tahan tubuh.'],
            ['name' => 'Masker KN95 isi 50', 'price' => 89000, 'stock' => 300, 'rating' => 4.4, 'categories' => ['Kesehatan'], 'description' => 'Masker KN95 5 lapis, filtrasi tinggi.'],
            ['name' => 'Hand Sanitizer Gel 500ml', 'price' => 35000, 'stock' => 400, 'rating' => 4.1, 'categories' => ['Kesehatan'], 'description' => 'Hand sanitizer gel alkohol 70%, lembut di kulit.'],
            ['name' => 'Minyak Kayu Putih Roll On 10ml', 'price' => 18000, 'stock' => 500, 'rating' => 4.5, 'categories' => ['Kesehatan'], 'description' => 'Minyak kayu putih roll on praktis dibawa kemana-mana.'],
            ['name' => 'Panci Stainless Steel 22cm', 'price' => 165000, 'stock' => 50, 'rating' => 4.3, 'categories' => ['Peralatan Rumah'], 'description' => 'Panci stainless steel anti karat, tutup kaca.'],
            ['name' => 'Rak Sepatu Susun 5 Tingkat', 'price' => 135000, 'stock' => 40, 'rating' => 4.2, 'categories' => ['Peralatan Rumah'], 'description' => 'Rak sepatu susun 5 tingkat bahan besi kokoh.'],
            ['name' => 'Dispenser Sabun Otomatis', 'price' => 145000, 'stock' => 60, 'rating' => 4.4, 'categories' => ['Peralatan Rumah'], 'description' => 'Dispenser sabun sensor otomatis, tanpa sentuh.'],
            ['name' => 'Gantungan Baju Kayu isi 10', 'price' => 55000, 'stock' => 150, 'rating' => 4.0, 'categories' => ['Peralatan Rumah'], 'description' => 'Gantungan baju bahan kayu natural, kuat dan tahan lama.'],
            ['name' => 'Termos Air Panas 1 Liter', 'price' => 195000, 'stock' => 70, 'rating' => 4.6, 'categories' => ['Peralatan Rumah'], 'description' => 'Termos vacuum insulated, tahan panas hingga 12 jam.'],
            ['name' => 'Sepatu Lari Running Shoes', 'price' => 385000, 'stock' => 55, 'rating' => 4.5, 'categories' => ['Olahraga', 'Fashion Pria'], 'description' => 'Sepatu lari ringan dengan sol empuk, cocok untuk jogging harian.'],
            ['name' => 'Matras Yoga TPE 6mm', 'price' => 175000, 'stock' => 80, 'rating' => 4.4, 'categories' => ['Olahraga'], 'description' => 'Matras yoga TPE anti slip, ketebalan 6mm.'],
            ['name' => 'Dumbbell Set 5kg Sepasang', 'price' => 250000, 'stock' => 40, 'rating' => 4.3, 'categories' => ['Olahraga'], 'description' => 'Dumbbell vinyl 5kg sepasang untuk latihan di rumah.'],
            ['name' => 'Resistance Band Set 5 Level', 'price' => 95000, 'stock' => 100, 'rating' => 4.6, 'categories' => ['Olahraga'], 'description' => 'Set resistance band 5 level kekuatan berbeda.'],
            ['name' => 'Botol Minum Sport 750ml', 'price' => 65000, 'stock' => 200, 'rating' => 4.2, 'categories' => ['Olahraga', 'Peralatan Rumah'], 'description' => 'Botol minum sport BPA free, tutup flip top.'],
            ['name' => 'Tas Ransel Anti Air', 'price' => 225000, 'stock' => 70, 'rating' => 4.5, 'categories' => ['Aksesoris', 'Fashion Pria'], 'description' => 'Tas ransel bahan anti air dengan slot laptop 15 inch.'],
            ['name' => 'Dompet Kulit Sintetis Pria', 'price' => 85000, 'stock' => 130, 'rating' => 4.3, 'categories' => ['Aksesoris', 'Fashion Pria'], 'description' => 'Dompet kulit sintetis premium, banyak slot kartu.'],
            ['name' => 'Kacamata Anti Blue Light', 'price' => 115000, 'stock' => 90, 'rating' => 4.4, 'categories' => ['Aksesoris', 'Kesehatan'], 'description' => 'Kacamata anti radiasi blue light, ringan dan nyaman.'],
            ['name' => 'Gelang Titanium Pria', 'price' => 75000, 'stock' => 100, 'rating' => 4.1, 'categories' => ['Aksesoris', 'Fashion Pria'], 'description' => 'Gelang titanium anti karat, desain minimalis.'],
            ['name' => 'Ikat Pinggang Kulit Asli', 'price' => 145000, 'stock' => 80, 'rating' => 4.5, 'categories' => ['Aksesoris', 'Fashion Pria'], 'description' => 'Ikat pinggang kulit asli dengan buckle metal premium.'],
            ['name' => 'Serum Vitamin C 20ml', 'price' => 89000, 'stock' => 150, 'rating' => 4.7, 'categories' => ['Kecantikan', 'Kesehatan'], 'description' => 'Serum vitamin C untuk mencerahkan wajah, cocok untuk semua jenis kulit.'],
            ['name' => 'Sunscreen SPF 50 PA+++', 'price' => 115000, 'stock' => 120, 'rating' => 4.6, 'categories' => ['Kecantikan', 'Kesehatan'], 'description' => 'Sunscreen SPF 50 ringan, tidak lengket, cocok untuk sehari-hari.'],
            ['name' => 'Moisturizer Gel Aloe Vera', 'price' => 69000, 'stock' => 180, 'rating' => 4.4, 'categories' => ['Kecantikan'], 'description' => 'Pelembab gel aloe vera, menyegarkan dan melembabkan.'],
            ['name' => 'Lip Tint Matte Velvet', 'price' => 55000, 'stock' => 200, 'rating' => 4.5, 'categories' => ['Kecantikan'], 'description' => 'Lip tint matte finish, tahan lama seharian.'],
            ['name' => 'Facial Wash Gentle Cleanser', 'price' => 49000, 'stock' => 250, 'rating' => 4.3, 'categories' => ['Kecantikan', 'Kesehatan'], 'description' => 'Facial wash gentle untuk kulit sensitif, pH balanced.'],
        ];

        $categoryMap = $createdCategories->keyBy('name');

        foreach ($products as $index => $productData) {
            $product = Product::create([
                'sku' => sprintf('PRD-%03d', $index + 1),
                'name' => $productData['name'],
                'price' => $productData['price'],
                'description' => $productData['description'],
                'stock' => $productData['stock'],
                'rating' => $productData['rating'],
            ]);

            $categoryIds = collect($productData['categories'])
                ->map(fn (string $cat) => $categoryMap->get($cat)?->id)
                ->filter()
                ->all();

            $product->productCategories()->attach($categoryIds);
        }
    }
}
