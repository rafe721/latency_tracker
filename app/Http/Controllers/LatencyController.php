<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PingService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;

class LatencyController extends Controller
{
    //
    protected $pingService;

    /**
     * @param PingService $pingService
     */
    public function __construct(PingService $pingService)
    {
        $this->pingService = $pingService;
    }

    /** Reesponds with the latency_tracker view
     * @return View
     */
    public function index() {
        return view("latency_tracker");
    }

    /** Endpoin
     * @param Request $request handles the 'hosts' value (comma seperated string of host names and/or IPv4 Addresses)
     *                          Returns JSON containing the corresponding Network latencies.
     *
     * @return Response Http Json response
     */
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
