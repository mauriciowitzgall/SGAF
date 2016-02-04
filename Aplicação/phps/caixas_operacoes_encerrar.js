window.onload = function(){
    $("input[name=valorfinal]").val("R$ 0,00").priceFormat({prefix: 'R$ ', centsSeparator: ',', thousandsSeparator: '.'});
    $("input[name=diferenca]").val("").priceFormat({prefix: 'R$ ', centsSeparator: ',', thousandsSeparator: '.'});
}
function popula_diferenca(valorfinal) {
    valorfinal=valorfinal.split(" ");
    valorfinal=valorfinal[1];
    caixa=valorfinal.replace(".","");
    caixa=caixa.replace(",",".");
    valoresperado=$("input[name=valoresperado2]").val();
    diferenca=caixa-valoresperado;
    diferenca=diferenca.toFixed(2);
    $("input[name=diferenca2]").val(diferenca);
    //alert(diferenca);
    var diferenca2= diferenca.toString();
    //alert(diferenca2);
    diferenca2=diferenca2.replace(".",",");
    $("input[name=diferenca]").val("R$ "+diferenca2);
}