<?php 

class ClassConect 
{
    public function conectaDB()
    {
        try {
            return $con=new \PDO("mysql:host=localhost;dbname=projeto_guardiao","root","");

        } catch (\PDOException $erro){
            return $erro->getMessage();
        }
    }
}




?>