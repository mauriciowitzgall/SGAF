$(document).ready(function() {
    $("input[name=descontomin]").val("0").priceFormat({
                prefix: '',
                centsLimit: 2,
                centsSeparator: ',',
                thousandsSeparator: '.'
    }); 
});


