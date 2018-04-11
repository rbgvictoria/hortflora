<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Solarium\Client;
use App\Services\SolrServices\SolrDeleteService;

class SolrDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hortflora:solr:delete {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a document from the SOLR index';

    protected $status = [
        '400' => '400: Bad request',
        '401' => '401: Unauthorised',
        '403' => '403: Forbidden',
        '404' => '404: Not found',
        '500' => '500: Server error',
        '503' => '503: Service unavailable',
        '0' => '0: Unknown',
    ];

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
        $client = new Client(config('solarium'));
        $service = new SolrDeleteService($client);
        $id = $this->argument('id');
        $status = $service->deleteById($id);
        if ($status) {
            $this->info($this->status[$status]);
        }
        else {
            $this->info("Document \"$id\" succesfully deleted");
        }
    }
}
