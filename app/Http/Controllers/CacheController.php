<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CacheController extends Controller
{
    public function index()
    {
        $key = 'testing';
        $dataArray = [
            'name' => 'Product 1',
            'description' => 'Product 1 description',
            'price' => 100
        ];
        $jsonData = json_encode($dataArray);

        $fromCache = Cache::get($key);
        if ($fromCache) {
            // existing from cache
            echo 'existing from cache';
            $fromCacheJsonData = json_decode($fromCache, true);
            dump($fromCacheJsonData);
        } else {
            // Not existing from cache
            Cache::put($key, $jsonData, now()->addMinutes(10));
            echo 'Inserted to cache';
        }

        return 'Caching';
    }
}
