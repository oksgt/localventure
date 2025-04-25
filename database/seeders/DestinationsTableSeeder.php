<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DestinationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('destinations')->insert([
            [
                'name' => 'Agrowisata Tambi',
                'description' => 'Perkebunan teh yang menawarkan pemandangan indah dan udara pegunungan yang segar.',
                'address' => 'Tambi, Wonosobo, Jawa Tengah',

                'available' => true,
                'latlon' => DB::raw("ST_GeomFromText('POINT(109.9035 -7.2995)')"), // Replace with actual coordinates
                'created_at' => now(),
                'created_by' => 1,

            ],
            [
                'name' => 'Kompleks Candi Arjuna',
                'description' => 'Kompleks candi bersejarah di Dataran Tinggi Dieng, cocok untuk pecinta sejarah.',
                'address' => 'Dieng, Wonosobo, Jawa Tengah',

                'available' => true,
                'latlon' => DB::raw("ST_GeomFromText('POINT(109.9225 -7.2045)')"), // Replace with actual coordinates
                'created_at' => now(),
                'created_by' => 1,

            ],
            [
                'name' => 'Dataran Tinggi Dieng',
                'description' => 'Terkenal dengan lanskap vulkanik, telaga warna, dan festival budaya.',
                'address' => 'Dieng, Wonosobo, Jawa Tengah',

                'available' => true,
                'latlon' => DB::raw("ST_GeomFromText('POINT(109.9108 -7.2100)')"), // Replace with actual coordinates
                'created_at' => now(),
                'created_by' => 1,

            ],
            [
                'name' => 'Gunung Bismo',
                'description' => 'Destinasi hiking populer dengan pemandangan panorama yang indah.',
                'address' => 'Bismo, Wonosobo, Jawa Tengah',

                'available' => true,
                'latlon' => DB::raw("ST_GeomFromText('POINT(109.8612 -7.3068)')"), // Replace with actual coordinates
                'created_at' => now(),
                'created_by' => 1,

            ],
            [
                'name' => 'Atraksi Balon Java',
                'description' => 'Acara unik yang menampilkan balon udara warna-warni.',
                'address' => 'Wonosobo, Jawa Tengah',

                'available' => true,
                'latlon' => DB::raw("ST_GeomFromText('POINT(109.9050 -7.3000)')"), // Replace with actual coordinates
                'created_at' => now(),
                'created_by' => 1,

            ],
            [
                'name' => 'Sindoro Sumbing Duathlon dan Triathlon',
                'description' => 'Acara olahraga petualangan yang menarik bagi pencari tantangan.',
                'address' => 'Gunung Sindoro dan Sumbing, Wonosobo, Jawa Tengah',

                'available' => true,
                'latlon' => DB::raw("ST_GeomFromText('POINT(109.9000 -7.2500)')"), // Replace with actual coordinates
                'created_at' => now(),
                'created_by' => 1,

            ],
            [
                'name' => 'Merti Bumi Igirmanak',
                'description' => 'Perayaan budaya yang menampilkan tradisi lokal.',
                'address' => 'Igirmanak, Wonosobo, Jawa Tengah',

                'available' => true,
                'latlon' => DB::raw("ST_GeomFromText('POINT(109.8700 -7.2900)')"), // Replace with actual coordinates
                'created_at' => now(),
                'created_by' => 1,

            ],
        ]);
    }

}
