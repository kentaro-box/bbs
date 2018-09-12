<?php
ini_set( 'display_errors', 1 );
session_start();
if (isset($_POST)){
	$post_id = $_POST['post_id'];
}
if (isset($_POST['password'])){
	$password = $_POST['password'];
}
if (!isset($_SESSION['user_id'])){
	header('Location: login.php');
}


	
if (isset($_POST['del'])){	
	$dsn = 'mysql:host=localhost;dbname=bbs;charset=utf8';
	$user = 'root';
	$pass = 'root';

	try {
		$db = new PDO($dsn, $user, $pass);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$stmt = $db->prepare("
			SELECT name,password,user_id FROM user 
			");
		$stmt->bindParam(':name',$loginName, PDO::PARAM_STR);
		$stmt->bindParam(':password',$password,PDO::PARAM_STR);
		$stmt->execute();

		$row = $stmt->fetch();


		
		$dbinner_pass = $row['password'];


			if (password_verify($password, $dbinner_pass)) {
								 $ok = "照合成功";
							// 	  header('Location: index.php');
							// exit();
							} else {
								$unknow_pass = "パスワードが違います。";
							}

		

	} catch (Exception $e) {
		echo $e->getMessage();
	}


  if (isset($ok)){


	$dsn = 'mysql:host=localhost;dbname=bbs;charset=utf8';
	$user = 'root';
	$pass = 'root';

	try {
		$db = new PDO($dsn, $user, $pass);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$stmt = $db->prepare("
			DELETE
			FROM posts 
			where post_id = :post_id;
			");
	
		
		// $stmt->bindParam(':password', $password,PDO::PARAM_STR);
		$stmt->bindParam(':post_id',$post_id,PDO::PARAM_STR);
		$stmt->execute();
		// $row = $stmt->fetch();
		// if ($row = $stmt->fetch()){
		// 	$_SESSION['']
		// }
// 		$dbinner_pass = $row['password'];
// var_dump($row['password']);
// 			if (password_verify($_POST['password'], $dbinner_pass)) {
// 								 echo "ooooo";
// 								  header('Location: index.php');
// 							exit();
// 							} else {
// 								$unapproved = "名前またはパスワードが違います。";
// 							}
		
			header('Location: index.php');
		exit();
		

		
	} catch (Exception $e) {
		echo $e->getMessage();
	}
}
}
 ?>
 <?php require('header.php'); ?>
<div class="post-colum">
<h2>投稿を削除する</h2>
<div class="delete">
<form action="" method="post">

	<input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
	<p>パスワードをご入力ください <span><?php if(isset($unknow_pass)){echo $unknow_pass;} ?></span></p>
	<input type="password" name="password" value="<?php if(isset($password))echo htmlspecialchars($password); ?>">
	<p><input type="submit" name="del" value="消しちゃう！！"></p>
</form>
</div>
</div>
</body>
</html>