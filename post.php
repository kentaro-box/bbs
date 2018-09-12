<?php 
session_start();
ini_set( 'display_errors', 1 );
if (!isset($_SESSION['user_id'])){
	header('Location: login.php');
}


 ?>

 <?php require('header.php'); ?>
 <h2 class="post-title">投稿する</h2>
 <div class="post">
 	<form action="write.php" method="post" enctype="multipart/form-data">
 		
 		<p>タイトル</p>
 		<p><input type="text" name="title" value=" <?php if(isset($_POST['title'])){echo $_POST['title'];} ?>"></p>
 		<p>本文</p>
 		<p><textarea name="body" value=" <?php echo $_POST['body']; ?>"></textarea></p>
 		<input type="hidden" name="password" value="<?php echo $_POST['password']; ?>">
 		<p>画像をつける</p>
 		<!-- <input type="hidden" name="MAX_FILE_SIZE" value="300000" /> -->
 		<p><input type="file" name="image"></p>
 		<p><input type="submit" name="send" value="投稿する"></p>
 	</form>

 </div>
 </body>
 </html>