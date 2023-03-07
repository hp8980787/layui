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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Client\Pool;

class CheckDomainStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected object $domains;

    public int $timeout = 99999;

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
            if (!is_dir($dir)) {
                continue;
            }
            $files = array_values(array_diff(scandir($dir), ['.', '..']));
            if (($count = count($files)) > 1) {
                $files = array_diff($files, ['sitemap.xml']);
                $index = rand(1, $count - 1);
                $file = $files[$index] ?? $files[1];
            } else {
                $file = $files[0];
            }
            $path = storage_path('app/sitemap/' . $domain -> name . '/' . $file);
            if (file_exists($path)) {
                try {
                    $xml = simplexml_load_file($path);
                } catch (\Exception $exception) {
                    Log ::channel('check') -> error($domain -> url . ' ' . $exception -> getMessage());
                    continue;
                }

                $xmlCount = $xml -> count();
                $firstNumberIndex = range(0, 3);
                $lastNumberIndex = array_map(fn($v) => rand(5, $xmlCount), array_fill(0, 6, null));
                $cols = array_merge($lastNumberIndex, $firstNumberIndex);
                $xmlChildren = $xml -> children();
                $urls = [];
                foreach ($cols as $index) {
                    $url = trim($xmlChildren[$index] -> loc -> __toString());
                    $urls[] = $url;

                }
                try {
                    $responses = Http ::pool(function (Pool $pool) use ($urls) {
                        foreach ($urls as $url) {
                            $pool -> get($url);
                        }
                    });
                    foreach ($responses as $key => $response) {
                        if ($response -> status() != 200) {
                            Redis ::lpush('errors.web', ($urls[$key] ?? $domain -> url) . ' ' . $response -> status());
                        }
                    }
                } catch (\Exception $exception) {
                    Log ::channel('check') -> error($domain -> url . ' ' . $exception -> getMessage());
                }


            }
        }
    }
}
