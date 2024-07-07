<?php

namespace Database\Seeders;

use App\Models\County;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use League\Csv\Reader;

class CountiesTableSeeder extends Seeder
{
    public function run()
    {
        // Load the CSV file
        $csvPath = base_path('/database/seeders/cities.csv');

        // Load the CSV document from a file path
        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        // Initialize an array to store unique counties
        $uniqueCounties = [];

        // Loop through the records and add unique counties to the array
        foreach ($records as $record) {
            $countyName = $record['county_name'];
            if (!in_array($countyName, $uniqueCounties)) {
                $uniqueCounties[] = $countyName;
            }
        }

        // Loop through the unique counties array and create records in the database
        foreach ($uniqueCounties as $county) {
            County::create(['name' => $county]);
        }
    }
}
