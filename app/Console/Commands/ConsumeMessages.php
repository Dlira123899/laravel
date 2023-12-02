<?php

namespace App\Console\Commands;

use App\Http\Controllers\RabbitMQService;
use App\Models\Product;
use Illuminate\Auth\Events\Registered;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ConsumeMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:consume-messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('ConsumeMessage Command executed');

        $rabbitMqService = new RabbitMQService();

        // Callback
        $callback = function ($msg) {
            $jsonData = $msg->body;
            // Decode json to array
            $dataArray = json_decode($jsonData, true);
            if (count($dataArray) > 0) {
                // Create product
                $product = Product::create($dataArray);
                event(new Registered($product));
                Log::info('Product Created');
            } else {
                Log::error('Message body was empty');
            }
        };

        $rabbitMqService->consume($callback);
    }
}
