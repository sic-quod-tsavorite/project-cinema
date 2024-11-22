<?php
$page_title = "Dashboard | Piximation Cinema";

include_once "./constants/pre-html.php";

if (!logged_in()) {
    header("Location: $base_url?signup=failed");
    exit;
}

include_once "./constants/header.php";
?>

<div class="container bg-primary-subtle rounded-3 p-5 my-5">
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid justify-content-center">
            <ul class="nav" id="npu-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="np-tab"
                        data-bs-toggle="tab" data-bs-target="#actors" type="button" role="tab" aria-controls="actors" aria-selected="true">Actors</button>
                </li>
                <h4 class="mt-1 pe-none">|</h4>
                <li class="nav-item" role="presentation">
                    <button class="nav-link"
                        data-bs-toggle="tab" data-bs-target="#tag" type="button" role="tab" aria-controls="tag" aria-selected="false">Tag</button>
                </li>
                <h4 class="mt-1 pe-none">|</h4>
                <li class="nav-item" role="presentation">
                    <button class="nav-link"
                        data-bs-toggle="tab" data-bs-target="#movies" type="button" role="tab" aria-controls="movies" aria-selected="false">Movies</button>
                </li>
                <h4 class="mt-1 pe-none">|</h4>
                <li class="nav-item" role="presentation">
                    <button class="nav-link"
                        data-bs-toggle="tab" data-bs-target="#cinemas" type="button" role="tab" aria-controls="cinemas" aria-selected="false">Cinemas</button>
                </li>
            </ul>
        </div>
    </nav>
    <div class="tab-content" id="npu-content">
        <div id="actors" class="tab-pane fade show active" role="tabpanel" aria-labelledby="actors-tab" tabindex="0">
            <?php include_once "content/tb_actors.php"; ?>
        </div>
        <div id="tag" class="tab-pane fade" role="tabpanel" aria-labelledby="tag-tab" tabindex="0">
            <?php include_once "content/tb_tag.php"; ?>
        </div>
        <div id="movies" class="tab-pane fade" role="tabpanel" aria-labelledby="movies-tab" tabindex="0">
            <?php include_once "content/tb_movies.php"; ?>
        </div>
        <div id="cinemas" class="tab-pane fade" role="tabpanel" aria-labelledby="cinemas-tab" tabindex="0">
            <?php include_once "content/tb_cinemahalls.php"; ?>
        </div>
    </div>
</div>

<script src="<?php echo $base_url; ?>/includes/scripts/ad_script.js"></script>
<?php include_once "./constants/footer.php"; ?>