<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeviceController extends Controller
{
 public function handle(Request $request)
{
    \Log::info('ZKTeco FULL DEBUG', [
        'method' => $request->method(),
        'url' => $request->fullUrl(),
        'query' => $request->query(),
        'headers' => $request->headers->all(),
        'body' => $request->getContent(),
    ]);

    return response("OK")->header('Content-Type', 'text/plain');
}
}
