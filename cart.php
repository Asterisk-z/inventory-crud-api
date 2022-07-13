<?php 
    header("Access-control-Allow-Origin: *");
    header("Content-Type: Application/json");
    header("charset=UTF-8");
    require_once("connection.php");
    require_once("query.php");
    require_once("response.php");

    $connection = new Connection();
    $query = new Items();
    $cart = new Cart();
    $response = new Response();

    if ($_POST["action"] == "add-to-cart") {
        
        $itemId     = trim($_POST['itemId']);
        $quantity   = trim($_POST['quantity']);
        $price       = trim($_POST['price']);
        $userId       = trim($_POST['userId']);

        if (!empty($itemId) && !empty($userId)  && !empty($price)  && !empty($quantity) ) {

            if ($cart->update($userId, $itemId, $price, $quantity)) {

                echo json_encode($response->success("Item Added To Cart"));

                die();
                
            };

            echo json_encode($response->error("Item Added Successfully"));

        }
    }

    if ($_POST["action"] == "checkout-cart") {

        $userId = $_POST['userId'];

        if ($cart->checkout($userId)) {

            echo json_encode($response->success("Item Updated Successfully"));

            die();
            
        };

        echo json_encode($response->error("Item Unable to update"));

    }

    if ($_POST["action"] == "remove-cart-item") {
        
        if (!empty($name) && !empty($price)  && !empty($quantity) && !empty($itemId) ) {

            if ($query->update($itemId, $name, $price, $quantity)) {

                echo json_encode($response->success("Item Updated Successfully"));

                die();
                
            };

            echo json_encode($response->error("Item Unable to update"));

        }
    }

    if ($_POST["action"] == "cancel-cart") {
        
        $itemId = trim($_POST['itemId']);

        if (!empty($itemId) ) {

            if ($query->delete($itemId)) {

                echo json_encode($response->success("Item Delete Successfully"));

                die();
                
            };

            echo json_encode($response->error("Item Unable to update"));

        }
    }

    if ($_POST["action"] == "list-user-cart" && $_POST['userId']) {

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



    if ($_POST["action"] == "list-user-cart" && $_POST['user'] == "admin") {

        $data = $query->list();

        if ($data) {

            echo json_encode($data);

            die();
            
        };

        echo json_encode($response->error("Item Unable to update"));

    }