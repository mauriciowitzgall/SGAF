window.onload = function(){
    //alert("ui");
};

function verifica_qtd_digitada (campo, itemvenda) {
    
    nomevaluni="valuni_"+itemvenda;
    nomevaltot="valtot_"+itemvenda;
  
    //Verifica se a quantidade digitada é maior que o limite máximo possivel de itens a ser devolvido
    if ( parseInt(campo.value) > parseInt(campo.max) ) {
        alert("Quantidade Inválida. Você pode devolver no máximo: "+campo.max);
        campo.value = '';
        $("span[name="+nomevaltot+"]").text(" - ");
    } else {

        //Calcula o Valor total
        if( ! parseInt(campo.value) ) {
            $("span[name="+nomevaltot+"]").text(" - ");
        } else {
            var valuni=$("span[id="+nomevaluni+"]").text();
            valuni=valuni.split(" ");
            valuni=valuni[2];
            valuni=valuni.replace(",",".");
            var valtot=valuni * campo.value;
            valtotmostra="R$ "+valtot.toFixed(2).toString().replace(".",",");
            $("span[name="+nomevaltot+"]").text(valtotmostra);
        }
    }
    
   
    //Habilita ou desabilita o botão continuar
    atualiza_continuar();
    
}

function atualiza_continuar() {
    var total = 0.0;
    
    $("#tabela1 .tab_linhas td:last-child").each(function() {
        total += parseFloat($(this).text().replace(',','.').replace('R$ ', '')) || 0;
    });
    
    if ( total > 0 ) {
        $('#CONTINUAR').removeAttr('disabled');
    } else {
        $('#CONTINUAR').attr('disabled', 'disabled');
    }
}
