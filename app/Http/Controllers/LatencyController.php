<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PingService;

class LatencyController extends Controller
{
    // Property service acts as a downstream API...
    protected $pingService;

    // Constructor with Dependency injection for PingService...
    public function __construct(PingService $pingService)
    {
        $this->pingService = $pingService;
    }

    //
    public function index() {
        return view("latency_tracker")->with(["hola" => "gestaop",
            'hello' => 'ouvra']);
    }

    public function ping(Request $request) {
        // Local constant...
        define("HOSTS_KEY", 'hosts');
        $latency = array();
        if (isset($request[HOSTS_KEY])) {
            $hosts = explode(",", $request[HOSTS_KEY]);
            $latency = $this->pingService->getLatency($hosts);
        }
        return response()->json($latency);
    }

}
