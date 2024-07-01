<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\County;
use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    public function run()
    {
        $cities = [
            ['name' => 'Alba Iulia', 'county_id' => County::where('name', 'Alba')->first()->id],
            ['name' => 'Arad', 'county_id' => County::where('name', 'Arad')->first()->id],
            ['name' => 'Pitești', 'county_id' => County::where('name', 'Argeș')->first()->id],
            ['name' => 'Bacău', 'county_id' => County::where('name', 'Bacău')->first()->id],
            ['name' => 'Oradea', 'county_id' => County::where('name', 'Bihor')->first()->id],
            ['name' => 'Bistrița', 'county_id' => County::where('name', 'Bistrița-Năsăud')->first()->id],
            ['name' => 'Botoșani', 'county_id' => County::where('name', 'Botoșani')->first()->id],
            ['name' => 'Brașov', 'county_id' => County::where('name', 'Brașov')->first()->id],
            ['name' => 'Brăila', 'county_id' => County::where('name', 'Brăila')->first()->id],
            ['name' => 'Buzău', 'county_id' => County::where('name', 'Buzău')->first()->id],
            ['name' => 'Reșița', 'county_id' => County::where('name', 'Caraș-Severin')->first()->id],
            ['name' => 'Călărași', 'county_id' => County::where('name', 'Călărași')->first()->id],
            ['name' => 'Cluj-Napoca', 'county_id' => County::where('name', 'Cluj')->first()->id],
            ['name' => 'Constanța', 'county_id' => County::where('name', 'Constanța')->first()->id],
            ['name' => 'Sfântu Gheorghe', 'county_id' => County::where('name', 'Covasna')->first()->id],
            ['name' => 'Târgoviște', 'county_id' => County::where('name', 'Dâmbovița')->first()->id],
            ['name' => 'Craiova', 'county_id' => County::where('name', 'Dolj')->first()->id],
            ['name' => 'Galați', 'county_id' => County::where('name', 'Galați')->first()->id],
            ['name' => 'Giurgiu', 'county_id' => County::where('name', 'Giurgiu')->first()->id],
            ['name' => 'Târgu Jiu', 'county_id' => County::where('name', 'Gorj')->first()->id],
            ['name' => 'Miercurea Ciuc', 'county_id' => County::where('name', 'Harghita')->first()->id],
            ['name' => 'Deva', 'county_id' => County::where('name', 'Hunedoara')->first()->id],
            ['name' => 'Slobozia', 'county_id' => County::where('name', 'Ialomița')->first()->id],
            ['name' => 'Iași', 'county_id' => County::where('name', 'Iași')->first()->id],
            ['name' => 'București', 'county_id' => County::where('name', 'București')->first()->id],
            ['name' => 'Baia Mare', 'county_id' => County::where('name', 'Maramureș')->first()->id],
            ['name' => 'Drobeta-Turnu Severin', 'county_id' => County::where('name', 'Mehedinți')->first()->id],
            ['name' => 'Târgu Mureș', 'county_id' => County::where('name', 'Mureș')->first()->id],
            ['name' => 'Piatra Neamț', 'county_id' => County::where('name', 'Neamț')->first()->id],
            ['name' => 'Slatina', 'county_id' => County::where('name', 'Olt')->first()->id],
            ['name' => 'Ploiești', 'county_id' => County::where('name', 'Prahova')->first()->id],
            ['name' => 'Satu Mare', 'county_id' => County::where('name', 'Satu Mare')->first()->id],
            ['name' => 'Zalău', 'county_id' => County::where('name', 'Sălaj')->first()->id],
            ['name' => 'Sibiu', 'county_id' => County::where('name', 'Sibiu')->first()->id],
            ['name' => 'Suceava', 'county_id' => County::where('name', 'Suceava')->first()->id],
            ['name' => 'Alexandria', 'county_id' => County::where('name', 'Teleorman')->first()->id],
            ['name' => 'Timișoara', 'county_id' => County::where('name', 'Timiș')->first()->id],
            ['name' => 'Tulcea', 'county_id' => County::where('name', 'Tulcea')->first()->id],
            ['name' => 'Vaslui', 'county_id' => County::where('name', 'Vaslui')->first()->id],
            ['name' => 'Râmnicu Vâlcea', 'county_id' => County::where('name', 'Vâlcea')->first()->id],
            ['name' => 'Focșani', 'county_id' => County::where('name', 'Vrancea')->first()->id],
        ];

        foreach ($cities as $city) {
            City::create($city);
        }
    }
}
