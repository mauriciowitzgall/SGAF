
function selecionar_subproduto (produto) {
    
    //Se o produto foi selecionado então habilita campo quantidade
    if (produto) {
        //alert("habilita");
        document.forms["form1"].quantidade.disabled = false;
    } else {
        document.forms["form1"].quantidade.disabled = true;
    }
    
    //Verifica qual é o tipo de contagem desse subproduto selecionado
    $.post("produtos_subproduto_verifica_tipocontagem.php",{
        produto:produto
    },function(valor2){
        valor2=valor2.split("|");
        tipocontagem=valor2[0];
        tipocontagem_sigla=valor2[1];
        var quantidade=parseFloat($("input[name=quantidade2]").val());
       
        
        //Altera a máscara da quantidade conforme o tipo de contagem do subproduto
        if ((tipocontagem==2)||(tipocontagem==3)) {
            quantidade=quantidade.toFixed(3);
            //alert(quantidade);
            $("input[name=quantidade]").val(quantidade).priceFormat({
                prefix: '',
                centsSeparator: ',',
                centsLimit: 3,
                thousandsSeparator: '.'
            });  
        } else {
            $("input[name=quantidade]").val(quantidade).priceFormat({
                prefix: '',
                centsSeparator: '',
                centsLimit: 0,
                thousandsSeparator: ''
            });        
        }
        
        //Popula sigla do tipo de contagem subproduto
        //alert(tipocontagem_sigla);
        $("#subproduto_tipocontagem").text(tipocontagem_sigla);
        
    });  
}

window.onload = function(){
    subproduto=$("input[name=subproduto2]").val();
    operacao=$("input[name=operacao]").val();
    if (operacao=="editar") selecionar_subproduto(subproduto);
}
