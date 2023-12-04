<?php

namespace App\Console\Commands;

use App\Services\DataSyncService;
use Illuminate\Console\Command;

class SyncData extends Command
{
    /**
     * The name and signature of the console command. SS
     *
     * @var string
     */
    protected $signature = 'sync:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Touchstone CRM Database';

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
    public function handle(DataSyncService $sync)
    {
        $sync->campaigns();
        $sync->clients();
        $sync->projects();
        $sync->users();
    }
}
