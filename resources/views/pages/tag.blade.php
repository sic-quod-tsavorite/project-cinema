@extends('layouts.app')

@section('title', $tag['name'] . ' | Piximation Cinema')

@section('content')
<div class="container d-flex flex-column pt-5">
    <h1 class="pb-5"><b>Showing category:</b> <i>{{ $tag['name'] }}</i></h1>
    <x-now-upcoming-tabs :nowPlaying="$nowPlaying" :upcoming="$upcoming" />
</div>
@endsection
