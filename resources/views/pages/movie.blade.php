@extends('layouts.app')

@section('title', $movie['title'] . ' | Piximation Cinema')

@section('content')
<div id="film-hero">
    <img class="z-n1" src="{{ asset($movie['heroimg']) }}" alt="">
    <h1 class="m-title pt-5">{{ $movie['title'] }}</h1>
    <div class="container d-flex py-4">
        <div class="d-flex flex-column justify-content-between">
            <p class="m-desc">{{ $movie['description'] }}</p>
            <div class="m-stars rounded-3 p-3">
                <h3>Starring:</h3>
                <ul>
                    @foreach($movie['actors'] as $actor)
                        <li>{{ $actor['name'] }} - As: {{ $actor['role'] }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="trailer-wrapper ms-5">
            <div class="ratio ratio-16x9">
                <iframe src="https://www.youtube.com/embed/{{ $movie['youtube_id'] }}"
                    title="{{ $movie['title'] }} Trailer"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        </div>
    </div>
    <div class="m-genre rounded-3 p-3 mt-4">
        <h3>Genre</h3>
        <div>
            @foreach($movieTags as $tag)
                <a class="btn btn-secondary rounded-1" href="{{ url('/tag/' . $tag['slug']) }}">{{ $tag['name'] }}</a>
            @endforeach
        </div>
    </div>
</div>
@endsection
