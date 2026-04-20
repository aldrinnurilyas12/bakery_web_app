<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class OmdbApiServices extends Controller
{
    public function index (){
        return view('layouts.omdbmovie');
    }

    public function search_movies(Request $rq)
    {
        $response = Http::get('http://www.omdbapi.com/', [
            'apikey' => config('services.omdb.key'),
            's' => $rq->title
        ]);
        $movies = $response->json();

        // dd($movies);

        return view('layouts.omdbmovie', compact('movies'));
    }
}
