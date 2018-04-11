<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SolrServices\SolrUpdateService;

class SolrUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hortflora:solr:update {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates a document in the SOLR index, or creates a new one if one doesn\'t already exist';

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
        $id = $this->argument('id');
        $service->updateDocument($id);
        $this->info("Document \"$id\" successfully updated");
    }
}
