<?php 
    
    session_start();

    function readJson($src){
        $jsonContent = file_get_contents($src);
        return $jsonContent;
    }

    function writeJason($src, $jsonData){
        file_put_contents($src, $jsonData);
    }

    if (isset($_SESSION['email']) && isset($_SESSION['password'])){
        if ($_SESSION['LoginSuccessful']){
            if (!empty($_GET['c'])){
                if ($_GET['c'] == 'cancelDeleteAccount'){

                    $userJson = readJson('data\user-hotel.json');
                    $Total_Users = json_decode($userJson, true); 

                    if ($_SESSION['user']['status'] == 'ADMIN'){
                        
                        if(isset($_GET['ID'])){

                            $ID = (int)$_GET['ID'];

                            $Total_Users[$ID]['deleteAccount']['status'] = false;
                            $Total_Users[$ID]['deleteAccount']['date'] = "";
                            $Total_Users[$ID]['deleteAccount']['left'] = "15";
                            $userJson = json_encode($Total_Users, JSON_PRETTY_PRINT);
                            writeJason('data\user-hotel.json', $userJson);
                            header('Location: system-user.php?s=edit&ID=' .  $ID);
                            exit(0);
                        }

                    } else {

                        $_SESSION['user']['deleteAccount']['status'] = false;
                        $_SESSION['user']['deleteAccount']['date'] = "";
                        $_SESSION['user']['deleteAccount']['left'] = "15";
                        $Total_Users[$_SESSION['user']['ID']] = $_SESSION['user'];
                        $userJson = json_encode($Total_Users, JSON_PRETTY_PRINT);
                        writeJason('data\user-hotel.json', $userJson);
                        header('Location: system-profile.php');
                        exit(0);
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