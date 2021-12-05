<?php
    session_start();
    if(isset($_SESSION['username'])){
        header("Location:  ./User/UserHomePage.html");
    }
    else{
        header("Location:  ./Login/login_form.php");
    }
?>