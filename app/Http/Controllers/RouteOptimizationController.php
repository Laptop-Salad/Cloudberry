<?php

namespace App\Http\Controllers;

use App\Services\RouteOptimizationService;
use Illuminate\Http\Request;

class RouteOptimizationController extends Controller
{
    protected RouteOptimizationService $optimizer;

    public function __construct(RouteOptimizationService $optimizer)
    {
        $this->optimizer = $optimizer;
    }

    /**
     * Post /routes/optimise
     */
    public function optimise(Request $request)
    {
        $result = $this->optimizer->generateOptimisedRoutes();

        return response()->json([
            'message' => 'Route optimisation completed successfully.',
            'data'    => $result,
        ]);
    }
}
