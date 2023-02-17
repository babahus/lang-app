<?php

namespace App\Console\Commands;

use App\Models\Dictionary;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class UpdateDictionariesStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dictionary:update_status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update solved status for Dictionary model';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return DB::table('user_exercise_type')
            ->where('type', '=', Dictionary::class)
            ->where('updated_at', '<', Carbon::now()->subDays())
            ->update(['solved' => false]);
    }
}
