<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DownloadSitemap implements ShouldQueue
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
        $sitemapName = '/sitemap.xml';
        foreach ($this -> domains as $domain) {
            $path = storage_path('app/sitemap/' . $domain -> name . '/sitemap.xml');
//            $path = $document . $sitemapName;
            $url = $domain -> url . '/sitemap.xml';
            if (!file_exists($path)) {
                $this -> download($url, $path);
            } else {
                $xml = simplexml_load_file($path);
                if ($xml->count()<20){
                    foreach ($xml -> children() as $value) {
                        $url = $value -> loc -> __toString();
                        $name = substr($url, strripos($url, '/') + 1);
                        $path = storage_path('app/sitemap/' . $domain -> name . '/' . $name);
                        if (!file_exists($path)){
                            $this -> download($url, $path);
                        }
                    }
                }

            }
        }
    }

    public function download(string $url, string $path)
    {
        try {
            downloadFile($url, $path);
        } catch (\Exception $exception) {
            Log ::error($exception -> getMessage());
        }
    }
}
