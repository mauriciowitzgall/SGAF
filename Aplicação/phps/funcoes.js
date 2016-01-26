function valida_filtro_entradas_numero() {  
    if(document.formfiltro.filtronumero.value == "") {
        document.formfiltro.filtrofornecedor.disabled=false;
        document.formfiltro.filtrosupervisor.disabled=false;
        document.formfiltro.filtroproduto.disabled=false;
    }
    else {  
        document.formfiltro.filtrofornecedor.disabled=true;
        document.formfiltro.filtrosupervisor.disabled=true;
        document.formfiltro.filtroproduto.disabled=true;
    } 
}
function valida_filtro_saidas_numero() {  
    if(document.formfiltro.filtro_numero.value == "") {
        document.formfiltro.filtro_consumidor.disabled=false;
        document.formfiltro.filtro_produto.disabled=false;
        document.formfiltro.filtro_lote.disabled=false;
    }
    else {  
        document.formfiltro.filtro_consumidor.disabled=true;
        document.formfiltro.filtro_produto.disabled=true;
        document.formfiltro.filtro_lote.disabled=true;
    } 
}
function valida_filtro_saidas_devolucao_numero() {  
    if(document.form_filtro.filtro_numero.value == "") {
        document.form_filtro.filtro_motivo.disabled=false;
        document.form_filtro.filtro_descricao.disabled=false;
        document.form_filtro.filtro_supervisor.disabled=false;
    //document.formfiltro.filtro_fornecedor.disabled=false;
    }
    else {  
        document.form_filtro.filtro_motivo.disabled=true;
        document.form_filtro.filtro_descricao.disabled=true;
        document.form_filtro.filtro_supervisor.disabled=true;
    //document.formfiltro.filtro_fornecedor.disabled=true;
    } 
}
function valida_filtro_pessoas_id() {  
    if(document.formfiltro.filtroid.value == "") {
        document.formfiltro.filtronome.disabled=false;
        document.formfiltro.filtrotipo.disabled=false;
    }
    else {  
        document.formfiltro.filtronome.disabled=true;
        document.formfiltro.filtronome.value="";
        document.formfiltro.filtrotipo.disabled=true;
    } 
}
function validarEntero(valor){
    //intento convertir a entero. 
    //si era un entero no le afecta, si no lo era lo intenta convertir
    valor = parseInt(valor)

    //Compruebo si es un valor numérico
    if (isNaN(valor)) {
        //entonces (no es un numero) devuelvo el valor cadena vacia
        return ""
    }else{
        //En caso contrario (Si era un número) devuelvo el valor
        return valor
    }
}

function valida_entradas_qtd(){
    //extraemos el valor del campo
    textoCampo = window.document.form1.qtd.value
    //lo validamos como entero
    textoCampo = validarEntero(textoCampo)
    //colocamos el valor de nuevo
    window.document.form1.qtd.value = textoCampo
}

function valida_entradas_id(){
    //extraemos el valor del campo
    textoCampo = window.document.form1.idfornecedor.value
    //lo validamos como entero
    textoCampo = validarEntero(textoCampo)
    //colocamos el valor de nuevo
    window.document.form1.idfornecedor.value = textoCampo

    //aqui é feito o tratamento para desabilitar alguns campos do formulario ao entrar neste campo!
    if(document.form1.idfornecedor.value == "") {
        document.form1.fornecedor.disabled=false;
    }
    else {  
        document.form1.fornecedor.disabled=true;
    } 
}
//funcao que faz os iframes auto redimensionar a altura conforme o seu conteudo
function autoResize(id){
    var newheight;
    var newwidth;

    if(document.getElementById){
        newheight=document.getElementById(id).contentWindow.document .body.scrollHeight;
        newwidth=document.getElementById(id).contentWindow.document .body.scrollWidth;
    }

    document.getElementById(id).height= (newheight) + "px";
    document.getElementById(id).width= (newwidth) + "px";
}



function somente_numero(campo) {  
    var digits="0123456789.,"
    var campo_temp 
    for (var i=0;i<campo.value.length;i++){
        campo_temp=campo.value.substring(i,i+1) 
        if (digits.indexOf(campo_temp)==-1){
            campo.value = campo.value.substring(0,i);
        }
    }    
}
function mascara_quantidade() {
    $('#qtd').priceFormat({
        prefix: '',
        centsSeparator: ',',
        thousandsSeparator: '.'
    });
    verifica_incluir();
}

function mascara_pesoqtd(){  
    $.post("entradas_qtdtipcon.php",{
        produto:$("select[name=produto]").val()
    },function(valor){
        $("span[name=qtdtipconcod]").html(valor);
        //alert (valor);
        if (valor=="<b>por un.</b>") {
            $('#qtd').priceFormat({
                prefix: '',  
                centsSeparator: '',  
                centsLimit:0,  
                thousandsSeparator: '.'  
            }); 
            //alert("qtd");
        } else {
            $('#qtd').priceFormat({
                prefix: '',  
                centsSeparator: ',',  
                centsLimit: 3,  
                thousandsSeparator: '.'  
            }); 
            //alert("peso");
        }            
    });
    verifica_incluir();
}  
function mascara_quantidade_ideal() {
    $('#qtdide').priceFormat({
        prefix: '',
        centsSeparator: ',',
        thousandsSeparator: '.'
    });
    verifica_incluir();
}

function pessoas_filtro_id() {
    
    if(document.formfiltro.filtro_id.value == "") {
        document.formfiltro.filtro_nome.disabled=false;
        document.formfiltro.filtro_tipo.disabled=false;
        document.formfiltro.filtro_possuiacesso.disabled=false;
    }
    else {  
        document.formfiltro.filtro_nome.disabled=true;
        document.formfiltro.filtro_nome.value="";
        document.formfiltro.filtro_tipo.disabled=true;
        document.formfiltro.filtro_possuiacesso.disabled=true;
    } 
}

//FUNÇÕES DA TELA DE PRODUTOS CADASTRAR
function sigla() {
    $.post("tipo_contagem_sigla.php", {
        tipocontagem: $("select[name=tipo]").val()
    }, function (valor) {
        $("label[name=sigla]").html(valor);        
    });
}
function etqmin() {
    $('#etqmin').priceFormat({
        prefix: '',
        centsSeparator: ',',
        thousandsSeparator: '.'
    }); 
}
function valorpago() {                    
    $('#valpago').priceFormat({
        prefix: 'R$ ',
        centsSeparator: ',',
        thousandsSeparator: '.'
    });
} 
function estoquemin() {                    
    $('#estoqueminimo').priceFormat({
        prefix: '',
        centsSeparator: ',',
        thousandsSeparator: '.'
    });
} 