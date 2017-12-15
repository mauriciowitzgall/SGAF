window.onload = function(){
    //alert("ui");
};

function verifica_qtd_digitada (campo, itemvenda) {
    
    nomevaluni="valuni_"+itemvenda;
    nomevaltot="valtot_"+itemvenda;
    nomevaltot_comdesconto="valtot_comdesconto_"+itemvenda;
  
    //Verifica se a quantidade digitada é maior que o limite máximo possivel de itens a ser devolvido
    if ( parseInt(campo.value) > parseInt(campo.max) ) {
        alert("Quantidade Inválida. Você pode devolver no máximo: "+campo.max);
        campo.value = '';
        $("span[name="+nomevaltot+"]").text(" - ");
        $("span[name="+nomevaltot_comdesconto+"]").text(" - ");
    } else {

        //Calcula o Valor total
        if( ! parseInt(campo.value) ) {
            $("span[name="+nomevaltot+"]").text(" - ");
            $("span[name="+nomevaltot_comdesconto+"]").text(" - ");
        } else {
            var valuni=$("span[id="+nomevaluni+"]").text();
            valuni=valuni.split(" ");
            valuni=valuni[2];
            valuni=valuni.replace(".","");
            valuni=valuni.replace(",",".");
            var valtot=valuni * campo.value;
            descper=$("input[name=descper]").val();
            descper=descper.replace("%","");
            descper=descper.replace(",",".");
            console.log(descper);
            var valtot_comdesconto= valtot * (100-descper)/100;
            valtotmostra="R$ "+valtot.formatMoney(2, ',', '.');
            valtotmostra_comdesconto="R$ "+valtot_comdesconto.formatMoney(2, ',', '.');
            $("span[name="+nomevaltot+"]").text(valtotmostra);
            $("span[name="+nomevaltot_comdesconto+"]").text(valtotmostra_comdesconto);
        }
    }
    
   
    //Habilita ou desabilita o botão continuar
    atualiza_continuar();
    
}

function atualiza_continuar() {
    var total = 0.0;
    var total_comdesconto = 0.0;
    
    $("#tabela1 .tab_linhas td:last-child").each(function() {
        total_comdesconto += parseFloat($(this).text().replace('R$ ', '').replace('.','').replace(',', '.')) || 0;
    });
    console.log(total_comdesconto);

    $("#tabela1 .tab_linhas td:nth-last-child(2)").each(function() {
        total += parseFloat($(this).text().replace('R$ ', '').replace('.','').replace(',', '.')) || 0;
    });
    //console.log(total);
    valorvendazero=$("input[name=valorvendazero]").val();
    if (( total > 0 )||(valorvendazero==1)) {
        $('#CONTINUAR').removeAttr('disabled');
        $('#valtot').text("R$ "+total.formatMoney(2, ',', '.'));
        $('#campooculto_valtot').val(total.toFixed(2));
        $('#valtot_comdesconto').text("R$ "+total_comdesconto.formatMoney(2, ',', '.'));
        $('#campooculto_valtot_comdesconto').val(total_comdesconto.toFixed(2));
    } else {
        $('#CONTINUAR').attr('disabled', 'disabled');
        $('#valtot_comdesconto').text(" - ");
        $('#campooculto_valtot_comdesconto').val("");
        $('#valtot').text(" - ");
        $('#campooculto_valtot').val("");
    }
}
