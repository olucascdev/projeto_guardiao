<?php 
  include_once 'Controller/conexao.php';
  session_start();
  

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons (for icons in sidebar) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
</head>
<body>
    <!-- Sidebar -->
   <div class="container mt-5">
    <h1 class="text-center mb-4">Cadastro de Novo Usuário</h1>
    <form class="needs-validation" novalidate>
      <div class="row">
        <!-- Nome do usuário -->
        <div class="col-md-6 mb-3">
          <label for="nome" class="form-label">Nome do Usuário</label>
          <input type="text" class="form-control" id="nome" placeholder="Informe o nome do usuário" required>
          <div class="invalid-feedback">Informe o nome do usuário.</div>
        </div>

        <!-- Permissão do usuário -->
        <div class="col-md-6 mb-3">
          <label for="permissao" class="form-label">Permissão do Usuário</label>
          <select class="form-select" id="permissao" required>
            <option selected disabled value="">Selecione o nível de permissão</option>
            <option value="1">Admin</option>
            <option value="2">Usuário comum</option>
          </select>
          <div class="invalid-feedback">Selecione a permissão.</div>
        </div>
      </div>

      <div class="row">
        <!-- Estabelecimento do usuário -->
        <div class="col-md-6 mb-3">
          <label for="estabelecimento" class="form-label">Estabelecimento do Usuário</label>
          <select class="form-select" id="estabelecimento">
            <option selected>Não vincular estabelecimento ao usuário</option>
            <option value="1">Estabelecimento A</option>
            <option value="2">Estabelecimento B</option>
          </select>
        </div>

        <!-- Empresa do usuário -->
        <div class="col-md-6 mb-3">
          <label for="empresa" class="form-label">Empresa do Usuário</label>
          <select class="form-select" id="empresa">
            <option selected>Não vincular empresa ao usuário</option>
            <option value="1">Empresa A</option>
            <option value="2">Empresa B</option>
          </select>
        </div>
      </div>

      <div class="row">
        <!-- Login do usuário -->
        <div class="col-md-6 mb-3">
          <label for="login" class="form-label">Login do Usuário</label>
          <input type="text" class="form-control" id="login" placeholder="Informe o login do usuário" required>
          <div class="invalid-feedback">Informe o login do usuário.</div>
        </div>

        <!-- E-mail do usuário -->
        <div class="col-md-6 mb-3">
          <label for="email" class="form-label">E-mail do Usuário</label>
          <input type="email" class="form-control" id="email" placeholder="email@usuario.com.br" required>
          <div class="invalid-feedback">Informe um e-mail válido.</div>
        </div>
      </div>

      <div class="row">
        <!-- Estado do usuário -->
        <div class="col-md-6 mb-3">
          <label for="estado" class="form-label">Estado (UF)</label>
          <select class="form-select" id="estado" required>
            <option selected disabled value="">Selecione o estado</option>
            <option value="SP">São Paulo</option>
            <option value="RJ">Rio de Janeiro</option>
          </select>
          <div class="invalid-feedback">Selecione o estado.</div>
        </div>

        <!-- Cidade do usuário -->
        <div class="col-md-6 mb-3">
          <label for="cidade" class="form-label">Cidade do Usuário</label>
          <select class="form-select" id="cidade" disabled>
            <option selected disabled>Aguardando seleção do estado</option>
          </select>
        </div>
      </div>

      <div class="row">
        <!-- Telefone fixo -->
        <div class="col-md-6 mb-3">
          <label for="telefone-fixo" class="form-label">Telefone Fixo</label>
          <input type="tel" class="form-control" id="telefone-fixo" placeholder="(00) 0000-0000">
        </div>

        <!-- Telefone móvel -->
        <div class="col-md-6 mb-3">
          <label for="telefone-movel" class="form-label">Telefone Móvel</label>
          <input type="tel" class="form-control" id="telefone-movel" placeholder="(00) 00000-0000">
        </div>
      </div>

      <div class="row">
        <!-- Observações -->
        <div class="col-md-12 mb-3">
          <label for="observacoes" class="form-label">Observações sobre o Usuário</label>
          <textarea class="form-control" id="observacoes" rows="3" placeholder="Informe observações se necessário"></textarea>
        </div>
      </div>

      <div class="row">
        <!-- Notificações -->
        <div class="col-md-6 mb-3">
          <label for="notificacoes" class="form-label">Enviar Notificações por E-mail</label>
          <select class="form-select" id="notificacoes">
            <option value="sim" selected>Sim, enviar e-mails de notificações</option>
            <option value="nao">Não enviar e-mails de notificações</option>
          </select>
        </div>

        <!-- Acesso expira em -->
        <div class="col-md-6 mb-3">
          <label for="data-expiracao" class="form-label">Acesso do Usuário Expira em</label>
          <input type="date" class="form-control" id="data-expiracao">
        </div>
      </div>

      <div class="d-flex justify-content-between">
        <button class="btn btn-success" type="submit">Salvar</button>
        <button class="btn btn-warning" type="reset">Limpar</button>
        <button class="btn btn-danger" type="button">Cancelar</button>
      </div>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function () {
      'use strict'

      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      var forms = document.querySelectorAll('.needs-validation')

      // Loop over them and prevent submission
      Array.prototype.slice.call(forms)
        .forEach(function (form) {
          form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
              event.preventDefault()
              event.stopPropagation()
            }

            form.classList.add('was-validated')
          }, false)
        })
    })()
  </script>          
</body>
</html>