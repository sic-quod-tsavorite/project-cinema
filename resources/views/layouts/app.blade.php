<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Piximation Cinema')</title>
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>

<body class="bg-dark">
    <div id="pg-content">
        <nav class="navbar navbar-expand-lg fixed-top border-bottom border-light-subtle" id="header">
            <div class="container-fluid">
                <div class="w-100">
                    <a class="navbar-brand pix-logo" href="{{ url('/') }}">
                        <img src="{{ asset('images/logo.png') }}" alt="Piximation">
                        Piximation
                    </a>
                </div>
                <div>
                    @php
                        $navTags = app(\App\Services\CinemaData::class)->getNavTags();
                    @endphp
                    @if(!isset($isHome) || !$isHome)
                    <div id="categorys">
                        <div>
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0 wmc">
                                @foreach($navTags as $tag)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/tag/' . $tag['slug']) }}">{{ $tag['name'] }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="w-100 d-flex justify-content-end">
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

</body>

</html>
