<?php
$page_title = "Front Page";
include_once "./constants/header.php";
?>

<div class="d-flex">
    <img class="banner-img" src="./includes/assets/tnbc.jpg" alt="">
    <img class="banner-img" src="./includes/assets/treasure-planet.jpg" alt="">
    <img class="banner-img" src="./includes/assets/shrek.jpg" alt="">
    <img class="banner-img" src="./includes/assets/twnotw.jpg" alt="">
</div>

<nav class="navbar navbar-expand-lg bg-transparent sticky-top justify-content-center">
    <div class="bg-dark">
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

<?php include_once "./constants/footer.php";    ?>