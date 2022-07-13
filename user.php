<?php 
    header("Access-control-Allow-Origin: *");
    header("Content-Type: Application/json");
    header("charset=UTF-8");
    require_once("connection.php");
    require_once("auth.php");
    require_once("response.php");

    $connection = new Connection();
    $query = new Auth();
    $response = new Response();

    if ($_POST["action"] == "create-user") {
        
        $name    = trim($_POST['name']);
        $role    = trim($_POST['role']);
        $password = trim($_POST['password']);

        if (!empty($name)) {

            if ($query->create_user($name, $password, $role)) {

                echo json_encode($response->success("User Added Successfully"));
                die();
                
            };

            echo json_encode($response->error("User Failed to register"));

        }
    }
