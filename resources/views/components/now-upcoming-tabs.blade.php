@props(['nowPlaying', 'upcoming'])

<div class="container bg-primary-subtle rounded-3 px-5 my-5">
    <nav class="navbar navbar-expand-lg pt-5">
        <div class="container-fluid justify-content-center">
            <ul class="nav" id="npu-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="np-tab"
                        data-bs-toggle="tab" data-bs-target="#nowplaying" type="button" role="tab"
                        aria-controls="nowplaying" aria-selected="true">Now playing</button>
                </li>
                <h4 class="mt-1 pe-none">|</h4>
                <li class="nav-item" role="presentation">
                    <button class="nav-link"
                        data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab"
                        aria-controls="upcoming" aria-selected="false">Upcoming</button>
                </li>
            </ul>
        </div>
    </nav>

    <div class="tab-content" id="npu-content">
        <div id="nowplaying" class="tab-pane fade show active" role="tabpanel" aria-labelledby="nowplaying-tab" tabindex="0">
            <div class="container d-flex flex-column">
                @foreach($nowPlaying as $movie)
                    <x-movie-card :movie="$movie" />
                @endforeach
            </div>
        </div>
        <div id="upcoming" class="tab-pane fade" role="tabpanel" aria-labelledby="upcoming-tab" tabindex="0">
            <div class="container d-flex flex-column">
                @foreach($upcoming as $movie)
                    <x-movie-card :movie="$movie" />
                @endforeach
            </div>
        </div>
    </div>
</div>
