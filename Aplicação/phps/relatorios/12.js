$(document).ready(function() {
    $("select[name=cooperativa]").change(function() {
        $("select[name=quiosque]").html('<option>Carregando</option>');   
        $.post("relatorios/12_popula_quiosque.php", {
            cooperativa:$(this).val()
        }, function(valor) {
            $("select[name=quiosque]").html(valor);
        });
        $("select[name=produto]").html("<option value=''>Todos</option>");
    });    
    $("select[name=quiosque]").change(function() {
        $("select[name=produto]").html('<option>Carregando</option>');   
        $.post("relatorios/12_popula_produto.php", {
            quiosque:$(this).val()
        }, function(valor) {
            $("select[name=produto]").html(valor);
        });
    });    
});


