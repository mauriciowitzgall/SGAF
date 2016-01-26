//Campo Desconto Percentagem 
function desconto(campo) {
    
    //Atribuir Mascara de dinheiro
    $('#descper').priceFormat({
        prefix: '',
        centsSeparator: ',',
        thousandsSeparator: '.'
    });

    //Recalcula o total
    $.post("saidas_totalcomdesconto.php",{
        valbru:$("input[name=valbru]").val(),
        descper:$("input[name=descper]").val()
    }, function(valor) {
        $("input[name=total]").val("R$ "+ valor);//alimenta o input desabilitado
        $("input[name=total2]").val("R$ "+valor);//alimenta o input hidden
    });
    
    //Zera o Desconto Valor e Dinheiro
    $("input[name=descval]").val("R$ 0,00"); 
    $("input[name=dinheiro]").val(""); 

}
//Campo Desconto Valor
function desconto2(campo) {
    //Atribuir Mascara de dinheiro
    $('#descval').priceFormat({
        prefix: 'R$ ',
        centsSeparator: ',',
        thousandsSeparator: '.'
    });

    //Recalcula o total
    $.post("saidas_totalcomdesconto2.php",{
        valbru:$("input[name=valbru]").val(),
        descval:$("input[name=descval]").val()
    }, function(valor) {
        $("input[name=total]").val("R$ "+valor);//alimenta o input desabilitado
        $("input[name=total2]").val("R$ "+valor);//alimenta o input hidden
    });
    
    //Zera o Desconto Percentagem e Dinheiro
    $("input[name=descper]").val("0"); 
    $("input[name=dinheiro]").val("R$ 0,00");     
}

//Campo Dinheiro
function mascara_dinheiro() {
    //Atribuir Mascara de dinheiro
    $('#dinheiro').priceFormat({
        prefix: 'R$ ',
        centsSeparator: ',',
        thousandsSeparator: '.'
    });    
}

function recalcula_total() {
    //Recalcula o total
    $.post("saidas_totalcomdesconto.php",{
        valbru:$("input[name=valbru]").val(),
        descper:$("input[name=descper]").val()
    }, function(valor) {
        $("input[name=total]").val("R$ "+ valor);//alimenta o input desabilitado
        $("input[name=total2]").val("R$ "+valor);//alimenta o input hidden
    });
}

function mascara_troco_devolvido() {
    $('#troco_devolvido').priceFormat({
        prefix: 'R$ ',
        centsSeparator: ',',
        thousandsSeparator: '.'
    });
    
}


