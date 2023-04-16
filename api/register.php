<?php

    require_once("./functions.php");

    if($_SERVER["REQUEST_METHOD"] === "POST"){ // Kollar att det är en POST request
        
        $value_from_post_request = json_decode(file_get_contents("php://input"), true);
        
        if($value_from_post_request["username"] === "" && $value_from_post_request["password"] === ""){
            sendResponse(406, ["message" => "Invalid credentials, need username or password"]);
            exit();
        }
        
        if($value_from_post_request["username"] === "" || $value_from_post_request["password"] === ""){
            sendResponse(406, ["message" => "Invalid credentials, need username or password"]);
            exit();
        }

        $new_data = [];
        $filename = "../database/user_database.json";
        if(file_exists($filename) === true){ // Kollar att filen finns

            $old_data_in_file = file_get_contents($filename);
            $new_data = json_decode($old_data_in_file, true);
            
            
            $new_user_data = [
                "username" => $value_from_post_request["username"],
                "password" => $value_from_post_request["password"],
                "points" => 0,
            ];

            function user_check($user){
                
                $filename = "../database/user_database.json";
                $old_data_in_file = file_get_contents($filename);
                $new_data = json_decode($old_data_in_file, true);

                foreach($new_data as $a_user){
                    if($a_user["username"] === $user["username"] && $a_user["password"] === $user["password"]){
                        return true;
                    }
                }
                return false;
            }
            
            if(user_check($new_user_data) === false){
                $new_data[] = $new_user_data;
                $new_data_json = json_encode($new_data, JSON_PRETTY_PRINT);
    
                $response_data_json = $new_user_data;
    
                file_put_contents($filename, $new_data_json);
    
                sendResponse(200, $response_data_json);
            }
            else{
               sendResponse(406, ["message" => "Sorry username already taken"]);
            }
            
        }
        else{ // Om inte filen finns så skapas det än med objektet som innehåller data from POST requestet

            $new_user_data = [
                "username" => $value_from_post_request["username"],
                "password" => $value_from_post_request["password"],
                "points" => 0,
            ];
            $new_data[] = $new_user_data;
            file_put_contents($filename, json_encode($new_data, JSON_PRETTY_PRINT));

           sendResponse(200, [
            "username" => $value_from_post_request["username"],
            "points" => 0,
           ]);
        }
        
    }
    else{ // Detta blir om det inte är en POST request
        sendResponse(400, ["message" => "Wrong HTTP request, only except POST (in register.php)"]);
    }

?>