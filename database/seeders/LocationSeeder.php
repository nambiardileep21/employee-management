<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            'India' => [
                'Maharashtra' => ['Mumbai', 'Navi Mumbai', 'Pune', 'Nagpur'],
                'Karnataka' => ['Bengaluru', 'Mysuru', 'Mangaluru', 'Hubballi'],
                'Delhi' => ['New Delhi', 'North Delhi', 'South Delhi'],
            ],
            'United States' => [
                'California' => ['Los Angeles', 'San Francisco', 'San Diego', 'San Jose'],
                'Texas' => ['Houston', 'Austin', 'Dallas', 'San Antonio'],
                'New York' => ['New York City', 'Buffalo', 'Rochester'],
            ],
            'United Kingdom' => [
                'England' => ['London', 'Manchester', 'Birmingham', 'Liverpool'],
                'Scotland' => ['Edinburgh', 'Glasgow', 'Aberdeen'],
            ],
        ];

        foreach ($locations as $countryName => $states) {
            $country = Country::create(['name' => $countryName]);

            foreach ($states as $stateName => $cities) {
                $state = State::create([
                    'country_id' => $country->id,
                    'name' => $stateName,
                ]);

                foreach ($cities as $cityName) {
                    City::create([
                        'state_id' => $state->id,
                        'name' => $cityName,
                    ]);
                }
            }
        }
    }
}