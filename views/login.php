<?php
$page_title = "Login or sign up";
include_once "./constants/header.php";
if (logged_in()) {
	redirect_to("/project-cinema/");
};
?>

<div class="container bg-primary-subtle rounded-3 p-5 my-5">
	<nav class="navbar navbar-expand-lg">
		<div class="container-fluid justify-content-center">
			<ul class="nav" id="npu-tabs" role="tablist">
				<li class="nav-item" role="presentation">
					<button class="nav-link active" id="np-tab"
						data-bs-toggle="tab" data-bs-target="#signin" type="button" role="tab" aria-controls="signin" aria-selected="true">Log in</button>
				</li>
				<h4 class="mt-1 pe-none">|</h4>
				<li class="nav-item" role="presentation">
					<button class="nav-link"
						data-bs-toggle="tab" data-bs-target="#signup" type="button" role="tab" aria-controls="signup" aria-selected="false">Create account</button>
				</li>
			</ul>
		</div>
	</nav>
	<div class="tab-content" id="npu-content">
		<div id="signin" class="tab-pane fade show active" role="tabpanel" aria-labelledby="signin-tab" tabindex="0">
			<?php include_once "./constants/signin.php"; ?>
		</div>
		<div id="signup" class="tab-pane fade" role="tabpanel" aria-labelledby="signup-tab" tabindex="0">
			<?php include_once "./constants/signup.php"; ?>
		</div>
	</div>
</div>

<?php include_once "./constants/footer.php";    ?>