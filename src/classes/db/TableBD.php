async function preUp(id) {
    document.getElementById("do").value = "ce";
    document.getElementById("editKey").value = id;
    let url = window.location.protocol + "//" + window.location.hostname + window.location.pathname + "?do=e&id=" + id
    
    const response = await fetch(url)
    const eventos = await response.json()
    
    for (const evento of eventos) {
        // TRATAR A DATA SEPARADAMENTE
        // Verifica se existe Data ou data no objeto
        let dataValor = evento.Data || evento.data || null;
        if (dataValor && dataValor !== 'CURRENT_TIMESTAMP') {
            // Remove a hora
            if (typeof dataValor === 'string') {
                if (dataValor.includes('T')) {
                    dataValor = dataValor.split('T')[0];
                } else if (dataValor.includes(' ') && dataValor.split(' ')[0].includes('-')) {
                    dataValor = dataValor.split(' ')[0];
                }
            }
            console.log('Definindo data para:', dataValor);
            $('#txtData').val(dataValor);
            console.log('Valor após definir:', $('#txtData').val());
        }
        
        // Processar os outros campos
        for (x in evento) {
            // Pular a data que já foi tratada (ignorar maiúsculas/minúsculas)
            if (x.toLowerCase() === 'data') continue;
            
            // Textarea com Summernote
            if ($('textarea').length > 1) {
                var markupStr = evento[x];
                $('textarea#txt' + x).val(markupStr);
            }
            
            // Para todos os outros campos, usa .val()
            $(`#txt${x}`).val(evento[x]);
            
            // Imagens
            if ($(`img#txt2${x}`).length) {
                $("#txt2" + x).attr("src", evento[x])
            }
            
            // Selects
            if ($(`select#txt${x}`).length) {
                $(`#txt${x}`).val(null);
                $(`#txt${x}`).val(evento[x]);
            }
        }
    }
}
