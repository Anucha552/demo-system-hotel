<?php 

    session_start();

    $Element_Ul = $color_box_status = '';
    $box_alert_data = "display: none;";
    $data_alert = '';
    $table_user = '';
    $name_content = '';
    $left = '';


    function readJson($src){
        $jsonContent = file_get_contents($src);
        return $jsonContent;
    }

    function writeJason($src, $jsonData){
        file_put_contents($src, $jsonData);
    }

    function table($user){

        $tr = array(
            '<table>
                        <tr>
                            <th class="center-t">ID</th>
                            <th>คำนำหน้า</th>
                            <th>ชื่อจริง</th>
                            <th>นามสกุล</th>
                            <th>เบอร์โทร</th>
                            <th>E-mail</th>
                            <th>วันที่สร้างบัญชี</th>
                            <th>สถานะลบบัญชี</th>
                            <th class="center-t">แก้ไข User</th>
                        </tr>',
                        false
        );
        $openSave = false;

        if (count($user) > 1) {

            $tr[1] = true;

            for ($r = 1; $r < count($user); $r++){
                $tr[0] .= '<tr>';
                $tr[0] .= '<td class="center-t">' . $user[$r]['ID'] . '</td>';
                $tr[0] .= '<td>' . $user[$r]['prefix'] . '</td>';
                $tr[0] .= '<td>' . $user[$r]['firstName'] . '</td>';
                $tr[0] .= '<td>' . $user[$r]['surName'] . '</td>';
                $tr[0] .= '<td>' . $user[$r]['tel'] . '</td>';
                $tr[0] .= '<td>' . $user[$r]['email'] . '</td>';
                $tr[0] .= '<td>' . $user[$r]['creationDate'] . '</td>';

                if ($user[$r]['deleteAccount']['status']){

                    $deleteAccount_date = $user[$r]['deleteAccount']['date'];
                    $current_Date = date("Y/m/d");
                    $registration_DateTime = new DateTime($deleteAccount_date);
                    $registration_DateTime->modify('+15 days');
                    $current_DateTime = new DateTime($current_Date);
                    $left = $current_DateTime->diff($registration_DateTime);
                    $left = $left->format('%r%a');

                    if ($left <= 0){
                        $tr[0] .= '<td>ลบบัญชีถาวร</td>';
                        $user[$r]['deleteAccount']['left'] = "0";
                        $openSave = true;
                    } else {
                        $tr[0] .= '<td>ลบบัญชี</td>';
                    }
                } else {
                    $tr[0] .= '<td>ปกติ</td>';
                }
                $tr[0] .= '<td class="center-t"><a href="system-user.php?s=edit&ID=' . $user[$r]['ID'] .'" class="edit-user">แก้ไข</a></td>';
                $tr[0] .= '</tr>';
            } 
        }

        $tr[0] .= '</table>';

        if ($openSave){
            
            $userJson = json_encode($user, JSON_PRETTY_PRINT);
            writeJason('data\user-hotel.json', $userJson);
        }

        return $tr;
    }

    if (isset($_SESSION['email']) && isset($_SESSION['password'])){
        if ($_SESSION['LoginSuccessful']){

            // แช็คว่าเป็น Admin หรือ User
            if ($_SESSION['user']['status'] == 'ADMIN'){

                $Element_Ul = '<li><a href="system-profile.php">Profile</a></li>
                    <li><a href="system-hotel.php">Hotels</a></li>
                    <li><a href="system-hotel-booking-users.php">Hotel booking users</a></li>
                    <li><a href="#" style="background-color: #0130bd;">Users</a></li>
                    <!-- เพิ่มเมนู -->
                    <li><a href="Log-out.php?l=logOut">Log out</a></li>';
                $color_box_status = '#208000';

                $userJson = readJson('data\user-hotel.json');
                $Total_Users = json_decode($userJson, true);

                if (!(isset($_GET['s']) && isset($_GET['ID']))){

                    $table = table($Total_Users);
                    $name_content = 'USERS';
    
                    if ($table[1] != false){
                        $table_user .= $table[0];
                    } else {
                        $table_user .= $table[0] . '<h3>No display user</h3>';
                    }
                } else {

                    $status = $_GET['s'];
                    $ID = $_GET['ID'];

                    if ($status == 'edit'){

                        $index_user = 0;
                        $cancel_delete = "";
                        $deleteAccount_status = "";
                        $name_content = 'EDIT USERS';

                        for ($i = 1 ; $i < count($Total_Users); $i++){
                            if ($Total_Users[$i]['ID'] == (int)$ID){
                                $index_user = $i;
                                break;
                            }
                        }

                        if ($Total_Users[$index_user]['deleteAccount']['status']){

                            $deleteAccount_date = $Total_Users[$index_user]['deleteAccount']['date'];
                            $current_Date = date("Y/m/d");
                            $registration_DateTime = new DateTime($deleteAccount_date);
                            $registration_DateTime->modify('+15 days');
                            $current_DateTime = new DateTime($current_Date);
                            $left = $current_DateTime->diff($registration_DateTime);
                            $left = $left->format('%r%a');

                            if ($left <= 0){
                                $deleteAccount_status = 'ลบบัญชีถาวร';
                                $cancel_delete = '<a href="cancel-delete-account.php?c=cancelDeleteAccount&ID=' . $index_user . '" class="cancel-permanent-deletion">Cancel permanent deletion</a>';
                            } else {
                                $deleteAccount_status = 'ลบบัญชี';
                                $cancel_delete = '<a href="cancel-delete-account.php?c=cancelDeleteAccount&ID=' . $index_user . '" class="delete-account">Cancel delete account</a>
                            <a href="permanent-delete.php?p=PermanentDelete&ID=' . $index_user . '" class="permanent-delete">Permanent Delete</a>';
                            }
                            
                        } else {
                            $deleteAccount_status = 'ปกติ';
                            $cancel_delete = '<a href="delete-account.php?d=deleteAccount&ID=' . $index_user . '" class="delete-account">Delete Account</a>
                            <a href="permanent-delete.php?p=PermanentDelete&ID=' . $index_user . '" class="permanent-delete">Permanent Delete</a>';
                        }

                        $table_user = '<div class="box-edit-header">
                        <div class="box-image"><img class="image-user" src="' . $Total_Users[$index_user]['profileImage'] . '" alt="image User" width="100" height="100"></div>
                        <div class="box-cancel-delete">' . $cancel_delete . '</div>
                        </div>
                        <div class="box-edit-content"><form action="system-user.php?s=saveDataUser&amp;ID=' . $ID . '" method="post"><table class="edit-user-table">
                        <tr class="edit-user-tr">
                        <td class="edit-user-td">สถานะลบบัญชี</td>
                        <td class="edit-user-td">' . $deleteAccount_status . '</td>
                        <td class="edit-user-td">Unable to edit</td>
                        </tr>
                        <tr class="edit-user-tr">
                        <td class="edit-user-td">ปี/เดือน/วัน ที่ลบบัญชี</td>
                        <td class="edit-user-td">' . $Total_Users[$index_user]['deleteAccount']['date'] . '</td>
                        <td class="edit-user-td">Unable to edit</td>
                        </tr>
                        <tr class="edit-user-tr">
                        <td class="edit-user-td">จำนวนวันที่เหลือ</td>
                        <td class="edit-user-td">' . $left . '</td>
                        <td class="edit-user-td">Unable to edit</td>
                        </tr>
                        <tr class="edit-user-tr">
                        <td class="edit-user-td">สถานะ</td>
                        <td class="edit-user-td">' . $Total_Users[$index_user]['status'] . '</td>
                        <td class="input-correct edit-user-td">Unable to edit</td>
                        </tr>
                        <tr class="edit-user-tr">
                        <td class="edit-user-td">วันที่สร้างบัญชี</td>
                        <td class="edit-user-td">' . $Total_Users[$index_user]['creationDate'] . '</td>
                        <td class="input-correct edit-user-td">Unable to edit</td>
                        </tr>
                        <tr class="edit-user-tr">
                        <td class="edit-user-td">คำนำหน้าชื่อ</td>
                        <td class="edit-user-td">' . $Total_Users[$index_user]['prefix'] . '</td>
                        <td class="input-correct edit-user-td"><input type="text" name="prefix" class="input-text"></td>
                        </tr>
                        <tr class="edit-user-tr">
                        <td class="edit-user-td">ชื่อจริง</td>
                        <td class="edit-user-td">' . $Total_Users[$index_user]['firstName'] . '</td>
                        <td class="input-correct edit-user-td"><input type="text" name="firstName" class="input-text"></td>
                        </tr>
                        <tr class="edit-user-tr">
                        <td class="edit-user-td">นามสกุล</td>
                        <td class="edit-user-td">' . $Total_Users[$index_user]['surName'] . '</td>
                        <td class="input-correct edit-user-td"><input type="text" name="surName" class="input-text"></td>
                        </tr>
                        <tr class="edit-user-tr">
                        <td class="edit-user-td">เบอร์โทร</td>
                        <td class="edit-user-td">' . $Total_Users[$index_user]['tel'] . '</td>
                        <td class="input-correct edit-user-td"><input type="number" name="tel" class="input-text"></td>
                        </tr>
                        <tr class="edit-user-tr">
                        <td class="edit-user-td">E-mail</td>
                        <td class="edit-user-td">' . $Total_Users[$index_user]['email'] . '</td>
                        <td class="input-correct edit-user-td"><input type="email" name="email" class="input-text"></td>
                        </tr>
                        <tr class="edit-user-tr">
                        <td class="edit-user-td">Password</td>
                        <td class="edit-user-td">********</td>
                        <td class="input-correct edit-user-td"><input type="text" name="passworld" class="input-text"></td>
                        </tr>
                        </table>
                        <div class="box-submid-edit">
                            <div class="box-submid">
                                <input type="submit" value="บันทึกข้อมูล" class="button-correct">
                                <a href="system-user.php" class="button-correct button-cancel">ยกเลิก</a>
                            </div>
                        </div>
                        </form></iv>';
                    } else if ($status = 'saveDataUser'){
                        
                        $OK_Edit_data = 0;
                        
                        // แด้ไขข้อมูลของ USER
                        if (!empty($_POST['prefix'])){
                            $Total_Users[$ID]['prefix'] = $_POST['prefix'];
                            $OK_Edit_data = 1;
                        }

                        if (!empty($_POST['firstName'])){
                            $Total_Users[$ID]['firstName'] = $_POST['firstName'];
                            $OK_Edit_data = 1;
                        }

                        if (!empty($_POST['surName'])){
                            $Total_Users[$ID]['surName'] = $_POST['surName'];
                            $OK_Edit_data = 1;
                        }

                        if (!empty($_POST['tel'])){
                            $Total_Users[$ID]['tel'] = $_POST['tel'];
                            $OK_Edit_data = 1;
                        }

                        if (!empty($_POST['email'])){
                            $Total_Users[$ID]['email'] = $_POST['email'];
                            $OK_Edit_data = 1;
                        }

                        if (!empty($_POST['passworld'])){
                            $Total_Users[$ID]['passworld'] = md5($_POST['passworld']);
                            $OK_Edit_data = 1;
                        }

                        if ($OK_Edit_data != 0){

                            $userJson = json_encode($Total_Users, JSON_PRETTY_PRINT);
                            writeJason('data\user-hotel.json', $userJson);
                            header('Location: system-user.php?s=edit&ID=' . $ID);
                        }
                    }
                }
            } else {
                
                header('Location: system-profile.php');
                exit(0);
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
    <title>User</title>
    <link rel="stylesheet" href="css/system.css">
    <link rel="stylesheet" href="css/system-user.css">
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
            </header>
            <div class="box-name-content">
                <h2><?php echo $name_content; ?></h2>
            </div>
            <section>
                <div class="content">
                   <?php echo $table_user; ?>
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

    <script src=""></script>
</body>
</html>
