<?php
require_once "helper_function.php";

    if(!isset($_SESSION['admin']) && $_SESSION['admin'] != true){
        $_SESSION['err_login'] = "Please Login First";
        // header("Location: admin.php");
        redirect("admin.php");
        exit; // Ensure no further code is executed after the redirect
    }
?>