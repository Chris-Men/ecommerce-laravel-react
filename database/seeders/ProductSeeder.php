<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Size;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Colores y tallas existentes en la base de datos
        $colorIds = Color::pluck('id')->toArray();
        $sizeIds = Size::pluck('id')->toArray();

        $productos = [
            // Juguetes
            ['name' => 'Lego City', 'brand' => 'Lego', 'category' => 'Juguetes', 'imagen' => 'lego city.jpg'],
            ['name' => 'Lego Star Wars', 'brand' => 'Lego', 'category' => 'Juguetes', 'imagen' => 'lego star wars.jpg'],
            ['name' => 'Lego Technic', 'brand' => 'Lego', 'category' => 'Juguetes', 'imagen' => 'lego technic.jpg'],
            ['name' => 'Lego Friends', 'brand' => 'Lego', 'category' => 'Juguetes', 'imagen' => 'lego friends.jpg'],

            ['name' => 'Barbie', 'brand' => 'Mattel', 'category' => 'Juguetes', 'imagen' => 'barbie.jpg'],
            ['name' => 'Hot Wheels', 'brand' => 'Mattel', 'category' => 'Juguetes', 'imagen' => 'hot wheels.jpg'],
            ['name' => 'Fisher-Price Laugh & Learn', 'brand' => 'Mattel', 'category' => 'Juguetes', 'imagen' => 'fisher price laugh learn.jpg'],
            ['name' => 'Uno (juego de cartas)', 'brand' => 'Mattel', 'category' => 'Juguetes', 'imagen' => 'uno juego de cartas.jpg'],

            ['name' => 'Monopoly', 'brand' => 'Hasbro', 'category' => 'Juguetes', 'imagen' => 'monopoly.jpg'],
            ['name' => 'Nerf Gun', 'brand' => 'Hasbro', 'category' => 'Juguetes', 'imagen' => 'nerf gun.jpg'],
            ['name' => 'Transformers', 'brand' => 'Hasbro', 'category' => 'Juguetes', 'imagen' => 'transformers.jpg'],
            ['name' => 'Play-Doh', 'brand' => 'Hasbro', 'category' => 'Juguetes', 'imagen' => 'play doh.jpg'],

            ['name' => 'Piano de aprendizaje', 'brand' => 'Fisher-Price', 'category' => 'Juguetes', 'imagen' => 'piano de aprendizaje.jpg'],
            ['name' => 'Andador de león', 'brand' => 'Fisher-Price', 'category' => 'Juguetes', 'imagen' => 'andador de leon.jpg'],
            ['name' => 'Teléfono parlante', 'brand' => 'Fisher-Price', 'category' => 'Juguetes', 'imagen' => 'telefono parlante.jpg'],
            ['name' => 'Silla vibradora', 'brand' => 'Fisher-Price', 'category' => 'Juguetes', 'imagen' => 'silla vibradora.jpg'],

            // Tecnología
            ['name' => 'Huawei Matepad 11', 'brand' => 'Huawei', 'category' => 'Tecnología', 'imagen' => 'huawei-matepad-11.jpg'],
            ['name' => 'Huawei Matebook D15', 'brand' => 'Huawei', 'category' => 'Tecnología', 'imagen' => 'huawei-matebook-d15.jpg'],
            ['name' => 'Huawei Watch GT5', 'brand' => 'Huawei', 'category' => 'Tecnología', 'imagen' => 'huawei-watch-gt5.jpg'],
            ['name' => 'Huawei Mate 50 Pro', 'brand' => 'Huawei', 'category' => 'Tecnología', 'imagen' => 'huawei-mate-50-pro.jpg'],

            ['name' => 'Sony WF 1000x', 'brand' => 'Sony', 'category' => 'Tecnología', 'imagen' => 'sony-wf-1000x.jpg'],
            ['name' => 'PlayStation 5', 'brand' => 'Sony', 'category' => 'Tecnología', 'imagen' => 'play-station-5.jpg'],
            ['name' => 'Sony WH-1000XM4', 'brand' => 'Sony', 'category' => 'Tecnología', 'imagen' => 'sony-wh-1000xm4.jpg'],
            ['name' => 'Sony Xperia 1', 'brand' => 'Sony', 'category' => 'Tecnología', 'imagen' => 'sony-xperia-1.jpg'],

            ['name' => 'Samsung Galaxy S24 Ultra', 'brand' => 'Samsung', 'category' => 'Tecnología', 'imagen' => 'samsung-galaxy-s24-ultra.jpg'],
            ['name' => 'Laptop Apple Macbook Pro', 'brand' => 'Apple', 'category' => 'Tecnología', 'imagen' => 'apple-macbook-pro.jpg'],
            ['name' => 'AirPods 3ra generación', 'brand' => 'Apple', 'category' => 'Tecnología', 'imagen' => 'airpods-3ra-generacion.jpg'],
            ['name' => 'Apple TV', 'brand' => 'Apple', 'category' => 'Tecnología', 'imagen' => 'apple-tv.jpg'],

            ['name' => 'iPhone 16', 'brand' => 'Apple', 'category' => 'Tecnología', 'imagen' => 'iphone-16.jpg'],
            ['name' => 'Samsung Galaxy Tab S8', 'brand' => 'Samsung', 'category' => 'Tecnología', 'imagen' => 'samsung-galaxy-tab-s8.jpg'],
            ['name' => 'Samsung Galaxy Buds', 'brand' => 'Samsung', 'category' => 'Tecnología', 'imagen' => 'samsung-galaxy-buds.jpg'],


            // Oficina
            ['name' => 'Impresora HP DeskJet', 'brand' => 'HP', 'category' => 'Oficina', 'imagen' => 'impresora hp deskjet.jpg'],
            ['name' => 'Laptop HP Pavilion', 'brand' => 'HP', 'category' => 'Oficina', 'imagen' => 'laptop hp pavilion.jpg'],
            ['name' => 'Mouse HP X1000', 'brand' => 'HP', 'category' => 'Oficina', 'imagen' => 'mouse hp x1000.jpg'],
            ['name' => 'Monitor HP 24', 'brand' => 'HP', 'category' => 'Oficina', 'imagen' => 'monitor hp 24.jpg'],

            ['name' => 'Cámara Canon EOS', 'brand' => 'Canon', 'category' => 'Oficina', 'imagen' => 'camara canon eos.jpg'],
            ['name' => 'Impresora Canon PIXMA', 'brand' => 'Canon', 'category' => 'Oficina', 'imagen' => 'impresora canon pixma.jpg'],
            ['name' => 'Escáner CanoScan', 'brand' => 'Canon', 'category' => 'Oficina', 'imagen' => 'escaner canoscan.jpg'],
            ['name' => 'Lente Canon EF', 'brand' => 'Canon', 'category' => 'Oficina', 'imagen' => 'lente canon ef.jpg'],

            ['name' => 'Impresora Epson EcoTank', 'brand' => 'Epson', 'category' => 'Oficina', 'imagen' => 'impresora epson ecotank.jpg'],
            ['name' => 'Proyector Epson Home Cinema', 'brand' => 'Epson', 'category' => 'Oficina', 'imagen' => 'proyector epson home cinema.jpg'],
            ['name' => 'Escáner Epson Perfection', 'brand' => 'Epson', 'category' => 'Oficina', 'imagen' => 'escaner epson perfection.jpg'],
            ['name' => 'Cartuchos Epson T664', 'brand' => 'Epson', 'category' => 'Oficina', 'imagen' => 'cartuchos epson t664.jpg'],

            ['name' => 'Mouse Logitech M185', 'brand' => 'Logitech', 'category' => 'Oficina', 'imagen' => 'mouse logitech m185.jpg'],
            ['name' => 'Teclado Logitech K380', 'brand' => 'Logitech', 'category' => 'Oficina', 'imagen' => 'teclado logitech k380.jpg'],
            ['name' => 'Audífonos G Pro X', 'brand' => 'Logitech', 'category' => 'Oficina', 'imagen' => 'audifonos g pro x.jpg'],
            ['name' => 'Cámara web Logitech C920', 'brand' => 'Logitech', 'category' => 'Oficina', 'imagen' => 'camara web logitech c920.jpg'],


            // Seguridad
            ['name' => 'switch tp-link tl sf1005d', 'brand' => 'TP-Link', 'category' => 'Seguridad', 'imagen' => 'switch tp-link tl sf1005d.jpg'],
            ['name' => 'tp-link wifi dongle', 'brand' => 'TP-Link', 'category' => 'Seguridad', 'imagen' => 'tp-link wifi dongle.jpg'],
            ['name' => 'repetidor tp-link 300mbps 2.4ghz pared', 'brand' => 'TP-Link', 'category' => 'Seguridad', 'imagen' => 'repetidor tp-link 300mbps 2.4ghz pared.jpg'],
            ['name' => 'ring floodlight cam', 'brand' => 'Ring', 'category' => 'Seguridad', 'imagen' => 'ring floodlight cam.jpg'],

            ['name' => 'ring smart video doorbell', 'brand' => 'Ring', 'category' => 'Seguridad', 'imagen' => 'ring smart video doorbell.jpg'],
            ['name' => 'ring detector de movimiento', 'brand' => 'Ring', 'category' => 'Seguridad', 'imagen' => 'ring detector de movimiento.jpg'],
            ['name' => 'ring spotlight cam pro battery', 'brand' => 'Ring', 'category' => 'Seguridad', 'imagen' => 'ring spotlight cam pro battery.jpg'],
            ['name' => 'hikvision pro series 4mp acusense camera', 'brand' => 'Hikvision', 'category' => 'Seguridad', 'imagen' => 'hikvision pro series 4mp acusense camera.jpg'],

            ['name' => 'hikvision hilook', 'brand' => 'Hikvision', 'category' => 'Seguridad', 'imagen' => 'hikvision hilook.jpg'],
            ['name' => 'grabador de video digital dvd hikvision', 'brand' => 'Hikvision', 'category' => 'Seguridad', 'imagen' => 'grabador de video digital dvd hikvision.jpg'],
            ['name' => 'hikvision ds 2cv1021 g0', 'brand' => 'Hikvision', 'category' => 'Seguridad', 'imagen' => 'hikvision ds 2cv1021 g0.jpg'],

            ['name' => 'camara domo hdcvi dahua', 'brand' => 'Dahua', 'category' => 'Seguridad', 'imagen' => 'camara domo hdcvi dahua.jpg'],
            ['name' => 'router Dahua ax1500', 'brand' => 'Dahua', 'category' => 'Seguridad', 'imagen' => 'router Dahua ax1500.jpg'],
            ['name' => 'camara wifi rotatoria dahua', 'brand' => 'Dahua', 'category' => 'Seguridad', 'imagen' => 'camara wifi rotatoria dahua.jpg'],
        ];

        foreach ($productos as $item) {
            $brand = Brand::firstOrCreate(['name' => $item['brand']]);
            $category = Category::firstOrCreate(['name' => $item['category']]);

            Product::create([
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'qty' => rand(10, 50),
                'price' => rand(50, 125),
                'description' => 'Producto de la marca ' . $item['brand'],
                'thumbnail' => $item['imagen'],
                'first_image' => $item['imagen'],
                'second_image' => $item['imagen'],
                'third_image' => $item['imagen'],
                'status' => true,
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'color_id' => $colorIds[array_rand($colorIds)],
                'size_id' => $sizeIds[array_rand($sizeIds)],
            ]);
        }
    }
}
