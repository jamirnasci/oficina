
function searchHandler(){
    const q = document.getElementById('nomeBusca')

    if(!q || !q.value){
        alert('Digite o nome do usuário para fazer a busca')
        return 
    }
    location.href = '/oficina/usuarios?q=' + q.value
}