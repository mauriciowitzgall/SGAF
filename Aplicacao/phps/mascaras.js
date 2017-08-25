$(document).ready(function(){
    
    /*
    var obj = $("#telefone1");
    $(obj).mask(
        ($(obj).val().length > 13) ? '(99)99999-9999' : '(99)9999-9999', {
            onKeyPress: function(phone, e, currentField, options){
                var new_sp_phone = phone.match(/^(\(11\)9(5[0-9]|6[0-9]|7[01234569]|8[0-9]|9[0-9])[0-9]{1})/g);
                alert("aaaa");
                new_sp_phone ? $(currentField).mask('(99)99999-9999', options) : $(currentField).mask('(99)9999-9999', options)
            }
        }
    );
    */

    if (document.getElementById('telefone1')) $("#telefone1").mask("(99)9999-9999");
    if (document.getElementById('telefone2')) $("#telefone2").mask("(99)9999-9999");
    if (document.getElementById('telefone1pv')) $("#telefone1pv").mask("(99)9999-9999");
    if (document.getElementById('telefone2pv')) $("#telefone2pv").mask("(99)9999-9999");
    if (document.getElementById('cpf')) $("#cpf").mask("999.999.999-99");
    if (document.getElementById('cep')) $("#cep").mask("99999-999");
    if (document.getElementById('cep2')) $("#cep2").mask("99999-999");
    if (document.getElementById('data')) $("#data").mask("99/99/9999");
    if (document.getElementById('data_1')) $("#data_1").mask("99/99/9999");
    if (document.getElementById('data_2')) $("#data_2").mask("99/99/9999");
    if (document.getElementById('data_5')) $("#data_5").mask("99/99/9999");
    if (document.getElementById('calendario'))  $("#calendario").mask("99/99/9999");
    if (document.getElementById('cnpj')) $("#cnpj").mask("99.999.999/9999-99");
});


