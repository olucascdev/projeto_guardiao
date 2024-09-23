<?php
header('Content-Type: application/json');
include_once '../Controller/conexao.php';

if (isset($_GET['id_estado'])) {
    $uf_id = $_GET['id_estado'];

    // Log para verificar se o ID foi recebido
    error_log("ID do estado recebido: " . $uf_id); 

    $stmt = $conn->prepare("SELECT * FROM cidades WHERE id_estado = ?");
    $stmt->bind_param("i", $uf_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $cidades = [];
        while ($row = $result->fetch_object()) {
            $cidades[] = $row;
        }

        echo json_encode($cidades);
    } else {
        echo json_encode(['status' => 'erro', 'message' => 'Nenhuma cidade encontrada']);
    }
} else {
    // Se o ID não foi enviado
    echo json_encode(['status' => 'erro', 'message' => 'ID do estado não enviado']);
}
?>
