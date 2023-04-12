
<?php
    require_once("./functions.php");
    if($_SERVER["REQUEST_METHOD"] === "POST"){

        $user_filename = "../database/user_database.json";
        if(file_exists($user_filename)){
            $user_php_format = json_decode(file_get_contents($user_filename), true); 

            $value_from_post_request = json_decode(file_get_contents("php://input"), true);
           
            for ($i=0; $i < count($user_php_format); $i++) {
                
                if($user_php_format[$i]["username"] === $value_from_post_request["username"]) {
                    $user_php_format[$i]["points"] = $user_php_format[$i]["points"] + $value_from_post_request["points"];
                    file_put_contents($user_filename, json_encode($user_php_format, JSON_PRETTY_PRINT));
                    sendResponse(200, [
                        "username" => $user_php_format[$i]["username"],
                        "points" => $user_php_format[$i]["points"],
                    ]);
                    exit();
                }
            }   
        }
        else{
            sendResponse(500,["message" => "Internal Server Error"]);
            exit();
        }
        

    }

    elseif($_SERVER["REQUEST_METHOD"] === "GET"){

        $user_filename = "../database/user_database.json";
        if(file_exists($user_filename)){
            $user_php_format = json_decode(file_get_contents($user_filename), true); 
        }
        else{
            sendResponse(500,["message" => "Internal Server Error"]);
            exit();
        }

        function comparison_callback($user1, $user2){
            if($user1["points"] > $user2["points"]){
                return -1;
            }
            else{
                return 1;
            }
        }
        
        usort($user_php_format, "comparison_callback");
        file_put_contents($user_filename, json_encode($user_php_format, JSON_PRETTY_PRINT));
        $four_highest_users = array_splice($user_php_format, 0, 4);


       sendResponse(200, $four_highest_users);
    }
    else{
       sendResponse(400, ["message" => "Wrong HTTP request method, only execept GET and POST (points.php)"]);
    }
?>
