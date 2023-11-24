<?php

namespace App\Jobs;

use App\Mail\ProductsNotif;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

class ProcessEmailNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $products;

    /**
     * Create a new job instance.
     */
    public function __construct($products)
    {
        Redis::class;
        Log::info('ProcessEmailNotification Jobs');
        $this->products = $products;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $notif = new ProductsNotif();
        Mail::to('dlira@stratpoint.com')->queue($notif);

        Log::info($this->products);
        Log::info('ProcessEmailNotification Jobs executed');
    }
}
