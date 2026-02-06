<?php

namespace App\Http\Controllers;

use App\Services\CinemaData;

class CinemaController extends Controller
{
    public function __construct(
        private CinemaData $cinema
    ) {}

    public function home()
    {
        return view('pages.home', [
            'isHome' => true,
            'featured' => $this->cinema->getFeaturedMovies(),
            'tags' => $this->cinema->getNavTags(),
            'nowPlaying' => $this->cinema->getMoviesByStatus(1),
            'upcoming' => $this->cinema->getMoviesByStatus(2),
        ]);
    }

    public function movie(string $slug)
    {
        $movie = $this->cinema->getMovie($slug);

        if (!$movie) {
            abort(404);
        }

        return view('pages.movie', [
            'movie' => $movie,
            'movieTags' => $this->cinema->getTagsForMovie($slug),
        ]);
    }

    public function tag(string $slug)
    {
        $tag = $this->cinema->getTag($slug);

        if (!$tag) {
            abort(404);
        }

        return view('pages.tag', [
            'tag' => $tag,
            'nowPlaying' => $this->cinema->getMoviesByTagAndStatus($slug, 1),
            'upcoming' => $this->cinema->getMoviesByTagAndStatus($slug, 2),
        ]);
    }
}
