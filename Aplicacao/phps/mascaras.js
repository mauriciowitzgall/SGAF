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
   
    $("#telefone1").mask("(99)9999-9999");
    $("#telefone2").mask("(99)9999-9999");
    $("#telefone1pv").mask("(99)9999-9999");
    $("#telefone2pv").mask("(99)9999-9999");
    $("#cpf").mask("999.999.999-99");
    $("#cep").mask("99999-999");
    $("#cep2").mask("99999-999");
    $("#data").mask("99/99/9999");
    $("#data_1").mask("99/99/9999");
    $("#data_2").mask("99/99/9999");
    $("#data_5").mask("99/99/9999");
    $("#calendario").mask("99/99/9999");
    $("#cnpj").mask("99.999.999/9999-99");
});


