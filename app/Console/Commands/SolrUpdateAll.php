<?php

namespace App\Console\Commands;

use App\Services\SolrServices\SolrUpdateService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Solarium\Client;


class SolrUpdateAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hortflora:solr:update-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates SOLR index';

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
     * @return mixed
     */
    public function handle()
    {
        $service = new SolrUpdateService();
        
        $taxa = DB::table('taxa')
                ->pluck('guid');
        $bar = $this->output->createProgressBar(count($taxa));
        
        foreach ($taxa as $id) {
            $service->updateDocument($id);
            $bar->advance();
        }
        
        $this->info('\r\nSOLR index has been updated');
    }
}
