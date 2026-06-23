<?php

namespace App\Http\Controllers;

use App\Services\GlobalSearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(private GlobalSearchService $searchService) {}

    public function index(Request $request)
    {
        $results = $this->searchService->search($request->string('q')->toString());

        return view('search.index', $results);
    }
}
