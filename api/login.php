<?php
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        $value_from_post_request = json_decode(file_get_contents("php://input"), true);
        
        $filename = "../database/user_database.json";
        if(file_exists($filename) === true){
            $old_data_in_file_as_php_arr = json_decode(file_get_contents($filename), true);
            foreach($old_data_in_file_as_php_arr as $user){
                if($value_from_post_request["username"] === $user["username"] && $value_from_post_request["password"] === $user["password"]){
                    http_response_code(200);
                    $response_json = json_encode([
                        "username" => $value_from_post_request["username"],
                        "password" => $value_from_post_request["password"],
                        "points" => $user["points"],
                    ]);
                    header("Content-Type: application/json");
                    echo $response_json;
                    exit(); // exit because we found the user
                }
            }

            // Detta blir felet om det inte finns en användare
            header("Content-Type: application/json");
            http_response_code(400); // if we could not find the user
            echo json_encode(["message" => "There are no users by those credentials"]);
            exit();
        }
        else{
            header("Content-Type: application/json");
            http_response_code(400); 
            echo json_encode(["message" => "There are no users"]);
            exit();
        }   
    }
    else{
        header("Content-Type: application/json");
        echo json_encode([
            "message" => "Wrong HTTP request method, only execept POST (login.php)"
        ]);
    }
?>