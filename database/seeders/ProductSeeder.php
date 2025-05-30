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
            ['name' => 'Lego City', 'brand' => 'Lego', 'category' => 'Juguetes', 'image' => 'products/lego city.jpg'],
            ['name' => 'Lego Star Wars', 'brand' => 'Lego', 'category' => 'Juguetes', 'image' => 'products/lego star wars.jpg'],
            ['name' => 'Lego Technic', 'brand' => 'Lego', 'category' => 'Juguetes', 'image' => 'products/lego technic.jpg'],
            ['name' => 'Lego Friends', 'brand' => 'Lego', 'category' => 'Juguetes', 'image' => 'products/lego friends.jpg'],

            ['name' => 'Barbie', 'brand' => 'Mattel', 'category' => 'Juguetes', 'image' => 'products/barbie.jpg'],
            ['name' => 'Hot Wheels', 'brand' => 'Mattel', 'category' => 'Juguetes', 'image' => 'products/hot wheels.jpg'],
            ['name' => 'Fisher-Price Laugh & Learn', 'brand' => 'Mattel', 'category' => 'Juguetes', 'image' => 'products/fisher price 12.jpg'],
            ['name' => 'Uno (juego de cartas)', 'brand' => 'Mattel', 'category' => 'Juguetes', 'image' => 'products/uno juego de cartas.jpg'],

            ['name' => 'Monopoly', 'brand' => 'Hasbro', 'category' => 'Juguetes', 'image' => 'products/monopoly.jpg'],
            ['name' => 'Nerf Gun', 'brand' => 'Hasbro', 'category' => 'Juguetes', 'image' => 'products/nerf gun.jpg'],
            ['name' => 'Transformers', 'brand' => 'Hasbro', 'category' => 'Juguetes', 'image' => 'products/transformers.jpg'],
            ['name' => 'Play-Doh', 'brand' => 'Hasbro', 'category' => 'Juguetes', 'image' => 'products/play doh.jpg'],

            ['name' => 'Piano de aprendizaje', 'brand' => 'Fisher-Price', 'category' => 'Juguetes', 'image' => 'products/piano de aprendizaje.jpg'],
            ['name' => 'Andador de león', 'brand' => 'Fisher-Price', 'category' => 'Juguetes', 'image' => 'products/andador de leon.jpg'],
            ['name' => 'Teléfono parlante', 'brand' => 'Fisher-Price', 'category' => 'Juguetes', 'image' => 'products/telefono parlante.jpg'],
            ['name' => 'Silla vibradora', 'brand' => 'Fisher-Price', 'category' => 'Juguetes', 'image' => 'products/silla vibradora.jpg'],

            // Tecnología
            ['name' => 'Huawei Matepad 11', 'brand' => 'Huawei', 'category' => 'Tecnología', 'image' => 'products/huawei matepad 11.jpg'],
            ['name' => 'Huawei Matebook D15', 'brand' => 'Huawei', 'category' => 'Tecnología', 'image' => 'products/huawei matebook d15.jpg'],
            ['name' => 'Huawei Watch GT5', 'brand' => 'Huawei', 'category' => 'Tecnología', 'image' => 'products/huawei watch gt5.jpg'],
            ['name' => 'Huawei Mate 50 Pro', 'brand' => 'Huawei', 'category' => 'Tecnología', 'image' => 'products/huawei mate 50 pro.jpg'],

            ['name' => 'Sony WF 1000x', 'brand' => 'Sony', 'category' => 'Tecnología', 'image' => 'products/sony wf 1000x.jpg'],
            ['name' => 'PlayStation 5', 'brand' => 'Sony', 'category' => 'Tecnología', 'image' => 'products/play station 5.jpg'],
            ['name' => 'Sony WH-1000XM4', 'brand' => 'Sony', 'category' => 'Tecnología', 'image' => 'products/sony wh 1000xm4.JPEG'],
            ['name' => 'Sony Xperia 1', 'brand' => 'Sony', 'category' => 'Tecnología', 'image' => 'products/sony xperia 1.JPEG'],

            ['name' => 'Samsung Galaxy S24 Ultra', 'brand' => 'Samsung', 'category' => 'Tecnología', 'image' => 'products/samsung galaxy s24 ultra.jpg'],
            ['name' => 'Laptop Apple Macbook Pro', 'brand' => 'Apple', 'category' => 'Tecnología', 'image' => 'products/laptop apple macbook pro.jpg'],
            ['name' => 'AirPods 3ra generación', 'brand' => 'Apple', 'category' => 'Tecnología', 'image' => 'products/airpods 3ra generacion.jpg'],
            ['name' => 'Apple TV', 'brand' => 'Apple', 'category' => 'Tecnología', 'image' => 'products/apple tv.jpg'],

            ['name' => 'iPhone 16', 'brand' => 'Apple', 'category' => 'Tecnología', 'image' => 'products/iphone 16.jpg'],
            ['name' => 'Samsung Galaxy Tab S8', 'brand' => 'Samsung', 'category' => 'Tecnología', 'image' => 'products/samsung galaxy tab s8.jpg'],
            ['name' => 'Samsung Galaxy Buds', 'brand' => 'Samsung', 'category' => 'Tecnología', 'image' => 'products/samsung galaxy buds.jpg'],


            // Oficina
            ['name' => 'Impresora HP DeskJet', 'brand' => 'HP', 'category' => 'Oficina', 'image' => 'products/impresora hp deskjet.jpg'],
            ['name' => 'Laptop HP Pavilion', 'brand' => 'HP', 'category' => 'Oficina', 'image' => 'products/laptop hp pavilion.jpg'],
            ['name' => 'Mouse HP X1000', 'brand' => 'HP', 'category' => 'Oficina', 'image' => 'products/mouse hp x1000.jpg'],
            ['name' => 'Monitor HP 24', 'brand' => 'HP', 'category' => 'Oficina', 'image' => 'products/monitor hp 24.jpg'],

            ['name' => 'Cámara Canon EOS', 'brand' => 'Canon', 'category' => 'Oficina', 'image' => 'products/camara canon eos.jpg'],
            ['name' => 'Impresora Canon PIXMA', 'brand' => 'Canon', 'category' => 'Oficina', 'image' => 'products/impresora canon pixma.jpg'],
            ['name' => 'Escáner CanoScan', 'brand' => 'Canon', 'category' => 'Oficina', 'image' => 'products/escaner canoscan.jpg'],
            ['name' => 'Lente Canon EF', 'brand' => 'Canon', 'category' => 'Oficina', 'image' => 'products/lente canon ef.jpg'],

            ['name' => 'Impresora Epson EcoTank', 'brand' => 'Epson', 'category' => 'Oficina', 'image' => 'products/impresora epson ecotank.jpg'],
            ['name' => 'Proyector Epson Home Cinema', 'brand' => 'Epson', 'category' => 'Oficina', 'image' => 'products/proyector epson home cinema.jpg'],
            ['name' => 'Escáner Epson Perfection', 'brand' => 'Epson', 'category' => 'Oficina', 'image' => 'products/escaner epson perfection.jpg'],
            ['name' => 'Cartuchos Epson T664', 'brand' => 'Epson', 'category' => 'Oficina', 'image' => 'products/cartuchos epson t664.jpg'],

            ['name' => 'Mouse Logitech M185', 'brand' => 'Logitech', 'category' => 'Oficina', 'image' => 'products/mouse logitech m185.jpg'],
            ['name' => 'Teclado Logitech K380', 'brand' => 'Logitech', 'category' => 'Oficina', 'image' => 'products/teclado logitech k380.jpg'],
            ['name' => 'Audífonos G Pro X', 'brand' => 'Logitech', 'category' => 'Oficina', 'image' => 'products/audífonos g pro x.jpg'],
            ['name' => 'Cámara web Logitech C920', 'brand' => 'Logitech', 'category' => 'Oficina', 'image' => 'products/camara web logitech c920.jpg'],


            // Seguridad
            ['name' => 'switch tp-link tl sf1005d', 'brand' => 'TP-Link', 'category' => 'Seguridad', 'image' => 'products/switch tp link tl sf1005d.jpg'],
            ['name' => 'tp-link wifi dongle', 'brand' => 'TP-Link', 'category' => 'Seguridad', 'image' => 'products/tp link wifi dongle.jpg'],
            ['name' => 'repetidor tp-link 300mbps 2.4ghz pared', 'brand' => 'TP-Link', 'category' => 'Seguridad', 'image' => 'products/repetidor tp link 300mbps.jpg'],
            ['name' => 'ring floodlight cam', 'brand' => 'Ring', 'category' => 'Seguridad', 'image' => 'products/ring floodlight cam.JPEG'],

            ['name' => 'ring smart video doorbell', 'brand' => 'Ring', 'category' => 'Seguridad', 'image' => 'products/ring smart video doorbell.jpg'],
            ['name' => 'ring detector de movimiento', 'brand' => 'Ring', 'category' => 'Seguridad', 'image' => 'products/ring detector de movimiento.jpg'],
            ['name' => 'ring spotlight cam pro battery', 'brand' => 'Ring', 'category' => 'Seguridad', 'image' => 'products/ring spotlight cam pro battery.jpg'],
            ['name' => 'hikvision pro series 4mp acusense camera', 'brand' => 'Hikvision', 'category' => 'Seguridad', 'image' => 'products/hikvision pro series 4mp acusense camera.jpg'],

            ['name' => 'hikvision hilook', 'brand' => 'Hikvision', 'category' => 'Seguridad', 'image' => 'products/hikvision hilook.jpg'],
            ['name' => 'grabador de video digital dvd hikvision', 'brand' => 'Hikvision', 'category' => 'Seguridad', 'image' => 'products/grabador de video digital dvd hikvision.jpg'],
            ['name' => 'hikvision ds 2cv1021 g0', 'brand' => 'Hikvision', 'category' => 'Seguridad', 'image' => 'products/hikvision ds 2cv.JPEG'],

            ['name' => 'camara domo hdcvi dahua', 'brand' => 'Dahua', 'category' => 'Seguridad', 'image' => 'products/camara domo hdcvi dahua.jpg'],
            ['name' => 'router Dahua ax1500', 'brand' => 'Dahua', 'category' => 'Seguridad', 'image' => 'products/router Dahua ax1500.jpg'],
            ['name' => 'camara wifi rotatoria dahua', 'brand' => 'Dahua', 'category' => 'Seguridad', 'image' => 'products/camara wifi rotatoria dahua.JPEG'],

//deporte
             // Productos de Reebok
            ['name' => 'reebok freestyle hi', 'brand' => 'Reebok', 'category' => 'Deporte', 'image' => 'products/reebok freestyle hi.JPEG'],
            ['name' => 'reebok princess w', 'brand' => 'Reebok', 'category' => 'Deporte', 'image' => 'products/reebok princess.jpg'],
            ['name' => 'reebok club c', 'brand' => 'Reebok', 'category' => 'Deporte', 'image' => 'products/reebok club c.jpg'],
            ['name' => 'reebok classic harman', 'brand' => 'Reebok', 'category' => 'Deporte', 'image' => 'products/reebok classic harman.jpg'],

            // Productos de Puma
            ['name' => 'calcetines deportivos puma repreve', 'brand' => 'Puma', 'category' => 'Deporte', 'image' => 'products/calcetines deportivos puma repreve.jpg'],
            ['name' => 'bolso de mano puma phase', 'brand' => 'Puma', 'category' => 'Deporte', 'image' => 'products/bolso de mano puma phase.JPEG'],
            ['name' => 'pasamontañas puma', 'brand' => 'Puma', 'category' => 'Deporte', 'image' => 'products/pasamontanas puma.jpg'],
            ['name' => 'puma motosport helmet bag', 'brand' => 'Puma', 'category' => 'Deporte', 'image' => 'products/puma motosport helmet bag.jpg'],

            // Productos de Adidas
            ['name' => 'adidas superstar', 'brand' => 'Adidas', 'category' => 'Deporte', 'image' => 'products/adidas superstar.JPEG'],
            ['name' => 'adidas ultraboost 5', 'brand' => 'Adidas', 'category' => 'Deporte', 'image' => 'products/adidas ultraboost 5.jpg'],
            ['name' => 'adidas samba It', 'brand' => 'Adidas', 'category' => 'Deporte', 'image' => 'products/adidas samba lt.jpg'],
            ['name' => 'adidas run 60s', 'brand' => 'Adidas', 'category' => 'Deporte', 'image' => 'products/adidas run 60s.JPEG'],

            // Productos de Nike
            ['name' => 'nike jam para mujer', 'brand' => 'Nike', 'category' => 'Deporte', 'image' => 'products/nike jam para mujer.JPEG'],
            ['name' => 'nike air max muse', 'brand' => 'Nike', 'category' => 'Deporte', 'image' => 'products/nike air max muse.JPEG'],
            ['name' => 'nike air max 270', 'brand' => 'Nike', 'category' => 'Deporte', 'image' => 'products/nike air max 270.JPEG'],
            ['name' => 'nike blazer mid 77 vintage', 'brand' => 'Nike', 'category' => 'Deporte', 'image' => 'products/nike blazer mid 77 vintage.JPEG'],

            //belleza
            // Productos de Dove
            ['name' => 'barra desodorante original', 'brand' => 'Dove', 'category' => 'Belleza', 'image' => 'products/barra desodorante original.jpg'],
            ['name' => 'shampoo bond intense repair', 'brand' => 'Dove', 'category' => 'Belleza', 'image' => 'products/shampoo bond intense repair.jpg'],
            ['name' => 'antitranspirante en spray dove', 'brand' => 'Dove', 'category' => 'Belleza', 'image' => 'products/antitranspirante en spray dove.JPEG'],
            ['name' => 'jabon original dove', 'brand' => 'Dove', 'category' => 'Belleza', 'image' => 'products/jabon original dove.jpg'],
            ['name' => 'crema revitalizante para hombre', 'brand' => 'Dove', 'category' => 'Belleza', 'image' => 'products/crema revitalizante para hombre.JPEG'],
            ['name' => 'crema facial tono natural', 'brand' => 'Dove', 'category' => 'Belleza', 'image' => 'products/crema facial tono natural.jpg'],
            ['name' => 'crema corporal nutritiva', 'brand' => 'Dove', 'category' => 'Belleza', 'image' => 'products/crema corporal nutritiva.jpg'],
            ['name' => 'locion corporal mantequilla de cacao', 'brand' => 'Dove', 'category' => 'Belleza', 'image' => 'products/locion corporal mantequilla de cacao.JPEG'],

            // Productos de Maybelline
            ['name' => 'base de maquillaje superstay', 'brand' => 'Maybelline', 'category' => 'Belleza', 'image' => 'products/base de maquillaje superstay.JPEG'],
            ['name' => 'corrector anti ojeras', 'brand' => 'Maybelline', 'category' => 'Belleza', 'image' => 'products/corrector anti ojeras.JPEG'],
            ['name' => 'labial liquido vinil', 'brand' => 'Maybelline', 'category' => 'Belleza', 'image' => 'products/labial liquido vinil.jpg'],
            ['name' => 'mascara para pestañas lavable', 'brand' => 'Maybelline', 'category' => 'Belleza', 'image' => 'products/mascara para pestañas lavable.jpg'],

            // Productos de L'Oreal
            ['name' => 'protector solar defender serum', 'brand' => 'L\'Oreal', 'category' => 'Belleza', 'image' => 'products/protector solar defender serum.JPEG'],
            ['name' => 'shampoo absolute repair', 'brand' => 'L\'Oreal', 'category' => 'Belleza', 'image' => 'products/shampoo absolute repair.jpg'],
            ['name' => 'cera true match tinted', 'brand' => 'L\'Oreal', 'category' => 'Belleza', 'image' => 'products/cera true match tinted.jpg'],
            ['name' => 'paris infallible fresh wear liquid', 'brand' => 'L\'Oreal', 'category' => 'Belleza', 'image' => 'products/paris infallible fresh wear liquid.JPEG'],

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
                'image' => $item['image'],

                'status' => true,
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'color_id' => $colorIds[array_rand($colorIds)],
                'size_id' => $sizeIds[array_rand($sizeIds)],
            ]);
        }
    }
}
