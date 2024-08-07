<?php
    session_start();

    $emailText = $passwordText = "none";

    function readJson($src){
        $jsonContent = file_get_contents($src);
        return $jsonContent;
    }

    function writeJason($src, $jsonData){
        file_put_contents($src, $jsonData);
    }

    function check_Email_Password_full($email, $password){

        $userJson = readJson('data\user-hotel.json');
        $Total_Users = json_decode($userJson, true);
        $open_User_Email = $open_User_Password = false;
        $ID_user_1 = $ID_user_2 = 0;
        $NoEmail = false;
        $NoPassword = false;

        for ($i = 0; $i < count($Total_Users); $i++){
            if ($email == $Total_Users[$i]['email']){
                $open_User_Email = true;
                $NoEmail = true;
                $NO_email = "";
                $ID_user_1 = $i;
                break;
            }
        }

        for ($j = 0; $j < count($Total_Users); $j++){
            if (md5($password) == $Total_Users[$j]['passworld']){
                $open_User_Password = true;
                $NoPassword = true;
                $NO_password = "";
                $ID_user_2 = $j;
                break;
            }
        }

        if (!$NoEmail){
            $_SESSION['NoEmail'] = 'E-mail ไม่ถูกต้อง';
            $_SESSION['emailText'] = '2px solid red';
        }
        if (!$NoPassword){
            $_SESSION['NoPassword'] = 'Password ไม่ถูกต้อง';
            $_SESSION['passwordText'] = '2px solid red';
        }

        if ($open_User_Email && $open_User_Password && $ID_user_1 == $ID_user_2){

            $_SESSION['user'] = $Total_Users[$ID_user_1];
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;
            return true;
        }

        return false;
    }


    // ส่วนการทำงาน
    if ($_SERVER['REQUEST_METHOD'] == "POST"){

        $email = $password = "";
        $NO_email_password = true;
        session_unset();

        if (empty($_POST['email'])){
            $emailText = "2px solid red";
            $NO_email_password = false;
        } else {
            $email = $_POST['email'];
        }
    
        if (empty($_POST['password'])){
            $passwordText = "2px solid red";
            $NO_email_password = false;
        } else {
            $password = $_POST['password'];
        }

        if ($NO_email_password){
            
            $Is_this_correct = check_Email_Password_full($email, $password);
            $_SESSION['LoginSuccessful'] = $Is_this_correct;

            if ($Is_this_correct){

                if ($_SESSION['user']['deleteAccount']['left'] != 0){

                    $deleteAccount_date = $_SESSION['user']['deleteAccount']['date'];
                    $current_Date = date("Y/m/d");
                    $registration_DateTime = new DateTime($deleteAccount_date);
                    $registration_DateTime->modify('+15 days');
                    $current_DateTime = new DateTime($current_Date);
                    $left = $current_DateTime->diff($registration_DateTime);
                    $left = $left->format('%r%a');
                        
                    if ($left <= 0) {
                            
                        $userJson = readJson('data\user-hotel.json');
                        $Total_Users = json_decode($userJson, true);
                        $Total_Users[$_SESSION['user']['ID']]['deleteAccount']['left'] = "0";
                        $userJson = json_encode($Total_Users, JSON_PRETTY_PRINT);
                        writeJason('data\user-hotel.json', $userJson);
                        session_unset();
                    } else {
                            
                        $_SESSION['user']['deleteAccount']['left'] = $left;
                        header('Location: system-profile.php');
                        exit(0);
                    }
                } else {
                    session_unset();
                }
            } else {
                header('Location: index.php');
                exit(0);
            }
        } else {
            $_SESSION['emailText'] = $emailText;
            $_SESSION['passwordText'] = $passwordText;
            header('Location: index.php');
            exit(0);
        }
    } else {
        header('Location: index.php');
        exit(0);
    }
?>

<!DOCTYPE html>
<html lang="en-th">
<head>
    <meta name="author" content="Developed by Mr. Anucha Khemthong Contact anuchahaha5@gmail.com">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="container-permane">
        บัญชีนี้ถูกลบอย่างถาวรแล้ว <a href="index.php">กลับสู่หน้า Login</a>
    </div>
</body>
</html>