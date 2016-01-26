$(document).ready(function() {
    $("select[name=pais]").change(function() {
        $("select[name=estado]").html('<option>Carregando</option>');   
        $("select[name=cidade]").html('<option>Selecione</option>');    
        $.post("cidades_paisestado.php", {
            pais:$(this).val()
        }, function(valor) {
            $("select[name=estado]").html(valor);
        });
    });    
});

