

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
    
    
});

function valida_ie2 (valor) {
    estado=document.getElementById("estado").options[document.getElementById("estado").selectedIndex].text;
    //alert("mascara im conforme estado: "+estado); 
    pesquisa_ie(valor,estado);
}


