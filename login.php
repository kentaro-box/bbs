<?php  
ini_set( 'display_errors', 1 );
error_reporting(E_ALL);

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	if (filter_input(INPUT_POST, 'login-name')){
		$name = $_POST['login-name'];
	}else {
		if (filter_input(INPUT_POST, 'logname') || filter_input(INPUT_POST, 'password')){
		$error_name = "";
		} else {
			$error_name = "お名前をご入力ください";
		}
	}
	

	if (filter_input(INPUT_POST, 'mail')){
		$mail = $_POST['mail'];
	}else {
		if (filter_input(INPUT_POST, 'logname') || filter_input(INPUT_POST, 'password')){
		$error_mail = "";
		} else {
			$error_mail = "メールアドレスをご入力ください";
		}
	}

	if (filter_input(INPUT_POST, 'passwords')){
		$passwords = $_POST['passwords'];
	}else {
		if (filter_input(INPUT_POST, 'logname') || filter_input(INPUT_POST, 'password')){
		$error_passwords = "";
		} else {
			$error_passwords = "パスワードをご入力ください";
		}
	}

	if (filter_input(INPUT_POST, 'logname')){
		$logname = $_POST['logname'];
	}else {
		if (filter_input(INPUT_POST, 'login-name') || filter_input(INPUT_POST, 'mail') || filter_input(INPUT_POST, 'passwords')){
		$error_logname = "";
		} else {
			$error_logname = "お名前をご入力ください";
		}
	}

	if (filter_input(INPUT_POST, 'password')){
		$logname = $_POST['password'];
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
	
	$dsn = 'mysql:host=localhost;dbname=bbs;charset=utf8';
	$user = 'root';
	$pass = 'root';

	// try {
	// 	$db = new PDO($dsn, $user, $pass);
	// 	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	// 	$stmt = $db->prepare("
	// 		SELECT
	// 		name
	// 		FROM 
	// 		user
	// 		");
	// 	$stmt->bindParam(':name',$name, PDO::PARAM_STR);
		
	// 	$stmt->execute();
	// 	while ($row = $stmt->fetch()){
	// 		$row['name'] = $samename;
			
	// 	}
		
	// } catch (Exception $e) {
	// 	echo $e->getMessage();
	// }






	try {
		$db = new PDO($dsn, $user, $pass);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$stmt = $db->prepare("
			INSERT  INTO user (name, meil, password, date)
			VALUES (:name, :meil, :password, now())
			");
		
		$stmt->bindParam(':name',$name, PDO::PARAM_STR);
		$stmt->bindParam(':meil',$mail,PDO::PARAM_STR);
		$stmt->bindParam(':password',$passwords,PDO::PARAM_STR);
		$stmt->execute();

		
		header('Location: completion.php');
		exit();
		

	} catch (Exception $e) {
		echo $e->getMessage();
	}

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

	<?php require('header.php'); ?>
<div class="login-area">
	<div class="login">
		<form action="" method="post">
			<p>お名前<span><?php if(isset($error_logname)){ echo $error_logname;} ?></span></p>
			<p><input type="text" name="logname" value="<?php if(isset($_POST['logname'])){
				echo $_POST['logname'];
			} ?>"></p>
			<p>パスワード<span><?php if(isset($error_password)){ echo $error_password;} ?></span></p>
			<p><input type="password" name="password" value="<?php if(isset($_POST['password'])){
				echo $_POST['password'];
			} ?>"></p>
			
			<p><input type="submit" value="ログイン"></p>
		</form>
	</div>
	<div class="new-entry">
		<form action="" method="post">
			<p>お名前 <span><?php if(isset($error_name)){ echo $error_name;} ?><?php if(isset($error)){ echo $error;} ?></span></p>
			<p><input type="text" name="login-name"  value="<?php if(isset($_POST['login-name'])){echo $_POST['login-name'];} ?>"></p>
			<p>メールアドレス<span><?php if(isset($error_mail)){ echo $error_mail;} ?></span></p>
			<p><input type="text" name="mail" value="<?php if(isset($_POST['mail'])){echo $_POST['mail'];} ?>"></p>
			<p>パスワード<span><?php if(isset($error_passwords)){ echo $error_passwords;} ?></span></p>
			<p><input type="password" name="passwords" <?php if(isset($_POST['passwords'])){echo $_POST['passwords'];} ?>></p>
			
			<p><input type="submit" value="登録"></p>
		</form>
	</div>
</div>	
</body>
</html>