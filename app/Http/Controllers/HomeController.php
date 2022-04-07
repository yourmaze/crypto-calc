<?php

namespace App\Http\Controllers;

use App\Http\Services\ProbabilityService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private ProbabilityService $service;
    /**
     * @return void
     */
    public function __construct(ProbabilityService $service)
    {
        $this->service = $service;
    }

    /**
     * @return Renderable
     */
    public function index(): Renderable
    {
        return view('home');
    }

    /**
     * Вероятность попасть одним аккаунтом, двумя, тремя и всеми.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $accounts = $request->input('accounts');
        $winners = $request->input('winners');
        $participants = $request->input('participants');

        $output = $this->service->calculate($accounts, $winners, $participants);

        return response()->json(['accounts' => $output]);
    }
}
