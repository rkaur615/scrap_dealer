<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\ServiceRequestController;
use App\Models\ServiceRequest;
use Illuminate\Console\Command;

class AutoAssignServiceProvider extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:serviceProvider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto assign service provider to pernding service request';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $serviceObj = new ServiceRequestController;
        $serviceObj->autoAssignServiceProvider();
        $this->info('Success');
    }
}
