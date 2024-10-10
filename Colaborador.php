<?php 
session_start(); // Inicia a sessão para gerenciar dados do usuário entre requisições.
include_once 'Controller/conexao.php'; // Inclui o arquivo de conexão com o banco de dados.

// Filtra e obtém o código da avaliação e o ID do estabelecimento da URL.
$codigo_avaliacao = filter_input(INPUT_GET, 'codigo', FILTER_SANITIZE_NUMBER_INT);
$id_estabelecimento = filter_input(INPUT_GET, 'estabelecimento', FILTER_SANITIZE_NUMBER_INT); // Adiciona o ID do estabelecimento

// Limpa a lista de colaboradores vinculados ao mudar a avaliação
if (!isset($_SESSION['vinculados'])) {
    $_SESSION['vinculados'] = []; // Inicializa a sessão 'vinculados' se não estiver definida
}

// Verifica se houve uma nova avaliação carregada
if (isset($_SESSION['codigo_atual']) && $_SESSION['codigo_atual'] != $codigo_avaliacao) {
    unset($_SESSION['vinculados']); // Limpa os colaboradores vinculados se a avaliação foi mudada
}

// Armazena o código da avaliação atual na sessão
$_SESSION['codigo_atual'] = $codigo_avaliacao; 

// Define quantos colaboradores vinculados serão exibidos por página
$por_pagina = 3; // Ajuste conforme necessário
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1; // Obtém o número da página atual ou define como 1
$offset = ($pagina - 1) * $por_pagina; // Calcula o deslocamento para a consulta

// Verifica se houve uma inserção de um novo colaborador
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['colaborador_id'])) {
        $colaborador_id = filter_input(INPUT_POST, 'colaborador_id', FILTER_SANITIZE_NUMBER_INT);
        
        // Verifica se o ID do colaborador é válido
        if ($colaborador_id) {
            // Obtém detalhes do colaborador a partir da tabela colaboradores
            $col_query = "SELECT nome, tel_movel, email FROM colaboradores WHERE id = $colaborador_id";
            $col_result = mysqli_query($conn1, $col_query);
            
            if ($colaborador = mysqli_fetch_assoc($col_result)) { // Verifica se retornou algum resultado
                // Verifica se o colaborador já está vinculado
                $is_vinculado = false;
                foreach ($_SESSION['vinculados'] as $vinculado) {
                    if ($vinculado['id'] == $colaborador_id) {
                        $is_vinculado = true; // O colaborador já está vinculado
                        break;
                    }
                }

                if (!$is_vinculado) { // Se não está vinculado
                    // Insere os dados do colaborador na tabela de vinculação
                    $insert_query = "INSERT INTO avaliacao_estabelecimento_colaborador (avaliacao_estabelecimento_id, colaborador_id, colaborador_telmovel, colaborador_email) 
                                     VALUES ('$codigo_avaliacao', '$colaborador_id', '{$colaborador['tel_movel']}', '{$colaborador['email']}')";
                    mysqli_query($conn, $insert_query); // Executa a inserção
                
                    // Adiciona à lista de colaboradores vinculados na sessão
                    $_SESSION['vinculados'][] = [
                        'id' => $colaborador_id,
                        'nome' => $colaborador['nome'],
                        'tel_movel' => $colaborador['tel_movel'],
                        'email' => $colaborador['email']
                    ];
                } else {
                    // Se o colaborador já está vinculado, mostra uma mensagem de erro
                    echo "<script>alert('Colaborador já está vinculado.');</script>";
                }
            } else {
                // Se não encontrou o colaborador, mostra uma mensagem de erro
                echo "<script>alert('Colaborador não encontrado.');</script>";
            }
        } else {
            // Se o ID não é válido, mostra uma mensagem de erro
            echo "<script>alert('Selecione um colaborador válido.');</script>";
        }
    }

    // Verifica se houve uma exclusão de um colaborador
    if (isset($_POST['excluir_id'])) {
        $excluir_id = filter_input(INPUT_POST, 'excluir_id', FILTER_SANITIZE_NUMBER_INT);

        // Remove o colaborador da tabela 'avaliacao_estabelecimento_colaborador'
        $delete_query = "DELETE FROM avaliacao_estabelecimento_colaborador WHERE colaborador_id = $excluir_id AND avaliacao_estabelecimento_id = $codigo_avaliacao";
        mysqli_query($conn, $delete_query); // Executa a exclusão

        // Remove o colaborador da lista de vinculados na sessão
        foreach ($_SESSION['vinculados'] as $key => $vinculado) {
            if ($vinculado['id'] == $excluir_id) {
                unset($_SESSION['vinculados'][$key]); // Remove da sessão
                break;
            }
        }
    }
}

// Armazena os colaboradores vinculados na variável
$vinculados = isset($_SESSION['vinculados']) ? $_SESSION['vinculados'] : []; // Verifica se 'vinculados' está definido na sessão

// Paginação dos colaboradores vinculados
$total_vinculados = count($vinculados); // Total de colaboradores vinculados
$total_paginas = ceil($total_vinculados / $por_pagina); // Calcula o total de páginas
$vinculados_pagina = array_slice($vinculados, $offset, $por_pagina); // Obtém os colaboradores da página atual
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Inclui o CSS do Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css"> <!-- Inclui ícones do Bootstrap -->
    <link rel="stylesheet" href="styles/colaborador.css"> <!-- Inclui CSS personalizado -->
    <title>Colaboradores</title> <!-- Título da página -->
</head>
<body>
    <div class="container"> <!-- Contêiner principal -->
        <div class="botao">
            <!-- Botão "Voltar" com os parâmetros de avaliação e estabelecimento na URL -->
            <a href="editar_avaliacoes.php?codigo=<?= $codigo_avaliacao; ?>&estabelecimento=<?= $id_estabelecimento; ?>" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
        <h3>Vincular Colaborador no Questionário</h3>
        <form method="POST" action=""> <!-- Formulário para adicionar colaboradores -->
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
                            <select class="form-select" name="colaborador_id" id="nome_colaborador"> <!-- Select para escolher um colaborador -->
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
                                    echo "<option value=''>Nenhum colaborador encontrado</option>"; // Mensagem se não houver colaboradores
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <button class="btn btn-success w-100" type="submit"><i class="bi bi-plus-circle"></i> Adicionar</button> <!-- Botão para adicionar colaborador -->
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>

        <h3>Colaboradores Vinculados ao Questionário</h3>
        <table class="table table-light table-bordered table-striped table-hover m-5" border="1" cellspacing="0" cellpadding="10"> <!-- Tabela para exibir colaboradores vinculados -->
            <thead>
                <tr>
                    <th>Nome</th>
                    <th width="12%">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vinculados_pagina as $vinculado): ?> <!-- Itera sobre os colaboradores vinculados para exibição -->
                    <tr>
                        <td><?= htmlspecialchars($vinculado['nome'], ENT_QUOTES); ?></td> <!-- Nome do colaborador -->
                        <td>
                            <form method="POST" action="" class="excluir-form"> <!-- Formulário para excluir colaborador -->
                                <input type="hidden" name="excluir_id" value="<?= $vinculado['id']; ?>"> <!-- ID do colaborador a ser excluído -->
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir?');"><i class="bi bi-trash"></i> Excluir</button> <!-- Botão para excluir colaborador -->
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Navegação de paginação -->
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <?php if ($pagina > 1): ?> <!-- Se não estiver na primeira página -->
                    <li class="page-item">
                        <a class="page-link" href="?codigo=<?= $codigo_avaliacao; ?>&estabelecimento=<?= $id_estabelecimento; ?>&pagina=<?= $pagina - 1; ?>">Anterior</a> <!-- Botão "Anterior" -->
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_paginas; $i++): ?> <!-- Itera sobre o total de páginas -->
                    <li class="page-item <?= $i === $pagina ? 'active' : ''; ?>">
                        <a class="page-link" href="?codigo=<?= $codigo_avaliacao; ?>&estabelecimento=<?= $id_estabelecimento; ?>&pagina=<?= $i; ?>"><?= $i; ?></a> <!-- Botões de página -->
                    </li>
                <?php endfor; ?>

                <?php if ($pagina < $total_paginas): ?> <!-- Se não estiver na última página -->
                    <li class="page-item">
                        <a class="page-link" href="?codigo=<?= $codigo_avaliacao; ?>&estabelecimento=<?= $id_estabelecimento; ?>&pagina=<?= $pagina + 1; ?>">Próximo</a> <!-- Botão "Próximo" -->
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</body>
</html>
