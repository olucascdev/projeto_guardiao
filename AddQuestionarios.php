<?php 
session_start(); // Inicia a sessão para manter as informações do usuário
include_once 'Controller/conexao.php'; // Inclui o arquivo de conexão com o banco de dados

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Pergunta</title>
    <!-- Inclusão do Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css"> <!-- Ícones do Bootstrap -->
</head>
<body class="p-4">

    <div class="container">
        <h2>Cadastro de Pergunta</h2>
        <form action="salvar_pergunta.php" method="POST">
            <!-- Nome da pergunta -->
            <div class="mb-3">
                <label for="nome_pergunta" class="form-label">Nome da Pergunta</label>
                <input type="text" class="form-control" id="nome_pergunta" name="nome_pergunta" required>
            </div>

            <!-- Tipo da pergunta: Discursiva ou Objetiva -->
            <div class="mb-3">
                <label class="form-label">Tipo da Pergunta</label>
                <div>
                    <input type="radio" id="discursiva" name="tipo_pergunta" value="discursiva" onclick="toggleTipoPergunta()" required>
                    <label for="discursiva">Discursiva</label>
                </div>
                <div>
                    <input type="radio" id="objetiva" name="tipo_pergunta" value="objetiva" onclick="toggleTipoPergunta()" required>
                    <label for="objetiva">Objetiva</label>
                </div>
            </div>

            <!-- Botão de salvar -->
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>

    <!-- Modal para preenchimento de opções de respostas para Objetiva -->
    <div class="modal fade" id="modalObjetiva" tabindex="-1" aria-labelledby="modalObjetivaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalObjetivaLabel">Selecione a quantidade de opções</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Seleção da quantidade de opções -->
                    <div class="mb-3">
                        <label for="quantidade_opcoes" class="form-label">Quantidade de Opções (1-5)</label>
                        <select id="quantidade_opcoes" class="form-select" onchange="mostrarOpcoes()">
                            <option value="0">Selecione a quantidade</option>
                            <option value="2">2 Opções</option>
                            <option value="3">3 Opções</option>
                            <option value="4">4 Opções</option>
                            <option value="5">5 Opções</option>
                        </select>
                    </div>

                    <!-- Campos de texto para as opções -->
                    <div id="opcoes_container">
                        <div class="mb-3 opcao" id="opcao1" style="display:none;">
                            <label for="objetiva_opcao1" class="form-label">Opção 1</label>
                            <input type="text" class="form-control" id="objetiva_opcao1" name="objetiva_opcao1">
                        </div>

                        <div class="mb-3 opcao" id="opcao2" style="display:none;">
                            <label for="objetiva_opcao2" class="form-label">Opção 2</label>
                            <input type="text" class="form-control" id="objetiva_opcao2" name="objetiva_opcao2">
                        </div>

                        <div class="mb-3 opcao" id="opcao3" style="display:none;">
                            <label for="objetiva_opcao3" class="form-label">Opção 3</label>
                            <input type="text" class="form-control" id="objetiva_opcao3" name="objetiva_opcao3">
                        </div>

                        <div class="mb-3 opcao" id="opcao4" style="display:none;">
                            <label for="objetiva_opcao4" class="form-label">Opção 4</label>
                            <input type="text" class="form-control" id="objetiva_opcao4" name="objetiva_opcao4">
                        </div>

                        <div class="mb-3 opcao" id="opcao5" style="display:none;">
                            <label for="objetiva_opcao5" class="form-label">Opção 5</label>
                            <input type="text" class="form-control" id="objetiva_opcao5" name="objetiva_opcao5">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Salvar opções</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Função para alternar entre Discursiva e Objetiva
        function toggleTipoPergunta() {
            var tipoPergunta = document.querySelector('input[name="tipo_pergunta"]:checked').value;

            if (tipoPergunta === "objetiva") {
                // Exibe o modal para perguntas objetivas
                var modalObjetiva = new bootstrap.Modal(document.getElementById("modalObjetiva"));
                modalObjetiva.show();
            }
        }

        // Função para mostrar as opções conforme a quantidade selecionada
        function mostrarOpcoes() {
            var quantidade = document.getElementById('quantidade_opcoes').value;

            // Oculta todas as opções inicialmente
            var opcoes = document.getElementsByClassName('opcao');
            for (var i = 0; i < opcoes.length; i++) {
                opcoes[i].style.display = 'none';
            }

            // Mostra as opções de acordo com a quantidade selecionada
            for (var i = 1; i <= quantidade; i++) {
                document.getElementById('opcao' + i).style.display = 'block';
            }
        }
    </script>

</body>
</html>
