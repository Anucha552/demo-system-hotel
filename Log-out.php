<?php
    session_start();

    if (!empty($_GET['l'])){
        if ($_GET['l'] == "logOut"){

            session_unset();
            header('Location: index.php');
        } else {
            header('Location: index.php');
            exit(0);
        }
    } else {
        header('Location: index.php');
        exit(0);
    }
?>