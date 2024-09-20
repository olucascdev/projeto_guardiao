<?php 
include('ClassConexao.php');
class ClassEstados extends ClassConect{
    public function getEstados(){
        $estados = $this->conectaDB()->PREPARE('SELECT* FROM uf');
        $estados-> execute();
        return $fEstados = $estados->fetchAll(\PDO::FETCH_OBJ);
    }
}



?>