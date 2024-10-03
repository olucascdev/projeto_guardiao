<?php 
session_start(); // Inicia a sessão
include_once 'Controller/conexao.php';

// Define quantos colaboradores vinculados por página
$por_pagina = 3; // Ajuste conforme necessário
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina - 1) * $por_pagina;

// Para armazenar colaboradores vinculados
if (!isset($_SESSION['vinculados'])) {
    $_SESSION['vinculados'] = [];
}

// Verifica se houve uma inserção
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['colaborador_id'])) {
        $colaborador_id = filter_input(INPUT_POST, 'colaborador_id', FILTER_SANITIZE_NUMBER_INT);
        
        // Verifica se o ID do colaborador é válido
        if ($colaborador_id) {
            // Obtém detalhes do colaborador
            $col_query = "SELECT nome, tel_movel, email FROM colaboradores WHERE id = $colaborador_id";
            $col_result = mysqli_query($conn1, $col_query);
            
            if ($colaborador = mysqli_fetch_assoc($col_result)) { // Verifica se retornou algum resultado
                
                // Verifica se o colaborador já está vinculado
                $is_vinculado = false;
                foreach ($_SESSION['vinculados'] as $vinculado) {
                    if ($vinculado['id'] == $colaborador_id) {
                        $is_vinculado = true;
                        break;
                    }
                }

                if (!$is_vinculado) {
                    // Insere os dados na tabela 'avaliacao_estabelecimento_colaborador'
                    $insert_query = "INSERT INTO avaliacao_estabelecimento_colaborador (colaborador_id, colaborador_telmovel, colaborador_email) 
                                     VALUES ('$colaborador_id', '{$colaborador['tel_movel']}', '{$colaborador['email']}')";
                    mysqli_query($conn, $insert_query);

                    // Adiciona à lista de vinculados na sessão
                    $_SESSION['vinculados'][] = [
                        'id' => $colaborador_id,
                        'nome' => $colaborador['nome'],
                        'tel_movel' => $colaborador['tel_movel'],
                        'email' => $colaborador['email']
                    ];
                } else {
                    // Se o colaborador já está vinculado, você pode definir uma mensagem de erro
                    echo "<script>alert('Colaborador já está vinculado.');</script>";
                }
            } else {
                // Se não encontrou o colaborador, você pode definir uma mensagem de erro
                echo "<script>alert('Colaborador não encontrado.');</script>";
            }
        } else {
            // Se o ID não é válido, você pode definir uma mensagem de erro
            echo "<script>alert('Selecione um colaborador válido.');</script>";
        }
    }

    // Verifica se houve uma exclusão
    if (isset($_POST['excluir_id'])) {
        $excluir_id = filter_input(INPUT_POST, 'excluir_id', FILTER_SANITIZE_NUMBER_INT);

        // Remove o colaborador da tabela 'avaliacao_estabelecimento_colaborador'
        $delete_query = "DELETE FROM avaliacao_estabelecimento_colaborador WHERE colaborador_id = $excluir_id";
        mysqli_query($conn, $delete_query);

        // Remove o colaborador da lista de vinculados
        foreach ($_SESSION['vinculados'] as $key => $vinculado) {
            if ($vinculado['id'] == $excluir_id) {
                unset($_SESSION['vinculados'][$key]);
                break;
            }
        }
    }
}

// Armazena os colaboradores vinculados
$vinculados = $_SESSION['vinculados'];

// Paginação dos colaboradores vinculados
$total_vinculados = count($vinculados);
$total_paginas = ceil($total_vinculados / $por_pagina);
$vinculados_pagina = array_slice($vinculados, $offset, $por_pagina);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="styles/colaborador.css">
    <title>Colaboradores</title>
</head>
<body>
    <div class="container">
        <h3>Vincular Colaborador no Questionário</h3>
        <form method="POST" action="">
            <table class="table table-light table-bordered table-striped table-hover m-5" border="1" cellspacing="0" cellpadding="10">
                <thead>
                    <tr>
                        <th>Colaborador</th>
                        <th width="12%">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select class="form-select" name="colaborador_id" id="nome_colaborador">
                                <option value="">Selecione o Colaborador</option>
                                <?php 
                                // Consulta para obter colaboradores
                                $query = "SELECT id, nome FROM colaboradores"; 
                                $result = mysqli_query($conn1, $query);
                                
                                // Verifica se houve resultados na consulta
                                if(mysqli_num_rows($result) > 0) {
                                    // Itera sobre os colaboradores retornados e preenche o select
                                    while($row = mysqli_fetch_assoc($result)) {
                                        echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>Nenhum colaborador encontrado</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <button class="btn btn-success w-100" type="submit"><i class="bi bi-plus-circle"></i> Adicionar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>

        <h3>Colaboradores Vinculados ao Questionário</h3>
        <table class="table table-light table-bordered table-striped table-hover m-5" border="1" cellspacing="0" cellpadding="10">
            <thead>
                <tr>
                    <th>Colaborador</th>
                    <th width="12%">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Mostra os colaboradores vinculados na página atual
                foreach ($vinculados_pagina as $vinculado) {
                    echo "<tr>
                            <td>{$vinculado['nome']}</td>
                            <td>
                                <form method='POST' action=''>
                                    <input type='hidden' name='excluir_id' value='{$vinculado['id']}'>
                                    <button class='btn btn-danger w-100' type='submit'><i class='bi bi-trash3'></i> Excluir</button>
                                </form>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Paginação -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= ($pagina == 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $pagina - 1 ?>" aria-label="Anterior">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php 
                for ($i = 1; $i <= $total_paginas; $i++): 
                    $active = ($i == $pagina) ? 'active' : '';
                ?>
                    <li class="page-item <?= $active ?>">
                        <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= ($pagina == $total_paginas) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $pagina + 1 ?>" aria-label="Próximo">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
           
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
