<?php

namespace App\Jobs;

use App\Models\Domain;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Iodev\Whois\Factory;
use Iodev\Whois\Exceptions\ConnectionException;
use Iodev\Whois\Exceptions\ServerMismatchException;
use Iodev\Whois\Exceptions\WhoisException;

/**
 *
 *检查域名到期时间
 **/
class CheckDomainsExpired implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $domains;



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
        $whois = Factory ::get() -> createWhois();
        foreach ($this -> domains as $domain) {
            try {
                $info = $whois -> loadDomainInfo($domain->name);
                if ($info && $info->expirationDate) {
                    $expiredTime = date("Y-m-d H:i:s", $info -> expirationDate);
                    $domain -> expired_time = $expiredTime;
                    $domain -> save();
                }
            } catch (ConnectionException $e) {
                Log ::channel('check') -> error("$domain->name Disconnect or connection timeout");
            } catch (ServerMismatchException $e) {
                Log ::channel('check') -> error("$domain->name TLD server (.com for google.com) not found in current server hosts");
            } catch (WhoisException $e) {
                Log ::channel('check') -> error("Whois server responded with error '{$e->getMessage()}'");;
            }catch (\Exception $exception){
                Log ::channel('check') -> error("$domain->id $domain->url '{$exception->getMessage()}'");;
            }
        }
    }
}
