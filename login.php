<?php  
session_start();


if (isset($_POST['registration'])) {
	$name = $_POST['login-name'];
	$meil = $_POST['mail'];
	$password = $_POST['password'];

	$dsn = 'mysql:host=localhost;dbname=bbs;charset=utf8';
	$user = 'root';
	$pass = 'root';

	try {
		$db = new PDO($dsn, $user, $pass);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$stmt = $db->prepare("
			INSERT INTO user (name, meil, password, date)
			VALUES (:name, :meil, :password, now())
			");
		$stmt->bindParam(':name',$name, PDO::PARAM_STR);
		$stmt->bindParam(':meil',$meil,PDO::PARAM_STR);
		$stmt->bindParam(':password',$password,PDO::PARAM_STR);

		$stmt->execute();

		header('Location: index.php');
		exit();
		
	} catch (Exception $e) {
		echo $e->getMessage();
	}

} 
if (isset($_SESSION['user_id'])){
	header('Location: index.php');
}else{
	if (isset($_POST['login-name']) && isset($_POST['password'])){
	$loginName = $_POST['login-name'];
	$mail = $_POST['mail'];
	$password = $_POST['password'];

	$dsn = 'mysql:host=localhost;dbname=bbs;charset=utf8';
	$user = 'root';
	$pass = 'root';

	try {
		$db = new PDO($dsn, $user, $pass);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$stmt = $db->prepare("
			SELECT * FROM user WHERE name=:name AND password=:password
			");
		$stmt->bindParam(':name',$loginName, PDO::PARAM_STR);
		$stmt->bindParam(':password',$password,PDO::PARAM_STR);

		$stmt->execute();

		if ($row = $stmt->fetch()){
			$_SESSION['user_id'] = $row['user_id'];
			header('Location: index.php');
			exit();
		} 
	} catch (Exception $e) {
		echo $e->getMessage();
	}

} 
	
}





?>
<!DOCTYPE html>
<html>
<head>
	<title>ログイン</title>
</head>
<body>
	<?php require('header.php'); ?>
<div class="login-area">
	<div class="login">
		<form action="" method="post">
			<p>お名前</p>
			<p><input type="text" name="login-name"></p>
			<p>パスワード</p>
			<p><input type="pass" name="password"></p>
			<p>ログイン</p>
			<p><input type="submit" name="login"></p>
		</form>
	</div>
	<div class="new-entry">
		<form action="" method="post">
			<p>お名前</p>
			<p><input type="text" name="login-name"></p>
			<p>メールアドレス</p>
			<p><input type="text" name="mail"></p>
			<p>パスワード</p>
			<p><input type="pass" name="password"></p>
			<p>登録</p>
			<p><input type="submit" name="registration"></p>
		</form>
	</div>
</div>	
</body>
</html>