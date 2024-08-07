<?php

    session_start();

    if (isset($_SESSION['email']) && isset($_SESSION['password'])){
        if ($_SESSION['LoginSuccessful']){
            header('Location: system-profile.php');
            exit(0);
        }
    }6
?>

<!DOCTYPE html>
<html lang="en-th">
<head>
    <meta name="author" content="Developed by Mr. Anucha Khemthong Contact anuchahaha5@gmail.com">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOIN</title>
    <link rel="stylesheet" href="css/login-index.css">
</head>
<body>
    <div class="bg-image"></div>

    <div class="bg-text">
        <div class="screen-max-500px">
            <div class="header-login">
                <span class="text-login">Please account</span>
                <span class="box-icon-login">
                    <img src="image/icon/login.png" alt="icon login" class="icon-login" width="70" height="70">
                </span>
            </div>
            <div class="section-login">
                <form action="Log-in.php" method="post">
                    <input type="email" name="email" id="email" style="border: <?php if(isset($_SESSION['emailText'])){ echo $_SESSION['emailText'];} ?>; margin-top: 0;" placeholder="E-mail" autofocus><br>
                        <div style="color: red;"><?php if (isset($_SESSION['NoEmail'])){ echo $_SESSION['NoEmail'];} ?></div>
                    <input type="password" name="password" style="border: <?php if(isset($_SESSION['passwordText'])){ echo $_SESSION['passwordText'];} ?>;" id="password" placeholder="Password"><br>
                        <div style="color: red;"><?php if (isset($_SESSION['NoPassword'])){ echo $_SESSION['NoPassword'];} ?></div>
                    <input type="submit" value="Login" id="login">
                </form>
            </div>
            <div class="footer-login">
                <p>
                    <span class="text-data-login"><a href="create-account.php" class="text-data-login">Create account</a></span> or 
                    <span class="text-data-login" onclick="reset()">reset data</span>
                </p>
            </div>
        </div>
        <div class="screen-min-500px">
            <span>หน้าต่าง screen น้อยกว่า 500 pixels แนะนำให้ใช้คอมพิวเตอร์แทน</span>
        </div>
    </div>

    <script src="js/login.js"></script>
</body>
</html>