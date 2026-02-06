@extends('layouts.app')

@section('title', 'Error 404 | Piximation Cinema')

@section('content')
<div class="container pt-5">
    <h1>Error 404</h1>
    <p>Page not found.</p>
    <a href="{{ url('/') }}" class="btn btn-primary mt-3">Back to Home</a>
</div>
@endsection
