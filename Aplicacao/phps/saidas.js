
function mascara_filtro_valorbruliq() {
    //alert("aaaaaaa");
    $("input[name=filtro_valorbruliq]").priceFormat({
        prefix: 'R$ ',
        centsSeparator: ',',
        thousandsSeparator: '.'
    });
}

shortcut.add("Ctrl+N", function() {
    link=document.getElementById("link_nova_venda").href;
    window.location = link;
});


