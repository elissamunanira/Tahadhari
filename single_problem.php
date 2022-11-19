<?php  include('config.php'); ?>
<?php  include('includes/public_functions.php'); ?>
<?php 
	if (isset($_GET['problem-description'])) {
		$problem = getProblem($_GET['problem-description']);
	}
	$topics = getAllTopics();
?>
<?php include('includes/head_section.php'); ?>
<title> <?php echo $problem['title'] ?> | LifeProblem</title>
</head>
<body>
<div class="container">
	<!-- Navbar -->
		<?php include( ROOT_PATH . '/includes/navbar.php'); ?>
	<!-- // Navbar -->
	
	<div class="content" >
		<!-- Page wrapper -->
		<div class="problem-wrapper">
			<!-- full problem div -->
			<div class="full-problem-div">
			<?php if ($problem['published'] == false): ?>
				<h2 class="problem-title">Sorry... This problem has not been published</h2>
			<?php else: ?>
				<h2 class="problem-title"><?php echo $problem['title']; ?></h2>
				<div class="problem-description-div">
					<?php echo html_entity_decode($problem['description']); ?>
				</div>
			<?php endif ?>
			</div>
			<!-- // full problem div -->
			
			<!-- comments section -->
			<!--  coming soon ...  -->
		</div>
		<!-- // Page wrapper -->

		<!-- problem sidebar -->
		<div class="problem-sidebar">
			<div class="card">
				<div class="card-header">
					<h2>Topics</h2>
				</div>
				<div class="card-content">
					<?php foreach ($topics as $topic): ?>
						<a 
							href="<?php echo BASE_URL . 'filtered_problems.php?topic=' . $topic['id'] ?>">
							<?php echo $topic['name']; ?>
						</a> 
					<?php endforeach ?>
				</div>
			</div>
		</div>
		<!-- // problem sidebar -->
	</div>
</div>
<!-- // content -->

<?php include( ROOT_PATH . '/includes/footer.php'); ?>