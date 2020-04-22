<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestPostRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:post {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a post request to an url';

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
      $url = $this->argument('url');

      //Make post request tu argument of command
      $response = Http::post($url, [
        'name' => 'Miguel'
      ]);

      //Question 5.
      if ($response->successful()) {
        collect($response)->map(fn($single) => $single['name']);
      } else {
        $this->requestError($response->clientError());
      }

    }

    //Question 4
    public function requestError($e)
    {
      if ($e) {
        //We can send an error mail for administrator to notify him
        try {
          //Retry request 5 times with 100miliseconds each time.
          $response = Http::retry(5, 100)->post($url, [
            'name' => 'Miguel'
          ]);
        } catch (\Exception $e) {
          //Catch exception
          //We can send an error mail for administrator to notify him
          $this->info('Endpoint not responding.');
        }
      }
    }
}
