<?php 
include('ClassConexao.php');
class ClassCidades extends ClassConect{
    public function getCidades($idEstado){
        $cidades = $this->conectaDB()->PREPARE('SELECT* FROM uf_cidades where id = ?');
        $cidades->bindValue(1,$idEstado);
        $cidades-> execute();
        return $fCidades = $cidades->fetchAll(\PDO::FETCH_OBJ);
    }
}



?>