<?php 
    header("Access-control-Allow-Origin: *");
    header("Content-Type: Application/json");
    header("charset=UTF-8");
    require_once("connection.php");
    require_once("query.php");
    require_once("response.php");

    $connection = new Connection();
    $items = new Items();
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

            echo json_encode($response->error("Add to cart Failed"));

        }
    }

    if ($_POST["action"] == "checkout-cart") {

        $userId = $_POST['userId'];

        if ($cart->checkout($userId)) {

            echo json_encode($response->success("Cart Checkout Successfully"));

            die();
            
        };

        echo json_encode($response->error("Cart Unable to Checkout"));

    }

    if ($_POST["action"] == "remove-cart-item") {

        $itemId     = trim($_POST['itemId']);
        $userId       = trim($_POST['userId']);
        
        if (!empty($itemId) ) {

            if ($cart->removeItem($userId, $itemId)) {

                echo json_encode($response->success("Item Updated Successfully"));

                die();
                
            };

            echo json_encode($response->error("Item Unable to update"));

        }
    }

    if ($_POST["action"] == "cancel-cart") {
        
        $itemId = trim($_POST['itemId']);

        if (!empty($itemId) ) {

            if ($items->delete($itemId)) {

                echo json_encode($response->success("Item Delete Successfully"));

                die();
                
            };

            echo json_encode($response->error("Item Unable to update"));

        }
    }

    if ($_POST["action"] == "list-user-cart" && $_POST['userId']) {

        $userId = $_POST["userId"];

        $data = $items->listByUser($userId);

        if ($data) {

            echo json_encode($data);

            die();
            
        };

        echo json_encode($response->error("Item Unable to update"));

    }

    if ($_POST["action"] == "single-item" ) {

        $itemId = $_POST["itemId"];

        $data = $items->singleItem($itemId);

        if ($data) {

            echo json_encode($data);

            die();
            
        };

        echo json_encode($response->error("Item Unable to update"));

    }



    if ($_POST["action"] == "list-user-cart" && $_POST['user'] == "admin") {

        $data = $items->list();

        if ($data) {

            echo json_encode($data);

            die();
            
        };

        echo json_encode($response->error("Item Unable to update"));

    }