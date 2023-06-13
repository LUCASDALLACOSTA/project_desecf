<?php
session_start();
if(isset($_SESSION['Type_user'])) {
setcookie('email','',time()-3600);
setcookie('password','',time()-3600);
setcookie('type','',time()-3600);
$_SESSION = array();
session_destroy();
//header("location:".  $_SERVER['HTTP_REFERER']);
}
header('Location:./connexion.php?disconnect=1'); 

?>