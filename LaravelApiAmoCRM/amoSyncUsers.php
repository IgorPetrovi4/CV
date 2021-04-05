<?php

namespace App\Console\Commands;

use App\Services\AmoCRM\Users;
use Illuminate\Console\Command;

class amoSyncUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amocrm:sync_users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    public function handle(Users $users)
    {
        return $users->syncAll();
    }
}
