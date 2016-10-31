function verifica_usuario (tipopessoa) {
    var acesso = $("select[name=possuiacesso]").val();            
    //alert(tipopessoa+"/"+acesso);
    if (acesso==0) {
        document.form1.cpf.required=false;
        document.form1.cnpj.required=false;
        document.form1.senha.disabled=true;
        document.form1.senha2.disabled=true;
        document.form1.grupopermissoes.disabled=true;            
        document.form1.quiosqueusuario.disabled=true;
//        if (document.form1.senhaatual=true) {
//            document.form1.senhaatual.disabled=true;
//        }
    } else if (acesso==1) {
        //alert(tipopessoa);
        if (tipopessoa==1) {
            document.form1.cpf.required=true;
            document.form1.cnpj.required=false;
        } else {
            document.form1.cpf.required=false;
            document.form1.cnpj.required=true;
            document.form1.senha.disabled=false;
            document.form1.senha2.disabled=false;
            document.form1.grupopermissoes.disabled=false;
            document.form1.quiosqueusuario.disabled=false;
        } 
    } else {
        //alert("Erro grave de Javascript! Verifique a funcao verifica_usuario no arquivo pessoas_cadastrar.js");
    }
}
function pessoas_popula_quiosque (valor) {
    $.post("pessoas_popula_quiosque.php",{
        pessoa:$("input[name=codigo]").val(),
        cooperativa:$("select[name=cooperativa]").val(),
        grupo_permissao:valor
    },function(valor2){
        //alert(valor2);
        $("select[name=quiosqueusuario]").html(valor2);
    });    
}




function tipo_pessoa(valor) {
    if (valor==1) { //Pessoa Física
        $("tr[id=tr_categoria]").hide(); 
        $("select[name=categoria]").attr("required", false);  
        $("tr[id=tr_cnpj]").hide();            
        $("tr[id=tr_cnpj]").attr("required", false);
        $("tr[id=tr_pessoacontato]").hide(); 
        $("input[id=telefone1ramal]").hide(); 
        $("input[id=telefone2ramal]").hide(); 
        $("tr[id=tr_cpf]").attr("required", true);
        $("tr[id=tr_cpf]").show(); 
        $("tr[id=tr_datanasc]").show(); 
        $("span[id=span_administrador]").show(); 
        $("span[id=span_gestor]").show(); 
        $("span[id=span_supervisor]").show(); 
        $("span[id=span_caixa]").show();             
    } else if (valor==2) { //Pessoa Jurídica
        //alert('2');
        $("tr[id=tr_categoria]").show(); 
        $("select[name=categoria]").attr("required", true);            
        $("tr[id=tr_cnpj]").show();            
        //$("input[name=cnpj]").attr("required", true);
        //$("input[name=cnpj]").required = true;
        $("tr[id=tr_pessoacontato]").show(); 
        $("input[id=telefone1ramal]").show(); 
        $("input[id=telefone2ramal]").show(); 
        $("tr[id=tr_cpf]").attr("required", false);
        $("tr[id=tr_cpf]").hide(); 
        $("tr[id=tr_datanasc]").hide(); 
        $("span[id=span_administrador]").hide(); 
        $("span[id=span_gestor]").hide(); 
        $("span[id=span_supervisor]").hide(); 
        $("span[id=span_caixa]").hide(); 
    } else {
        alert("Erro de envio de parametros para a função");
    }       
}

function aparece_tiponegociacao() {
    //alert("opa");
    var fornec= $("input[id=fornec]").val();
    //alert(fornec);
    if (document.form1.fornec.checked == true) {
        $("tr[id=tr_tiponegociacao]").show();
    }
    else
        $("tr[id=tr_tiponegociacao]").hide();       
}

$(document).ready(function() {

        aparece_tiponegociacao();

        tippes = $("select[name=tipopessoa]").val();    
        verifica_usuario (tippes); 


});