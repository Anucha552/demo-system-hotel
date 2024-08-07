<?php 

    session_start();

    $Element_Ul = $color_box_status = '';
    $box_alert_data = "display: none;";
    $data_alert = '';
    $text_delete_account = "";
    $table_bookHotel = '<table>
                        <tr>
                            <th class="center-t">ลำดับ</th>
                            <th>โรงแรม</th>
                            <th>วันที่จอง</th>
                            <th>เช้าพักกี่คืน</th>
                            <th>พัดลม/เดี่ยว/350</th>
                            <th>พัดลม/คู่/450</th>
                            <th>แอร์/เดี่ยว/600</th>
                            <th>แอร์/คู่/800</th>
                            <th>ราคารวม</th>
                            <th class="center-t">การจอง</th>
                        </tr>';


    function readJson($src){
        $jsonContent = file_get_contents($src);
        return $jsonContent;
    }

    function writeJason($src, $jsonData){
        file_put_contents($src, $jsonData);
    }

    function table(){

        $tr = "";

        if ($_SESSION['user']['bookHotel'] != 0){

            for ($r = 0; $r < count($_SESSION['user']['bookHotel']); $r++){
                $Results = 0;
                $tr .= '<tr>';
                $tr .= '<td class="center-t">' . ($r + 1) . '</td>';
                $tr .= '<td>' . $_SESSION['user']['bookHotel'][$r]['nameHotel'] . '</td>';
                $tr .= '<td>' . $_SESSION['user']['bookHotel'][$r]['BookingDate'] . '</td>';
                $tr .= '<td class="center-t">' . $_SESSION['user']['bookHotel'][$r]['HowManyNights'] . '</td>';
                if ($_SESSION['user']['bookHotel'][$r]['SingleBedWithFan'] != ""){
                    $tr .= '<td>จอง</td>';
                    $Results = $Results + (350 * (int)$_SESSION['user']['bookHotel'][$r]['HowManyNights']);
                } else {
                    $tr .= '<td>ไม่จอง</td>';
                };
                if ($_SESSION['user']['bookHotel'][$r]['DoubleBedWithFan'] != ""){
                    $tr .= '<td>จอง</td>';
                    $Results = $Results + (450 * (int)$_SESSION['user']['bookHotel'][$r]['HowManyNights']);
                } else {
                    $tr .= '<td>ไม่จอง</td>';
                };
                if ($_SESSION['user']['bookHotel'][$r]['SingleBedWithAirConditioning'] != ""){
                    $tr .= '<td>จอง</td>';
                    $Results = $Results + (600 * (int)$_SESSION['user']['bookHotel'][$r]['HowManyNights']);
                } else {
                    $tr .= '<td>ไม่จอง</td>';
                };
                if ($_SESSION['user']['bookHotel'][$r]['DoubleBedWithAirConditioning'] != ""){
                    $tr .= '<td>จอง</td>';
                    $Results = $Results + (800 * (int)$_SESSION['user']['bookHotel'][$r]['HowManyNights']);
                } else {
                    $tr .= '<td>ไม่จอง</td>';
                };
                $tr .= '<td>' . $Results . '</td>';
                $tr .= '<td class="center-t"><a href="cancel-bookHotel.php?r=' . ($r + 1) . '&h=' . $_SESSION['user']['bookHotel'][$r]['nameHotel'] . '&f=systemHotelUser" class="cancel-book">ยกเลิก</a></td>';
                $tr .= '</tr>';
            }
        } else {
            return false;
        }

        return $tr;
    }


    if (isset($_SESSION['email']) && isset($_SESSION['password'])){
        if ($_SESSION['LoginSuccessful']){

            // แช็คว่าเป็น Admin หรือ User
            if ($_SESSION['user']['status'] == 'USER'){

                $Element_Ul = '<li><a href="system-profile.php">Profile</a></li>
                    <li><a href="system-hotel.php">Hotels</a></li>
                    <li><a style="background-color: #0130bd;" href="#">Number of bookings</a></li>
                    <!-- เพิ่มเมนู -->
                    <li><a href="Log-out.php?l=logOut">Log out</a></li>';

                    $userJson = readJson('data\user-hotel.json');
                    $Total_Users = json_decode($userJson, true);
                    $_SESSION['user']['bookHotel'] = $Total_Users[$_SESSION['user']['ID']]['bookHotel'];

                    $table = table(); 

                    if($table != false){
                        $table_bookHotel .= $table . '</table';
                    } else {
                        $table_bookHotel .= '</table><h3>No display data</h3>';
                    }

                if ($_SESSION['user']['deleteAccount']['status']){

                    $color_box_status = ' #cc0000';
                    $delete_account = '<a href="cancel-delete-account.php?c=cancelDeleteAccount" class="delete-account">Cancel delete account</a>';
                    $text_delete_account = 'บัญชีเหลืออีก ' . $_SESSION['user']['deleteAccount']['left'] . ' วัน ก่อนจะถูกลบถาวร <a href="cancel-delete-account.php?c=cancelDeleteAccount" class="cancel-delete-account">ยกเลิกลบบัญชี</a>';
                } else {

                    $color_box_status = ' #7a0099';
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
    <title>Number of bookings</title>
    <link rel="stylesheet" href="css/system.css">
    <link rel="stylesheet" href="css/system-number-bookings.css">
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
                <h2>NUMBER OF BOOKINGS</h2>
            </div>
            <section>
                <div class="content">
                   <?php echo $table_bookHotel; ?>
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
