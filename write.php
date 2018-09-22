<?php  
session_start();
ini_set( 'display_errors', 1 );

if (is_uploaded_file($_FILES['image']['tmp_name'])){
	
	$mimetype  = mime_content_type($_FILES['image']['tmp_name']);
    $extension = array_search($mimetype, [
        'jpg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif',
    ]);

    if ($extension){
    	$imgname =sha1($_FILES['image']['name']);
    	$img_dir = "./img";
		$file = $imgname .".".basename($extension);
    	if(file_exists('img')){
    	
    	move_uploaded_file($_FILES['image']['tmp_name'],"$img_dir/$imgname.$extension");

    	}
    } else {
    	   	
    	header('Location: post.php');
    	exit();
    }
		

}else {
	$file = "";
}

if (isset($_POST['title']) && isset($_POST['body'])){

$image = $file;
$title = htmlspecialchars($_POST['title'], ENT_QUOTES, "UTF-8");
$body = htmlspecialchars($_POST['body'], ENT_QUOTES, "UTF-8");
$user_id = $_SESSION['user_id'];




	$dsn = 'mysql:host=localhost;dbname=bbs;charset=utf8';
	$user = 'bbs';
	$pass = 'bbs';

	try {
		$db = new PDO($dsn, $user, $pass);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$stmt = $db->prepare("
			INSERT INTO posts (title, body, date,user_id,image)
			VALUES (:title, :body, now(), :user_id,:image)"
		);

		$stmt->bindParam(':title', $title, PDO::PARAM_STR);
		$stmt->bindParam(':body', $body, PDO::PARAM_STR);
		$stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
		$stmt->bindParam(':image',$file,PDO::PARAM_STR);
		$stmt->execute();
		$row = $stmt->fetch();
		// $row['post_id'] = $_SESSION['post_id'];
		$_FILES = "";
		
		header('Location: index.php');
		exit();
	 } catch (Exception $e) {
		echo $e->$getMessage();
	}
}
