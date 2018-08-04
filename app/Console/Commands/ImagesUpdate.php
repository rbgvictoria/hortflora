<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ImageLoaderService;

class ImagesUpdate extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'hortflora:images:update';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Updates images from Roboflow export';

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
    $dir = env('APP_PATH') . '/resources/roboflow';
    $this->info($dir);
    chdir($dir);
    if (is_dir($dir)) {
      if ($dh = opendir($dir)) {
        $files = [];
        while (($file = readdir($dh)) !== false) {
          $files[] = $file;
        }
        sort($files);
        $filesRev = array_reverse($files);
        $this->info('Processing file: ' . $filesRev[0]);
        $service = new ImageLoaderService($filesRev[0], 'hortflora');
        $service->parseFile();
        $bar = $this->output->createProgressBar(count($service->items));
        foreach ($service->items as $index => $item) {
          $service->processItem($item);
          $bar->advance();
        }
        $this->info("\n");
        $this->info('Images have been updated');
      }
    }
  }
}
