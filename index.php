<?php 
session_start();
if (!isset($_SESSION['user_id'])){
	header('Location: login.php');
}
	$dsn = 'mysql:host=localhost;dbname=bbs;charset=utf8';
	$user = 'root';
	$pass = 'root';

	try {
		$db = new PDO($dsn, $user, $pass);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$stmt = $db->prepare("
			SELECT
			user.user_id,
			user.name,
			posts.title,
			posts.body,
			posts.date
			FROM 
			user INNER JOIN posts ON user.user_id = posts.user_id
			");
		$stmt->bindParam(':name',$name, PDO::PARAM_STR);
		$stmt->bindParam(':title',$title,PDO::PARAM_STR);
		$stmt->bindParam(':body', $body,PDO::PARAM_STR);
		$stmt->bindParam(':date', $date,PDO::PARAM_STR);
		$stmt->execute();
		
		
	} catch (Exception $e) {
		echo $e->getMessage();
	}

 ?>
 
 
 	<?php require('header.php'); ?>
 <h1>投稿一覧</h1>
<?php while ($row = $stmt->fetch()):
			$title = $row['title'] ? $row['title'] : '(無題)';
?>
<div class="post-colum">
<p>名前</p>
<p><?php echo $row['name']; ?></p>
<p>タイトル</p>
<p><?php echo $title; ?></p>
<p>投稿日</p>
<p><?php echo $row['date'] ;?></p>
<p>投稿文</p>
<p><?php echo nl2br($row['body']);?></p>
</div>
<?php endwhile; ?>
 </body>
 </html>