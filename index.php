<?php 
session_start();
ini_set( 'display_errors', 1 );
if (!isset($_SESSION['user_id'])){
	header('Location: login.php');
}
 
 

	$dsn = 'mysql:host=localhost;dbname=bbs;charset=utf8';
	$user = 'bbs';
	$pass = 'bbs';

	try {
		$db = new PDO($dsn, $user, $pass);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$stmt = $db->prepare("
			SELECT
			user.user_id,
			user.name,
			posts.title,
			posts.body,
			posts.date,
			posts.post_id,
			posts.image
			FROM 
			user INNER JOIN posts ON user.user_id = posts.user_id
			ORDER BY posts.post_id DESC;
			");
		$stmt->bindParam(':name',$name, PDO::PARAM_STR);
		$stmt->bindParam(':title',$title,PDO::PARAM_STR);
		$stmt->bindParam(':body', $body,PDO::PARAM_STR);
		$stmt->bindParam(':date', $date,PDO::PARAM_STR);
		$stmt->bindParam(':post_id', $post_id,PDO::PARAM_STR);
		$stmt->bindParam(':image', $image,PDO::PARAM_STR);
		$stmt->execute();
		
		
	} catch (Exception $e) {
		echo $e->getMessage();
	}


	try {
		$db = new PDO($dsn, $user, $pass);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$stmta = $db->prepare("
			SELECT
			comments.post_id,
			comments.c_body,
			comments.c_name,
			comments.comment_id,
			posts.post_id
			FROM 
			comments INNER JOIN posts ON comments.post_id = posts.post_id
			ORDER BY comments.post_id DESC;
			");
		$stmta->bindParam(':c_name',$c_name, PDO::PARAM_STR);
		$stmta->bindParam(':c_body', $c_body,PDO::PARAM_STR);
		$stmta->bindParam(':comment_id',$comment_id, PDO::PARAM_STR);
		$stmta->bindParam(':post_id', $post_id,PDO::PARAM_STR);
		$stmta->execute();
		

	} catch (Exception $e) {
		echo $e->getMessage();
	}
 ?>

 
 	<?php require('header.php'); ?>
 <h1 class="post-title">投稿一覧</h1>
<?php while ($row = $stmt->fetch()):
	
		$title = $row['title'] ? $row['title'] : '(無題)';
		$row['body'] = htmlspecialchars($row['body'], ENT_QUOTES, "UTF-8");
?>
<div class="post-colum">
	<div class="post-grid">
	<div class="grid-left">
		
		<p>名前</p>
		<p class="stand-out"><?php echo htmlspecialchars($row['name'], ENT_QUOTES, "UTF-8"); ?></p>
		<p>タイトル</p>
		<p class="stand-out"><?php echo htmlspecialchars($title, ENT_QUOTES, "UTF-8"); ?></p>
		<p>投稿日</p>
		<p class="stand-out"><?php echo htmlspecialchars($row['date'], ENT_QUOTES, "UTF-8") ;?></p>
		<p>投稿文</p>
		<p class="stand-out"><?php echo nl2br($row['body']);?></p>
	</div>
	<div class="grid-right">
<?php if ($row['image']): ?>
<p><img src=" <?php echo 'img/'.$row['image']; ?>" width="480px" ></p>
<?php endif; ?>
	</div>
	</div>
<div class="delete-form">
<form action="delete.php" method="post">
	<input type="hidden" name="post_id" value="<?php echo htmlspecialchars($row['post_id'], ENT_QUOTES, "UTF-8"); ?>">
	<input type="submit" value="投稿削除">
</form>
</div>
<div class="comment">
<?php while ($rowa = $stmta->fetch()): ?>

	<?php if ($row['post_id'] === $rowa['post_id']): ?>
		<div class="comment-inner">
		<p>名前</p>	
		<p class="comment-p"><?php echo htmlspecialchars($rowa['c_name'], ENT_QUOTES, "UTF-8"); ?></p>
		<p>コメント</p>
		<p class="comment-p"><?php echo $rowa['c_body']; ?></p>
		</div>
	<?php else: ?>
	<?php break; ?>

	<?php endif; ?>

<?php endwhile; ?>
</div>
<div class="comment-form">
<form action="comment.php" method="post">
	<p>コメントする</p>
	<p><input type="hidden" name="post_id" value="<?php echo htmlspecialchars($row['post_id'], ENT_QUOTES, "UTF-8");?>" ></p>
	<p><input type="text" name="c_body" value="<?php echo htmlspecialchars(@$_POST['c_body'], ENT_QUOTES, "UTF-8"); ?>"></p>
	<input type="submit" name="comment" value="送信">
</form>
</div>
	
</div>
<?php endwhile; ?>

 </body>
 </html>