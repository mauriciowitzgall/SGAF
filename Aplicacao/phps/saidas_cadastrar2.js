window.onload = function(){
    metodopagamento();

}


function metodopagamento() {  
    valor=$("select[name=metodopag]").val();
    //alert(valor);
    var total = $("input[name=total]").val();
    $("input[name=dinheiro]").val("R$ 0,00");
    $("input[name=recebidodinheiro]").val("R$ 0,00");
    $("input[name=recebidocartao]").val("R$ 0,00");    
    if ((valor==1)||(valor==4)||(valor==5)) {
        document.form1.dinheiro.disabled = false;
        $("input[name=dinheiro]").val("");
        $("tr[id=tr_recebidodinheiro]").hide();
        $("tr[id=tr_recebidocartao]").hide();
    } else if (valor==2) {
        document.form1.dinheiro.disabled = false;
        $("input[name=dinheiro]").val(total);
        $("tr[id=tr_recebidodinheiro]").hide();
        $("tr[id=tr_recebidocartao]").hide();
    } else if (valor==3) {
        document.form1.dinheiro.disabled = false;
        $("input[name=dinheiro]").val(total);
        $("tr[id=tr_recebidodinheiro]").hide();
        $("tr[id=tr_recebidocartao]").hide();
    } else if ((valor==6)||(valor==7)) {
        $("tr[id=tr_dinheiro]").hide();
        $("tr[id=tr_recebidodinheiro]").show();
        $("tr[id=tr_recebidocartao]").show();
        $("input[name=recebidodinheiro]").focus();

    }
}


//Campo Desconto Percentagem 
function desconto(campo) {
    
    //Atribuir Mascara de dinheiro
    $('#descper').priceFormat({
        prefix: '',
        centsSeparator: ',',
        thousandsSeparator: '.'
    });
    
    total2=$("#valbru").val();
    total=$("#valbru").val();
    total3=total.split(" ");
    total=total3[1];
    total=total.replace(".","");
    total=total.replace(",",".");
    
    descper=$("#descper").val();
    descper=descper.replace(".","");
    descper=descper.replace(",",".");
    //alert(descper);
    
    if ((descper>100)||(descper<0)) {
        alert("O valor de desconto deve ser maior que 0 e menor que 100!");
        $('#descper').val("0,00");
        $("#total").val(total2);
        return false;
    }

    //Zera o Desconto Valor e Dinheiro
    $("input[name=dinheiro]").val(""); 
    
    //Atualiza desconto em valor conforme percentual
    descval=total*descper/100;
    descval=descval.toFixed(2);
    //alert(total+"/"+descper+"/"+descval);
    $("input[name=descval]").val(descval).priceFormat({
        prefix: 'R$ ',
        centsLimit: 2,
        centsSeparator: ',',
        thousandsSeparator: '.'
    });
    
    
    //Recalcula o total final com desconto
    valorfinal=total-descval;
    $("input[name=total]").val("R$ "+valorfinal.formatMoney(2, ',', '.'));//alimenta o input desabilitado
    $("input[name=total2]").val("R$ "+valorfinal);//alimenta o input hidden

    
    
    

}
//Campo Desconto Valor
function desconto2(campo) {
    //Atribuir Mascara de dinheiro
    $('#descval').priceFormat({
        prefix: 'R$ ',
        centsSeparator: ',',
        thousandsSeparator: '.'
    });

    //Atualiza percentual de desconto conforme o valor digitado
    total2=$("#valbru").val();
    total=$("#valbru").val();
    total3=total.split(" ");
    total=total3[1];
    total=total.replace(".","");
    total=total.replace(",",".");
    total = parseFloat(total);
    
    descval=$("#descval").val();
    descval3=descval.split(" ");
    descval=descval3[1];
    descval=descval.replace(".","");
    descval=descval.replace(",",".");
    descval = parseFloat(descval);

    descper=parseFloat(descval*100/total);
    descper=descper.toFixed(2);
    $("input[name=descper]").val(descper).priceFormat({
        prefix: '',
        centsLimit: 2,
        centsSeparator: ',',
        thousandsSeparator: '.'
    });
    
    //alert(" "+descval+" / "+total+"/"+descper);
    
    if (descval>total) {
        alert("Você não pode dar um desconto com valor maior ao valor total da venda!");
         $("input[name=descper]").val("0,00");
         $("input[name=descval]").val("R$ 0,00");
         $("input[name=total]").val(total2);
         return false;
    }
    
    

    //Recalcula o total
    $.post("saidas_totalcomdesconto2.php",{
        valbru:$("input[name=valbru]").val(),
        descval:$("input[name=descval]").val()
    }, function(valor) {
        $("input[name=total]").val("R$ "+valor);//alimenta o input desabilitado
        $("input[name=total2]").val("R$ "+valor);//alimenta o input hidden
    });
    
    //Zera o Desconto Percentagem e Dinheiro
    $("input[name=dinheiro]").val("");     
}




//Campo Dinheiro
function mascara_dinheiro() {
    //Atribuir Mascara de dinheiro
    $('#dinheiro').priceFormat({
        prefix: 'R$ ',
        centsSeparator: ',',
        thousandsSeparator: '.'
    }); 
    $('#recebidodinheiro').priceFormat({
        prefix: 'R$ ',
        centsSeparator: ',',
        thousandsSeparator: '.'
    });
    $('#recebidocartao').priceFormat({
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


