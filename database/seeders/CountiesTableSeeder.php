<?php

namespace Database\Seeders;

use App\Models\County;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CountiesTableSeeder extends Seeder
{
    public function run()
    {
        $counties = [
            ['name' => 'Alba'],
            ['name' => 'Arad'],
            ['name' => 'Argeș'],
            ['name' => 'Bacău'],
            ['name' => 'Bihor'],
            ['name' => 'Bistrița-Năsăud'],
            ['name' => 'Botoșani'],
            ['name' => 'Brașov'],
            ['name' => 'Brăila'],
            ['name' => 'Buzău'],
            ['name' => 'Caraș-Severin'],
            ['name' => 'Călărași'],
            ['name' => 'Cluj'],
            ['name' => 'Constanța'],
            ['name' => 'Covasna'],
            ['name' => 'Dâmbovița'],
            ['name' => 'Dolj'],
            ['name' => 'Galați'],
            ['name' => 'Giurgiu'],
            ['name' => 'Gorj'],
            ['name' => 'Harghita'],
            ['name' => 'Hunedoara'],
            ['name' => 'Ialomița'],
            ['name' => 'Iași'],
            ['name' => 'Ilfov'],
            ['name' => 'Maramureș'],
            ['name' => 'Mehedinți'],
            ['name' => 'Mureș'],
            ['name' => 'Neamț'],
            ['name' => 'Olt'],
            ['name' => 'Prahova'],
            ['name' => 'Satu Mare'],
            ['name' => 'Sălaj'],
            ['name' => 'Sibiu'],
            ['name' => 'Suceava'],
            ['name' => 'Teleorman'],
            ['name' => 'Timiș'],
            ['name' => 'Tulcea'],
            ['name' => 'Vaslui'],
            ['name' => 'Vâlcea'],
            ['name' => 'Vrancea'],
            ['name' => 'București'], // Bucharest is the capital and also considered a county
        ];

        foreach ($counties as $county) {
            County::create($county);
        }
    }
}
