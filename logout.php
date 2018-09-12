<?php 

session_start();
ini_set( 'display_errors', 1 );
session_destroy();
header('Location: login.php');
 ?>
<?php require('header.php'); ?>
 
 </body>
 </html>