

$(document).ready(function() {
    $("select[name=pais]").change(function() {
        $("select[name=estado]").html('<option>Carregando</option>');   
        $("select[name=cidade]").html('<option>Selecione</option>');    
        $.post("paisestado.php", {
            pais:$(this).val()
        }, function(valor) {
            $("select[name=estado]").html(valor);
        });
    });    
    $("select[name=estado]").change(function() {
        $("select[name=cidade]").html('<option>Carregando</option>');
        $.post("estadocidade.php", {
            estado:$(this).val()
        }, function(valor) {
            $("select[name=cidade]").html(valor);
            $("input[name=ie]").val("");
            $("input[name=im]").val("");
            //alert("aaa");
            var est= document.getElementById("estado").options[document.getElementById("estado").selectedIndex].text;
            mascara_ie(est);
        });
    });

    estado=document.getElementById("estado").options[document.getElementById("estado").selectedIndex].text;
    //alert(estado);
    mascara_ie(estado);
    
    var usa= $("input[name=usamodulofiscal]").val();
    usa_modulo_fiscal(usa);
    
});

function valida_ie2 (valor) {
    estado=document.getElementById("estado").options[document.getElementById("estado").selectedIndex].text;
    //alert("mascara im conforme estado: "+estado); 
    pesquisa_ie(valor,estado);
}


function usa_modulo_fiscal (usa) {
    var tipopessoa= $("input[name=tipopessoa]").val();
    if (usa==1) {
        document.form1.ie.required=true;
        document.form1.endereco.required=true;
        document.form1.numero.required=true;
        document.form1.bairro.required=true;
        document.form1.cep.required=true;
        if (tipopessoa==1) {
            document.form1.cpf.required=true;
            document.form1.cnpj.required=false;
            document.form1.razaosocial.required=false;
        } else if (tipopessoa==2){
            document.form1.cpf.required=false;
            document.form1.cnpj.required=true;  
            document.form1.razaosocial.required=true; 
        } else {
            alert("Erro grave, contate o suporte! Erro: 5434")
        }
    } else {
        
    }
}
