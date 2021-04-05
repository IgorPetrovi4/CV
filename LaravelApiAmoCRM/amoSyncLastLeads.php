<?php

namespace App\Console\Commands;


use App\Services\AmoCRM\Leads;
use Illuminate\Console\Command;

class amoSyncLastLeads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amocrm:sync_last_leads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'AmoCrm  leads limit 1000 sync';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle(Leads $leads)
    {
       return $leads->syncLatest();
    }
}
