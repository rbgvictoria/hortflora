<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateTaxonTree extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hortflora:taxon-tree:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates (or re-creates) the Taxon Tree';

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
        $tree = new \App\Services\TaxonTreeService();
        $tree->create();
        $this->info('Taxon tree has been successfully created');
    }
}
