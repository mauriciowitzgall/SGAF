
function referencia_valida_caracteres_especiais (valor) {
    if(valor.match(/'/)) valor = valor.replace("'","");
    if(valor.match(/!/)) valor = valor.replace("!","");
    if(valor.match(/@/)) valor = valor.replace("@","");
    if(valor.match(/#/)) valor = valor.replace("#","");
    if(valor.match(/$/)) valor = valor.replace("$","");
    if(valor.match(/%/)) valor = valor.replace("%","");
    if(valor.match(/^/)) valor = valor.replace("ˆ","");
    if(valor.match(/&/)) valor = valor.replace("&","");
    if(valor.match(/{/)) valor = valor.replace("{","");
    if(valor.match(/}/)) valor = valor.replace("}","");
    if(valor.match(/|/)) valor = valor.replace("|","");
    if(valor.match(/˜/)) valor = valor.replace("˜","");
    if(valor.match(/`/)) valor = valor.replace("`","");
    //alert(valor);
    $("input[name=referencia_nova]").val(valor);
    produto=$("input[name=codigo_produto]").val();
    if (valor=="") {
        document.getElementById("referencia_icone").src="../imagens/icones/geral/confirmar2.png";
        $("span[id=span_ref]").text("Ex: numero do pedido/cardápio");
        document.getElementById("span_ref").classList.remove("correto2");
        document.getElementById("span_ref").classList.remove("errado2");
        document.getElementById("span_ref").classList.add("dicacampo");

    } else { 
        $.post("produtos_valida_referencia.php",{
            ref:valor,
            produto:produto
        },function(valor2){
            //alert(valor2);
            if (valor2==1) { //Já existe uma referencia igual cadastrada em outro produto
                document.getElementById("referencia_icone").src="../imagens/icones/geral/erro.png";
                $("span[id=span_ref]").text("Referência em uso!");
                document.getElementById("span_ref").classList.remove("dicacampo");
                document.getElementById("span_ref").classList.remove("correto2");
                document.getElementById("span_ref").classList.add("errado2");
            } else {
                document.getElementById("span_ref").classList.remove("dicacampo");
                document.getElementById("span_ref").classList.remove("errado2");
                document.getElementById("span_ref").classList.add("correto2");
                document.getElementById("referencia_icone").src="../imagens/icones/geral/confirmar.png";
                $("span[id=span_ref]").text("Refência válida");
            }
        });
    }
}


function verifica_referencia_valida(valor) {
    
    if (valor!="") {
        produto=$("input[name=codigo_produto]").val();
        $.post("produtos_valida_referencia.php",{
            ref:valor,
            produto:produto
        },function(valor2){
            //alert(valor2);
            if (valor2==1) { //Já existe uma referencia igual cadastrada em outro produto
                alert("Já existe um produto cadastrado com esta referencia!");
                 $("input[name=referencia]").val("");
                 $("input[name=referencia]").focus();
                 referencia_valida_caracteres_especiais("");
            } else {
                
            }
        });   
    } else {
        document.getElementById("referencia_icone").src="../imagens/icones/geral/confirmar2.png";
    }


}