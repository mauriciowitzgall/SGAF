window.onload = function() {
    metodopagamento();

    //Se estiver parametrizado para nao acetar vendas a receber então ocultar o campo e padronizar a opção Não
    permitevendasareceber = $("input[name=permitevendasareceber]").val();
    if (permitevendasareceber==0) {
        $("tr[id=linha_areceber]").hide();
        document.form1.areceber.required=false;

    } else {
        document.form1.areceber.required=true;
        $("tr[id=linha_areceber]").show();
    }

    valorareceber($("select[name=areceber]").val());


}

function calcula_total_recebido_misto () {
    recebidocartao=$("input[name=recebidocartao]").val();
    recebidocartao=recebidocartao.replace("R$","");
    recebidocartao=recebidocartao.replace(".","");
    recebidocartao=recebidocartao.replace(",",".");
    if (recebidocartao=="") recebidocartao=0;


    recebidodinheiro=$("input[name=recebidodinheiro]").val();
    recebidodinheiro=recebidodinheiro.replace("R$", "");
    recebidodinheiro=recebidodinheiro.replace(".","");
    recebidodinheiro=recebidodinheiro.replace(",",".");
    if (recebidodinheiro=="") recebidodinheiro=0;

    totalrecebidomisto=parseFloat(recebidocartao)+parseFloat(recebidodinheiro);
    $("input[name=recebidomistototal]").val(totalrecebidomisto.toFixed(2)).priceFormat({prefix: 'R$ ', centsSeparator: ',', thousandsSeparator: '.'});
}


function metodopagamento() {  
    valor=$("select[name=metodopag]").val();
    //alert(valor);
    var total = $("input[name=total]").val();
    if ((valor==1)||(valor==4)||(valor==5)) { //Dinheiro ou Cheque, ou Desconhecido
        $("tr[id=tr_dinheiro]").show();
        document.form1.dinheiro.required=true;
        $("input[name=dinheiro]").focus();
        document.form1.recebidodinheiro.required=false;
        $("tr[id=tr_recebidodinheiro]").hide();
        document.form1.recebidocartao.required=false;
        $("tr[id=tr_recebidocartao]").hide();
        $("tr[id=tr_recebidomistototal]").hide();
        $("tr[id=tr_cartaobandeira]").hide();
        passo=$("input[name=passo]").val();
        if (passo==1) {
            $("input[name=dinheiro]").val("");
            document.form1.dinheiro.disabled = false;
        } else if (passo==2) {
            //O php desabilita o campo
        }
        document.form1.cartaobandeira.required=false;        

    } else if (valor==2) { //Cartão de Crédito
        $("tr[id=tr_dinheiro]").show();
        document.form1.dinheiro.required=true;
        document.form1.dinheiro.disabled = false;
        $("select[name=cartaobandeira]").focus();
        $("input[name=dinheiro]").val(total);
        $("tr[id=tr_recebidodinheiro]").hide();
        $("tr[id=tr_recebidocartao]").hide();
        $("tr[id=tr_recebidomistototal]").hide();
        $("tr[id=tr_cartaobandeira]").show();
        document.form1.recebidodinheiro.required=false;
        document.form1.recebidocartao.required=false;        
        document.form1.cartaobandeira.required=true;        
    } else if (valor==3) {  //Cartão de Débito  
        $("tr[id=tr_dinheiro]").show();
        document.form1.dinheiro.required=true;
        document.form1.dinheiro.disabled =false;
        $("select[name=cartaobandeira]").focus();
        $("input[name=dinheiro]").val(total);
        $("tr[id=tr_recebidodinheiro]").hide();
        $("tr[id=tr_recebidocartao]").hide();
        $("tr[id=tr_recebidomistototal]").hide();
        $("tr[id=tr_cartaobandeira]").show();
        document.form1.recebidodinheiro.required=false;
        document.form1.recebidocartao.required=false; 
        document.form1.cartaobandeira.required=true;        

    } else if ((valor==6)||(valor==7)) { //6=Dinheiro + Cartão Débito / 7=Dinheiro + Cartão Crédito
        document.form1.dinheiro.required=false;
        $("tr[id=tr_dinheiro]").hide();
        $("tr[id=tr_recebidodinheiro]").show();
        $("tr[id=tr_recebidocartao]").show();
        $("tr[id=tr_recebidomistototal]").show();
        $("select[name=cartaobandeira]").focus();
        $("tr[id=tr_cartaobandeira]").show();
        document.form1.recebidodinheiro.required=true;
        document.form1.recebidocartao.required=true;
        document.form1.cartaobandeira.required=true;        

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


