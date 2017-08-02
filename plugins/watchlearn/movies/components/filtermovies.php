<?php
/**
 * Created by PhpStorm.
 * User: BaTryXaaa
 * Date: 7/29/2017
 * Time: 18:36
 */

namespace Watchlearn\Movies\Components;

use Cms\Classes\ComponentBase;
use Watchlearn\Movies\Models\Movie;
use Watchlearn\Movies\Models\Genre;
use Input;


class filtermovies extends ComponentBase
{
    public $movies;
    public $genres;
    public $years;

    public function componentDetails()
    {
        return [
            'name' => 'Filter movies',
            'description' => 'Filter movies'
        ];
    }

    public function onRun()
    {
        $this->movies = $this->filterMovies();
        $this->genres = Genre::all();
        $this->years = $this->filterYears();
    }


    public function filterYears()
    {

        $query = Movie::all();
        $years = [];

        foreach ($query as $movie) {
            $years[] = $movie->year;
        }

        $years = array_unique($years);
        return $years;
    }

    protected function filterMovies()
    {

        $year = Input::get('year');
        $genre = Input::get('genre');
        $query = Movie::all();

        if ($year) {
            $query = Movie::where('year', '=', $year)->get();
        }
        if ($genre) {
            $query = Movie::whereHas('genres', function ($filter) use ($genre) {
                $filter->where('slug', '=', $genre);
            })->get();
        }
        if ($year && $genre) {
            $query = Movie::whereHas('genres', function ($filter) use ($genre) {
                $filter->where('slug', '=', $genre);
            })->where('year', '=', $year)->get();
        }

        return $query;
    }

}