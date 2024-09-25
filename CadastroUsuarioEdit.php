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
        <h1 class="text-left mb-4">Cadastro de Novo Usuário</h1>
            
             <!-- tratar os dados com JAVASCRIPT, PQ AS CIDADES NAO FUNCIONA COM <FORM> -->
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
                                    <option value="">4</option>
                                    
                                </select>
                                <div class="invalid-feedback">Selecione a permissão.</div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="estabelecimento" class="form-label">Estabelecimento do Usuário</label>
                                <select class="form-select" id="estabelecimento">
                                    <option selected>Não vincular estabelecimento ao usuário</option>
                                    <option value="">SPT25</option>
                                    <option value="">SPT26</option>
                                    <option value="">SPT32</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="empresa" class="form-label">Empresa do Usuário</label>
                                <select class="form-select" id="empresa">
                                    <option selected>Não vincular empresa ao usuário</option>
                                    <option value="">PERBRAS</option>
                                </select>
                            </div>
                        </div>
                        <!-- Segunda linha com 4 colunas -->
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="login" class="form-label">Login do Usuário</label>
                                <input type="text" class="form-control" id="usuario" placeholder="Informe o login do usuário" required>
                                <div class="invalid-feedback">Informe o login do usuário.</div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="email" class="form-label">E-mail do Usuário</label>
                                <input type="email" class="form-control" id="email" placeholder="email@usuario.com.br" required>
                                <div class="invalid-feedback">Informe um e-mail válido.</div>
                            </div>
                <!-- Dropdown de Estados -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Estado (UF)</label>
                            <select class="form-select" name="uf" id="uf" onchange="cidades()">
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
                        </div>
                    <!-- Dropdown de Cidades -->
                        <div class="col-md-3 mb-3">
                            <label for="cidade" class="form-label">Cidade do Usuário</label>
                            <select  class="form-select" name="cidades" id="cidades">
                                <option>Selecione o Estado primeiro</option>
                            </select>
                        </div>
                
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="telefone-fixo" class="form-label">Telefone Fixo</label>
                        <input type="tel" class="form-control" id="telefone-fixo" placeholder="(00) 0000-0000">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="telefone-movel" class="form-label">Telefone Móvel</label>
                        <input type="tel" class="form-control" id="telefone-movel" placeholder="(00) 00000-0000">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="observacoes" class="form-label">Observações sobre o Usuário</label>
                        <textarea class="form-control" id="observacoes" rows="2" placeholder="Informe observações se necessário"></textarea>
                    </div>
                </div>
                <!-- Quarta linha com 2 colunas -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="notificacoes" class="form-label">Enviar Notificações por E-mail</label>
                        <select class="form-select" id="notificacoes">
                            <option value="sim" selected>Sim, enviar e-mails de notificações</option>
                            <option value="nao">Não enviar e-mails de notificações</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="data-expiracao" class="form-label">Acesso do Usuário Expira em</label>
                        <input type="date" class="form-control" id="data-expiracao">
                    </div>
                </div>
                <!-- Botões -->
                <div class="d-flex">
                    <button class="btn btn-success w-75 p-2 m-3" type="submit" onclick="enviarFormulario()" >Salvar</button>
                    <button class="btn btn-warning w-75 p-2 m-3" type="reset">Limpar</button>
                    <a href="Users.php"><button class="btn btn-danger  w-75 p-2 m-3" type="button">Cancelar</button></a>
                </div>
            
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
    

<script> //não ta chegando no banco de dados porem ta falando que o usuario foi cadastrado
function enviarFormulario() {
    // Captura os valores dos campos
    var nome = document.getElementById('nome').value;
    var usuario = document.getElementById('usuario').value;
    var email = document.getElementById('email').value;
    var uf = document.getElementById('uf').value;
    var cidade = document.getElementById('cidades').value;
    var telefoneFixo = document.getElementById('telefone-fixo').value;
    var telefoneMovel = document.getElementById('telefone-movel').value;
    var observacoes = document.getElementById('observacoes').value;
    var notificacoes = document.getElementById('notificacoes').value;
    var dataExpiracao = document.getElementById('data-expiracao').value;

    // Cria um objeto para enviar via POST
    var dados = {
        nome: nome,
        usuario: usuario,
        email: email,
        uf: uf,
        cidade: cidade,
        telefone_fixo: telefoneFixo,
        telefone_movel: telefoneMovel,
        observacoes: observacoes,
        notificacoes: notificacoes,
        data_expiracao: dataExpiracao
    };

    // Envia via Fetch API para o PHP processar
    fetch('Controller/forms_cad_user.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(dados)  // Converte os dados para JSON
    })
    .then(response => response.text())  // Pega a resposta do servidor
    .then(result => {
        console.log(result);  // Mostra no console a resposta do PHP
        alert(result);

        if (result.includes("Usuário cadastrado com sucesso!")) {
            setTimeout(() => {
                window.location.href = 'Users.php';
            }, 1000); // Redireciona após 1 segundo
}

    })
    .catch(error => {
        console.error('Erro:', error);
    });
}

</script>

</body>
</html>
