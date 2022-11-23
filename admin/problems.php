<?php  include('../config.php'); ?>
<?php  include(ROOT_PATH . '/admin/includes/admin_functions.php'); ?>
<?php  include(ROOT_PATH . '/admin/includes/problem_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/head_section.php'); ?>

<!-- Get all admin problems from DB -->
<?php $problems = getAllProblems(); ?>
	<title>Admin | Manage problems</title>
</head>
<body>
	<!-- admin navbar -->
	<?php include(ROOT_PATH . '/admin/includes/navbar.php') ?>

	<div class="container content">
		<!-- Left side menu -->
		<?php include(ROOT_PATH . '/admin/includes/menu.php') ?>

		<!-- Display records from DB-->
		<div class="table-div"  style="width: 80%;">
			<!-- Display notification message -->
			<?php include(ROOT_PATH . '/includes/messages.php') ?>

			<?php if (empty($problems)): ?>
				<h1 style="text-align: center; margin-top: 20px;">No problems in the database.</h1>
			<?php else: ?>
				<table class="table">
						<thead>
						<th>N</th>
						<th>Title</th>
						<th>Author</th>
						<th>Views</th>
						<!-- Only Admin can publish/unpublish problem -->
						<?php if ($_SESSION['user']['role'] == "Admin"): ?>
							<th><small>Publish</small></th>
						<?php endif ?>
						<th><small>Edit</small></th>
						<th><small>Delete</small></th>
					</thead>
					<tbody>
					<?php foreach ($problems as $key => $problem): ?>
						<tr>
							<td><?php echo $key + 1; ?></td>
							<td><?php echo $problem['author']; ?></td>
							<td>
								<a 	target="_blank"
								href="<?php echo BASE_URL . 'single_problem.php?problem-slug=' . $problem['slug'] ?>">
									<?php echo $problem['title']; ?>	
								</a>
							</td>
							<td><?php echo $problem['views']; ?></td>
							
							<!-- Only Admin can publish/unpublish problem -->
							<?php if ($_SESSION['user']['role'] == "Admin" ): ?>
								<td>
								<?php if ($problem['published'] == true): ?>
									<a class="fa fa-check btn unpublish"
										href="problems.php?unpublish=<?php echo $problem['id'] ?>">
									</a>
								<?php else: ?>
									<a class="fa fa-times btn publish"
										href="problems.php?publish=<?php echo $problem['id'] ?>">
									</a>
								<?php endif ?>
								</td>
							<?php endif ?>

							<td>
								<a class="fa fa-pencil btn edit"
									href="create_problem.php?edit-problem=<?php echo $problem['id'] ?>">
								</a>
							</td>
							<td>
								<a  class="fa fa-trash btn delete" 
									href="create_problem.php?delete-problem=<?php echo $problem['id'] ?>">
								</a>
							</td>
						</tr>
					<?php endforeach ?>
					</tbody>
				</table>
			<?php endif ?>
		</div>
		<!-- // Display records from DB -->
	</div>
</body>
</html>