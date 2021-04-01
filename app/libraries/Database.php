<?php
    class Database
    {
        private $servername = DB_HOST;
        private $username = DB_USER;
        private $password = DB_PASS;
        private $dbname = DB_NAME;

        private $statement;
        private $dbHandler;
        private $error;

        public function __construct()
        {
            try
            {
                $ds = 'mysql:host=' . $this->servername . ';dbname=' . $this->dbname;
                $options = array(
                    PDO::ATTR_PERSISTENT => true,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                );

                $this->dbHandler = new PDO($ds, $this->username, $this->password, $options);
            }
            catch(PDOException $e)
            {
                $this->error = $e->getMessage();
                echo "Connection failed: " . $this->error;
            }
        }

        //Write queries
        public function query($sql)
        {
            $this->statement = $this->dbHandler->prepare($sql);
        }

        //Bind values
        public function bind($parameter, $value, $type = null)
        {
            switch(is_null($type))
            {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;             
            }
            $this->statement->bindValue($parameter, $value, $type);
        }

        //Execute the prepared statement
        public function execute()
        {
            return $this->statement->execute();
        }

        //Return an array
        public function resultSet()
        {
            $this->execute();
            return $this->statement->fetchAll(PDO::FETCH_OBJ);
        }

        //Return a specific row as an object
        public function single()
        {
            $this->execute();
            return $this->statement->fetch(PDO::FETCH_OBJ);
        }

        //Get's the row count
        public function rowCount()
        {
            return $this->statement->rowCount();
        }
    }