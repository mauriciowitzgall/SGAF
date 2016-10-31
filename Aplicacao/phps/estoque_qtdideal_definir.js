window.onload = function(){
    //alert("ui");
    qtdide=$("input[name=qtdide2]").val();
    tipocontagem=$("input[name=tipocontagem]").val();
    //alert(tipocontagem+"/"+qtdide);
    
    if (tipocontagem==1) {
        $("input[name=qtdide]").val(qtdide).priceFormat({
            prefix: '',
            centsSeparator: '',
          centsLimit: 0,
          thousandsSeparator: '.'
        });
    } else if ((tipocontagem==2)||(tipocontagem==3)) {
        $("input[name=qtdide]").val(qtdide).priceFormat({
            prefix: '',
            centsSeparator: ',',
          centsLimit: 3,
          thousandsSeparator: '.'
        });        
    } else {
        alert("o tipo de contagem não é valido, revisar regras!")
    }
}