<?php 
ini_set( 'display_errors', 1 );
error_reporting(E_ALL);

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
	if (filter_input(INPUT_POST, 'logname')){
		$logname = $_POST['logname'];
	}else {
		$error_logname = "お名前をご入力ください";
		}
	

	if (filter_input(INPUT_POST, 'password')){
		$logname = $_POST['password'];
	}else {
			$error_password = "パスワードをご入力ください";
		}
}


if (isset($_SESSION['user_id'])){
	header('Location: index.php');
}else{
	if (!empty($_POST['logname']) && !empty($_POST['password'])){
	$loginName = $_POST['logname'];
	$password = $_POST['password'];

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
		while($row = $stmt->fetch()){


		
		$dbinner_pass = $row['password'];


			if (password_verify($_POST['password'], $dbinner_pass)) {
								  $_SESSION['user_id'] = $row['user_id'];
								  header('Location: index.php');
							exit();
							} else {
								$unapproved = "名前またはパスワードが違います。";
							}
		}
	} catch (Exception $e) {
		echo $e->getMessage();
	}
} 
}
 ?>
 <?php require('header.php'); ?>

 <h1 class="post-title">登録完了致しました</h1>
 <div class="comp-login">
		<form action="" method="post">
 		<input type="hidden" name="password" value="<?php $_POST['password']; ?>">
			<p>お名前<span><?php if(isset($error_logname)){ echo $error_logname;} ?></span></p>
			<p><input type="text" name="logname" value="<?php if(isset($_POST['logname'])){
				echo $_POST['logname'];
			} ?>"></p>
			<p>パスワード<span><?php if(isset($error_password)){ echo $error_password;} ?></span></p>
			<p><input type="password" name="password" value="<?php if(isset($_POST['password'])){
				echo $_POST['password'];
			} ?>"></p>
			
			<p><input type="submit" value="ログイン"></p>
			<p><span class="left"><?php  if(isset($unapproved)){ echo $unapproved;}?></span></p>

		</form>
	</div>
 </body>
 </html>