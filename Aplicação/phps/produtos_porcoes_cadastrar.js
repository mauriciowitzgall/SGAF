window.onload = function(){
    
    quantidade=$("input[name=porcao_quantidade2]").val();
    valuniref=$("input[name=porcao_valuniref2]").val();
    
    tipocontagem=$("input[name=tipocontagem2]").val();
    if ((tipocontagem==2)||(tipocontagem==3)) {
        $("input[name=porcao_quantidade]").val(quantidade).priceFormat({
            prefix: '',
            centsSeparator: ',',
            centsLimit: 3,
            thousandsSeparator: '.'
        });  
      
    } else {
        $("input[name=porcao_quantidade]").val(quantidade).priceFormat({
            prefix: '',
            centsSeparator: '',
            centsLimit: 0,
            thousandsSeparator: ''
        });        
          
    }


    $("input[name=porcao_valuniref]").val(valuniref).priceFormat({
        prefix: 'R$ ',
        centsSeparator: ',',
        centsLimit: 2,
        thousandsSeparator: '.'
    });       



}
