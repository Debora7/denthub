<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\County;
use League\Csv\Reader;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesTableSeeder extends Seeder
{
    public function run()
    {
        $csvPath = base_path('/database/seeders/cities.csv');

        // Load the CSV document from a file path
        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setHeaderOffset(0);

        // Iterate through the CSV records
        foreach ($csv as $record) {
            DB::table('cities')->insert([
                'name' => $record['name'],
                'county_id' => County::where('name', $record['county_name'])->first()->id,
            ]);
        }
    }
}
