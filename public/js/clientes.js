
function searchHandler(){
    const q = document.getElementById('nomeBusca')

    if(!q || !q.value){
        alert('Digite o nome ou cpf do cliente para fazer a busca')
        return 
    }
    location.href = '/oficina/clientes?q=' + q.value
}