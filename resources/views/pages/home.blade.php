@extends('layouts.app')

@section('title', 'Piximation Cinema')

@section('content')
<div class="d-flex">
    @foreach($featured as $movie)
        <a class="banner-img" href="{{ url('/movie/' . $movie['slug']) }}">
            <img src="{{ asset($movie['poster']) }}" alt="{{ $movie['title'] }}">
        </a>
    @endforeach
</div>

<nav class="navbar navbar-expand-lg bg-transparent sticky-top justify-content-center" id="fp-nav">
    <div id="categorys">
        <div>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 wmc">
                @foreach($tags as $tag)
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/tag/' . $tag['slug']) }}">{{ $tag['name'] }}</a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>

<x-now-upcoming-tabs :nowPlaying="$nowPlaying" :upcoming="$upcoming" />
@endsection
