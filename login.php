<?php  
ini_set( 'display_errors', 1 );
error_reporting(E_ALL);
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	if (filter_input(INPUT_POST, 'login-name')){
		$name = htmlspecialchars($_POST['login-name'], ENT_QUOTES, "UTF-8");
	}else {
		if (filter_input(INPUT_POST, 'logname') || filter_input(INPUT_POST, 'password')){
		$error_name = "";
		} else {
			$error_name = "お名前をご入力ください";
		}
	}
	

	if (filter_input(INPUT_POST, 'mail')){
		$mail = htmlspecialchars($_POST['mail'], ENT_QUOTES, "UTF-8");
	}else {
		if (filter_input(INPUT_POST, 'logname') || filter_input(INPUT_POST, 'password')){
		$error_mail = "";
		} else {
			$error_mail = "メールアドレスをご入力ください";
		}
	}

	if (filter_input(INPUT_POST, 'passwords')){
		$passwords = password_hash($_POST['passwords'], PASSWORD_DEFAULT);
	}else {
		if (filter_input(INPUT_POST, 'logname') || filter_input(INPUT_POST, 'password')){
		$error_passwords = "";
		} else {
			$error_passwords = "パスワードをご入力ください";
		}
	}

	if (filter_input(INPUT_POST, 'logname')){
		$logname = htmlspecialchars($_POST['logname'], ENT_QUOTES, "UTF-8");
	}else {
		if (filter_input(INPUT_POST, 'login-name') || filter_input(INPUT_POST, 'mail') || filter_input(INPUT_POST, 'passwords')){
		$error_logname = "";
		} else {
			$error_logname = "お名前をご入力ください";
		}
	}

	if (filter_input(INPUT_POST, 'password')){
		$logname = htmlspecialchars($_POST['password'], ENT_QUOTES, "UTF-8");
	}else {
		if (filter_input(INPUT_POST, 'login-name') || filter_input(INPUT_POST, 'mail') || filter_input(INPUT_POST, 'passwords')){
		$error_password = "";
		} else {
			$error_password = "パスワードをご入力ください";
		}
}



if (filter_input(INPUT_POST, 'login-name') && filter_input(INPUT_POST, 'mail') && filter_input(INPUT_POST, 'passwords'))  {
	$name = filter_input(INPUT_POST, 'login-name');
	$mail = filter_input(INPUT_POST, 'mail');
	$passwords = filter_input(INPUT_POST, 'passwords');
	$hash = password_hash($passwords, PASSWORD_DEFAULT);
	$_SESSION['password'] = $hash;
	$dsn = 'mysql:host=localhost;dbname=bbs;charset=utf8';
	$user = 'bbs';
	$pass = 'bbs';


	try {
		$db = new PDO($dsn, $user, $pass);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$stmt = $db->prepare("
			SELECT
			name
			FROM 
			user WHERE name=:name
			");
		$stmt->bindParam(':name',$name, PDO::PARAM_STR);
		
		$stmt->execute();
		$samename = filter_input(INPUT_POST, 'login-name');
		while ($row = $stmt->fetch()){
			if ($samename === $row['name']) {
				$error = "すでに登録済みの名前です。";

				header('Location: login.php');
				exit();
			}
		}
		
	} catch (Exception $e) {
		echo $e->getMessage();
	}

	if(empty($error)){

		try {
			$db = new PDO($dsn, $user, $pass);
			$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$stmt = $db->prepare("
				INSERT  INTO user (name, meil, password, date)
				VALUES (:name, :meil, :password, now())
				");
			
			$stmt->bindParam(':name',$name, PDO::PARAM_STR);
			$stmt->bindParam(':meil',$mail,PDO::PARAM_STR);
			$stmt->bindParam(':password',$hash,PDO::PARAM_STR);
			$stmt->execute();
				
				header('Location: completion.php');
				exit();

		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
}
}

if (isset($_SESSION['user_id'])){
	header('Location: index.php');
}else{
	if (!empty($_POST['logname']) && !empty($_POST['password'])){
	$loginName = htmlspecialchars($_POST['logname'], ENT_QUOTES, "UTF-8");
	$post_password = htmlspecialchars($_POST['password'], ENT_QUOTES, "UTF-8");
	
	$dsn = 'mysql:host=localhost;dbname=bbs;charset=utf8';
	$user = 'bbs';
	$pass = 'bbs';

	try {
		$db = new PDO($dsn, $user, $pass);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$stmt = $db->prepare("
			SELECT name,password,user_id FROM user 
			");
		$stmt->bindParam(':name',$loginName, PDO::PARAM_STR);
		$stmt->bindParam(':password',$password,PDO::PARAM_STR);
		$stmt->execute();
		$_SESSION['password'] = htmlspecialchars($_POST['password'], ENT_QUOTES, "UTF-8");
		$_SESSION['name'] = htmlspecialchars($_POST['logname'], ENT_QUOTES, "UTF-8");
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
<div class="login-area">
	<div class="login">
		<form action="" method="post">
			<p>お名前<span><?php if(isset($error_logname)){ echo $error_logname;} ?></span></p>
			<p><input type="text" name="logname" value="<?php if(isset($_POST['logname'])){
				echo htmlspecialchars($_POST['logname'], ENT_QUOTES, "UTF-8");
			} ?>"></p>
			<p>パスワード<span><?php if(isset($error_password)){ echo $error_password;} ?></span></p>
			<p><input type="password" name="password" value="<?php if(isset($_POST['password'])){
				echo htmlspecialchars($_POST['password'], ENT_QUOTES, "UTF-8");
			} ?>"></p>
			
			<p><input type="submit" value="ログイン"></p>
			<p><span class="left"><?php  if(isset($unapproved)){ echo $unapproved;}?></span></p>
		</form>
	</div>
	<div class="new-entry">
		<form action="" method="post">
			<p>お名前 <span><?php if(isset($error_name)){ echo $error_name;} ?><?php if(isset($error)){ echo $error;} ?></span></p>
			<p><input type="text" name="login-name"  value="<?php if(isset($_POST['login-name'])){echo htmlspecialchars($_POST['login-name'], ENT_QUOTES, "UTF-8");} ?>"></p>
			<p>メールアドレス<span><?php if(isset($error_mail)){ echo $error_mail;} ?></span></p>
			<p><input type="text" name="mail" value="<?php if(isset($_POST['mail'])){echo htmlspecialchars($_POST['mail'], ENT_QUOTES, "UTF-8");} ?>"></p>
			<p>パスワード<span><?php if(isset($error_passwords)){ echo $error_passwords;} ?></span></p>
			<p><input type="password" name="passwords" <?php if(isset($_POST['passwords'])){echo htmlspecialchars($_POST['passwords'], ENT_QUOTES, "UTF-8");} ?>></p>
			
			<p><input type="submit" value="登録"></p>
		</form>
	</div>
</div>	
</body>
</html>