<?php 

    session_start();

    $Element_Ul = $color_box_status = '';
    $box_alert_data = "display: none;";
    $data_alert = '';
    $table_bookHotel = '';
    $name_content = '';


    function readJson($src){
        $jsonContent = file_get_contents($src);
        return $jsonContent;
    }

    function writeJason($src, $jsonData){
        file_put_contents($src, $jsonData);
    }

    function table_user($user){

        $tr = array(
            '<table>
                        <tr>
                            <th class="center-t">ลำดับ</th>
                            <th>คำนำหน้า</th>
                            <th>ชื่อจริง</th>
                            <th>นามสกุล</th>
                            <th>เบอร์โทร</th>
                            <th>E-mail</th>
                            <th>จำนวนการจอง</th>
                            <th class="center-t">การจอง</th>
                        </tr>',
                        false
        );
        $number = 1;

        for ($i = 1; $i < count($user); $i++){
            if (count($user[$i]['bookHotel']) != 0){
                $tr[1] = true;
                $tr[0] .= '<tr>';
                $tr[0] .= '<td class="center-t">' . $number++ . '</td>';
                $tr[0] .= '<td>' . $user[$i]['prefix'] . '</td>';
                $tr[0] .= '<td>' . $user[$i]['firstName'] . '</td>';
                $tr[0] .= '<td>' . $user[$i]['surName'] . '</td>';
                $tr[0] .= '<td>' . $user[$i]['tel'] . '</td>';
                $tr[0] .= '<td>' . $user[$i]['email'] . '</td>';
                $tr[0] .= '<td>' . count($user[$i]['bookHotel']) . ' รายการ</td>';
                $tr[0] .= '<td class="center-t"><a href="system-hotel-booking-users.php?s=viewBookings&ID=' . $user[$i]['ID'] . '" class="view-bookings">ดูการจอง</a></td>';
                $tr[0] .= '</tr>';
            }
        }
        
        $tr[0] .= '</table>';

        return $tr;
    }

    function table_Hotel($hotel, $ID){

        $tr = '<table>
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

        for ($r = 0; $r < count($hotel); $r++){
            $Results = 0;
            $tr .= '<tr>';
            $tr .= '<td class="center-t">' . ($r + 1) . '</td>';
            $tr .= '<td>' . $hotel[$r]['nameHotel'] . '</td>';
            $tr .= '<td>' . $hotel[$r]['BookingDate'] . '</td>';
            $tr .= '<td class="center-t">' . $hotel[$r]['HowManyNights'] . '</td>';
            if ($hotel[$r]['SingleBedWithFan'] != ""){
                $tr .= '<td>จอง</td>';
                $Results = $Results + (350 * (int)$hotel[$r]['HowManyNights']);
            } else {
                $tr .= '<td>ไม่จอง</td>';
            };
            if ($hotel[$r]['DoubleBedWithFan'] != ""){
                $tr .= '<td>จอง</td>';
                $Results = $Results + (450 * (int)$hotel[$r]['HowManyNights']);
            } else {
                $tr .= '<td>ไม่จอง</td>';
            };
            if ($hotel[$r]['SingleBedWithAirConditioning'] != ""){
                $tr .= '<td>จอง</td>';
                $Results = $Results + (600 * (int)$hotel[$r]['HowManyNights']);
            } else {
                $tr .= '<td>ไม่จอง</td>';
            };
            if ($hotel[$r]['DoubleBedWithAirConditioning'] != ""){
                $tr .= '<td>จอง</td>';
                $Results = $Results + (800 * (int)$hotel[$r]['HowManyNights']);
            } else {
                $tr .= '<td>ไม่จอง</td>';
            };
            $tr .= '<td>' . $Results . '</td>';
            $tr .= '<td class="center-t"><a href="cancel-bookHotel.php?r=' . ($r + 1) . '&h=' . $hotel[$r]['nameHotel'] . '&f=systemHotelAdmin&ID=' . $ID . '" class="cancel-book">ยกเลิก</a></td>';
            $tr .= '</tr>';
        }

        $tr .= '</table>';

        return $tr;
    }

    if (isset($_SESSION['email']) && isset($_SESSION['password'])){
        if ($_SESSION['LoginSuccessful']){

            // แช็คว่าเป็น Admin หรือ User
            if ($_SESSION['user']['status'] == 'ADMIN'){

                $Element_Ul = '<li><a href="system-profile.php">Profile</a></li>
                    <li><a href="system-hotel.php">Hotels</a></li>
                    <li><a href="#" style="background-color: #0130bd;">Hotel booking users</a></li>
                    <li><a href="system-user.php">Users</a></li>
                    <!-- เพิ่มเมนู -->
                    <li><a href="Log-out.php?l=logOut">Log out</a></li>';
                    $color_box_status = '#208000';

                    $userJson = readJson('data\user-hotel.json');
                    $Total_Users = json_decode($userJson, true);

                    if (!(isset($_GET['s']) && isset($_GET['ID']))){

                        $table = table_user($Total_Users);
                        $name_content = 'HOTEL BOOKING USERS';

                        if ($table[1] != false){
                            $table_bookHotel .= $table[0];
                        } else {
                            $table_bookHotel .= $table[0] . '<h3>No display user</h3>';
                        }
                    } else {

                        $status = $_GET['s'];
                        $ID = (int)$_GET['ID'];
                        $name_content = 'RESERVATION LISTS';

                        if ($status == 'viewBookings'){

                            $table_bookHotel = table_Hotel($Total_Users[$ID]['bookHotel'], $ID) . '<a href="system-hotel-booking-users.php" class="button-correct button-cancel">ยกเลิก</a>';
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
    <title>Hotel booking users</title>
    <link rel="stylesheet" href="css/system.css">
    <link rel="stylesheet" href="css/system-hotel-booking-users.css">
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
