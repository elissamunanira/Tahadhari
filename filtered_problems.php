<?php include('config.php'); ?>
<?php include('includes/public_functions.php'); ?>
<?php include('includes/head_section.php'); ?>
<?php 
	// Get problems under a particular topic
	if (isset($_GET['topic'])) {
		$topic_id = $_GET['topic'];
		$problems = getPublishedProblemsByTopic($topic_id);
	}
?>
	<title>TahadhariTech | Home </title>
</head>
<body>
<div class="container">
<!-- Navbar -->
	<?php include( ROOT_PATH . '/includes/navbar.php'); ?>
<!-- // Navbar -->
<!-- content -->
<div class="content">
	<h2 class="content-title">
		Articles on <u><?php echo getTopicNameById($topic_id); ?></u>
	</h2>
	<hr>
	<?php foreach ($problems as $problem): ?>
		<div class="problem" style="margin-left: 0px;">
			<img src="<?php echo BASE_URL . '/static/images/' . $problem['image']; ?>" class="problem_image" alt="">
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
<!-- // content -->
</div>
<!-- // container -->

<!-- Footer -->
	<?php include( ROOT_PATH . '/includes/footer.php'); ?>
<!-- // Footer -->