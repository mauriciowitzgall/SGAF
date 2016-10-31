function tipo_entradasaida_caixa() {
    tipocai=$("select[name=tipo]").val();
    //alert(tipocai);
    if (tipocai==2) {
        $("tr[id=tr_areceber]").hide(); 
        $("tr[id=tr_consumidor]").hide(); 
        $("tr[id=tr_datavenda]").hide(); 
        $("tr[id=tr_venda]").hide(); 
        $("select[name=areceber]").val("2"); 

    } else {
        $("tr[id=tr_areceber]").show();
        $("tr[id=tr_consumidor]").hide(); 
        $("tr[id=tr_datavenda]").hide(); 
        $("tr[id=tr_venda]").hide(); 
        //$("tr[id=tr_consumidor]").show(); 
        //$("tr[id=tr_datavenda]").show(); 
        //$("tr[id=tr_venda]").show(); 
    }    
}

function areceber_entradasaida_caixa() {
    areceber=$("select[name=areceber]").val();
    $('#link_vervenda').hide();
    //alert(areceber);
    if (areceber==0) {
        $("tr[id=tr_consumidor]").hide(); 
        $("tr[id=tr_datavenda]").hide(); 
        $("tr[id=tr_venda]").hide(); 
    } else {
        $("tr[id=tr_consumidor]").show(); 
        $("tr[id=tr_datavenda]").show(); 
        $("tr[id=tr_venda]").show();
        
        //Popula consumidor
        $("select[name=datavenda]").html('<option> </option>');   
        $("select[name=venda]").html('<option> </option>');   
        $("select[name=consumidor]").html('<option>Carregando...</option>');   
        
        $.post("caixas_entradassaidas_cadastrar_areceber_popula_consumidor.php", {
        }, function(valor) {
            //alert(valor);
            $("select[name=consumidor]").html(valor);
        });
    }    
}

function consumidor_entradasaida_caixa() {
    consumidor=$("select[name=consumidor]").val();
    //alert(areceber);

    //Popula Data Venda
    $("select[name=venda]").html('<option> </option>');   
    $("select[name=datavenda]").html('<option>Carregando...</option>');   

    $.post("caixas_entradassaidas_cadastrar_consumidor_popula_datavenda.php", {
        consumidor:consumidor
    }, function(valor) {
        //alert(valor);
        $("select[name=datavenda]").html(valor);
    });
    $('#link_vervenda').hide();    
}


function datavenda_entradasaida_caixa() {
    consumidor=$("select[name=consumidor]").val();
    datavenda=$("select[name=datavenda]").val();
    //alert(areceber);
    //Popula Venda
    $("select[name=venda]").html('<option>Carregando...</option>');   
    $.post("caixas_entradassaidas_cadastrar_datavenda_popula_venda.php", {
        datavenda:datavenda,
        consumidor:consumidor
    }, function(valor) {
        //alert(valor);
        $("select[name=venda]").html(valor);
    });
    $('#link_vervenda').hide();    
}

function venda_entradasaida_caixa() {
    venda=$("select[name=venda]").val();
    //alert(venda);
    if (venda=="") {
        $('#link_vervenda').hide();

    } else {
        $('#link_vervenda').show();
        document.getElementById("link_vervenda").href="saidas_ver.php?codigo="+venda+"&tiposaida=1&ope=4&botaofechar=1"; 
    }
}


window.onload = function(){
    
    tipo_entradasaida_caixa();
    
    //Mascara valor
    operacao=$("input[name=operacao]").val();
    valortela=$("input[name=valortela]").val();
    if (valortela=="") valortela="R$ 0,00";
    //alert(valortela);
    $("input[name=valor]").val(valortela).priceFormat({prefix: 'R$ ', centsSeparator: ',', thousandsSeparator: '.'});
}

