
<?php
    if($_SERVER["REQUEST_METHOD"] === "POST"){

        $user_filename = "../database/user_database.json";
        $highscore_filename = "../database/higest_user_score_database.json";
        $user_php_format = json_decode(file_get_contents($user_filename), true); 

        $value_from_post_request = json_decode(file_get_contents("php://input"), true);

        for ($i=0; $i < count($user_php_format); $i++) {
            if($user_php_format[$i]["username"] === $value_from_post_request["username"] ) {
                $user_php_format[$i]["points"] = $user_php_format[$i]["points"] + $value_from_post_request["points"];
                file_put_contents($user_filename, json_encode($user_php_format, JSON_PRETTY_PRINT));
                header("Content-Type: application/json");
                echo json_encode($user_php_format[$i]);
                exit();
            }
        }   

    }

    elseif($_SERVER["REQUEST_METHOD"] === "GET"){

        $user_filename = "../database/user_database.json";
        $highscore_filename = "../database/higest_user_score_database.json";

        $user_php_format = json_decode(file_get_contents($user_filename), true); 

        $data_for_highscore = [];

        function comparison_callback($user1, $user2){
            if($user1["points"] > $user2["points"]){
                return 1;
            }
            else{
                return -1;
            }
        }
        
        usort($user_php_format, "comparison_callback");
        $last_index = (count($user_php_format) - 1);
        if(count($user_php_format) >= 4){
            for ($i = $last_index; $i > $last_index - 4; $i--) { 
                $data_for_highscore[] = 
                [
                    "username" => $user_php_format[$i]["username"],
                    "points" => $user_php_format[$i]["points"],
                ];
            }
        }
        else{
            for ($i = $last_index; $i >= 0; $i--) { 
                $data_for_highscore[] = 
                [
                    "username" => $user_php_format[$i]["username"],
                    "points" => $user_php_format[$i]["points"],
                ];
            }
        }
        file_put_contents($highscore_filename, json_encode($data_for_highscore, JSON_PRETTY_PRINT));
        header("Content-Type: application/json");
        echo json_encode($data_for_highscore);
    }
    else{
        header("Content-Type: application/json");
        echo json_encode([
            "message" => "Wrong HTTP request method, only execept GET and POST (points.php)"
        ]);
    }
?>
