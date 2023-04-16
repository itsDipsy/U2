<?php
    require_once("./functions.php");
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        $value_from_post_request = json_decode(file_get_contents("php://input"), true);
        
        $filename = "../database/user_database.json";
        if(file_exists($filename) === true){

            $old_data_in_file_as_php_arr = json_decode(file_get_contents($filename), true);
            
            foreach($old_data_in_file_as_php_arr as $user){
                if($value_from_post_request["username"] === $user["username"] && $value_from_post_request["password"] === $user["password"] ){
                    sendResponse(200, [
                        "username" => $value_from_post_request["username"],
                        "points" => $user["points"],
                    ]);
                    exit(); // exit because we found the user
                }
            }

            // Detta blir felet om det inte finns en användare
            sendResponse(404, ["message" => "There are no users by those credentials"]);
            exit();
        }
        else{
            sendResponse(500, ["message" => "There are no users"]);
            exit();
        }   
    }
    else{

        sendResponse(400, ["message" => "Wrong HTTP request method, only execept POST (login.php)"]);
        exit();
    }
?>