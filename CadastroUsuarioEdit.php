<?php 
include_once 'Controller/conexao.php';

// Inicializa a variável do usuário
$usuario = null;

// Se você precisar preencher o formulário para um usuário específico
if (isset($_GET['id'])) {
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_object();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/editar.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-left mb-4">Editar Usuário</h1>
        
        <div class="row">
            <div class="col-md-3 mb-3">
                <label for="nome" class="form-label">Nome do Usuário</label>
                <input type="text" class="form-control" id="nome" placeholder="Informe o nome do usuário" required value="<?php echo isset($usuario) ? htmlspecialchars($usuario->nome) : ''; ?>">
            </div>
            <div class="col-md-3 mb-3">
                <label for="permissao" class="form-label">Permissão do Usuário</label>
                <select class="form-select" id="permissao" required>
                    <option selected disabled value="">Selecione o nível de permissão</option>
                    <option value="0" <?php echo (isset($usuario) && $usuario->acesso == 0) ? 'selected' : ''; ?>>Visitante</option>
                    <option value="1" <?php echo (isset($usuario) && $usuario->acesso == 1) ? 'selected' : ''; ?>>Usuário</option>
                    <option value="2" <?php echo (isset($usuario) && $usuario->acesso == 2) ? 'selected' : ''; ?>>Gestor</option>
                    <option value="3" <?php echo (isset($usuario) && $usuario->acesso == 3) ? 'selected' : ''; ?>>Administrador</option>
                    <option value="4" <?php echo (isset($usuario) && $usuario->acesso == 4) ? 'selected' : ''; ?>>Master</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label for="estabelecimento" class="form-label">Estabelecimento do Usuário</label>
                <select class="form-select" id="estabelecimento">
                    <option selected>Não vincular estabelecimento ao usuário</option>
                    <option value="SPT25">SPT25</option>
                    <option value="SPT26">SPT26</option>
                    <option value="SPT32">SPT32</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label for="empresa" class="form-label">Empresa do Usuário</label>
                <select class="form-select" id="empresa">
                    <option selected>Não vincular empresa ao usuário</option>
                    <option value="PERBRAS">PERBRAS</option>
                </select>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-3 mb-3">
                <label for="usuario" class="form-label">Login do Usuário</label>
                <input type="text" class="form-control" id="usuario" placeholder="Informe o login do usuário" required value="<?php echo isset($usuario) ? htmlspecialchars($usuario->usuario) : ''; ?>">
            </div>
            <div class="col-md-3 mb-3">
                <label for="email" class="form-label">E-mail do Usuário</label>
                <input type="email" class="form-control" id="email" placeholder="email@usuario.com.br" required value="<?php echo isset($usuario) ? htmlspecialchars($usuario->email) : ''; ?>">
            </div>
            <div class="col-md-3 mb-3">
                <label for="uf" class="form-label">Estado (UF)</label>
                <select class="form-select" id="uf" onchange="cidades()">
                    <option value="">Selecione um Estado</option>
                    <?php
                        $stmt = $conn->prepare('SELECT * FROM estados');
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while($row = $result->fetch_object()){
                            echo "<option value='{$row->id}'" . (isset($usuario) && $usuario->uf == $row->id ? ' selected' : '') . ">{$row->nome}</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label for="cidades" class="form-label">Cidade do Usuário</label>
                <select class="form-select" id="cidades">
                    <option>Selecione o Estado primeiro</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="telefone-fixo" class="form-label">Telefone Fixo</label>
                <input type="tel" class="form-control" id="telefone-fixo" placeholder="(00) 0000-0000" value="<?php echo htmlspecialchars($usuario->telefone_fixo ?? ''); ?>">
            </div>
            <div class="col-md-4 mb-3">
                <label for="telefone-movel" class="form-label">Telefone Móvel</label>
                <input type="tel" class="form-control" id="telefone-movel" placeholder="(00) 00000-0000" value="<?php echo htmlspecialchars($usuario->telefone_movel ?? ''); ?>">

            </div>
            <div class="col-md-4 mb-3">
                <label for="observacoes" class="form-label">Observações sobre o Usuário</label>
                <textarea class="form-control" id="observacoes" rows="2" placeholder="Informe observações se necessário"><?php echo htmlspecialchars($usuario->observacoes ?? ''); ?></textarea>

            </div>
        </div>

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
                <input type="date" class="form-control" id="data-expiracao" value="<?php echo htmlspecialchars($usuario->data_expiracao ?? ''); ?>">

            </div>
        </div>

        <div class="d-flex">
            <button class="btn btn-primary  p-3 m-2 w-25" onclick="salvarUsuario(<?php echo isset($usuario) ? $usuario->id : 'null'; ?>)">Salvar Usuário</button>
            <a href="Users.php"><button class="btn btn-danger  p-3 m-2 w-100" type="button">Cancelar</button></a>
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
                xhr.onload = function() {
                    if (this.status === 200) {
                        var response = JSON.parse(this.responseText);
                        var options = '<option value="">Selecione uma cidade...</option>';
                        response.forEach(function(cidade) {
                            options += `<option value="${cidade.id}">${cidade.nome}</option>`;
                        });
                        selecionaCidade.innerHTML = options;
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

        function salvarUsuario(id) {
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
            var permissao = document.getElementById('permissao').value; // Captura o valor da permissão

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'Controller/salvar_usuario.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (this.status === 200) {
                    alert('Usuário salvo com sucesso!');
                    window.location.href = 'Users.php'; // Redireciona após salvar
                } else {
                    alert('Erro ao salvar usuário: ' + this.responseText);
                }
            };
            xhr.send(`id=${id}&nome=${encodeURIComponent(nome)}&usuario=${encodeURIComponent(usuario)}&email=${encodeURIComponent(email)}&uf=${encodeURIComponent(uf)}&cidade=${encodeURIComponent(cidade)}&telefoneFixo=${encodeURIComponent(telefoneFixo)}&telefoneMovel=${encodeURIComponent(telefoneMovel)}&observacoes=${encodeURIComponent(observacoes)}&notificacoes=${encodeURIComponent(notificacoes)}&dataExpiracao=${encodeURIComponent(dataExpiracao)}&permissao=${encodeURIComponent(permissao)}`); // Inclui a permissão
        }

        
    </script>
</body>
</html>