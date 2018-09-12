<?php 
session_start();
ini_set( 'display_errors', 1 );

if (filter_input(INPUT_POST, 'comment')){


$c_body = $_POST['c_body'];
$user_id = $_SESSION['user_id'];
$post_id = $_POST['post_id'];
	$dsn = 'mysql:host=localhost;dbname=bbs;charset=utf8';
	$user = 'root';
	$pass = 'root';
var_dump($post_id);
	try {
		$db = new PDO($dsn, $user, $pass);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$stmt = $db->prepare("
			INSERT INTO comments (c_name, c_body, c_time, post_id)
			VALUES (:c_name, :c_body, now(), :post_id)"
		);
		$stmt->bindParam(':c_body', $c_body, PDO::PARAM_STR);
		$stmt->bindParam(':c_name', $_SESSION['name'], PDO::PARAM_STR);
		$stmt->bindParam(':post_id',$post_id,PDO::PARAM_STR);
		$stmt->execute();
		$row = $stmt->fetch();
		// $row['post_id'] = $_SESSION['post_id'];
		
		
		header('Location: index.php');
		exit();
	 } catch (Exception $e) {
		echo $e->$getMessage();
	}
}
 ?>
<?php require('header.php'); ?>

 </body>
 </html>