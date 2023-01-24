<?php

namespace Database\Seeders;

use App\Models\CompilePhrase;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('compile_phrases')->truncate();
        DB::table('user_exercise_type')->truncate();
        Schema::enableForeignKeyConstraints();

       $phrases = [
           ['phrase' => 'Test phrase number 1'],
           ['phrase' => 'Test phrase number 2'],
           ['phrase' => 'Does Samuel L. Jackson like anime?'],
           ['phrase' => 'How did you pass the exam?'],
       ];


       foreach ($phrases as $phrase)
       {
           CompilePhrase::create($phrase);
       }

    }
}
