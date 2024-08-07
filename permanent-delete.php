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
            if (!empty($_GET['p'])){
                if ($_GET['p'] == 'PermanentDelete'){

                    $userJson = readJson('data\user-hotel.json');
                    $Total_Users = json_decode($userJson, true); 
                    $date = date("Y/m/d"); 

                    if ($_SESSION['user']['status'] == 'ADMIN'){
                        
                        if(isset($_GET['ID'])){

                            $ID = (int)$_GET['ID'];

                            $Total_Users[$ID]['deleteAccount']['status'] = true;
                            $Total_Users[$ID]['deleteAccount']['date'] = $date;
                            $Total_Users[$ID]['deleteAccount']['left'] = "0";
                            $userJson = json_encode($Total_Users, JSON_PRETTY_PRINT);
                            writeJason('data\user-hotel.json', $userJson);
                            header('Location: system-user.php?s=edit&ID=' .  $ID);
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