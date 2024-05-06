<?php
session_start();
if(!$_SESSION){
    session_destroy();
    header('location: login.html');
}else if($_SESSION['key']<>'PHP1.$#@'){
    session_destroy();
    header('location: ../login.html');
}
?>
