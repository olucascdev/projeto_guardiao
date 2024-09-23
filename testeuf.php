<?php 
include_once 'Controller/conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Novo Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons (for icons in sidebar) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstra0p-icons/1.8.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="styles/CadastroUser.css">
</head>
<body>
    <!-- Primeira linha com 4 colunas -->
    <div class="container mt-5">
        <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="nome" class="form-label">Nome do Usuário</label>
                        <input type="text" class="form-control" id="nome" placeholder="Informe o nome do usuário" required>
                        <div class="invalid-feedback">Informe o nome do usuário.</div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="permissao" class="form-label">Permissão do Usuário</label>
                        <select class="form-select" id="permissao" required>
                            <option selected disabled value="">Selecione o nível de permissão</option>
                        </select>
                        <div class="invalid-feedback">Selecione a permissão.</div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="estabelecimento" class="form-label">Estabelecimento do Usuário</label>
                        <select class="form-select" id="estabelecimento">
                            <option selected>Não vincular estabelecimento ao usuário</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="empresa" class="form-label">Empresa do Usuário</label>
                        <select class="form-select" id="empresa">
                            <option selected>Não vincular empresa ao usuário</option>
                        </select>
                    </div>
                </div>

                <!-- Segunda linha com 4 colunas -->
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="login" class="form-label">Login do Usuário</label>
                        <input type="text" class="form-control" id="login" placeholder="Informe o login do usuário" required>
                        <div class="invalid-feedback">Informe o login do usuário.</div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="email" class="form-label">E-mail do Usuário</label>
                        <input type="email" class="form-control" id="email" placeholder="email@usuario.com.br" required>
                        <div class="invalid-feedback">Informe um e-mail válido.</div>
                    </div>
        <!-- Dropdown de Estados -->
        
            <select name="uf" id="uf" onchange="cidades()">
                <option value="">Selecione um Estado</option>
                <?php
            
                    $stmt = $conn->prepare('SELECT * FROM estados');
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while($row = $result->fetch_object()){
                        echo "<option value='{$row->id}'>{$row->nome}</option>";
                    }
                ?>
            </select>
            <!-- Dropdown de Cidades -->
            <select name="cidades" id="cidades">
                <option>Selecione o Estado primeiro</option>
            </select>
    
    </div>               
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function cidades() {
            var ufID = document.getElementById('uf').value;
            var selecionaCidade = document.getElementById('cidades');
            selecionaCidade.innerHTML = '<option value="">Carregando...</option>';

            if (ufID) {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'teste/cidades.php?id_estado=' + ufID, true);

                // Log para ver se a requisição foi feita corretamente
                console.log('Requisição enviada para teste/cidades.php?id_estado=' + ufID);

                xhr.onload = function() {
                    if (this.status === 200) {
                        console.log('Resposta recebida: ', this.responseText); // Adicionando log para a resposta

                        var response = JSON.parse(this.responseText);
                        var options = '<option value="">Selecione uma cidade...</option>';

                        response.forEach(function(cidade) {
                            options += `<option value="${cidade.id}">${cidade.nome}</option>`;
                        });

                        selecionaCidade.innerHTML = options;
                    } else {
                        console.error('Erro na requisição: ', this.status, this.statusText);
                    }
                };

                xhr.onerror = function() {
                    console.error('Erro de rede ou de servidor.');
                };

                xhr.send();
            } else {
                selecionaCidade.innerHTML = '<option value="">Selecione um estado</option>';
            }
        }
    </script>
</body>
</html>
