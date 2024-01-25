<?php 
    session_start();
    if(empty($_SESSION['nama'])){

        session_destroy();
        header('Location: loginAdmin.php');

    } else {
        session_destroy();
        header('Location: loginUser.php');
    }
?>