<?php 
session_start();
session_destroy();
header('Location: login.php');
 ?>
<?php require('header.php'); ?>
 
 </body>
 </html>