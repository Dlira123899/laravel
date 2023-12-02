<?php

namespace App\Http\Controllers;

use App\Http\Controllers\RabbitMQService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RabbitMQController extends Controller
{
  public function publishMessage(Request $request)
  {
    $date = date('Y-m-d h:i:s');
    $dataArray = [
      'name' => 'Product ' . $date,
      'description' => 'Description ' . $date,
      'price' => 12
    ];
    // Convert array to json
    $jsonData = json_encode($dataArray);

    $rabbitMQService = new RabbitMQService();
    $rabbitMQService->publish($jsonData);
    Log::info('Publish a message');
    Log::info($jsonData);
    return response('Message published to rabbitMQ: ' . $jsonData);
  }
}
