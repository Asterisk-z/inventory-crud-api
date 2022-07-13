<?php 

    require_once ("connection.php");
    require_once ("jwt.php");

    class Auth {

        private $connection;

        function __construct() {

            $this->connection = new Connection();

        }

        function create_user($name, $password, $role) {

            if (strlen($role) < 1) {

                $role = "user";

            }

            $token = md5($name.$password.time());

            $password = password_hash($password, PASSWORD_DEFAULT);

            $this->parameters = [$name, $password, $role, $token];

            $this->query = "INSERT INTO users (name, password, role, token) 
                            VALUES (?, ?, ?, ?)";

            $this->parametersTypes = "ssss";

            return $this->connection->sendQuery($this->query, $this->parametersTypes, $this->parameters);
 
        }

        function auth() {
            $iat = time();
            $exp = $iat + 60 * 5;
            $payload = [
                "iat" => $iat
            ];
        }






    }
















?>