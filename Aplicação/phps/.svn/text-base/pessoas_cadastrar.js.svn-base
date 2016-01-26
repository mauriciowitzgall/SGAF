$(document).ready(function() {
    //Ao selecionar o pais popular a lista de estados
    $("select[name=pais]").change(function() {
        $("select[name=estado]").html('<option>Carregando</option>');   
        $("select[name=cidade]").html('<option>Selecione</option>');    
        $.post("paisestado.php", {
            pais:$(this).val()
        }, function(valor) {
            $("select[name=estado]").html(valor);
        });
    });    
    //Ao selecionar o estado popular a lista de cidades
    $("select[name=estado]").change(function() {
        $("select[name=cidade]").html('<option>Carregando</option>');
        $.post("estadocidade.php", {
            estado:$(this).val()
        }, function(valor) {
            $("select[name=cidade]").html(valor);
        });
    });       
    
    //Ao selecionar a cooperativa popular quiosques
    $("select[name=cooperativa]").change(function() {
        $("select[name=quiosqueusuario]").html('<option>Carregando</option>');   
        $.post("verifica_quiosqueusuario.php", {
            cooperativa:$(this).val(),
            pessoa:$("input[name=codigo]").val()            
        }, function(valor) {
            $("select[name=quiosqueusuario]").html(valor);
        });
    });
       
    //Ao entrar pela primeira vez na pagina já verificar se o usuário tem acesso ao sistema ou não
    verifica_usuario ();      
    
    //Se o usuário não tem acesso ao sistema então desabilitar alguns campos
    $("select[name=possuiacesso]").change(function () {
        verifica_usuario ();
    });
});

function verifica_usuario () {
    var acesso = $("select[name=possuiacesso]").val();        
    if (acesso==0) {
        document.form1.senha.disabled=true;
        document.form1.senha2.disabled=true;
        document.form1.grupopermissoes.disabled=true;            
        document.form1.quiosqueusuario.disabled=true;
//        if (document.form1.senhaatual=true) {
//            document.form1.senhaatual.disabled=true;
//        }
    } else {
        document.form1.senha.disabled=false;
        document.form1.senha2.disabled=false;
//        if (document.form1.senhaatual=true) {
//            document.form1.senhaatual.disabled=false;
//        }
        document.form1.grupopermissoes.disabled=false;
        document.form1.quiosqueusuario.disabled=false;
    } 
}
    
   
//function verifica_senhas(){
//    senhaatual= document.form1.senhaatual.value;
//    
//    //Verifica se o campo senha atual existe
//    if (typeof(senhaatual)=='undefined') {
//        //O campo não existe, então só comparar as senhas novas
//        compara_senhas();
//    //alert('NÃO EXISTE')
//    } else {
//        //O campo existe, verificar se a senha digitada é igual a senha do banco
//        $.post("verifica_senha.php", { 
//            
//        }, function(valor) {            
//                alert(valor);           
//        });
//        alert('...');
//    }
//}
//
//function compara_senhas (){
//    alert('Comparando as senhas...');
//    senha1 = document.form1.senha.value
//    senha2 = document.form1.senha2.value
//
//    if (senha1 == senha2)
//        alert("SENHAS IGUAIS")
//    else
//        alert("SENHAS DIFERENTES")
//}   