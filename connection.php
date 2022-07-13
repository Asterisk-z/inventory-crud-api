<?php 

class Connection
{
    private $host = "localhost";
    private $username = "general";
    private $password = "1234567890";
    private $database = "test";
    private $connection;
    
    public function __construct()
    {
        $this->connection = $this->connect();
    }
    
    public function connect()
    {
        $connection = mysqli_connect($this->host, $this->username, $this->password, $this->database);

        return $connection;
    }
        
    function sendQuery($query, $parameterType, $parameters) {

        $sql = $this->connection->prepare($query);

        $this->bindData($sql, $parameterType, $parameters);

        return $sql->execute();

    }
        
    function bindData($sql, $parameterType, $parameters) {

        $values[] = &$parameterType;

        for($i = 0; $i < count($parameters); $i++) {

            $values[] = & $parameters[$i];
            
        }
        
        $sql->bind_param(...$values);
        
    }
    
    function queryAllData($query) {

        $response = $this->connection->query($query);  

        if ($response->num_rows > 0) {

            while($row = $response->fetch_assoc()) {

                $results[] = $row;

            }

        }

        return $results;
    }
    
    
    
    function querySpecificData($query, $parameterType, $parameters) {

        $sql = $this->connection->prepare($query);

        $this->bindData($sql, $parameterType, $parameters);

        $sql->execute();

        $response = $sql->get_result();
        
        if ($response->num_rows > 0) {

            while($row = $response->fetch_assoc()) {

                $list[] = $row;

            }

        }
        
        if(!empty($list)) {

            return $list;

        }

    }

}


?>