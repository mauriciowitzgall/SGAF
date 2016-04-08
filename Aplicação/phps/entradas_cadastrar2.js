window.onload = function(){
    //alert("ui");
}


function habilitar_quantidade (valor,nomecomlote,tipocontagem,nomesemlote,produto,subproduto,item) {
    nome1="qtddigitada_"+trim(nomecomlote);
    nome2="qtdaretirar_"+trim(nomesemlote);
    nome3="qtdselecionada_"+trim(nomesemlote);
    nome4="qtdemestoque_"+trim(nomecomlote);
    nome5="span_qtdselecionada_"+trim(nomesemlote);
    nome6="situacao_"+trim(nomesemlote);
    
    //alert("("+nome2+")");

    if (valor == true) {
        
        //Habilitar campo
        document.forms["formfiltro"].elements[nome1].disabled = false;
        
        
        //Popular o campo quantidade com a quantidade que resta para completar a seleção do subproduto.
        //Se a quantidade em estoque for menor que essa quantidade a ser preenchida, então popular com a quantidade do estoque
        qtdaretirar=document.forms["formfiltro"].elements[nome2].value;
        qtdaretirar=parseFloat(qtdaretirar);
        
        qtdselecionada=document.forms["formfiltro"].elements[nome3].value;
        qtdselecionada=parseFloat(qtdselecionada);
        
        qtdpopular=qtdaretirar-qtdselecionada;
        qtdpopular=parseFloat(qtdpopular);
        
        
        qtdemestoque=document.forms["formfiltro"].elements[nome4].value;
        qtdemestoque=parseFloat(qtdemestoque);
        
        //Se a quantidade em estoque é inferior a quantidade necessária a retirar então popula a do estoque
        if (qtdemestoque < qtdpopular) qtdpopular=qtdemestoque;

        //Popula qtddigitada
        if ((tipocontagem==2)||(tipocontagem==3)) {
            $("input[name="+nome1+"]").val(qtdpopular.toFixed(3)).priceFormat({
                prefix: '',
                centsSeparator: ',',
                centsLimit: 3,
                thousandsSeparator: '.'
            });  
        } else {
            $("input[name="+nome1+"]").val(qtdpopular).priceFormat({
                prefix: '',
                centsSeparator: '',
                centsLimit: 0,
                thousandsSeparator: ''
            });        
        }

        //Popula qtdselecionada
        qtdselecionadanova=qtdselecionada+qtdpopular;
        qtdselecionadanova=parseFloat(qtdselecionadanova);
        if ((tipocontagem==2)||(tipocontagem==3)) {
            qtdselecionadanova=qtdselecionadanova.toFixed(3);
        }

        $("input[name="+nome3+"]").val(qtdselecionadanova);
        if ((tipocontagem==2)||(tipocontagem==3)) {
            $("span[id="+nome5+"]").text(qtdselecionadanova);
        } else {
            $("span[id="+nome5+"]").text(qtdselecionadanova);
        }
        
        
    } else {
        //alert("desabilitar");
        
        qtddigitada=document.forms["formfiltro"].elements[nome1].value;
        qtddigitada=qtddigitada.replace(".","");
        qtddigitada=qtddigitada.replace(",",".");
        qtddigitada=parseFloat(qtddigitada);
        
        qtdselecionada=document.forms["formfiltro"].elements[nome3].value;
        qtdselecionada=parseFloat(qtdselecionada);
        
        qtdselecionadanova=qtdselecionada-qtddigitada;
        qtdselecionadanova=parseFloat(qtdselecionadanova);
        

        $("input[name="+nome3+"]").val(qtdselecionadanova);
        if ((tipocontagem==2)||(tipocontagem==3)) {
            $("span[id="+nome5+"]").text(qtdselecionadanova.toFixed(3));
        } else {
            $("span[id="+nome5+"]").text(qtdselecionadanova);
        }
        
        document.forms["formfiltro"].elements[nome1].value="";
        document.forms["formfiltro"].elements[nome1].disabled = true;
    }
    
    atualiza_icone_confirmar(produto,subproduto,item)
    
    
    
    
}

function calcula_qtd_selecionada (qtddigitada,nomecomlote,subproduto,subproduto_tipocontagem,produto,item) {
    qtddigitada=qtddigitada.replace(".","");
    qtddigitada=qtddigitada.replace(",",".");
    qtddigitada=parseFloat(qtddigitada);
    nome4="qtdemestoque_"+trim(nomecomlote);
    nome5="span_qtdselecionada_"+item+"_"+produto+"_"+subproduto;

    
    //verifica se a quantidade digitada é maior que a quantidade disponível em estoque
    qtdemestoque=document.forms["formfiltro"].elements[nome4].value;
    qtdemestoque=parseFloat(qtdemestoque);
    if (qtddigitada>qtdemestoque) {
        alert("A quantidade digitada foi de "+qtddigitada+ ", no estoque há apenas "+qtdemestoque+". ");
        if ((subproduto_tipocontagem==2)||(subproduto_tipocontagem==3))
            document.forms["formfiltro"].elements[nome1].value = "0,000";
        else 
            document.forms["formfiltro"].elements[nome1].value = "0";
    } else {
        //Verifica se a quantidade digitada é maior que a quantidade  a retirar
        nome2="qtdaretirar_"+item+"_"+produto+"_"+subproduto;
        qtdaretirar=document.forms["formfiltro"].elements[nome2].value;
        qtdaretirar=parseFloat(qtdaretirar);

        if (qtddigitada>qtdaretirar) {
            if ((subproduto_tipocontagem==2)||(subproduto_tipocontagem==3)) {
                qtdaretirar2=qtdaretirar.toFixed(3);
                qtddigitada2=qtddigitada.toFixed(3);
            } else {
                qtdaretirar2=qtdaretirar;
                qtddigitada2=qtddigitada;

            }
            qtdaretirar2=qtdaretirar2.toString();
            qtdaretirar2=qtdaretirar2.replace(".",",");
            qtddigitada2=qtddigitada2.toString();
            qtddigitada2=qtddigitada2.replace(".",",");



            alert("Foi digitado a quantidade de  "+qtddigitada2+ ", porém, é necessário retirar apenas "+qtdaretirar2+". ");
            if ((subproduto_tipocontagem==2)||(subproduto_tipocontagem==3))
                document.forms["formfiltro"].elements[nome1].value = "0,000";
            else 
                document.forms["formfiltro"].elements[nome1].value = "0";
        }
    }
    
    //Recalcula quantidade selecionada
    recalcula_qtdselecionada(produto,subproduto,subproduto_tipocontagem,item);
    
    
}    
    
function recalcula_qtdselecionada (produto,subproduto,subproduto_tipocontagem,item){
    //Verifica quais são os lotes do produto e subproduto
    $.post("entradas_subproduto_verifica_lotes.php", {
        subproduto:subproduto
    }, function(valor) {
        lotes=valor.split(",");
        qtdselecionadatot=parseFloat(0);
        for (i=0;i<lotes.length;i++) {
            lotes[i]=parseInt(lotes[i]);
            nome="qtddigitada_"+item+"_"+produto+"_"+subproduto+"_"+lotes[i];
            qtdselecionada=document.forms["formfiltro"].elements[nome].value;
            qtdselecionada=qtdselecionada.replace(".","");
            qtdselecionada=qtdselecionada.replace(",",".");
            if (!qtdselecionada) qtdselecionada=0; 
            qtdselecionada=parseFloat(qtdselecionada);
            qtdselecionadatot=qtdselecionadatot+qtdselecionada;
        }
        //Verifica se a quantidade total selecionada é menor que a quantidade necessária a retirar
        nome2="qtdaretirar_"+item+"_"+produto+"_"+subproduto;
        qtdaretirar=document.forms["formfiltro"].elements[nome2].value;
        qtdaretirar=parseFloat(qtdaretirar);
        if (qtdselecionadatot>qtdaretirar) {
            alert("A quantidade selecioanda é maior que a quantidade necessário retirar!");
            qtdselecionadatot=0;
            for (i=0;i<lotes.length;i++) {
                lotes[i]=parseInt(lotes[i]);
                nome="qtddigitada_"+item+"_"+produto+"_"+subproduto+"_"+lotes[i];
                nomebox="box_"+item+"_"+produto+"_"+subproduto+"_"+lotes[i];
                document.forms["formfiltro"].elements[nome].value = "";
                document.forms["formfiltro"].elements[nome].disabled = true;
                document.forms["formfiltro"].elements[nomebox].checked = false;

            }
        
        
            
        } 
        nome2="span_qtdselecionada_"+item+"_"+produto+"_"+subproduto;
        if ((subproduto_tipocontagem==2)||(subproduto_tipocontagem==3)) {
            qtdselecionadatot=qtdselecionadatot.toFixed(3);
        } 
        qtdselecionadatot2=qtdselecionadatot.toString();
        qtdselecionadatot2=qtdselecionadatot2.replace(".",",");
        $("span[id="+nome2+"]").text(qtdselecionadatot2);
        nome3="qtdselecionada_"+item+"_"+produto+"_"+subproduto;
        $("input[name="+nome3+"]").val(qtdselecionadatot);
        
        //alert(item);
        
        //Atualiza Icone Confirmar
        atualiza_icone_confirmar(produto,subproduto,item);
    });

}

function atualiza_icone_confirmar(produto,subproduto,item) {
    nome2="qtdaretirar_"+item+"_"+produto+"_"+subproduto;
    nome3="qtdselecionada_"+item+"_"+produto+"_"+subproduto;
    
   
    //alert(produto+"/"+subproduto+"/"+item);
    
    qtdselecionada=document.forms["formfiltro"].elements[nome3].value;
    qtdselecionada=parseFloat(qtdselecionada);
    
    qtdaretirar=document.forms["formfiltro"].elements[nome2].value;
    qtdaretirar=parseFloat(qtdaretirar);
    
    
    if (qtdselecionada==qtdaretirar) {
        document.getElementById(nome6).src="../imagens/icones/geral/confirmar.png";
    } else {
        document.getElementById(nome6).src="../imagens/icones/geral/confirmar2.png";
    }
    
    atualiza_continuar();
    
}

function atualiza_continuar() {
    entrada=$("input[name=entrada2]").val();
    $.post("entradas_verifica_botaoconfirmar.php", {
        entrada: entrada
    }, function(valor) {
        //alert(valor);
        itens=valor.split(",");
        continuar=1;
        for (i=0;i<itens.length;i++) {
            item=itens[i].replace(/[\n]/g, '', "")
            situacao="situacao_"+item;
            imagemlink = document.getElementById(situacao).src;
            //alert(nomedaimagem);
            var nomedaimagem = imagemlink.substring(imagemlink.lastIndexOf('/') + 1);
            //var Extensao= Arquivo.substring(Arquivo.lastIndexOf('.') + 1);
            //alert(nomedaimagem);
            
            if ((nomedaimagem=="confirmar2.png")||(nomedaimagem=="atencao.png")) {
                continuar=0;
                //alert("entrou");
            }
        }
        
        if (continuar==1) {
            //alert("pode continuar");
            document.forms["formfiltro"].elements["CONTINUAR"].disabled = false;
        } else {
            //alert("não pode pode continuar");
            document.forms["formfiltro"].elements["CONTINUAR"].disabled = true;
        }
    });
}

function trim(str) {
    return str.replace(/^\s+|\s+$/g,"");
}