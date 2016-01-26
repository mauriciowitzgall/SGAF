function verifica_usuario (tipopessoa) {
    var acesso = $("select[name=possuiacesso]").val();            
    if (acesso==0) {
        document.form1.cpf.required=false;
        document.form1.cnpj.required=false;
        document.form1.senha.disabled=true;
        document.form1.senha2.disabled=true;
        document.form1.grupopermissoes.disabled=true;            
        document.form1.quiosqueusuario.disabled=true;
//        if (document.form1.senhaatual=true) {
//            document.form1.senhaatual.disabled=true;
//        }
    } else if (acesso==1) {
        //alert(tipopessoa);
        if (tipopessoa==1)
            document.form1.cpf.required=true;
        else 
            document.form1.cnpj.required=true;
        document.form1.senha.disabled=false;
        document.form1.senha2.disabled=false;
//        if (document.form1.senhaatual=true) {
//            document.form1.senhaatual.disabled=false;
//        }
        document.form1.grupopermissoes.disabled=false;
        document.form1.quiosqueusuario.disabled=false;
    } else {
        //alert("Erro grave de Javascript! Verifique a funcao verifica_usuario no arquivo pessoas_cadastrar.js");
    }
}
function pessoas_popula_quiosque (valor) {
    $.post("pessoas_popula_quiosque.php",{
        pessoa:$("input[name=codigo]").val(),
        cooperativa:$("select[name=cooperativa]").val(),
        grupo_permissao:valor
    },function(valor2){
        //alert(valor2);
        $("select[name=quiosqueusuario]").html(valor2);
    });    
}