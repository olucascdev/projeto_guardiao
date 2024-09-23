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