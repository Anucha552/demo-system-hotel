<?php
    $prefix = $firstName = $surName = $tel = $email = $password = "";
    $prefixText = $firstNameText = $surNameText = $telText = $emailText = $passwordText = "none";
    $recordJson = true;
    $NO_Email = $NO_Password = "";
    $box_confirm_account = "none";
    $box_form = "block";
    
    function writeJason($src, $jsonData){
        file_put_contents($src, $jsonData);
    }

    function readJson($src){
        $jsonContent = file_get_contents($src);
        return $jsonContent;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        if (empty($_POST['prefix'])){
            $prefixText = '2px solid red';
            $recordJson = false;
        } else {
            $prefix = $_POST['prefix'];
        }

        if (empty($_POST['firstName'])) {
            $firstNameText = '2px solid red';
            $recordJson = false;
        } else {
            $firstName = $_POST['firstName'];
        }

        if (empty($_POST['surName'])) {
            $surNameText = '2px solid red';
            $recordJson = false;
        } else {
            $surName = $_POST['surName'];
        }

        if (empty($_POST['tel'])) {
            $telText = '2px solid red';
            $recordJson = false;
        } else {
            $tel = $_POST['tel'];
        }

        if (empty($_POST['email'])) {
            $emailText = '2px solid red';
            $recordJson = false;
        } else {
            $email = $_POST['email'];
        }

        if (empty($_POST['password'])) {
            $passwordText = '2px solid red';
            $recordJson = false;
        } else {
            $password = $_POST['password'];
        }

        if ($recordJson){

            $userJson = readJson('data\user-hotel.json');
            $user = json_decode($userJson, true);
            $date = date("Y/m/d");
            $ID = $user[count($user) - 1]['ID'] + 1;
            $dataError = true;
        
            for ($i = 0; $i < count($user); $i++){
                if ($email == $user[$i]['email']){
                    $NO_Email = "<span style='color: red;'>E-mail นี้มีอยู่ในระบบแล้ว</span><br>";
                    $emailText = '2px solid red';
                    $dataError = false;
                    break;
                }
            }

            for ($j = 0; $j < count($user); $j++){
                if (md5($password) == $user[$j]['passworld']){
                    $NO_Password = "<span style='color: red;'>Passworld นี้มีอยู่ในระบบแล้ว</span><br>";
                    $passwordText = '2px solid red';
                    $dataError = false;
                    break;
                }
            }
            
            if ($dataError){
                
                $newDataUser = array(
                    "ID" => $ID,
                    "status" => "USER",
                    "profileImage" => "image/icon/profile.png",
                    "creationDate" => $date,
                    "prefix" => $prefix,
                    "firstName" => $firstName,
                    "surName" => $surName,
                    "tel" => $tel,
                    "email" => $email,
                    "passworld" => md5($password),
                    "bookHotel" => array(),
                    "deleteAccount" => array(
                        "status" => false,
                        "date" => "",
                        "left" => 15
                    )
                );
            
                $user[count($user)] = $newDataUser;
                $userJson = json_encode($user, JSON_PRETTY_PRINT);
                writeJason('data\user-hotel.json', $userJson);
                $box_form = "none";
                $box_confirm_account = "block";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en-th">
<head>
    <meta name="author" content="Developed by Mr. Anucha Khemthong Contact anuchahaha5@gmail.com">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create account</title>
    <link rel="stylesheet" href="css/create-account.css">
</head>
<body>
    <div class="bg-image"></div>

    <div class="bg-text">
       <div class="screen-max-500px">
            <div class="box-form" style="display: <?php echo $box_form; ?>;">
                <div class="header-create">
                    <span class="text-create">Create account</span>
                </div>
                <div class="section-login">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                        <span style="padding: 3px; border-radius: 10px; border: <?php echo $prefixText; ?>;">
                            <input type="radio" name="prefix" id="mr" value="นาย">
                            <label for="mr" class="mr-mrs-miss">นาย</label>
                            <input type="radio" name="prefix" id="mrs" value="นาง">
                            <label for="mrs" class="mr-mrs-miss">นาง</label>
                            <input type="radio" name="prefix" id="miss" value="น.ส.">
                            <label for="miss" class="mr-mrs-miss">น.ส.</label>
                        </span><br>
                        <input type="text" placeholder="ชื่อ" id="firstName" name="firstName" style="border: <?php echo $firstNameText; ?>;"><br>
                        <input type="text" placeholder="นามสกุล" id="surName" name="surName" style="border: <?php echo $surNameText; ?>;"><br>
                        <input type="number" placeholder="เบอร์โทร" id="tel" name="tel" style="border: <?php echo $telText; ?>;"><br>
                        <input type="email" placeholder="E-mail" id="email" name="email" style="border: <?php echo $emailText; ?>;"><br>
                        <?php echo $NO_Email; ?>
                        <input type="password" placeholder="Password" id="password" name="password" style="border: <?php echo $passwordText; ?>;"><br>
                        <?php echo $NO_Password; ?>
                        <input type="submit" value="Create account" id="submit"><br>
                    </form>
                    <button onclick="reset()" id="reset">Reset data</button>                
                </div>
            </div>
            <div class="box-confirm-account" style="display: <?php echo $box_confirm_account; ?>;">
                <div class="header-create">
                    <span class="text-create">Account created successfully</span>
                </div>
                <div class="return-login">
                    <span>สร้างบัญชีของคุณสำเร็จ </span><span><a href="index.php">กลับสู่หน้า Login</a></span>
                </div>
            </div>
       </div>
        <div class="screen-min-500px">
            <span>หน้าต่าง screen น้อยกว่า 500 pixels แนะนำให้ใช้คอมพิวเตอร์แทน</span>
        </div>
    </div>

    <script src="js/create-account.js"></script>
</body>
</html>