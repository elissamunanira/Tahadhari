<?php 
// problem variables
$problem_id = 0;
$isEditingproblem = false;
$published = 0;
$title = "";
$problem_desciption = "";
$description = "";
$featured_image = "";
$problem_topic = "";

/* - - - - - - - - - - 
-  problem functions
- - - - - - - - - - -*/
// get all problems from DB
function getAllProblems()
{
	global $conn;
	
	// Admin can view all problems
	// Author can only view their problems
	if ($_SESSION['user']['role'] == "Admin") {
		$sql = "SELECT * FROM problems";
	} elseif ($_SESSION['user']['role'] == "Author") {
		$user_id = $_SESSION['user']['id'];
		$sql = "SELECT * FROM problems WHERE user_id=$user_id";
	}
	$result = mysqli_query($conn, $sql);
	$problems = mysqli_fetch_all($result, MYSQLI_ASSOC);

	$final_problems = array();
	foreach ($problems as $problem) {
		$problem['author'] = getProblemAuthorById($problem['user_id']);
		array_push($final_problems, $problem);
	}
	return $final_problems;
}
// get the author/username of a problem
function getProblemAuthorById($user_id)
{
	global $conn;
	$sql = "SELECT username FROM users WHERE id=$user_id";
	$result = mysqli_query($conn, $sql);
	if ($result) {
		// return username
		return mysqli_fetch_assoc($result)['username'];
	} else {
		return null;
	}
}



/* - - - - - - - - - - 
-  problem actions
- - - - - - - - - - -*/
// if user clicks the create problem button
if (isset($_problem['create_problem'])) { createProblem($_POST); }
// if user clicks the Edit problem button
if (isset($_GET['edit-problem'])) {
	$isEditingproblem = true;
	$problem_id = $_GET['edit-problem'];
	editproblem($problem_id);
}
// if user clicks the update problem button
if (isset($_problem['update_problem'])) {
	updateproblem($_POST);
}
// if user clicks the Delete problem button
if (isset($_GET['delete-problem'])) {
	$problem_id = $_GET['delete-problem'];
	deleteproblem($problem_id);
}

/* - - - - - - - - - - 
-  problem functions
- - - - - - - - - - -*/
function createproblem($request_values)
	{
		global $conn, $errors, $title, $featured_image, $topic_id, $description, $published;
		$title = esc($request_values['title']);
		$description = htmlentities(esc($request_values['description']));
		if (isset($request_values['topic_id'])) {
			$topic_id = esc($request_values['topic_id']);
		}
		if (isset($request_values['publish'])) {
			$published = esc($request_values['publish']);
		}
		// create desciption: if title is "The Storm Is Over", return "the-storm-is-over" as desciption
		$problem_desciption = makeDesciption($title);
		// validate form
		if (empty($title)) { array_push($errors, "problem title is required"); }
		if (empty($description)) { array_push($errors, "problem description is required"); }
		if (empty($topic_id)) { array_push($errors, "problem topic is required"); }
		// Get image name
	  	$featured_image = $_FILES['featured_image']['name'];
	  	if (empty($featured_image)) { array_push($errors, "Featured image is required"); }
	  	// image file directory
	  	$target = "../static/images/" . basename($featured_image);
	  	if (!move_uploaded_file($_FILES['featured_image']['tmp_name'], $target)) {
	  		array_push($errors, "Failed to upload image. Please check file settings for your server");
	  	}
		// Ensure that no problem is saved twice. 
		$problem_check_query = "SELECT * FROM problems WHERE desciption='$problem_desciption' LIMIT 1";
		$result = mysqli_query($conn, $problem_check_query);

		if (mysqli_num_rows($result) > 0) { // if problem exists
			array_push($errors, "A problem already exists with that title.");
		}
		// create problem if there are no errors in the form
		if (count($errors) == 0) {
			$query = "INSERT INTO problems (user_id, title, desciption, image, description, published, created_at, updated_at) VALUES(1, '$title', '$problem_desciption', '$featured_image', '$description', $published, now(), now())";
			if(mysqli_query($conn, $query)){ // if problem created successfully
				$inserted_problem_id = mysqli_insert_id($conn);
				// create relationship between problem and topic
				$sql = "INSERT INTO problem_topic (problem_id, topic_id) VALUES($inserted_problem_id, $topic_id)";
				mysqli_query($conn, $sql);

				$_SESSION['message'] = "problem created successfully";
				header('location: problems.php');
				exit(0);
			}
		}
	}

	/* * * * * * * * * * * * * * * * * * * * *
	* - Takes problem id as parameter
	* - Fetches the problem from database
	* - sets problem fields on form for editing
	* * * * * * * * * * * * * * * * * * * * * */
	function editProblem($role_id)
	{
		global $conn, $title, $problem_desciption, $description, $published, $isEditingproblem, $problem_id;
		$sql = "SELECT * FROM problems WHERE id=$role_id LIMIT 1";
		$result = mysqli_query($conn, $sql);
		$problem = mysqli_fetch_assoc($result);
		// set form values on the form to be updated
		$title = $problem['title'];
		$description = $problem['description'];
		$published = $problem['published'];
	}

	function updateproblem($request_values)
	{
		global $conn, $errors, $problem_id, $title, $featured_image, $topic_id, $description, $published;

		$title = esc($request_values['title']);
		$description = esc($request_values['description']);
		$problem_id = esc($request_values['problem_id']);
		if (isset($request_values['topic_id'])) {
			$topic_id = esc($request_values['topic_id']);
		}
		// create desciption: if title is "The Storm Is Over", return "the-storm-is-over" as desciption
		$problem_desciption = makedesciption($title);

		if (empty($title)) { array_push($errors, "problem title is required"); }
		if (empty($description)) { array_push($errors, "problem description is required"); }
		// if new featured image has been provided
		if (isset($_problem['featured_image'])) {
			// Get image name
		  	$featured_image = $_FILES['featured_image']['name'];
		  	// image file directory
		  	$target = "../static/images/" . basename($featured_image);
		  	if (!move_uploaded_file($_FILES['featured_image']['tmp_name'], $target)) {
		  		array_push($errors, "Failed to upload image. Please check file settings for your server");
		  	}
		}

		// register topic if there are no errors in the form
		if (count($errors) == 0) {
			$query = "UPDATE problems SET title='$title', desciption='$problem_desciption', views=0, image='$featured_image', description='$description', published=$published, updated_at=now() WHERE id=$problem_id";
			// attach topic to problem on problem_topic table
			if(mysqli_query($conn, $query)){ // if problem created successfully
				if (isset($topic_id)) {
					$inserted_problem_id = mysqli_insert_id($conn);
					// create relationship between problem and topic
					$sql = "INSERT INTO problem_topic (problem_id, topic_id) VALUES($inserted_problem_id, $topic_id)";
					mysqli_query($conn, $sql);
					$_SESSION['message'] = "problem created successfully";
					header('location: problems.php');
					exit(0);
				}
			}
			$_SESSION['message'] = "problem updated successfully";
			header('location: problems.php');
			exit(0);
		}
	}
	// delete blog problem
	function deleteproblem($problem_id)
	{
		global $conn;
		$sql = "DELETE FROM problems WHERE id=$problem_id";
		if (mysqli_query($conn, $sql)) {
			$_SESSION['message'] = "problem successfully deleted";
			header("location: problems.php");
			exit(0);
		}
	}

    ?>