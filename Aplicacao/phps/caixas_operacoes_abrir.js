window.onload = function(){
    $("input[name=valorinicial]").val("R$ 0,00").priceFormat({prefix: 'R$ ', centsSeparator: ',', thousandsSeparator: '.'});
}