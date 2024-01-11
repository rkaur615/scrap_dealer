<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class SetupCSC extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csc:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Country State City Setup Database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $countriesResponse = Http::get('https://raw.githubusercontent.com/dr5hn/countries-states-cities-database/master/sql/countries.sql');
        $countrySql = $countriesResponse->body();
        DB::unprepared($countrySql);
        //dd($countrySql);
        return 0;
    }
}
