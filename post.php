<?php 



 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<title>投稿する</title>
 </head>
 <body>
 <?php require('header.php'); ?>
 	<form action="write.php" method="post">
 		<p>名前</p>
 		<p><input type="text" name="name"></p>
 		<p>タイトル</p>
 		<p><input type="text" name="title"></p>
 		<p>本文</p>
 		<p><textarea name="body"></textarea></p>
 		<p>投稿する</p>
 		<p><input type="submit" name="send"></p>
 	</form>
 </body>
 </html>