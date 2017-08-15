window.onload = function(){
    
    //Mascara valor
    operacao=$("input[name=operacao]").val();
    valortela=$("input[name=valortela]").val();
    if (valortela=="") valortela="R$ 0,00";
    //alert(valortela);
    $("input[name=valor]").val(valortela).priceFormat({prefix: 'R$ ', centsSeparator: ',', thousandsSeparator: '.'});
}

