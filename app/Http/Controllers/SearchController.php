<?php

namespace App\Http\Controllers;

use App\SearchResult;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function suggestions($query = null)
    {
        if($query == null)
        {
            return SearchResult::where('type', 'Tag')->orWhere('type', 'Data Set')->pluck('search');

        }
        else
        {
            return SearchResult::where('search', 'LIKE', '%' . $query . '%')->pluck('search');
        }
    }

    public function search(Request $request)
    {
        $results = SearchResult::where('search', 'LIKE', '%' . $request->get('query') . '%')->count();

        if($results == 1)
        {
            return redirect(SearchResult::where('search', 'LIKE', '%' . $request->get('query') . '%')->first()->link);
        }
        else
        {
            $results = SearchResult::where('search', 'LIKE', '%' . $request->get('query') . '%')->paginate(10);

            return view('search.results', compact('results'));
        }


    }

}
