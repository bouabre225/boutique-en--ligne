<?php

class Database
{
    private $host = "localhost";
    private $dbname = "vente";
    private $username = "root";
    private $password = "";
    private $pdo;

    public function __construct ()
    {
        try 
        {
            // Connexion à la base de données
            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e)
        {
            die("Erreur de connexion: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->pdo;
    }
} 

?>