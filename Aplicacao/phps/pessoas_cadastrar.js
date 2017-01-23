function verifica_usuario (tipopessoa) {
    var acesso = $("select[name=possuiacesso]").val();            
    //alert(tipopessoa+"/"+acesso);
    if (acesso==0) {
        document.form1.cpf.required=false;
        document.form1.cnpj.required=false;
        //document.form1.senha.disabled=true;
        //document.form1.senha2.disabled=true;
        //document.form1.grupopermissoes.disabled=true;            
        //document.form1.quiosqueusuario.disabled=true;
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
            document.form1.senha.disabled=false;
            document.form1.senha2.disabled=false;
            document.form1.grupopermissoes.disabled=false;
            document.form1.quiosqueusuario.disabled=false;
            document.form1.cnpj.required=true;
            document.form1.pais.required=true;
            document.form1.estado.required=true;
            document.form1.cidade.required=true;
            document.form1.cidade.required=true;
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
        $("tr[id=tr_cnpj]").hide();            
        $("tr[id=tr_pessoacontato]").hide(); 
        $("input[id=telefone1ramal]").hide(); 
        $("input[id=telefone2ramal]").hide(); 
        $("tr[id=tr_cpf]").show(); 
        $("tr[id=tr_datanasc]").show(); 
        $("span[id=span_administrador]").show(); 
        $("span[id=span_gestor]").show(); 
        $("span[id=span_supervisor]").show(); 
        $("span[id=span_caixa]").show();             
        $("tr[id=linha_razaosocial]").hide();             
        $("tr[id=linha_ie]").hide();             
        $("tr[id=linha_im]").hide();             
        $("tr[id=linha_contribuinteicms]").hide();   
        $("select[name=cnpj]").attr("required", false);  
        $("select[name=razaosocial]").attr("required", false);  
        $("select[name=ie]").attr("required", false);  
        $("select[name=im]").attr("required", false);  
        $("select[name=contribuinteicms]").attr("required", false); 
        $("select[name=categoria]").attr("required", false);
        $("select[name=pais]").attr("required", false);            
        $("select[name=estado]").attr("required", false);            
        $("select[name=cidade]").attr("required", false);            
        $("select[name=contribuinteicms]").attr("required", false);        
        var usamodulofiscal=$("input[name=usamodulofiscal]").val();
        if (usamodulofiscal==1) {
            $("input[name=cpf]").attr("required", true);
        } else {
            $("input[name=cpf]").attr("required", false);
        }
    } else if (valor==2) { //Pessoa Jurídica
        //alert('2');
        $("tr[id=tr_categoria]").show(); 
        $("tr[id=tr_cnpj]").show();            
        $("tr[id=tr_pessoacontato]").show(); 
        $("input[id=telefone1ramal]").show(); 
        $("input[id=telefone2ramal]").show(); 
        $("tr[id=tr_cpf]").hide(); 
        $("tr[id=tr_datanasc]").hide(); 
        $("span[id=span_administrador]").hide(); 
        $("span[id=span_gestor]").hide(); 
        $("span[id=span_supervisor]").hide(); 
        $("span[id=span_caixa]").hide(); 
        $("tr[id=linha_razaosocial]").show();             
        $("tr[id=linha_ie]").show();             
        $("tr[id=linha_im]").show();             
        $("tr[id=linha_contribuinteicms]").show();
        $("select[name=categoria]").attr("required", true);            
        $("input[name=cpf]").attr("required", false);
        var usamodulofiscal=$("input[name=usamodulofiscal]").val();
        //alert(usamodulofiscal);
        if (usamodulofiscal==1) {
            $("input[name=cnpj]").attr("required", true);            
            $("input[name=ie]").attr("required", true);            
            $("input[name=im]").attr("required", true);            
            $("input[name=razaosocial]").attr("required", true);            
            $("select[name=pais]").attr("required", true);            
            $("select[name=estado]").attr("required", true);            
            $("select[name=cidade]").attr("required", true);            
            $("select[name=contribuinteicms]").attr("required", true);            
        } else {
            $("input[name=cnpj]").attr("required", false);            
            $("input[name=ie]").attr("required", false);            
            $("input[name=im]").attr("required", false);            
            $("input[name=razaosocial]").attr("required", false);            
            $("select[name=pais]").attr("required", false);            
            $("select[name=estado]").attr("required", false);            
            $("select[name=cidade]").attr("required", false);            
            $("select[name=contribuinteicms]").attr("required", false);   
        }
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

        //Verifica o tipo de pessoa fisica ou jurídica para mostrar os campos corretos na tela
        pessoa=$("select[name=tipopessoa]").val();
        //alert(pessoa);
        tipo_pessoa(pessoa);
        
        //Verifica qual mascara de IE deve usar
        estado=document.getElementById("estado").options[document.getElementById("estado").selectedIndex].text;
        mascara_ie(estado);

});

function valida_ie2 (valor) {
    estado=document.getElementById("estado").options[document.getElementById("estado").selectedIndex].text;
    //alert("mascara im conforme estado: "+estado); 
    pesquisa_ie(valor,estado);
}

function popula_cidades2() {
    $("select[name=cidade]").html('<option>Carregando</option>');
    $.post("estadocidade.php", {
        estado: $("select[name=estado]").val()
    }, function(valor) {
        $("select[name=cidade]").html(valor);
        $("input[name=ie]").val("");
        $("input[name=im]").val("");
        //alert("aaa");
        var est= document.getElementById("estado").options[document.getElementById("estado").selectedIndex].text;
        mascara_ie(est);
    });
}