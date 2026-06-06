<?php

namespace App\Services;

class CinemaData
{
    public function getMovies(): array
    {
        return config('cinema.movies');
    }

    public function getMovie(string $slug): ?array
    {
        return config("cinema.movies.{$slug}");
    }

    public function getTags(): array
    {
        return config('cinema.tags');
    }

    public function getTag(string $slug): ?array
    {
        return config("cinema.tags.{$slug}");
    }

    public function getNavTags(): array
    {
        return config('cinema.tags');
    }

    public function getMoviesByStatus(int $nowUpcoming): array
    {
        return collect(config('cinema.movies'))
            ->filter(fn($movie) => $movie['now_upcoming'] === $nowUpcoming)
            ->all();
    }

    public function getMoviesByTag(string $tagSlug): array
    {
        return collect(config('cinema.movies'))
            ->filter(fn($movie) => in_array($tagSlug, $movie['tags']))
            ->all();
    }

    public function getMoviesByTagAndStatus(string $tagSlug, int $nowUpcoming): array
    {
        return collect(config('cinema.movies'))
            ->filter(fn($movie) => in_array($tagSlug, $movie['tags']) && $movie['now_upcoming'] === $nowUpcoming)
            ->all();
    }

    public function getFeaturedMovies(): array
    {
        return collect(config('cinema.movies'))
            ->filter(fn($movie) => $movie['is_news'])
            ->take(4)
            ->all();
    }

    public function getTagsForMovie(string $movieSlug): array
    {
        $movie = $this->getMovie($movieSlug);
        if (!$movie) return [];

        return collect($movie['tags'])
            ->map(fn($tagSlug) => $this->getTag($tagSlug))
            ->filter()
            ->all();
    }
}
