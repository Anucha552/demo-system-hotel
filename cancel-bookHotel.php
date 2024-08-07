<?php 
    
    session_start();

    function readJson($src){
        $jsonContent = file_get_contents($src);
        return $jsonContent;
    }

    function writeJason($src, $jsonData){
        file_put_contents($src, $jsonData);
    }

    function check_hotel($hotel){

        $nameHotel = array('The Urban Condo in Central Pattaya','Siamese Serenity Stays','Flipper House Hotel - SHA Extra Plus','SN Plus Hotel - SHA Plus');
    
        for ($i = 0; $i < count($nameHotel); $i++){
            if ($nameHotel[$i] == $hotel){
                return true;
            }
        }

        return false;
    }

    if (isset($_SESSION['email']) && isset($_SESSION['password'])){
        if ($_SESSION['LoginSuccessful']){
            if (!empty($_GET['r'])){
                    
                $row = ((int)$_GET['r'] - 1);
                $hotel = $_GET['h'];

                if (check_hotel($hotel)){
                        
                    if ($_GET['f'] == 'systemHotelUser'){
                        
                        $userJson = readJson('data\user-hotel.json');
                        $Total_Users = json_decode($userJson, true);
                        array_splice($_SESSION['user']['bookHotel'], $row, true);
                        $Total_Users[$_SESSION['user']['ID']]['bookHotel'] = $_SESSION['user']['bookHotel'];
                        $userJson = json_encode($Total_Users, JSON_PRETTY_PRINT);
                        writeJason('data\user-hotel.json', $userJson);

                        header('Location: system-number-bookings.php');
                        exit(0);
                    } else if ($_GET['f'] == 'systemHotelAdmin'){
                        
                        $userJson = readJson('data\user-hotel.json');
                        $Total_Users = json_decode($userJson, true);
                        $hotel = $Total_Users[(int)$_GET['ID']]['bookHotel'];
                        array_splice($hotel, $row, true);
                        $Total_Users[(int)$_GET['ID']]['bookHotel'] = $hotel;
                        $userJson = json_encode($Total_Users, JSON_PRETTY_PRINT);
                        writeJason('data\user-hotel.json', $userJson);

                        if (count($hotel) == 0){
                            header('Location: system-hotel-booking-users.php');
                            exit(0);
                        } else {
                            header('Location: system-hotel-booking-users.php?s=viewBookings&ID=' . $_GET['ID']);
                            exit(0);
                        }
                        
                    }
                } else {
                    header('Location: system-profile.php');
                    exit(0);
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
        sexit(0);
    }
?>