
function mascara_filtro_valorbruliq() {
    //alert("aaaaaaa");
    $("input[name=filtro_valorbruliq]").priceFormat({
        prefix: 'R$ ',
        centsSeparator: ',',
        thousandsSeparator: '.'
    });
}



