<?php 
/* * * * * * * * * * * * * * *
* Returns all published problem
* * * * * * * * * * * * * * */
function getPublishedProblem() {
	// use global $conn object in function
	global $conn;
	$sql = "SELECT * FROM problems WHERE published=true";
	$result = mysqli_query($conn, $sql);
	// fetch all problem as an associative array called $problem
	$problems = mysqli_fetch_all($result, MYSQLI_ASSOC);

	$final_problem = array();
	foreach ($problems as $problem) {
		$problem['topic'] = getProblemTopic($problem['id']); 
		array_push($final_problem, $problem);
	}
	return $final_problem;
}
/* * * * * * * * * * * * * * *
* Receives a problem id and
* Returns topic of the problem
* * * * * * * * * * * * * * */
function getProblemTopic($problem_id){
	global $conn;
	$sql = "SELECT * FROM topics WHERE id=
			(SELECT topic_id FROM problem_topic WHERE problem_id=$problem_id) LIMIT 1";
	$result = mysqli_query($conn, $sql);
	$topic = mysqli_fetch_assoc($result);
	return $topic;
}


/* * * * * * * * * * * * * * * *
* Returns all problems under a topic
* * * * * * * * * * * * * * * * */
function getPublishedProblemsByTopic($topic_id) {
	global $conn;
	$sql = "SELECT * FROM problems ps 
			WHERE ps.id IN 
			(SELECT pt.problem_id FROM problem_topic pt 
				WHERE pt.topic_id=$topic_id GROUP BY pt.problem_id 
				HAVING COUNT(1) = 1)";
	$result = mysqli_query($conn, $sql);
	// fetch all problems as an associative array called $problems
	$problems = mysqli_fetch_all($result, MYSQLI_ASSOC);

	$final_problems = array();
	foreach ($problems as $problem) {
		$problem['topic'] = getProblemTopic($problem['id']); 
		array_push($final_problems, $problem);
	}
	return $final_problems;
}
/* * * * * * * * * * * * * * * *
* Returns topic name by topic id
* * * * * * * * * * * * * * * * */
function getTopicNameById($id)
{
	global $conn;
	$sql = "SELECT name FROM topics WHERE id=$id";
	$result = mysqli_query($conn, $sql);
	$topic = mysqli_fetch_assoc($result);
	return $topic['name'];
}


/* * * * * * * * * * * * * * *
* Returns a single problem
* * * * * * * * * * * * * * */
function getProblem($description){
	global $conn;
	// Get single problem description
	$problem_description = $_GET['problem-description'];
	$sql = "SELECT * FROM problems WHERE description='$problem_description' AND published=true";
	$result = mysqli_query($conn, $sql);

	// fetch query results as associative array.
	$problem = mysqli_fetch_assoc($result);
	if ($problem) {
		// get the topic to which this problem belongs
		$problem['topic'] = getProblemTopic($problem['id']);
	}
	return $problem;
}
/* * * * * * * * * * * *
*  Returns all topics
* * * * * * * * * * * * */
function getAllTopics()
{
	global $conn;
	$sql = "SELECT * FROM topics";
	$result = mysqli_query($conn, $sql);
	$topics = mysqli_fetch_all($result, MYSQLI_ASSOC);
	return $topics;
}


?>