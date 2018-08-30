<?php  
session_start();

if (isset($_POST['title']) && isset($_POST['body'])){

$name = $_POST['name'];
$title = $_POST['title'];
$body = $_POST['body'];
$user_id = $_SESSION['user_id'];
	$dsn = 'mysql:host=localhost;dbname=bbs;charset=utf8';
	$user = 'root';
	$pass = 'root';

	try {
		$db = new PDO($dsn, $user, $pass);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$stmt = $db->prepare("
			INSERT INTO posts (title, body, date,user_id)
			VALUES (:title, :body, now(), :user_id)"
		);

		$stmt->bindParam(':title', $title, PDO::PARAM_STR);
		$stmt->bindParam(':body', $body, PDO::PARAM_STR);
		$stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
		$stmt->execute();

		header('Location: index.php');
		exit();
	} catch (Exception $e) {
		echo $e->$getMessage();
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

</body>
</html>