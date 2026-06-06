@props(['movie'])

@php
    $hours = floor($movie['length'] / 60);
    $minutes = $movie['length'] % 60;
    $movieTags = collect($movie['tags'])->map(fn($tagSlug) => config("cinema.tags.{$tagSlug}"))->filter()->all();
@endphp

<div class="item d-flex flex-row mb-5">
    <a class="img me-5" href="{{ url('/movie/' . $movie['slug']) }}">
        <img src="{{ asset($movie['poster']) }}" alt="{{ $movie['title'] }}">
    </a>
    <div class="film-content">
        <a class="h3 link-underline link-underline-opacity-0" href="{{ url('/movie/' . $movie['slug']) }}">
            {{ $movie['title'] }}
        </a>
        <h5>{{ $hours }}h {{ $minutes }}min</h5>
        <p>Director: {{ $movie['director'] }}</p>
        <divider class="my-4"></divider>
        <p>Actor:</p>
        <ul>
            @foreach($movie['actors'] as $actor)
                <li>{{ $actor['name'] }} - As: {{ $actor['role'] }}</li>
            @endforeach
        </ul>
        <p>Genre:</p>
        <ul>
            @foreach($movieTags as $tag)
                <li>
                    <a class="link-offset-1 link-underline link-underline-opacity-0 link-underline-opacity-75-hover"
                       href="{{ url('/tag/' . $tag['slug']) }}">{{ $tag['name'] }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
