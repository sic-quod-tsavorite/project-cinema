<?php
$page_title = "Front Page";
include_once "./constants/header.php";
?>

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