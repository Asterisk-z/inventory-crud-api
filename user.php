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
        
        $userId     = trim($_POST['userId']);
        $name       = trim($_POST['name']);
        $price      = trim($_POST['price']);
        $quantity   = trim($_POST['quantity']);

        if (!empty($name) && !empty($price)  && !empty($quantity) ) {

            if ($query->create($userId, $name, $price, $quantity)) {

                echo json_encode($response->success("Item Added Successfully"));
                die();
                
            };

            echo json_encode($response->error("Item Added Successfully"));

        }
    }

    if ($_POST["action"] == "update-item") {
        
        $itemId     = trim($_POST['itemId']);
        $name       = trim($_POST['name']);
        $price      = trim($_POST['price']);
        $quantity   = trim($_POST['quantity']);

        if (!empty($name) && !empty($price)  && !empty($quantity) && !empty($itemId) ) {

            if ($query->update($itemId, $name, $price, $quantity)) {

                echo json_encode($response->success("Item Updated Successfully"));

                die();
                
            };

            echo json_encode($response->error("Item Unable to update"));

        }
    }

    if ($_POST["action"] == "delete-item") {
        
        $itemId = trim($_POST['itemId']);

        if (!empty($itemId) ) {

            if ($query->delete($itemId)) {

                echo json_encode($response->success("Item Delete Successfully"));

                die();
                
            };

            echo json_encode($response->error("Item Unable to update"));

        }
    }

    if ($_POST["action"] == "list-items" && $_POST['user'] == "admin") {

        $data = $query->list();

        if ($data) {

            echo json_encode($data);

            die();
            
        };

        echo json_encode($response->error("Item Unable to update"));

    }

    if ($_POST["action"] == "list-item" && $_POST['userId']) {

        $userId = $_POST["userId"];

        $data = $query->listByUser($userId);

        if ($data) {

            echo json_encode($data);

            die();
            
        };

        echo json_encode($response->error("Item Unable to update"));

    }

    if ($_POST["action"] == "single-item" ) {

        $itemId = $_POST["itemId"];

        $data = $query->singleItem($itemId);

        if ($data) {

            echo json_encode($data);

            die();
            
        };

        echo json_encode($response->error("Item Unable to update"));

    }
