<?php

require_once ("connection.php");

class Items {

    private $connection;
    private $parameters;
    private $parametersTypes;
    private $query;

    function __construct()  {

        $this->connection = new Connection();
        $this->parameters = [];
        $this->parametersTypes = "";
        $this->query = "";

    }

    function create($userId, $name, $price, $quantity) {

        $this->parameters = [$name, $price, $quantity, $userId];

        $this->query = "INSERT INTO items (name, price, quantity, userId) 
                        VALUES (?, ?, ?, ?)";

        $this->parametersTypes = "ssss";

        return $this->connection->sendQuery($this->query, $this->parametersTypes, $this->parameters);
        
    }

    function update($itemId, $name, $price, $quantity) {

        $this->parameters = [$name, $price, $quantity, $itemId];

        $this->query = "UPDATE items SET name = ?, price = ?, quantity = ? WHERE id = ?";

        $this->parametersTypes = "sssi";

        return $this->connection->sendQuery($this->query, $this->parametersTypes, $this->parameters);
        
    }

    function updateQuantity($itemId, $quantity) {

        $this->parameters = [$quantity, $itemId];

        $this->query = "UPDATE items SET quantity = ? WHERE id = ?";

        $this->parametersTypes = "si";

        return $this->connection->sendQuery($this->query, $this->parametersTypes, $this->parameters);
        
    }

    function delete($itemId) {

        $this->parameters = [$itemId];

        $this->query = "DELETE FROM items WHERE id = ?";

        $this->parametersTypes = "i";

        return $this->connection->sendQuery($this->query, $this->parametersTypes, $this->parameters);
        
    }

    function list() {

        $this->query = "SELECT * FROM items ORDER BY createdAt";

        return $this->connection->queryAllData($this->query);
        
    }

    function listByUser($userId) {

        $this->parameters = [$userId];

        $this->query = "SELECT * FROM items WHERE userId = ? ORDER BY createdAt";

        $this->parametersTypes = "i";

        return $this->connection->querySpecificData($this->query, $this->parametersTypes, $this->parameters);
        
    }

    function singleItem($id) {

        $this->parameters = [ $id ];

        $this->query = "SELECT * FROM items WHERE id = ? ORDER BY createdAt";

        $this->parametersTypes = "i";

        return $this->connection->querySpecificData($this->query, $this->parametersTypes, $this->parameters);
        
    }



}

class Cart {

    private $connection;
    private $parameters;
    private $parametersTypes;
    private $query;

    private $cart;

    function __construct()  {

        $this->connection = new Connection();
        $this->parameters = [];
        $this->parametersTypes = "";
        $this->query = "";

    }

    function update($userId, $itemId, $price, $quantity) {

        $this->getPending($userId);

        $cartId = $this->cart['id'];
        
        if (!$this->checkQuantity($itemId, $quantity)) {
            return null;
        }

        $oldItems    = explode(',', $this->cart['itemsId']);
        $oldQuantity = explode(',', $this->cart['quantities']);
        $oldPrices   = explode(',', $this->cart['prices']);

        if (in_array($itemId, $oldItems)) {

            $index = array_search($itemId, $oldItems);
            $oldItems[$index] = $itemId;
            $oldQuantity[$index] = $oldQuantity[$index] + $quantity;//Increases What was in th cart with what we are adding now to the database
            $oldPrices[$index] = $price;
            $itemId = implode(',', $oldItems);
            $quantity = implode(',', $oldQuantity);
            $price = implode(',', $oldPrices);

        } else {
            $itemId = implode(',', $oldItems).",".$itemId;
            $quantity = implode(',', $oldQuantity).",".$quantity;
            $price = implode(',', $oldPrices).",".$price;
        }

        $this->parameters = [$itemId, $quantity, $price, $cartId];

        $this->query = "UPDATE cart SET itemsId = ?, quantities = ?, prices = ? WHERE id = ?";

        $this->parametersTypes = "sssi";

        return $this->connection->sendQuery($this->query, $this->parametersTypes, $this->parameters);
        
    }

    function removeItem($userId, $itemId) {

      
        $this->getPending($userId);

        $cartId = $this->cart['id'];

        $oldItems    = explode(',', $this->cart['itemsId']);
        $oldQuantity = explode(',', $this->cart['quantities']);
        $oldPrices   = explode(',', $this->cart['prices']);

        if (in_array($itemId, $oldItems)) {

            $index = array_search($itemId, $oldItems);

            unset($oldItems[$index]);
            unset($oldQuantity[$index]);
            unset($oldPrices[$index]);

            $itemId = implode(',', $oldItems);
            $quantity = implode(',', $oldQuantity);
            $price = implode(',', $oldPrices);

        } else {
            $itemId = implode(',', $oldItems);
            $quantity = implode(',', $oldQuantity);
            $price = implode(',', $oldPrices);
        }

        $this->parameters = [$itemId, $quantity, $price, $cartId];

        $this->query = "UPDATE cart SET itemsId = ?, quantities = ?, prices = ? WHERE id = ?";

        $this->parametersTypes = "sssi";

        return $this->connection->sendQuery($this->query, $this->parametersTypes, $this->parameters);
        
    }

    function checkQuantity($itemId, $quantity) {

        $itemModel = new Items();

        $item = $itemModel->singleItem($itemId);

        $quantityInStock = $item[0]["quantity"];

        if (intval($quantityInStock) < intval($quantity)) {
            return false;
        } else {
            return true;
        }

    }

    function getPending($userId) {

        $this->parameters = [$userId];

        $this->query = "SELECT itemsId, quantities, prices, id, status FROM cart WHERE userId = ? ORDER BY id DESC LIMIT 1";

        $this->parametersTypes = "i";

        $this->cart = $this->connection->querySpecificData($this->query, $this->parametersTypes, $this->parameters);

        if ($this->cart && ($this->cart[0]['status'] !== 'checkout')) {

            $this->cart = $this->cart[0];
            
        }else {

            $this->createEmptyCart($userId);

            $this->getPending($userId);

        }

    }

    function createEmptyCart($userId) {

        $this->parameters = ["", $userId, "", ""];

        $this->query = "INSERT INTO cart (itemsId, userId, quantities, prices) 
                        VALUES (?, ?, ?, ?)";

        $this->parametersTypes = "siss";

        return $this->connection->sendQuery($this->query, $this->parametersTypes, $this->parameters);
    }

    function checkout($userId) {

        $this->getPending($userId);

        if (strlen($this->cart['itemsId']) <= 1) {
            return null;
        }

        $items      = explode(',', $this->cart['itemsId']);
        $quantities = explode(',', $this->cart['quantities']);

        foreach ($items as $index => $item) {

            $itemModel = new Items();

            $oldItem = $itemModel->singleItem($item);

            $oldQuantity = $oldItem[0]["quantity"];

            $newQuantity = floatval($oldQuantity) - floatval($quantities[$index]);

            $itemModel->updateQuantity($item, $newQuantity);

        }

        $this->parameters = ["checkout", $this->cart['id']];

        $this->query = "UPDATE cart SET status = ? WHERE id = ?";

        $this->parametersTypes = "si";

        return $this->connection->sendQuery($this->query, $this->parametersTypes, $this->parameters);
        
    }
}
























?>