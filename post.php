<?php 
session_start();
if (!isset($_SESSION['user_id'])){
	header('Location: login.php');
}


 ?>

 <?php require('header.php'); ?>
 	<form action="write.php" method="post">
 		
 		<p>タイトル</p>
 		<p><input type="text" name="title"></p>
 		<p>本文</p>
 		<p><textarea name="body"></textarea></p>
 		<p>投稿する</p>
 		<p><input type="submit" name="send"></p>
 	</form>
 </body>
 </html>