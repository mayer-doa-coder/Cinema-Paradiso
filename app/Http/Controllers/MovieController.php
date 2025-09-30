<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Display the movie grid view.
     *
     * @return \Illuminate\View\View
     */
    public function grid()
    {
        // In a real application, you would fetch movies from the database
        // For now, we're returning the static view
        return view('moviegrid');
    }

    /**
     * Display the movie list view.
     *
     * @return \Illuminate\View\View
     */
    public function list()
    {
        // In a real application, you would fetch movies from the database
        // For now, we're returning the static view
        return view('movielist');
    }

    /**
     * Display the movie search results.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        // Handle movie search functionality
        $query = $request->get('q', '');
        $genre = $request->get('genre', '');
        $rating = $request->get('rating', '');
        $year_from = $request->get('year_from', '');
        $year_to = $request->get('year_to', '');
        
        // In a real application, you would search the database
        // For now, return the grid view with search parameters
        return view('moviegrid', compact('query', 'genre', 'rating', 'year_from', 'year_to'));
    }

    /**
     * Display a single movie details.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // In a real application, you would fetch the movie by ID from database
        // For now, this is a placeholder
        return view('moviesingle', compact('id'));
    }
}