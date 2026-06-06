<?php

namespace Database\Seeders;

use App\Models\LandingPageContent;
use Illuminate\Database\Seeder;

class LandingPageContentSeeder extends Seeder
{
    public function run(): void
    {
        $content = [
            ['section' => 'hero', 'key' => 'hero_title_1', 'value' => 'Hidangan Sehat', 'type' => 'text'],
            ['section' => 'hero', 'key' => 'hero_title_2', 'value' => 'Lezat', 'type' => 'text'],
            ['section' => 'hero', 'key' => 'hero_subtitle', 'value' => 'Catering terbaik di Singkawang dengan bahan segar, higienis, dan cita rasa autentik yang memanjakan lidah.', 'type' => 'text'],
            ['section' => 'about', 'key' => 'about_title', 'value' => 'Kenapa AISH Catering?', 'type' => 'text'],
            ['section' => 'about', 'key' => 'about_description', 'value' => 'Kami percaya bahwa makanan lezat berawal dari bahan yang segar dan diolah dengan penuh kasih. Sejak 2018, AISH Catering telah melayani ribuan acara di Singkawang dengan standar kualitas tinggi.', 'type' => 'text'],
            ['section' => 'about', 'key' => 'about_image', 'value' => 'uploads/contents/about.png', 'type' => 'image'],
            ['section' => 'hero', 'key' => 'hero_image', 'value' => 'uploads/contents/hero.png', 'type' => 'image'],
            ['section' => 'contact', 'key' => 'whatsapp_number', 'value' => '628123456789', 'type' => 'text'],
            ['section' => 'contact', 'key' => 'email', 'value' => 'halo@aishcatering.id', 'type' => 'text'],
            ['section' => 'footer', 'key' => 'footer_copy', 'value' => 'Menyajikan kebahagiaan melalui hidangan terbaik sejak 2015.', 'type' => 'text'],
            // Gallery placeholders
            ['section' => 'gallery', 'key' => 'gallery_1', 'value' => 'https://images.unsplash.com/photo-1555244162-803834f70033', 'type' => 'image'],
            ['section' => 'gallery', 'key' => 'gallery_2', 'value' => 'https://images.unsplash.com/photo-1555244162-803834f70033', 'type' => 'image'],
            ['section' => 'gallery', 'key' => 'gallery_3', 'value' => 'https://images.unsplash.com/photo-1555244162-803834f70033', 'type' => 'image'],
            ['section' => 'packages', 'key' => 'package_wedding_image', 'value' => 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3', 'type' => 'image'],
            ['section' => 'packages', 'key' => 'package_birthday_image', 'value' => 'https://images.unsplash.com/photo-1530103043960-ef38714abb15', 'type' => 'image'],
            ['section' => 'packages', 'key' => 'package_office_image', 'value' => 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622', 'type' => 'image'],
            // Nav & UI
            ['section' => 'nav', 'key' => 'nav_login', 'value' => 'Masuk', 'type' => 'text'],
            ['section' => 'nav', 'key' => 'nav_register', 'value' => 'Daftar', 'type' => 'text'],
            ['section' => 'nav', 'key' => 'nav_logout', 'value' => 'Keluar', 'type' => 'text'],
        ];

        foreach ($content as $item) {
            LandingPageContent::updateOrCreate(['key' => $item['key']], $item);
        }
    }
}
