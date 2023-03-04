<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class CheckDomainStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected object $domains;

    public function __construct(Collection $domains)
    {
        $this -> domains = $domains;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this -> domains as $domain) {

            $dir = storage_path('app/sitemap/' . $domain -> name);
//            $files =array_filter(scandir($dir),fn($v)=>$v!='.'&&$v!='..');
            $files = array_values(array_diff(scandir($dir), ['.', '..']));
            if (($count = count($files)) > 1) {
                $files = array_diff($files, ['sitemap.xml']);
                $index = rand(0, $count - 1);
                $file = $files[$index];
            } else {
                $file = $files[0];
            }
            $path = storage_path('app/sitemap/' . $domain -> name . '/' . $file);
            if (file_exists($path)) {
                $xml = simplexml_load_file($path);
                $xmlCount = $xml -> count();
                $firstNumberIndex = range(0, 5);
                $lastNumberIndex = array_map(fn($v) => rand(5, $xmlCount), array_fill(0, 6, null));
                $cols = array_merge($lastNumberIndex, $firstNumberIndex);
                $xmlChildren = $xml -> children();
                foreach ($cols as $index) {
                    try {
                        $url = trim($xmlChildren[$index] -> loc -> __toString());
                        $response = Http ::get($url);
                        if ($response -> status() != 200) {

                        }
                    } catch (\Exception $exception) {

                    }


                }
            }
        }
    }
}
