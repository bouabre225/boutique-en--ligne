<?php
session_start();
if(isset($_SESSION['login']) && isset($_SESSION['password']))
{
    session_destroy();
    $_SESSION = array();
    unset($_SESSION);
}
header("Location: index.php");
exit();
?>
