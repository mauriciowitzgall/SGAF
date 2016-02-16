window.onload = function(){
    
    quantidade=$("input[name=porcao_quantidade2]").val();
    //if (quantidade=="") quantidade=0;
    
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
}