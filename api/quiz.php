
<?php
    if($_SERVER["REQUEST_METHOD"] === "GET"){
        
        $alternatives = [];
        
        function get_image_randomly(){ // hämtar en random hund
            $dir_path = "../images/";
            $files = scandir($dir_path);
            if($files === false){ // blir false om något går fel därför har jag en if-sats för att see att de går bra
                header("Content-Type: application/json");
                http_response_code(500);
                echo json_encode([
                    "message" => "Data is lost (internal server error)"
                ]);
                exit();
            }
            $count = count($files);
            $index = rand(2, ($count - 1)); // annars kan det bli .. det måste vara rand(2, ...) 
            $image = $files[$index];
            return $image;
        }

        function RemoveSpecialChar($str){
            $res = str_ireplace( "_", ' ', $str);
            return $res;
        }

        function make_alternatives() { // Denna gör så att alla alternativen blir random skapade och det inte finns två eller flera av samma (algoritmen är inte klar än)

            $dir_path = "../images/";
            $alternatives = [];
            $count = 0;
            while($count < 4) {
                $image_random_dog = get_image_randomly();
                $random_dog_not_done = basename($dir_path . $image_random_dog, ".jpg");
                $random_dog_done = RemoveSpecialChar($random_dog_not_done);
                
                if(in_array($random_dog_done, $alternatives, true) !== true){ // in_array() kollar att just den hunden inte är med i arrayen så att det inte blir fler av samma
                    $alternatives[] = $random_dog_done;
                    $count++;
                }
                
            }
            return $alternatives;
        }

        function make_return_alternatives($alternatives){ // gör så att alla alternativen skickas i rätt format
            $right_dog = $alternatives[rand(0,3)];
            for ($i = 0; $i < count($alternatives); $i++) { 
                if($alternatives[$i] === $right_dog){
                    $alternatives[$i] = [
                        "correct" => true,
                        "name" => $alternatives[$i],
                    ];
                }
                else{
                    $alternatives[$i] = [
                        "correct" => false,
                        "name" => $alternatives[$i],
                    ];
                }
            }
            return $alternatives;
        }

        function get_image($alternatives){ // gör så att imagen som ska skickas kommer i rätt format
            $image_name = "";
            $dir_path = "../images/";
            function ReverseRemoveSpecialChar($str){
                $res = str_ireplace( " ", '_', $str);
                return $res;
            }
            foreach($alternatives as $user){
                if($user["correct"] === true){
                    $the_user = $user["name"];
                    $image_name = ReverseRemoveSpecialChar($the_user);
                    $image_name = $dir_path . $image_name . ".jpg";
                    break;
                }
            }
            return $image_name;
        }

        $alternatives = make_return_alternatives(make_alternatives());
        header("Content-Type: application/json");
        echo json_encode([
            "image" => get_image($alternatives),
            "alternatives" => $alternatives,
        ]);
       
        exit();
    }
    else { // Om det inte var en Get 
        header("Content-Type: application/json");
        http_response_code(400);
        echo json_encode([
            "message" => "Wrong HTTP request method, only execept GET (quiz.php)"
        ]);
    }
?>    