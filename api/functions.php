<?php
    function sendResponse($status_code, $response_data){
        header("Content-Type: application/json");
        http_response_code($status_code);
        echo json_encode($response_data);
    }

?>