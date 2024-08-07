<?php 

    session_start();

    $Element_Ul = $color_box_status = '';
    $box_alert_data = "display: none;";
    $data_alert = '<form action="system-profile.php" method="post" enctype="multipart/form-data">
                <input type="file" name="IMGProfile" id="IMGProfile"><br>
                <input type="submit" name="submitFile" value="Upload" id="submit-alert-data">
                <div onclick="submit_cancel()" class="submit-cancel">Cancel</div>
                </form>';
    $delete_account = '';
    $text_delete_account = "";

    function readJson($src){
        $jsonContent = file_get_contents($src);
        return $jsonContent;
    }

    function writeJason($src, $jsonData){
        file_put_contents($src, $jsonData);
    }

    function check_email_pass($email, $password){

        $userJson = readJson('data\user-hotel.json');
        $user = json_decode($userJson, true);
        $dataError = array(
            "No_Email" => array(
                "data" => false,
                "text" => ""
            ),
            "No_Pass" => array(
                "data" => false,
                "text" => ""
            )
        );

        
        if ($email != "NO"){
            for ($i = 0; $i < count($user); $i++){
                if ($email == $user[$i]['email']){
                    $dataError['No_Email']['data'] = true;
                    $dataError['No_Email']['text'] = "E-mail นี้มีอยู่ในระบบแล้ว";
                    break;
                }
            }
        }

        if ($password != "NO"){
            for ($j = 0; $j < count($user); $j++){
                if (md5($password) == $user[$j]['passworld']){
                    $dataError['No_Pass']['data'] = true;
                    $dataError['No_Pass']['text'] = "Passworld นี้มีอยู่ในระบบแล้ว";
                    break;
                }
            }
        }
            
        return $dataError;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $target_file = "";
        $OK_Edit_data = $Ok_email_pass = 0;
        $Edit_Email = $Edit_Pass = "NO";
        $input_user = $_SESSION['user'];
        
        // แด้ไขข้อมูลของ USER
        if (!empty($_POST['prefix'])){
            $input_user['prefix'] = $_POST['prefix'];
            $OK_Edit_data = 1;
        }

        if (!empty($_POST['firstName'])){
            $input_user['firstName'] = $_POST['firstName'];
            $OK_Edit_data = 1;
        }

        if (!empty($_POST['surName'])){
            $input_user['surName'] = $_POST['surName'];
            $OK_Edit_data = 1;
        }

        if (!empty($_POST['tel'])){
            $input_user['tel'] = $_POST['tel'];
            $OK_Edit_data = 1;
        }

        if (!empty($_POST['email'])){
            $Edit_Email = $_POST['email'];
            $Ok_email_pass = 1;
        }

        if (!empty($_POST['passworld'])){
            $Edit_Pass = $_POST['passworld'];
            $Ok_email_pass = 1;
        }

        if ($Ok_email_pass == 1){

            $dataError = check_email_pass($Edit_Email, $Edit_Pass);
            $open_text_error = false;
            $text_error ='';

            if ($dataError['No_Email']['data']){

                $box_alert_data = "display: block;";
                $open_text_error = true;
                $text_error .= $dataError['No_Email']['text'] . '<br>';
            } else {

                if ($Edit_Email != "NO"){

                    $input_user['email'] = $Edit_Email;
                }
            }

            if ($dataError['No_Pass']['data']){

                $box_alert_data = "display: block;";
                $open_text_error = true;
                $text_error .= $dataError['No_Pass']['text'];
            } else {

                if ($Edit_Pass != 'NO'){

                    $input_user['passworld'] = md5($Edit_Pass);
                }
            }
            
            if ($open_text_error){

                $OK_Edit_data = 0;
                $data_alert = '<div class="box-alert-error">' . $text_error . '</div>
                            <a href="system-profile.php" class="submit-cancel">OK</a>';
            } else {

                $OK_Edit_data = 1;
            }
        }

        if ($OK_Edit_data == 1){

            $userJson = readJson('data\user-hotel.json');
            $Total_Users = json_decode($userJson, true);
            $_SESSION['user'] = $input_user;
            $Total_Users[$_SESSION['user']['ID']] = $_SESSION['user'];
            $userJson = json_encode($Total_Users, JSON_PRETTY_PRINT);
            writeJason('data\user-hotel.json', $userJson);
            header('Location: ' . $_SERVER['REQUEST_URI']);
        }

        

        // upload file
        if (isset($_POST['submitFile'])){
            
            $target_dir = "image/imgUser/";
            $target_file = $target_dir . basename($_FILES["IMGProfile"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            
            if (file_exists($target_file)) {
                $uploadOk = 0;
            }
            
            if ($_FILES["IMGProfile"]["size"] > 500000) {
                $uploadOk = 0;
            }
            
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                $uploadOk = 0;
            }
            
            if ($uploadOk == 1) {

                move_uploaded_file($_FILES["IMGProfile"]["tmp_name"], $target_file);
                $userJson = readJson('data\user-hotel.json');
                $Total_Users = json_decode($userJson, true);

                for ($i = 0; $i < count($Total_Users); $i++){
                    if ($Total_Users[$i]['ID'] == $_SESSION['user']['ID']){

                        if ($_SESSION['user']['profileImage'] != 'image/icon/profile.png'){
                            unlink($_SESSION['user']['profileImage']); 
                        }

                        $_SESSION['user']['profileImage'] = $target_file;
                        $Total_Users[$_SESSION['user']['ID']] = $_SESSION['user'];
                        $userJson = json_encode($Total_Users, JSON_PRETTY_PRINT);
                        writeJason('data\user-hotel.json', $userJson);
                        break;
                    }
                }

                header('Location: ' . $_SERVER['REQUEST_URI']);
            }
        } 
    }

    if (isset($_SESSION['email']) && isset($_SESSION['password'])){
        if ($_SESSION['LoginSuccessful']){

            // แช็คว่าเป็น Admin หรือ User
            if ($_SESSION['user']['status'] == 'ADMIN'){

                $Element_Ul = '<li><a href="#" style="background-color: #0130bd;">Profile</a></li>
                    <li><a href="system-hotel.php">Hotels</a></li>
                    <li><a href="system-hotel-booking-users.php">Hotel booking users</a></li>
                    <li><a href="system-user.php">Users</a></li>
                    <!-- เพิ่มเมนู -->
                    <li><a href="Log-out.php?l=logOut">Log out</a></li>';
                $color_box_status = '#208000';
            } else {

                $Element_Ul = '<li><a href="#" style="background-color: #0130bd;">Profile</a></li>
                    <li><a href="system-hotel.php">Hotels</a></li>
                    <li><a href="system-number-bookings.php">Number of bookings</a></li>
                    <!-- เพิ่มเมนู -->
                    <li><a href="Log-out.php?l=logOut">Log out</a></li>';

                if ($_SESSION['user']['deleteAccount']['status']){

                    $color_box_status = ' #cc0000';
                    $delete_account = '<a href="cancel-delete-account.php?c=cancelDeleteAccount" class="delete-account">Cancel delete account</a>';
                    $text_delete_account = 'บัญชีเหลืออีก ' . $_SESSION['user']['deleteAccount']['left'] . ' วัน ก่อนจะถูกลบถาวร <a href="cancel-delete-account.php?c=cancelDeleteAccount" class="cancel-delete-account">ยกเลิกลบบัญชี</a>';
                } else {

                    $color_box_status = ' #7a0099';
                    $delete_account = '<a href="delete-account.php?d=deleteAccount" class="delete-account">Delete Account</a>';
                }
            }
            
        } else {
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
    <title>Profile</title>
    <link rel="stylesheet" href="css/system.css">
    <link rel="stylesheet" href="css/system-profile.css">
</head>
<body>
    <aside class="box-nav-left">
        <div class="box-icon">
            <img class="icon" src="<?php echo $_SESSION['user']['profileImage'] ?>" alt="icon" width="120" height="120">
        </div>
        <div class="box-status" style="background-color: <?php echo $color_box_status ?>">
            <span class="text-status"><?php echo $_SESSION['user']['status']; ?></span>
        </div>
        <nav>
            <ul>
                <?php echo $Element_Ul; ?>
            </ul>
        </nav>
    </aside>
    <div class="container-content">
        <div class="box-content">
            <header style="background-color: <?php echo $color_box_status ?>">
                <h1><?php echo $_SESSION['user']['prefix'] . ' ' . $_SESSION['user']['firstName'] . ' ' . $_SESSION['user']['surName'] . ' สร้างบัญชีเมื่อวันที่ ' . $_SESSION['user']['creationDate'];  ?></h1>
                <h3><?php echo $text_delete_account; ?></h3>
            </header>
            <div class="box-name-content">
                <h2>PROFILE</h2>
            </div>
            <section>
                <div class="content">
                    <div class="box-icon-user">
                        <div class="box-icon2">
                            <img class="icon" src="<?php echo $_SESSION['user']['profileImage'] ?>" alt="icon" width="100" height="100">
                        </div>
                        <div class="box-upload-image">
                            <button class="button-upload" onclick="upload_image()">Upload Image</button>
                        </div>
                        <div class="box-delete-account">
                            <?php echo $delete_account; ?>
                        </div>
                    </div>
                    <div class="box-table">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                        <table>
                            <tr>
                                <td>สถานะ</td>
                                <td><?php echo $_SESSION['user']['status']; ?></td>
                                <td class="input-correct" style="display: none;">Unable to edit</td>
                            </tr>
                            <tr>
                                <td>วันที่สร้างบัญชี</td>
                                <td><?php echo $_SESSION['user']['creationDate']; ?></td>
                                <td class="input-correct" style="display: none;">Unable to edit</td>
                            </tr>
                            <tr>
                                <td>คำนำหน้าชื่อ</td>
                                <td><?php echo $_SESSION['user']['prefix']; ?></td>
                                <td class="input-correct" style="display: none;"><input type="text" name="prefix" class="input-text"></td>
                            </tr>
                            <tr>
                                <td>ชื่อจริง</td>
                                <td><?php echo $_SESSION['user']['firstName']; ?></td>
                                <td class="input-correct" style="display: none;"><input type="text" name="firstName" class="input-text"></td>
                            </tr>
                            <tr>
                                <td>นามสกุล</td>
                                <td><?php echo $_SESSION['user']['surName']; ?></td>
                                <td class="input-correct" style="display: none;"><input type="text" name="surName" class="input-text"></td>
                            </tr>
                            <tr>
                                <td>เบอร์โทร</td>
                                <td><?php echo $_SESSION['user']['tel']; ?></td>
                                <td class="input-correct" style="display: none;"><input type="number" name="tel" class="input-text"></td>
                            </tr>
                            <tr>
                                <td>E-mail</td>
                                <td><?php echo $_SESSION['user']['email']; ?></td>
                                <td class="input-correct" style="display: none;"><input type="email" name="email" class="input-text"></td>
                            </tr>
                            <tr>
                                <td>Password</td>
                                <td>********</td>
                                <td class="input-correct" style="display: none;"><input type="text" name="passworld" class="input-text"></td>
                            </tr>
                        </table>
                        <div class="box-submid-correct" style="display: none;">
                            <div class="box-submid">
                                <input type="submit" value="บันทึกข้อมูล" class="button-correct">
                                <a href="<?php echo $_SERVER['REQUEST_URI']; ?>" class="button-correct button-cancel">ยกเลิก</a>
                            </div>
                        </div>
                        </form>
                        <div class="box-buttom-correct" style="display: block;">
                            <button class="button-correct" onclick="correct()">แก้ไขข้อมูล</button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- บล็อกสำหลับ Upload ข้อมูล -->
    <div class="box-alert-data" style="<?php echo $box_alert_data; ?>">
        <div class="box-text-alert-data">
            <?php echo $data_alert; ?>
        </div>
    </div>

    <script src="js/system-profile.js"></script>
</body>
</html>