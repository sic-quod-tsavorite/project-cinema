<?php
$page_title = "Front Page";
include_once "./constants/header.php";
?>

<div class="d-flex">
    <a class="banner-img" href="#">
        <img src="./includes/assets/tnbc.jpg" alt="">
    </a>
    <a class="banner-img" href="/project-cinema/film-page">
        <img src="./includes/assets/treasure-planet.jpg" alt="">
    </a>
    <a class="banner-img" href="#">
        <img src="./includes/assets/shrek.jpg" alt="">
    </a>
    <a class="banner-img" href="#">
        <img src="./includes/assets/twnotw.jpg" alt="">
    </a>
</div>

<nav class="navbar navbar-expand-lg bg-transparent sticky-top justify-content-center">
    <div id="categorys">
        <div>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="#">Stop Motion</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Hand-Drawn</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Computer 3D</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Modern 2D</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container bg-primary-subtle rounded-3 p-5 my-5">
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid justify-content-center">
            <ul class="nav" id="npu-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="np-tab"
                        data-bs-toggle="tab" data-bs-target="#nowplaying" type="button" role="tab" aria-controls="nowplaying" aria-selected="true">Now playing</button>
                </li>
                <h4 class="mt-1 pe-none">|</h4>
                <li class="nav-item" role="presentation">
                    <button class="nav-link"
                        data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab" aria-controls="upcoming" aria-selected="false">Upcoming</button>
                </li>
            </ul>
        </div>
    </nav>
    <div class="tab-content" id="npu-content">
        <div id="nowplaying" class="tab-pane fade show active" role="tabpanel" aria-labelledby="nowplaying-tab" tabindex="0">
            ->calander<-
                <div class="container d-flex">
                <div class="img me-5">
                    <img src="includes/assets/tnbc.jpg" alt="">
                </div>
                <div class="film-content">
                    <h3>The Nightmare before christmas</h3>
                    <h5>1h 16min</h5>
                    <p>Director: Henry Selick</p>
                    <p>Actor: Danny Elfman, Chris Sarandon, Catherine O'hara</p>
                    <p>Genre: Animation, Family, Fantasy, Musical</p>
                    <divider class="my-4">
                    </divider>
                    <p>Airings</p>
                    ->timetable<-
                        </div>
                </div>
        </div>
        <div id="upcoming" class="tab-pane fade" role="tabpanel" aria-labelledby="upcoming-tab" tabindex="0">
            upcoming
        </div>
    </div>
</div>

<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        Dropdown button
    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="#">Action</a></li>
        <li><a class="dropdown-item" href="#">Another action</a></li>
        <li><a class="dropdown-item" href="#">Something else here</a></li>
    </ul>
</div>
<div id="test">
    test div
    <div id="test2">asdf</div>
</div>

<?php include_once "../constants/footer.php";    ?>