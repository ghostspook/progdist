<?php

namespace Database\Seeders;

use App\Models\InstructorArea;
use Illuminate\Database\Seeder;

class InstructorAreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        InstructorArea::create(['area_id' =>  1, 'instructor_id' => 10]);
        InstructorArea::create(['area_id' =>  1, 'instructor_id' =>  6]);
        InstructorArea::create(['area_id' =>  2, 'instructor_id' =>  6]);
        InstructorArea::create(['area_id' =>  5, 'instructor_id' =>  17]);
        InstructorArea::create(['area_id' =>  5, 'instructor_id' =>  48]);
    }
}
