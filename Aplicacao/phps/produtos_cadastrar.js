function atualiza_categorias () {
$.post("produtos_cadastrar_atualiza_categorias.php",{
        cooperativa:$("input[name=cooperativa_oculto]").val()
    },function(valor2){
        //alert(valor2);
        $("select[name=categoria]").html(valor2);
    });    
}
 
function atualiza_recipientes () {
    $.post("produtos_cadastrar_atualiza_recipientes.php",{
        cooperativa:$("input[name=cooperativa_oculto]").val()
    },function(valor2){
        //alert(valor2);
        $("select[name=recipiente").html(valor2);
    });    
}


function pesquisa_ncm (valor) {
    //alert("Pesquisa e popula label NCM: "+valor);
    $.post("produtos_pesquisa_ncm.php",{
        id:valor
    },function(valor2){
        //alert(valor2);
        valor2=valor2.split("^");
        valor3=valor2[0];
        valor4=valor2[1];
        //alert("v3:"+valor3+" e v4:"+valor4);
        $("label[id=label_ncm]").text(valor3);
        $("input[name=nfencm_codigo]").val(valor4);
    });
}
        
function pesquisa_cfop (valor) {
    /*
    $('#nfecfop').priceFormat({
        prefix: '',
        sufix: '',
        centsLimit: 0,
        centsSeparator: '',
        thousandsSeparator: '.'
    });
    */
    $.post("produtos_pesquisa_cfop.php",{
        id:valor
    },function(valor2){
        valor2=valor2.split("/");
        valor3=valor2[0];
        valor4=valor2[1];
        $("label[id=label_cfop]").text(valor3);
        $("input[name=nfecfop_codigo]").val(valor4);
    });
}


function pesquisa_origem (valor) {
       
    $.post("produtos_pesquisa_origem.php",{
        codigo:valor
    },function(valor2){
        //alert(valor2);
        $("label[id=label_origem]").text(valor2);
    });
}


function formato_porcentagem() {
    $('#nfeipi').priceFormat({
        prefix: '',
        centsSeparator: ',',
        thousandsSeparator: ''
    });
    $('#nfepis').priceFormat({
        prefix: '',
        centsSeparator: ',',
        thousandsSeparator: ''
    });
    $('#nfecofins').priceFormat({
        prefix: '',
        centsSeparator: ',',
        thousandsSeparator: ''
    });
}
   
function dados_fiscais(valor) {
    crt = $("input[name=nfecrt]").val();
    if (valor==1) {
        document.form1.nfencm.required=true;
        document.form1.nfecfop.required=true;
        document.form1.nfeorigem.required=true;

        $("tr[id=linha_ncm]").show(); 
        $("tr[id=linha_cfop]").show(); 
        $("tr[id=linha_icms]").show(); 
        $("tr[id=linha_ipi]").show();
        $("tr[id=linha_pis]").show();
        $("tr[id=linha_cofins]").show();
        $("tr[id=linha_origem]").show();


    } else {
        document.form1.nfencm.required=false;
        document.form1.nfecfop.required=false;
        document.form1.nfeipi.required=false;
        document.form1.nfepis.required=false;
        document.form1.nfecofins.required=false;
        document.form1.nfeorigem.required=false;
        $("tr[id=linha_ncm]").hide(); 
        $("tr[id=linha_cfop]").hide(); 
        $("tr[id=linha_icms]").hide(); 
        $("tr[id=linha_ipi]").hide();
        $("tr[id=linha_pis]").hide();
        $("tr[id=linha_cofins]").hide();
        $("tr[id=linha_origem]").hide();
    }
  
}

function referencia_valida_caracteres_especiais (valor) {
    valor = remove_caracteres_especiais(valor);
    //alert(valor);
    $("input[name=referencia]").val(valor);
    produto=$("input[name=codigo_produto]").val();
    if (valor=="") {
        document.getElementById("referencia_icone").src="../imagens/icones/geral/confirmar2.png";
        $("span[id=span_ref]").text("Ex: numero do pedido/cardápio");
        document.getElementById("span_ref").classList.remove("correto2");
        document.getElementById("span_ref").classList.remove("errado2");
        document.getElementById("span_ref").classList.add("dicacampo");

    } else { 
        $.post("produtos_valida_referencia.php",{
            ref:valor,
            produto:produto
        },function(valor2){
            //alert(valor2);
            if (valor2==1) { //Já existe uma referencia igual cadastrada em outro produto
                document.getElementById("referencia_icone").src="../imagens/icones/geral/erro.png";
                $("span[id=span_ref]").text("Referência em uso!");
                document.getElementById("span_ref").classList.remove("dicacampo");
                document.getElementById("span_ref").classList.remove("correto2");
                document.getElementById("span_ref").classList.add("errado2");
            } else {
                document.getElementById("span_ref").classList.remove("dicacampo");
                document.getElementById("span_ref").classList.remove("errado2");
                document.getElementById("span_ref").classList.add("correto2");
                document.getElementById("referencia_icone").src="../imagens/icones/geral/confirmar.png";
                $("span[id=span_ref]").text("Refência válida");
            }
        });
    }
}


function verifica_referencia_valida(valor) {
    
    if (valor!="") {
        produto=$("input[name=codigo_produto]").val();
        $.post("produtos_valida_referencia.php",{
            ref:valor,
            produto:produto
        },function(valor2){
            //alert(valor2);
            if (valor2==1) { //Já existe uma referencia igual cadastrada em outro produto
                alert("Já existe um produto cadastrado com esta referencia!");
                 $("input[name=referencia]").val("");
                 $("input[name=referencia]").focus();
                 referencia_valida_caracteres_especiais("");
            } else {
                
            }
        });   
    } else {
        document.getElementById("referencia_icone").src="../imagens/icones/geral/confirmar2.png";
    }


}

function verifica_controlarestoque () {
    controlarestoque=$("select[name=controlarestoque]").val();
    if (controlarestoque==0) {
        $("tr[id=valunicusto_linha]").show(); 
        $("tr[id=valunivenda_linha]").show(); 
        document.form1.valunivenda.required=true;
        $("input[name=valunicusto]").priceFormat({
            prefix: 'R$ ',
            centsSeparator: ',',
            thousandsSeparator: '.'
        });

        $("input[name=valunivenda]").priceFormat({
            prefix: 'R$ ',
            centsSeparator: ',',
            thousandsSeparator: '.'
        });        
    } else {
        $("tr[id=valunicusto_linha]").hide(); 
        $("tr[id=valunivenda_linha]").hide(); 
        document.form1.valunivenda.required=false;
    }



}

function verifica_evendido () {
    evendido=$("select[name=evendido]").val();
    if (evendido==1) {
        $("tr[id=linha_tiponeg]").show(); 
        //document.form1.box.required=true;
    } else {
        $("tr[id=linha_tiponeg]").hide(); 
        //if (document.form1.box) document.form1.box.required=false;
    }
}

   
window.onload = function(){
    //industrializado
    ind=$("select[name=industrializado]").val();
    if (ind==0) {
        $("tr[id=id_marca]").hide(); 
        $("tr[id=id_codigounico]").hide(); 
    } else {
        $("tr[id=id_marca]").show(); 
        $("tr[id=id_codigounico]").show(); 
    }
    
    //tipo de contagem    
    tipocon=$("select[name=tipo]").val();
    if ((tipocon==2)||(tipocon==3)) {
        $("tr[id=id_volume]").hide(); 
        $("tr[id=id_recipiente]").hide(); 
    } else {
        $("tr[id=id_volume]").show(); 
        $("tr[id=id_recipiente]").show(); 
        
    }
    
    //Produto vendido sim ou nao
    var usavendas=$("input[name=usavendas]").val();
    if (usavendas==1) {
        $("tr[id=linha_evendido]").show(); 
    } else {
        $("tr[id=linha_evendido]").hide(); 
    }


    verifica_controlarestoque();
    
    
    var dadosfiscais = $("select[name=dadosfiscais]").val();
    dados_fiscais(dadosfiscais);
    
    var ncm = $("input[name=nfencm]").val();
    pesquisa_ncm(ncm);
    
    var cfop = $("input[name=nfecfop]").val();
    pesquisa_cfop(cfop);
    
    var origem = $("input[name=nfeorigem]").val();
    pesquisa_origem(origem);
    
    verifica_evendido();



    //referencia_valida_caracteres_especiais("");
    
}   

