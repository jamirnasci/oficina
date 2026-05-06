function buscarVeiculos(clienteId) {
    const veiculoSelect = document.getElementById('veiculo_select');

    if (!clienteId) {
        veiculoSelect.innerHTML = '<option value="">Selecione o cliente primeiro...</option>';
        veiculoSelect.disabled = true;
        return;
    }

    // Faz a requisição para um pequeno arquivo PHP que retorna os veículos em JSON
    fetch(`/oficina/veiculos/veiculo_cliente.php?id=${clienteId}`)
        .then(response => response.json())
        .then(data => {
            veiculoSelect.innerHTML = '<option value="">Selecione o veículo...</option>';
            data.forEach(v => {
                veiculoSelect.innerHTML += `<option value="${v.id}">${v.marca} ${v.modelo} (${v.placa})</option>`;
            });
            veiculoSelect.disabled = false;
        })
        .catch(error => {
            console.error('Erro ao buscar veículos:', error);
            alert('Erro ao carregar veículos deste cliente.');
        });
}