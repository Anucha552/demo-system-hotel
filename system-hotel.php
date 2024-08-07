<?php 

    session_start();

    $Element_Ul = $color_box_status = '';
    $box_alert_data = "display: none;";
    $data_alert = '<h2 class="nameHotel"></h2>
            <form action="" method="post">
                <div class="box-hidden-nameHotel"></div>
                <label for="BookingDate">จองวันที่</label>
                <input type="date" name="BookingDate" id="BookingDate"><br>
                <input type="checkbox" name="SingleBedWithFan" id="SingleBedWithFan" value="SingleBedWithFan">
                <label for="SingleBedWithFan">เตียงเดียวห้องพัดลมคืนละ 350 บาท</label><br>
                <input type="checkbox" name="DoubleBedWithFan" id="DoubleBedWithFan" value="DoubleBedWithFan">
                <label for="DoubleBedWithFan">เตียงคู่ห้องพัดลมคืนละ 450 บาท</label><br>
                <input type="checkbox" name="SingleBedWithAirConditioning" id="SingleBedWithAirConditioning" value="SingleBedWithAirConditioning">
                <label for="SingleBedWithAirConditioning">เตียงเดี่ยวห้องแอร์คืนละ 600 บาท</label><br>
                <input type="checkbox" name="DoubleBedWithAirConditioning" id="DoubleBedWithAirConditioning" value="DoubleBedWithAirConditioning">
                <label for="DoubleBedWithAirConditioning">เตียงคู่ห้องแอร์คืนละ 800 บาท</label><br>
                <label for="HowManyNights">เข้าพักกี่คืน</label>
                <input type="number" name="HowManyNights" id="HowManyNights"><br>
                <div class="box-BookRoom">
                    <input type="submit" value="จอง" id="BookRoom">
                    <div onclick="submit_cancel()" class="submit-cancel">Cancel</div>
                </div>
            </form>';
    $text_delete_account = "";
    $Hotel_booking_button_1 = $Hotel_booking_button_2 = $Hotel_booking_button_3 = $Hotel_booking_button_4 = "";


    function readJson($src){
        $jsonContent = file_get_contents($src);
        return $jsonContent;
    }

    function writeJason($src, $jsonData){
        file_put_contents($src, $jsonData);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $nameHotel = $BookingDate = $SingleBedWithFan = $DoubleBedWithFan = "";
        $SingleBedWithAirConditioning = $DoubleBedWithAirConditioning = $HowManyNights = "";

        if (!empty($_POST['nameHotel'])){
            $nameHotel = $_POST['nameHotel'];
        }
        
        if (!empty($_POST['BookingDate'])){
            $BookingDate = $_POST['BookingDate'];
        }

        if (!empty($_POST['SingleBedWithFan'])){
            $SingleBedWithFan = $_POST['SingleBedWithFan'];
        }

        if (!empty($_POST['DoubleBedWithFan'])){
            $DoubleBedWithFan = $_POST['DoubleBedWithFan'];
        }

        if (!empty($_POST['SingleBedWithAirConditioning'])){
            $SingleBedWithAirConditioning = $_POST['SingleBedWithAirConditioning'];
        }

        if (!empty($_POST['DoubleBedWithAirConditioning'])){
            $DoubleBedWithAirConditioning = $_POST['DoubleBedWithAirConditioning'];
        }

        if (!empty($_POST['HowManyNights'])){
            $HowManyNights = $_POST['HowManyNights'];
        }

        if (!($SingleBedWithFan == "" && $DoubleBedWithFan == "" && $SingleBedWithAirConditioning == "" && $DoubleBedWithAirConditioning == "") && $nameHotel != "" && $BookingDate != "" && $HowManyNights != ""){

            $bookHotel = array(
                "nameHotel" => $nameHotel,
                "BookingDate" => $BookingDate,
                "HowManyNights" => $HowManyNights,
                "SingleBedWithFan" => $SingleBedWithFan,
                "DoubleBedWithFan" => $DoubleBedWithFan,
                "SingleBedWithAirConditioning" => $SingleBedWithAirConditioning,
                "DoubleBedWithAirConditioning" => $DoubleBedWithAirConditioning
            );
            
            $userJson = readJson('data\user-hotel.json');
            $Total_Users = json_decode($userJson, true);
            $_SESSION['user']['bookHotel'][count($_SESSION['user']['bookHotel'])] = $bookHotel;
            $Total_Users[$_SESSION['user']['ID']]['bookHotel'] = $_SESSION['user']['bookHotel'];
            $userJson = json_encode($Total_Users, JSON_PRETTY_PRINT);
            writeJason('data\user-hotel.json', $userJson);
        } else {

            $data_alert = '<div class="box-ok">กรุณาเลือกข้อมูลจองห้องพักให้ครบถ้วน <br> <a href="system-hotel.php" class="button-ok">OK</a></div>';
            $box_alert_data = 'display: block;';
        }
    }


    if (isset($_SESSION['email']) && isset($_SESSION['password'])){
        if ($_SESSION['LoginSuccessful']){

            // แช็คว่าเป็น Admin หรือ User
            if ($_SESSION['user']['status'] == 'ADMIN'){

                $Element_Ul = '<li><a href="system-profile.php">Profile</a></li>
                    <li><a href="#" style="background-color: #0130bd;">Hotels</a></li>
                    <li><a href="system-hotel-booking-users.php">Hotel booking users</a></li>
                    <li><a href="system-user.php">Users</a></li>
                    <!-- เพิ่มเมนู -->
                    <li><a href="Log-out.php?l=logOut">Log out</a></li>';
                $color_box_status = '#208000';
            } else {

                $Element_Ul = '<li><a href="system-profile.php">Profile</a></li>
                    <li><a href="#" style="background-color: #0130bd;">Hotels</a></li>
                    <li><a href="system-number-bookings.php">Number of bookings</a></li>
                    <!-- เพิ่มเมนู -->
                    <li><a href="Log-out.php?l=logOut">Log out</a></li>';
                $Hotel_booking_button_1 = '<button class="button-ook-hotel" onclick="Book_hotel(\'The Urban Condo in Central Pattaya\')">จองห้องพัก</button>';
                $Hotel_booking_button_2 = '<button class="button-ook-hotel" onclick="Book_hotel(\'Siamese Serenity Stays\')">จองห้องพัก</button>';
                $Hotel_booking_button_3 = '<button class="button-ook-hotel" onclick="Book_hotel(\'Flipper House Hotel - SHA Extra Plus\')">จองห้องพัก</button>';
                $Hotel_booking_button_4 = '<button class="button-ook-hotel" onclick="Book_hotel(\'SN Plus Hotel - SHA Plus\')">จองห้องพัก</button>';

                if ($_SESSION['user']['deleteAccount']['status']){

                    $color_box_status = ' #cc0000';
                    $delete_account = '<a href="cancel-delete-account.php?c=cancelDeleteAccount" class="delete-account">Cancel delete account</a>';
                    $text_delete_account = 'บัญชีเหลืออีก ' . $_SESSION['user']['deleteAccount']['left'] . ' วัน ก่อนจะถูกลบถาวร <a href="cancel-delete-account.php?c=cancelDeleteAccount" class="cancel-delete-account">ยกเลิกลบบัญชี</a>';
                } else {

                    $color_box_status = ' #7a0099';
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
    <title>Hotels</title>
    <link rel="stylesheet" href="css/system.css">
    <link rel="stylesheet" href="css/system-hotel.css">
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
                <h2>HOTELS</h2>
            </div>
            <section>
                <div class="content">
                <div class="container-hotel">
                        <div class="hotel-1">
                            <div class="header-hotel">
                                <h2>The Urban Condo in Central Pattaya</h2>
                            </div>
                            <div class="section-content-hotel">
                                <div class="box-image-hotel">
                                    <img src="image/imgHotel/534516816.jpg" alt="hotel 1" class="image-hotel">
                                </div>
                                <p class="content">ท่านมีสิทธิ์รับส่วนลด Genius ที่ The Urban Condo in Central Pattaya เพียงเข้าสู่ระบบเพื่อประหยัดเมื่อจองที่พักนี้ The Urban Condo in Central Pattaya อยู่ในพัทยากลาง ห่างจากสนามกอล์ฟบางพระ อินเตอร์เนชั่นแนล 40 กม. มอบบริการที่พักซึ่งประกอบด้วยลานระเบียง สวน และสระว่ายน้ำกลางแจ้งแบบเปิดตลอดทั้งปี อพาร์ตเมนต์นี้มีบริการที่พักพร้อมระเบียงและอินเทอร์เน็ตไร้สาย (WiFi) ฟรี อพาร์ตเมนต์ติดตั้งเครื่องปรับอากาศนี้ประกอบด้วย 1 ห้องนอน ห้องนั่งเล่น ห้องครัวซึ่งครบครันด้วยตู้เย็น และมี 1 ห้องน้ำพร้อมฝักบัวและไดร์เป่าผม ผ้าเช็ดตัวและชุดเครื่องนอนมีให้บริการในอพาร์ตเมนต์นี้ The Urban Condo in Central Pattaya มีซาวน่า อีสเทิร์นสตาร์ กอล์ฟคอร์ส อยู่ห่างจากที่พักนี้ 43 กม. ส่วนคริสตัล เบย์ กอล์ฟคลับ อยู่ห่างจากที่พัก 44 กม. สนามบินที่ใกล้ที่สุดคือสนามบินนานาชาติอู่ตะเภา ระยอง-พัทยา ซึ่งห่างจาก The Urban Condo in Central Pattaya 44 กม.</p>
                            </div>
                        </div>

                        <div class="hotel-2">
                            <div class="header-hotel">
                                <h2>Siamese Serenity Stays</h2>
                            </div>
                            <div class="section-content-hotel">
                                <div class="box-image-hotel">
                                    <img src="image/imgHotel/558555145.jpg" alt="hotel 2" class="image-hotel">
                                </div>
                                <p class="content">Siamese Serenity Stays อยู่ในพัทยากลาง ห่างจากสนามกอล์ฟบางพระ อินเตอร์เนชั่นแนล 40 กม. มอบบริการที่พักพร้อมลานระเบียง ที่จอดรถส่วนตัวฟรี และบาร์ โรงแรม 3 ดาวนี้มีโต๊ะบริการทัวร์ ที่พักนี้ให้บริการแผนกต้อนรับตลอด 24 ชั่วโมง บริการรับส่งสนามบิน รูมเซอร์วิส และอินเทอร์เน็ตไร้สาย (WiFi) ฟรีทั่วบริเวณที่พัก ที่ Siamese Serenity Stays ภายในห้องพักมีโต๊ะ ทุกยูนิตที่ที่พักนี้ มีโทรทัศน์จอแบนและเครื่องปรับอากาศ รวมถึงห้องน้ำแบบส่วนตัวซึ่งมีฝักบัวและเครื่องใช้ในห้องน้ำฟรี และบางห้องพักมีระเบียง ที่ Siamese Serenity Stays ห้องพักมีชุดเครื่องนอนและผ้าเช็ดตัว อีสเทิร์นสตาร์ กอล์ฟคอร์ส อยู่ห่างจาก Siamese Serenity Stays 43 กม. ส่วนคริสตัล เบย์ กอล์ฟคลับ อยู่ห่างออกไป 44 กม. สนามบินที่ใกล้ที่สุดคือสนามบินนานาชาติอู่ตะเภา ระยอง-พัทยา ซึ่งห่างจากที่พักนี้ 43 กม.</p>
                            </div>
                        </div>

                        <footer class="box-button-hotel-1">
                            <?php echo $Hotel_booking_button_1; ?>
                        </footer>

                        <footer class="box-button-hotel-2">
                            <?php echo $Hotel_booking_button_2; ?>
                        </footer>

                        <div class="hotel-3">
                            <div class="header-hotel">
                                <h2>Flipper House Hotel - SHA Extra Plus</h2>
                            </div>
                            <div class="section-content-hotel">
                                <div class="box-image-hotel">
                                    <img src="image/imgHotel/280207824.jpg" alt="hotel 3" class="image-hotel">
                                </div>
                                <p class="content">ท่านมีสิทธิ์รับส่วนลด Genius ที่ Flipper House Hotel - SHA Extra Plus เพียงเข้าสู่ระบบเพื่อประหยัดเมื่อจองที่พักนี้
Flipper House Hotel ตั้งอยู่ห่างจากทิฟฟานีโชว์ อัลคาซาร์โชว์ และพิพิธภัณฑ์ริบลีส์ เชื่อหรือไม่! 5 นาทีเมื่อเดินทางโดยรถยนต์ โรงแรมแห่งนี้มีสระว่ายน้ำกลางแจ้ง 2 สระ ที่จอดรถส่วนตัวฟรี แผนกต้อนรับ 24 ชั่วโมงเพื่ออำนวยความสะดวกยิ่งขึ้น และบริการอินเทอร์เน็ตไร้สาย (WiFi) ฟรีทั่วบริเวณ
</p>
                            </div>
                        </div>

                        <div class="hotel-4">
                            <div class="header-hotel">
                                <h2>SN Plus Hotel - SHA Plus</h2>
                            </div>
                            <div class="section-content-hotel">
                                <div class="box-image-hotel">
                                    <img src="image/imgHotel/81436404.jpg" alt="hotel 4" class="image-hotel">
                                </div>
                                <p class="content">ท่านมีสิทธิ์รับส่วนลด Genius ที่ SN Plus Hotel - SHA Plus เพียงเข้าสู่ระบบเพื่อประหยัดเมื่อจองที่พักนี้
SN Plus Hotel ตั้งอยู่ใกล้ถนนพัทยาเหนือ อยู่ห่างจากชายหาดเพียง 5 นาทีโดยการเดินทางด้วยรถยนต์และมีสระว่ายน้ำ ห้องพักทันสมัยตกแต่งสไตล์คลาสสิก มีเครื่องปรับอากาศและอินเทอร์เน็ตไร้สาย (WiFi) ฟรี

โรงแรมนี้อยู่ห่างจากสนามไดร์ฟกอล์ฟพัทยา 100 ม. และอยู่ห่างจากศูนย์การค้าเซ็นทรัลเฟสติวัลพัทยาโดยใช้เวลาเดินทางโดยรถยนต์เพียงครู่เดียว ที่พักนี้อยู่ห่างจากอาร์ตอินพาราไดซ์ 800 ม. และอยู่ห่างจากสนามบินนานาชาติสุวรรณภูมิ 84 กม.</p>
                            </div>
                        </div>

                        <footer class="box-button-hotel-3">
                            <?php echo $Hotel_booking_button_3; ?>
                        </footer>

                        <footer class="box-button-hotel-4">
                            <?php echo $Hotel_booking_button_4; ?>
                        </footer>
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

    <script src="js/system-hotel.js"></script>
</body>
</html>