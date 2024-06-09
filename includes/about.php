<!-- The first include should be config.php -->
<?php require_once('config.php') ?>

<?php require_once( ROOT_PATH . '/includes/head_section.php') ?>
	<title>tahadhari</title>
</head>
<body>

		<!-- config.php should be here as the first include  -->

	<?php require_once( ROOT_PATH . '/includes/public_functions.php') ?>

	<?php require_once( ROOT_PATH . '/includes/registration_login.php') ?>

	<!-- Retrieve all problems from database  -->
	<?php $problems = getPublishedProblem(); ?>

	<!-- container - wraps whole page -->
	<div class="container">
		<!-- navbar -->
		<?php include( ROOT_PATH . '/includes/navbar.php') ?>
		<!-- // navbar -->

		<!-- banner -->
		<?php include( ROOT_PATH . '/includes/banner.php') ?>
		<!-- // banner -->

		<!-- Page content -->
		<div class="content">
			<h2 class="content-title">About us</h2>
			<hr>
			<!-- more content still to come here ... -->

			<hr>
			<!-- more content still to come here ... -->

			<?php foreach ($problems as $problem): ?>
				<div class="problem" style="margin-left: 0px;">
					<img src="<?php echo BASE_URL . '/static/images/' . $problem['image']; ?>" class="problem_image" alt="">
					<!-- Added this if statement... -->
					<?php if (isset($problem['topic']['name'])): ?>
						<a 
							href="<?php echo BASE_URL . 'filtered_problems.php?topic=' . $problem['topic']['id'] ?>"
							class="btn category">
							<?php echo $problem['topic']['name'] ?>
						</a>
					<?php endif ?>

					<a href="single_problem.php?problem-description=<?php echo $problem['description']; ?>">
						<div class="problem_info">
							<h3><?php echo $problem['title'] ?></h3>
							<div class="info">
								<span><?php echo date("F j, Y ", strtotime($problem["created_at"])); ?></span>
								<span class="read_more">Read more...</span>
							</div>
						</div>
					</a>
				</div>
			<?php endforeach ?>
		</div>
		<!-- // Page content -->

		<!-- footer -->
		<?php include( ROOT_PATH . '/includes/footer.php') ?>
		<!-- // footer -->