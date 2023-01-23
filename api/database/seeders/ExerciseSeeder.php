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
        DB::table('exercise_types')->truncate();
        Schema::enableForeignKeyConstraints();

       $phrases = [
           ['phrase' => 'Test phrase number 1'],
           ['phrase' => 'Test phrase number 2'],
       ];

       foreach ($phrases as $phrase)
       {
           $createdPhrase = CompilePhrase::create($phrase);
           $createdPhrase->exerciseType()->create(['exercise_id' => $createdPhrase->id, 'user_id' => 0]);
       }

    }
}
