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
        });
    });   
});

