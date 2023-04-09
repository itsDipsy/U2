<?php
    if($_SERVER["REQUEST_METHOD"] === "POST"){ // Kollar att det är en POST request
        $value_from_post_request = json_decode(file_get_contents("php://input"), true);
        
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
                    if($a_user["username"] === $user["username"]){
                        return true;
                    }
                }
                return false;
            }
            
            if(user_check($new_user_data) === false){
                $new_data[] = $new_user_data;
                $new_data_json = json_encode($new_data, JSON_PRETTY_PRINT);
    
                $response_data_json = json_encode($new_user_data);
    
                file_put_contents($filename, $new_data_json);
    
                header("Content-Type: application/json");
                http_response_code(200);
                echo $response_data_json;
            }
            else{
                header("Content-Type: application/json");
                http_response_code(400);
                echo json_encode(["message" => "Sorry username already taken"]);
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

            header("Content-Type: application/json");
            http_response_code(200);
            echo json_encode($new_user_data);
        }
        
    }
    else{ // Detta blir om det inte är en POST request
        header("Content-Type: application/json");
        http_response_code(400);
        echo json_encode(["message" => "Wrong HTTP request, only except POST (in register.php)"]);
    }

?>