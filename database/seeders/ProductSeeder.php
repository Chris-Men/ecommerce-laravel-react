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
            ['name' => 'Lego City', 'brand' => 'Lego', 'category' => 'Juguetes', 'imagen' => 'products/lego city.jpg'],
            ['name' => 'Lego Star Wars', 'brand' => 'Lego', 'category' => 'Juguetes', 'imagen' => 'products/lego star wars.jpg'],
            ['name' => 'Lego Technic', 'brand' => 'Lego', 'category' => 'Juguetes', 'imagen' => 'products/lego technic.jpg'],
            ['name' => 'Lego Friends', 'brand' => 'Lego', 'category' => 'Juguetes', 'imagen' => 'products/lego friends.jpg'],

            ['name' => 'Barbie', 'brand' => 'Mattel', 'category' => 'Juguetes', 'imagen' => 'products/barbie.jpg'],
            ['name' => 'Hot Wheels', 'brand' => 'Mattel', 'category' => 'Juguetes', 'imagen' => 'products/hot wheels.jpg'],
            ['name' => 'Fisher-Price Laugh & Learn', 'brand' => 'Mattel', 'category' => 'Juguetes', 'imagen' => 'products/fisher price 12.jpg'],

            ['name' => 'Uno (juego de cartas)', 'brand' => 'Mattel', 'category' => 'Juguetes', 'imagen' => 'products/uno juego de cartas.jpg'],

            ['name' => 'Monopoly', 'brand' => 'Hasbro', 'category' => 'Juguetes', 'imagen' => 'products/monopoly.jpg'],
            ['name' => 'Nerf Gun', 'brand' => 'Hasbro', 'category' => 'Juguetes', 'imagen' => 'products/nerf gun.jpg'],
            ['name' => 'Transformers', 'brand' => 'Hasbro', 'category' => 'Juguetes', 'imagen' => 'products/transformers.jpg'],
            ['name' => 'Play-Doh', 'brand' => 'Hasbro', 'category' => 'Juguetes', 'imagen' => 'products/play doh.jpg'],

            ['name' => 'Piano de aprendizaje', 'brand' => 'Fisher-Price', 'category' => 'Juguetes', 'imagen' => 'products/piano de aprendizaje.jpg'],
            ['name' => 'Andador de león', 'brand' => 'Fisher-Price', 'category' => 'Juguetes', 'imagen' => 'products/andador de leon.jpg'],
            ['name' => 'Teléfono parlante', 'brand' => 'Fisher-Price', 'category' => 'Juguetes', 'imagen' => 'products/telefono parlante.jpg'],
            ['name' => 'Silla vibradora', 'brand' => 'Fisher-Price', 'category' => 'Juguetes', 'imagen' => 'products/silla vibradora.jpg'],

            // Tecnología
            ['name' => 'Huawei Matepad 11', 'brand' => 'Huawei', 'category' => 'Tecnología', 'imagen' => 'products/huawei matepad 11.jpg'],
            ['name' => 'Huawei Matebook D15', 'brand' => 'Huawei', 'category' => 'Tecnología', 'imagen' => 'products/huawei matebook d15.jpg'],
            ['name' => 'Huawei Watch GT5', 'brand' => 'Huawei', 'category' => 'Tecnología', 'imagen' => 'products/huawei watch gt5.jpg'],
            ['name' => 'Huawei Mate 50 Pro', 'brand' => 'Huawei', 'category' => 'Tecnología', 'imagen' => 'products/huawei mate 50 pro.jpg'],

            ['name' => 'Sony WF 1000x', 'brand' => 'Sony', 'category' => 'Tecnología', 'imagen' => 'products/sony wf 1000x.jpg'],
            ['name' => 'PlayStation 5', 'brand' => 'Sony', 'category' => 'Tecnología', 'imagen' => 'products/play station 5.jpg'],
            ['name' => 'Sony WH-1000XM4', 'brand' => 'Sony', 'category' => 'Tecnología', 'imagen' => 'products/sony wh 1000xm4.JPEG'],
            ['name' => 'Sony Xperia 1', 'brand' => 'Sony', 'category' => 'Tecnología', 'imagen' => 'products/sony xperia 1.JPEG'],

            ['name' => 'Samsung Galaxy S24 Ultra', 'brand' => 'Samsung', 'category' => 'Tecnología', 'imagen' => 'products/samsung galaxy s24 ultra.jpg'],
            ['name' => 'Laptop Apple Macbook Pro', 'brand' => 'Apple', 'category' => 'Tecnología', 'imagen' => 'products/laptop apple macbook pro.jpg'],
            ['name' => 'AirPods 3ra generación', 'brand' => 'Apple', 'category' => 'Tecnología', 'imagen' => 'products/airpods 3ra generacion.jpg'],
            ['name' => 'Apple TV', 'brand' => 'Apple', 'category' => 'Tecnología', 'imagen' => 'products/apple tv.jpg'],

            ['name' => 'iPhone 16', 'brand' => 'Apple', 'category' => 'Tecnología', 'imagen' => 'products/iphone 16.jpg'],
            ['name' => 'Samsung Galaxy Tab S8', 'brand' => 'Samsung', 'category' => 'Tecnología', 'imagen' => 'products/samsung galaxy tab s8.jpg'],
            ['name' => 'Samsung Galaxy Buds', 'brand' => 'Samsung', 'category' => 'Tecnología', 'imagen' => 'products/samsung galaxy buds.jpg'],


            // Oficina
            ['name' => 'Impresora HP DeskJet', 'brand' => 'HP', 'category' => 'Oficina', 'imagen' => 'products/impresora hp deskjet.jpg'],
            ['name' => 'Laptop HP Pavilion', 'brand' => 'HP', 'category' => 'Oficina', 'imagen' => 'products/laptop hp pavilion.jpg'],
            ['name' => 'Mouse HP X1000', 'brand' => 'HP', 'category' => 'Oficina', 'imagen' => 'products/mouse hp x1000.jpg'],
            ['name' => 'Monitor HP 24', 'brand' => 'HP', 'category' => 'Oficina', 'imagen' => 'products/monitor hp 24.jpg'],

            ['name' => 'Cámara Canon EOS', 'brand' => 'Canon', 'category' => 'Oficina', 'imagen' => 'products/camara canon eos.jpg'],
            ['name' => 'Impresora Canon PIXMA', 'brand' => 'Canon', 'category' => 'Oficina', 'imagen' => 'products/impresora canon pixma.jpg'],
            ['name' => 'Escáner CanoScan', 'brand' => 'Canon', 'category' => 'Oficina', 'imagen' => 'products/escaner canoscan.jpg'],
            ['name' => 'Lente Canon EF', 'brand' => 'Canon', 'category' => 'Oficina', 'imagen' => 'products/lente canon ef.jpg'],

            ['name' => 'Impresora Epson EcoTank', 'brand' => 'Epson', 'category' => 'Oficina', 'imagen' => 'products/impresora epson ecotank.jpg'],
            ['name' => 'Proyector Epson Home Cinema', 'brand' => 'Epson', 'category' => 'Oficina', 'imagen' => 'products/proyector epson home cinema.jpg'],
            ['name' => 'Escáner Epson Perfection', 'brand' => 'Epson', 'category' => 'Oficina', 'imagen' => 'products/escaner epson perfection.jpg'],
            ['name' => 'Cartuchos Epson T664', 'brand' => 'Epson', 'category' => 'Oficina', 'imagen' => 'products/cartuchos epson t664.jpg'],

            ['name' => 'Mouse Logitech M185', 'brand' => 'Logitech', 'category' => 'Oficina', 'imagen' => 'products/mouse logitech m185.jpg'],
            ['name' => 'Teclado Logitech K380', 'brand' => 'Logitech', 'category' => 'Oficina', 'imagen' => 'products/teclado logitech k380.jpg'],
            ['name' => 'Audífonos G Pro X', 'brand' => 'Logitech', 'category' => 'Oficina', 'imagen' => 'products/audífonos g pro x.jpg'],
            ['name' => 'Cámara web Logitech C920', 'brand' => 'Logitech', 'category' => 'Oficina', 'imagen' => 'products/camara web logitech c920.jpg'],


            // Seguridad
            ['name' => 'switch tp-link tl sf1005d', 'brand' => 'TP-Link', 'category' => 'Seguridad', 'imagen' => 'products/switch tp link tl sf1005d.jpg'],
            ['name' => 'tp-link wifi dongle', 'brand' => 'TP-Link', 'category' => 'Seguridad', 'imagen' => 'products/tp link wifi dongle.jpg'],
            ['name' => 'repetidor tp-link 300mbps 2.4ghz pared', 'brand' => 'TP-Link', 'category' => 'Seguridad', 'imagen' => 'products/repetidor tp link 300mbps.jpg'],
            ['name' => 'ring floodlight cam', 'brand' => 'Ring', 'category' => 'Seguridad', 'imagen' => 'ring floodlight cam.JPEG'],

            ['name' => 'ring smart video doorbell', 'brand' => 'Ring', 'category' => 'Seguridad', 'imagen' => 'products/ring smart video doorbell.jpg'],
            ['name' => 'ring detector de movimiento', 'brand' => 'Ring', 'category' => 'Seguridad', 'imagen' => 'products/ring detector de movimiento.jpg'],
            ['name' => 'ring spotlight cam pro battery', 'brand' => 'Ring', 'category' => 'Seguridad', 'imagen' => 'products/ring spotlight cam pro battery.jpg'],
            ['name' => 'hikvision pro series 4mp acusense camera', 'brand' => 'Hikvision', 'category' => 'Seguridad', 'imagen' => 'products/hikvision pro series 4mp acusense camera.jpg'],

            ['name' => 'hikvision hilook', 'brand' => 'Hikvision', 'category' => 'Seguridad', 'imagen' => 'products/hikvision hilook.jpg'],
            ['name' => 'grabador de video digital dvd hikvision', 'brand' => 'Hikvision', 'category' => 'Seguridad', 'imagen' => 'products/grabador de video digital dvd hikvision.jpg'],
            ['name' => 'hikvision ds 2cv1021 g0', 'brand' => 'Hikvision', 'category' => 'Seguridad', 'imagen' => 'products/hikvision ds 2cv.JPEG'],

            ['name' => 'camara domo hdcvi dahua', 'brand' => 'Dahua', 'category' => 'Seguridad', 'imagen' => 'products/camara domo hdcvi dahua.jpg'],
            ['name' => 'router Dahua ax1500', 'brand' => 'Dahua', 'category' => 'Seguridad', 'imagen' => 'products/router Dahua ax1500.jpg'],
            ['name' => 'camara wifi rotatoria dahua', 'brand' => 'Dahua', 'category' => 'Seguridad', 'imagen' => 'products/camara wifi rotatoria dahua.JPEG'],

//deporte
             // Productos de Reebok
            ['name' => 'reebok freestyle hi', 'brand' => 'Reebok', 'category' => 'Deporte', 'imagen' => 'products/reebok freestyle hi.JPEG'],
            ['name' => 'reebok princess w', 'brand' => 'Reebok', 'category' => 'Deporte', 'imagen' => 'products/reebok princess.jpg'],
            ['name' => 'reebok club c', 'brand' => 'Reebok', 'category' => 'Deporte', 'imagen' => 'products/reebok club c.jpg'],
            ['name' => 'reebok classic harman', 'brand' => 'Reebok', 'category' => 'Deporte', 'imagen' => 'products/reebok classic harman.jpg'],

            // Productos de Puma
            ['name' => 'calcetines deportivos puma repreve', 'brand' => 'Puma', 'category' => 'Deporte', 'imagen' => 'products/calcetines deportivos puma repreve.jpg'],
            ['name' => 'bolso de mano puma phase', 'brand' => 'Puma', 'category' => 'Deporte', 'imagen' => 'products/bolso de mano puma phase.JPEG'],
            ['name' => 'pasamontañas puma', 'brand' => 'Puma', 'category' => 'Deporte', 'imagen' => 'products/pasamontanas puma.jpg'],
            ['name' => 'puma motosport helmet bag', 'brand' => 'Puma', 'category' => 'Deporte', 'imagen' => 'products/puma motosport helmet bag.jpg'],

            // Productos de Adidas
            ['name' => 'adidas superstar', 'brand' => 'Adidas', 'category' => 'Deporte', 'imagen' => 'products/adidas superstar.JPEG'],
            ['name' => 'adidas ultraboost 5', 'brand' => 'Adidas', 'category' => 'Deporte', 'imagen' => 'products/adidas ultraboost 5.jpg'],
            ['name' => 'adidas samba It', 'brand' => 'Adidas', 'category' => 'Deporte', 'imagen' => 'products/adidas samba lt.jpg'],
            ['name' => 'adidas run 60s', 'brand' => 'Adidas', 'category' => 'Deporte', 'imagen' => 'products/adidas run 60s.JPEG'],

            // Productos de Nike
            ['name' => 'nike jam para mujer', 'brand' => 'Nike', 'category' => 'Deporte', 'imagen' => 'products/nike jam para mujer.JPEG'],
            ['name' => 'nike air max muse', 'brand' => 'Nike', 'category' => 'Deporte', 'imagen' => 'products/nike air max muse.JPEG'],
            ['name' => 'nike air max 270', 'brand' => 'Nike', 'category' => 'Deporte', 'imagen' => 'products/nike air max 270.JPEG'],
            ['name' => 'nike blazer mid 77 vintage', 'brand' => 'Nike', 'category' => 'Deporte', 'imagen' => 'products/nike blazer mid 77 vintage.JPEG'],

            //belleza
            // Productos de Dove
            ['name' => 'barra desodorante original', 'brand' => 'Dove', 'category' => 'Belleza', 'imagen' => 'products/barra desodorante original.jpg'],
            ['name' => 'shampoo bond intense repair', 'brand' => 'Dove', 'category' => 'Belleza', 'imagen' => 'products/shampoo bond intense repair.jpg'],
            ['name' => 'antitranspirante en spray dove', 'brand' => 'Dove', 'category' => 'Belleza', 'imagen' => 'products/antitranspirante en spray dove.JPEG'],
            ['name' => 'jabon original dove', 'brand' => 'Dove', 'category' => 'Belleza', 'imagen' => 'products/jabon original dove.jpg'],
            ['name' => 'crema revitalizante para hombre', 'brand' => 'Dove', 'category' => 'Belleza', 'imagen' => 'products/crema revitalizante para hombre.JPEG'],
            ['name' => 'crema facial tono natural', 'brand' => 'Dove', 'category' => 'Belleza', 'imagen' => 'products/crema facial tono natural.jpg'],
            ['name' => 'crema corporal nutritiva', 'brand' => 'Dove', 'category' => 'Belleza', 'imagen' => 'products/crema corporal nutritiva.jpg'],
            ['name' => 'locion corporal mantequilla de cacao', 'brand' => 'Dove', 'category' => 'Belleza', 'imagen' => 'products/locion corporal mantequilla de cacao.JPEG'],

            // Productos de Maybelline
            ['name' => 'base de maquillaje superstay', 'brand' => 'Maybelline', 'category' => 'Belleza', 'imagen' => 'products/base de maquillaje superstay.JPEG'],
            ['name' => 'corrector anti ojeras', 'brand' => 'Maybelline', 'category' => 'Belleza', 'imagen' => 'products/corrector anti ojeras.JPEG'],
            ['name' => 'labial liquido vinil', 'brand' => 'Maybelline', 'category' => 'Belleza', 'imagen' => 'products/labial liquido vinil.jpg'],
            ['name' => 'mascara para pestañas lavable', 'brand' => 'Maybelline', 'category' => 'Belleza', 'imagen' => 'products/mascara para pestañas lavable.jpg'],

            // Productos de L'Oreal
            ['name' => 'protector solar defender serum', 'brand' => 'L\'Oreal', 'category' => 'Belleza', 'imagen' => 'products/protector solar defender serum.JPEG'],
            ['name' => 'shampoo absolute repair', 'brand' => 'L\'Oreal', 'category' => 'Belleza', 'imagen' => 'products/shampoo bond intense repair.jpg'],
            ['name' => 'cera true match tinted', 'brand' => 'L\'Oreal', 'category' => 'Belleza', 'imagen' => 'products/cera true match tinted.jpg'],
            ['name' => 'paris infallible fresh wear liquid', 'brand' => 'L\'Oreal', 'category' => 'Belleza', 'imagen' => 'products/paris infallible fresh wear liquid.JPEG'],

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
                'image' => $item['imagen'],

                'status' => true,
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'color_id' => $colorIds[array_rand($colorIds)],
                'size_id' => $sizeIds[array_rand($sizeIds)],
            ]);
        }
    }
}
