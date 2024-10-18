<?php 
session_start(); // Inicia a sessão para armazenar dados temporariamente
include_once 'Controller/conexao.php'; // Inclui o arquivo de conexão com o banco de dados

// Configuração de paginação
$itens_por_pagina = 5; // Define quantos itens serão exibidos por página
$pagina_atual = $_GET['pagina'] ?? 1; // Obtém a página atual da URL ou define como 1 se não estiver presente

// Verifica se um estabelecimento foi selecionado e armazena o ID na sessão
if (isset($_GET['estabelecimento_id'])) {
    $estabelecimento_id = intval($_GET['estabelecimento_id']); // Obtém o ID do estabelecimento da URL
    $_SESSION['estabelecimento_id'] = $estabelecimento_id; // Armazena o ID na sessão

  
}

// Pesquisa
if (!empty($_GET['search'])) {
    $data = $_GET['search']; // Obtém o termo de pesquisa da URL
    // Realiza uma consulta no banco de dados filtrando pelo ID ou pela sigla
    $rows = mysqli_query($conn, "SELECT * FROM estabelecimentos WHERE id LIKE '%$data%' or abrev LIKE '%$data%' ORDER BY id asc");
} else {
    // Se não houver pesquisa, obtém todos os estabelecimentos
    $rows = mysqli_query($conn, "SELECT * FROM estabelecimentos ORDER BY id asc");
}

// Calcula o total de itens e páginas
$total_itens = mysqli_num_rows($rows); // Conta o número total de itens
$total_paginas = ceil($total_itens / $itens_por_pagina); // Calcula o total de páginas
$inicio = ($pagina_atual - 1) * $itens_por_pagina; // Calcula o índice de início para a consulta

// Filtra os dados para a página atual
if (!empty($_GET['search'])) {
    // Consulta com limite de itens para a página atual
    $rows = mysqli_query($conn, "SELECT * FROM estabelecimentos WHERE id LIKE '%$data%' or abrev LIKE '%$data%' ORDER BY id asc LIMIT $inicio, $itens_por_pagina");
} else {
    // Se não houver pesquisa, obtém os estabelecimentos com limite de itens para a página atual
    $rows = mysqli_query($conn, "SELECT * FROM estabelecimentos ORDER BY id asc LIMIT $inicio, $itens_por_pagina");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolher Estabelecimento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Importa o CSS do Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css"> <!-- Importa ícones do Bootstrap -->
    <link rel="stylesheet" href="styles/Users.css"> <!-- Importa o CSS personalizado -->
</head>
<body>

<!-- Seção de lista de estabelecimentos -->
<div class="container2">
    <div class="row">
        <div class="col m-5">
            <h2>Escolher Estabelecimento</h2> <!-- Título da página -->
        </div>
    </div>
    <!-- Seção de Pesquisa -->
    <div class="box-search w-auto">
        <a href="Users.php" class="btn btn-primary"> <!-- Botão para voltar à página anterior -->
            <i class="bi bi-arrow-left"></i> Voltar</a>
        <button type="button" class="btn btn-info" onclick="atualizarPagina()"> <!-- Botão para atualizar a página -->
            <i class="bi bi-arrow-clockwise"></i> Atualizar
        </button>
        <input type="search" class="form-control w-50" placeholder="Pesquisar por Cód / Sigla" id="pesquisar"> <!-- Campo de pesquisa -->
        <button class="btn btn-primary" onclick="searchData()"><i class="bi bi-search"></i></button> <!-- Botão de pesquisa -->
    </div>

    <!-- Tabela para exibir estabelecimentos cadastrados -->
    <table class="table table-light table-bordered table-striped table-hover m-5" border="1" cellspacing=0 cellpadding=10>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nome do Estabelecimento</th>
                <th>Sigla</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row) : ?> <!-- Laço para exibir os dados de cada estabelecimento -->
            <tr>
                <td><?php echo $row['id']; ?></td> <!-- Exibe o código do estabelecimento -->
                <td><?php echo $row['estabelecimento']; ?></td> <!-- Exibe o nome do estabelecimento -->
                <td><?php echo $row['abrev']; ?></td> <!-- Exibe a sigla do estabelecimento -->
                <td>
                    <?php 
                        // Exibe o status do estabelecimento
                        if ($row['ativo'] == 1) {
                            echo 'ATIVO';
                        } else {
                            echo 'DESATIVADO';
                        }
                    ?>
                </td>
                <td class="text-center d-flex justify-content-center">
                    <!-- Botão para escolher o estabelecimento e redirecionar para a página de avaliações -->
                    <a href="avaliacoesHome.php?estabelecimento_id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-box-arrow-in-right"></i>
                    </a>
                </td>

            </tr>
            <?php endforeach; ?>                              
        </tbody>
    </table>

    <!-- Paginação -->
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php if ($pagina_atual == 1) echo 'disabled'; ?>"> <!-- Botão "Anterior" -->
                <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']; ?>?pagina=<?php echo $pagina_atual - 1; ?>">Anterior</a>
            </li>
            <?php for ($i = 1; $i <= $total_paginas; $i++) : ?> <!-- Laço para gerar os números de página -->
            <li class="page-item <?php if ($pagina_atual == $i) echo 'active'; ?>">
                <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']; ?>?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
            <?php endfor; ?>
            <li class="page-item <?php if ($pagina_atual == $total_paginas) echo 'disabled'; ?>"> <!-- Botão "Próximo" -->
                <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']; ?>?pagina=<?php echo $pagina_atual + 1; ?>">Próximo</a>
            </li>
        </ul>
    </nav>             
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> <!-- Importa o JS do Bootstrap -->
<script>
// Script para pesquisa
var search = document.getElementById('pesquisar'); // Obtém o campo de pesquisa
search.addEventListener("keydown", function(event){
    if (event.key === "Enter") { // Detecta a tecla Enter para realizar a pesquisa
        searchData();
    }
});                       

function searchData() {
    // Redireciona para a página de avaliação com o termo de pesquisa
    window.location = 'avaliacao_estabelecimento.php?search=' + search.value;
}

// Script para atualizar a página e resetar a pesquisa
function atualizarPagina() {
    document.getElementById('pesquisar').value = ''; // Limpa o campo de pesquisa
    const urlSemParametros = window.location.href.split('?')[0]; // Obtém a URL sem parâmetros
    window.location.href = urlSemParametros; // Redireciona para a URL limpa
}
</script>
</body>
</html>
