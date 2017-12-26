
$(window).load(function() {

    //Verifica se usa EAN, se não usa, oculta
    usaean=$("input[name=usaean]").val();
    if (usaean==1) $("tr[id=linha_ean]").show();
    else $("tr[id=linha_ean]").hide();

    //Verifica se usa código de barras interno, se não usa, oculta
    usacodigobarrasinterno=$("input[name=usacodigobarrasinterno]").val();
    if (usacodigobarrasinterno==1) $("tr[id=linha_codigobarrasinterno]").show();
    else $("tr[id=linha_codigobarrasinterno]").hide();


    //Verifica se usa porcoes
    var usavendaporcoes=$("input[name=usavendaporcoes]").val();
    if (usavendaporcoes==1) {
        $("tr[id=linha_porcoes]").show();
        $("tr[id=linha_porcoes_qtd]").show();
    } else {
        $("tr[id=linha_porcoes]").hide();
        $("tr[id=linha_porcoes_qtd]").hide();
    }

    //Verifica tipo de pessoa fisica ou juridica
    $("select[id=consumidor]").change(function () {
        consumidor=$("select[id=consumidor]").val();
        $("input[id=cpf]").val("");
        $("input[id=cnpj]").val("");
    });
    $("input[id=cliente_nome]").hide();
    tipopessoa=$("select[name=tipopessoa]").val();
    if (tipopessoa==1) { //Se for novo registro, então o padrão é aparecer o cpf, pessoa fisica
        $("input[id=cpf]").show();
        $("input[id=cnpj]").hide();
    } else if (tipopessoa==2) {
        $("input[id=cnpj]").show();
        $("input[id=cpf]").hide();
    } 


    //Verifica se usa comanda
    var usacomanda= $("input[name=usacomanda]").val();
    if (usacomanda==1) {
        document.form1.id.required=true;
        $("input[id=linha_comanda]").show();
        //Foco no ID
        if (document.forms["form1"].elements["id"]) {
            document.forms["form1"].elements["id"].focus();
        }
    } else if (usacomanda==0) {
        document.form1.id.required=false;
        $("tr[id=linha_comanda]").hide();
    } else {
        alert("Erro Grave, contate o suporte: 44545");
    }  



    //Verifica qual é a forma de identificação do cliente na venda: CPF/Telefone ou não usa
    var identificacaoconsumidorvenda=$("input[name=identificacaoconsumidorvenda]").val();
    if (identificacaoconsumidorvenda==3) { //Não identifica consumidor
        //$("tr[id=linha_comanda]").hide();
        $("tr[id=linha_consumidor]").hide();
    } else if (identificacaoconsumidorvenda==2) { //Identifica por Telefone
        $("input[name=cpf]").hide();
        $("input[name=fone]").show();
        $("select[name=tipopessoa]").hide();
        document.forms["form1"].elements["fone"].focus();
    } else if (identificacaoconsumidorvenda==1) { //Identifica por CPF
        $("input[name=cpf]").show();
        if (document.forms["form1"].elements["cpf"]) document.forms["form1"].elements["cpf"].focus();
        $("input[name=fone]").hide();
        $("select[name=tipopessoa]").show();
    } else {
        alert("Há algo estranho, chamar o suporte!");
    }

     
    //Verifica se ignora lotes
    passo=$("input[name=passo]").val();
    //console.log(passo);
    if (passo>1) {
        ignorarlotes=$("input[name=ignorarlotes]").val();
        if (ignorarlotes==1) {
            document.forms["form1"].lote.required = false;
            $("tr[id=linha_fornecedor]").hide();
            $("tr[id=linha_lote]").hide();
        } 
    }



    //Popular Produto    
    popular_produto();

    //Define o foco padrão
    var temp=document.forms["form1"].elements["produto_referencia"]; if (temp) document.forms["form1"].elements["produto_referencia"].focus();
    
    //Ao selecionar porcoes
    //$("select[name=porcao]").change(function () { selecionar_porcoes(0) });

    //Atalhos Teclado
    shortcut.add("Ctrl+D", function() {
        link=document.getElementById("link_eliminar_venda").href;
        window.location = link;
    });
    shortcut.add("Ctrl+F", function() {
        if(document.getElementById('enviar_formulario').disabled) alert("Não é possível Avançar!");
        else  document.form2.submit();
        //window.location = link;
    });

    
    //Cria uma delay entre uma tecla e outra ao digitar a referencia do produto.
    //Isso é necessário para não executar AJAX a cada tecla sobrecarrengando o servidor.
    //Mas o principal é que as vezes o usuário digita tão rápido que a chamado onkeyup falha algumas teclas, enviando apenas parte da palavra digitada
    var delay = (function(){
      var timer = 0;
      return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
      };
    })();
    Usage:
    $("input[name=produto_referencia]").keyup(function() {
        delay(function(){
          //alert('Time elapsed!');
          referencia=$("input[name=produto_referencia]").val();
          verifica_produto_referencia(referencia);
        }, 200 );
    });

    entrega=$("select[name=entrega]").val();
    console.log(entrega);
    verifica_entrega (entrega);

});

function consumidor_selecionado(consumidor) {
    consumidor=parseInt(consumidor);
    entrega=$("select[name=entrega]").val();
    //alert("("+consumidor+")")
    if (consumidor==0) {
        //alert(consumidor);
        select_selecionar("entrega",0);
        verifica_entrega(0);
    } else {
        if (entrega==1) {
            verifica_entrega(1);
        } 


    }
}


function verifica_entrega (valor) {

    usuario_quiosque_pais=$("input[name=usuario_quiosque_pais]").val();
    usuario_quiosque_estado=$("input[name=usuario_quiosque_estado]").val();
    usuario_quiosque_cidade=$("input[name=usuario_quiosque_cidade]").val();

    if ((valor=="")||(valor==0)) { //nao tem entrega
        $("tr[id=linha_endereco]").hide();
        $("tr[id=linha_bairro]").hide();
        $("tr[id=linha_cidade]").hide();
        $("tr[id=linha_dataentrega]").hide();
        $("tr[id=linha_fone1]").hide();
        $("tr[id=linha_fone2]").hide();
        document.form1.dataentrega.required=false;
        document.form1.cidade.required=false;
        document.form1.estado.required=false;
        document.form1.pais.required=false;
        $("input[name=endereco]").val("");
        $("input[name=endereco_numero]").val("");
        $("input[name=bairro]").val("");
        $("input[name=fone1]").val("");
        $("input[name=fone2]").val("");

        popula_estados(usuario_quiosque_pais,usuario_quiosque_estado); 
        popula_cidades(usuario_quiosque_estado,usuario_quiosque_cidade); 


    } else { //sim tem entrega

        //Verifica se foi selecionado um consumidor, ou se está sendo cadastrado um novo cliente, se identificado consumidor então pode entregar.
        consumidor_novo_nome=$("input[name=cliente_nome]").val();
        consumidor=$("select[name=consumidor]").val();
        console.log(consumidor);
        permitir_entrega=0;
        consumidor_existente=0;
        if ((consumidor_novo_nome!="")&&(consumidor_novo_nome!=undefined)) permitir_entrega=1; 
        if ((consumidor!=0)&&(consumidor!=undefined)) { console.log("entrou"); permitir_entrega=1; consumidor_existente=1;}
        if (permitir_entrega==1) { //o consumidor foi identificado
            $("tr[id=linha_endereco]").show();
            $("tr[id=linha_bairro]").show();
            $("tr[id=linha_cidade]").show();
            $("tr[id=linha_dataentrega]").show();
            $("tr[id=linha_fone1]").show();
            $("tr[id=linha_fone2]").show();
            document.form1.dataentrega.required=true;
            document.form1.cidade.required=true;
            document.form1.estado.required=true;
            document.form1.pais.required=true; 
            if (consumidor_existente==1) { //Trata-se de um consumidor já cadastrado 
                //Pegar dados do banco e popular os campos de endereço da tela.
                $.post("saidas_consumidor_existente_comentrega.php", { consumidor: consumidor }, function(valor2) {
                    valor2=valor2.split("|");
                    consumidor_codigo=valor2[0];
                    consumidor_nome=valor2[1];
                    consumidor_fone1=valor2[2];
                    consumidor_fone2=valor2[3];
                    consumidor_endereco=valor2[4];
                    consumidor_bairro=valor2[5];
                    consumidor_cidade=parseInt(valor2[6]);
                    consumidor_estado=parseInt(valor2[7]);
                    consumidor_pais=parseInt(valor2[8]);
                    $("input[name=fone1]").val(consumidor_fone1);
                    $("input[name=fone2]").val(consumidor_fone2);
                    $("input[name=endereco]").val(consumidor_endereco);
                    $("input[name=bairro]").val(consumidor_bairro);
                    popula_estados(consumidor_pais,consumidor_estado); 
                    popula_cidades(consumidor_estado,consumidor_cidade);

                });
            } else { //é um consumidor novo
                //Se o método de identificão do consumidor for por telefone, então popula o campo telefone1 com o mesmo numero
                if ($("input[name=fone]")) {
                    fone=$("input[name=fone]").val();
                    $("input[name=fone1]").val(fone);
                }
            }
        } else { //consumidor nao identificado, nao pode fazer entrega
            alert("Para realizar uma entrega é necessário identificar o consumidor!");
            $("tr[id=linha_endereco]").hide();
            $("tr[id=linha_bairro]").hide();
            $("tr[id=linha_cidade]").hide();
            $("tr[id=linha_dataentrega]").hide();
            $("tr[id=linha_fone1]").hide();
            $("tr[id=linha_fone2]").hide();
            document.form1.dataentrega.required=false;
            document.form1.cidade.required=false;
            document.form1.estado.required=false;
            document.form1.pais.required=false;
            select_selecionar("entrega",'0');

          
            popula_estados(usuario_quiosque_pais,usuario_quiosque_estado); 
            popula_cidades(usuario_quiosque_estado,usuario_quiosque_cidade);           
        }
    }
}


function mascara_telefone1 (fone) {
    if (fone.length<=13)  $("#fone1").mask("(00)0000-00009");
    else $("#fone1").mask("(00)00000-0000");
}

function verifica_telefone1 (fone) {
    if (fone!="") {
        if ((fone.length==13)||(fone.length==14)) {
            //Telefone digitado corretamente
        } else {
            alert("Número de telefone incorreto!");
            $("input[name=fone1]").val("");
            $("input[name=fone1]").focus();
        }
    }
}

function mascara_telefone2 (fone) {
    if (fone.length<=13)  $("#fone2").mask("(00)0000-00009");
    else $("#fone2").mask("(00)00000-0000");
}

function verifica_telefone2 (fone) {
    if (fone!="") {
        if ((fone.length==13)||(fone.length==14)) {
            //Telefone digitado corretamente
        } else {
            alert("Número de telefone incorreto!");
            $("input[name=fone2]").val("");
            $("input[name=fone2]").focus();
        }
    }
}



function atualiza_consumidor () {
$.post("saidas_cadastrar_atualiza_consumidor.php",{
    },function(valor2){
        //alert(valor2);
        $("select[name=consumidor]").html(valor2);
    });    
}

function selecionar_porcoes(porcao) {
        
    $("input[name=qtd]").val("");
    $("input[name=qtd2]").val("");
    $("input[name=porcao_qtd]").val("");
    $("input[name=valtot]").val("");

    if (porcao==0) porcao=$("select[name=porcao]").val();
    

    //Popula a quantidade da porção (campo oculto)
    $.post("saidas_popula_porcoesqtdoculto.php", {
        porcao: porcao
    }, function(valor1) {
        valor = valor1.replace("\r", ""); 
        valor = valor.replace("\r", ""); 
        valor = valor.replace("\r", ""); 
        valor = valor.replace("\r", ""); 
        valor = valor.replace("\t", "");
        valor = valor.replace("\t", "");
        valor = valor.replace("\t", "");
        valor = valor.replace("\t", "");
        valor = valor.replace("\n", "");
        valor = valor.replace("\n", "");
        valor = valor.replace("\n", "");
        valor1 = valor.replace("\n", "");        
        //alert(valor);
        $("input[name=porcao_oculto]").val(valor1);
        

        //Popula o valor unitário referencial caso tenha
        if (porcao=="") {
            porcao=0;
            $("input[name=porcao_qtd]").val("");
            var temp=document.forms["form1"].qtd; if (temp) document.forms["form1"].qtd.disabled = false;

        }
        $.post("saidas_popula_valuniref.php", {
            porcao: porcao
        }, function(valor2) {
            valor = valor2.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\n", "");
            valor = valor.replace("\n", "");
            valor = valor.replace("\n", "");
            valor2 = valor.replace("\n", "");            
            //alert(valor2);
            if (valor2==0) {
                valuni=$("input[name=valuni2]").val();
                valuni2=valuni.split(" ");
                valuni=valuni2[1];
                valuni=valuni.replace(".","");
                valuni=valuni.replace(",",".");
            } else {
                valuni=valor2;
            }
            
            $("input[name=valuni]").val(valuni).priceFormat({
                prefix: 'R$ ',
                centsLimit: 2,
                centsSeparator: ',',
                thousandsSeparator: '.'
            });   
            $("input[name=valuni3]").val(valuni).priceFormat({
                prefix: 'R$ ',
                centsLimit: 2,
                centsSeparator: ',',
                thousandsSeparator: '.'
            }); 

            //alert("selecionar a porcao select");
            select_selecionar("porcao",porcao);
            porcao = porcao.replace("\r", ""); 
            porcao = porcao.replace("\r", ""); 
            porcao = porcao.replace("\r", ""); 
            porcao = porcao.replace("\r", ""); 
            porcao = porcao.replace("\t", "");
            porcao = porcao.replace("\t", "");
            porcao = porcao.replace("\t", "");
            porcao = porcao.replace("\t", "");
            porcao = porcao.replace("\n", "");
            porcao = porcao.replace("\n", "");
            porcao = porcao.replace("\n", "");
            porcao = porcao.replace("\n", "");
            console.log("("+porcao+")");
            if (porcao=="naotem") { //Se não tem nenhuma porção
                document.forms["form1"].porcao_qtd.disabled = true;
            } else { //Caso tenho pelo menos uma porção
                lote2=$("select[name=lote]").val();
                if (((lote2!="")&&(lote2!= null)&&(lote2!== undefined)&&(lote2!= 0))||(ignorarlotes==1)) { //Se tem um lote selecionado ou se o sistema esta parametrizado para ignorar lotes, então habilita qtd porcoes e desabilita qtd
                    var temp=document.forms["form1"].qtd; if (temp) document.forms["form1"].qtd.disabled = true;
                    var temp=document.forms["form1"].porcao_qtd; if (temp) {
                        document.forms["form1"].porcao_qtd.disabled = false;
                        document.forms["form1"].porcao_qtd.focus();
                        $("input[name=porcao_qtd]").val(1);
                        $("input[name=porcao_qtd]").select();
                        porcoesqtd();
                    }
                } else {
                    var temp=document.forms["form1"].qtd; if (temp) document.forms["form1"].qtd.disabled = false;
                    var temp=document.forms["form1"].porcao_qtd; if (temp) {
                        document.forms["form1"].porcao_qtd.disabled = true;
                        document.forms["form1"].qtd.focus();
                    }
                }
            }
        });      
    });
}

function popular_produto (produto) {
    //alert(produto);
    $.post("saidas_popula_produto.php", { produto: produto }, function(valor) {
        $("select[name=produto]").html(valor);
        //console.log(valor);
        var temp=document.forms["form1"].qtd; if (temp) document.forms["form1"].qtd.disabled = true;
        var temp=document.forms["form1"].porcao_qtd; if (temp) document.forms["form1"].porcao_qtd.disabled = true;
    });
        
}

function selecionar_produto (produto,focoqtd) {
    
    ref=$("input[name=produto_referencia]").val();
    if (produto=="") produto=0;

    //Zerar campos
    $("input[name=etiqueta]").val("");
    $("input[name=etiqueta2]").val("");
    $("input[name=qtd]").val("");
    $("input[name=qtd2]").val("");
    $("select[name=lote]").html("");
    $("input[name=valuni]").val("");
    $("input[name=valuni3]").val("");
    $("input[name=valtot]").val("");
    $("span[name=qtdnoestoque]").text("");
    $("select[name=porcao]").html("");
    $("span[name=porcao_qtd_label]").text("");
    $("input[name=porcao_qtd]").val("");

    //Popula tipo de contagem ao lado do campo de quantidade
    $.post("saidas_verifica_tipocontagem_nome.php", {
        produto: $("select[name=produto]").val()
    }, function(valor) {
        valor = valor.replace("\r", ""); 
        valor = valor.replace("\r", ""); 
        valor = valor.replace("\r", ""); 
        valor = valor.replace("\r", ""); 
        valor = valor.replace("\t", "");
        valor = valor.replace("\t", "");
        valor = valor.replace("\t", "");
        valor = valor.replace("\t", "");
        valor = valor.replace("\n", "");
        valor = valor.replace("\n", "");
        valor = valor.replace("\n", "");
        valor = valor.replace("\n", "");           
        $("span[name=tipocontagem]").text(valor);

        //Desabilita o campo quantidade e o botão de incluir
        var temp=document.forms["form1"].qtd; if (temp) document.forms["form1"].qtd.disabled = true;
        var temp=document.forms["form1"].porcao_qtd; if (temp) document.forms["form1"].porcao_qtd.disabled = true;
        document.forms["form1"].botao_incluir.disabled = true;

        //Define máscara para campo quantidade de porções
        $("input[name=porcao_qtd]").val("").priceFormat({
            prefix: '',
            centsSeparator: '',
            centsLimit: 0,
            thousandsSeparator: ''
        }); 
        ignorarlotes=$("input[name=ignorarlotes]").val();

        $.post("produto_verifica_estoque_infinito.php", {
            produto: produto
        }, function(valor) {  
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\n", "");
            valor = valor.replace("\n", "");
            valor = valor.replace("\n", "");
            valor = valor.replace("\n", "");             

            valor=valor.split("|");
            produto_controlar_estoque=valor[0];
            $("input[name=produto_controlar_estoque]").val(produto_controlar_estoque);
            valunicusto=valor[1];
            valunivenda=valor[2];


            if (produto_controlar_estoque==0) { //Produto com estoque infinito
                var temp=document.forms["form1"].porcao_qtd; if (temp) document.forms["form1"].porcao_qtd.disabled = true;                        
                var temp=document.forms["form1"].porcao; if (temp) document.forms["form1"].porcao.disabled = true;                        
                var temp=document.forms["form1"].lote; if (temp) document.forms["form1"].lote.disabled = true;                        
                var temp=document.forms["form1"].fornecedor; if (temp) document.forms["form1"].fornecedor.disabled = true;                        
                //document.forms["form1"].botao_incluir.disabled = true; 
                if (document.forms["form1"].qtd) document.forms["form1"].qtd.disabled = false;                        
                qtd=1;
                $("input[name=qtd]").val(qtd);
                $("input[name=qtd]").focus();
                $("input[name=qtd]").select();
                valunivenda=parseFloat(valunivenda);
                $("input[name=valuni]").val("R$ "+valunivenda.toLocaleString('pt-BR', { minimumFractionDigits: 2 } ));
                $("input[name=valtot]").val(valunivenda*qtd);
                saidas_qtd();
            } else {

                //Se está parametrizado para ignorar lotes, então o fornecedor e lotes devem ser selecionados automaticamente, pegando o mais antigo lote por padrão
                if (ignorarlotes==1) {
                    if (produto!=0) {
                        $.post("produto_selecionado_ignorandolotes.php", {
                            produto: produto
                        }, function(valor) {
                            //console.log(valor);
                            valor = valor.replace("\r", ""); 
                            valor = valor.replace("\r", ""); 
                            valor = valor.replace("\r", ""); 
                            valor = valor.replace("\r", ""); 
                            valor = valor.replace("\t", "");
                            valor = valor.replace("\t", "");
                            valor = valor.replace("\t", "");
                            valor = valor.replace("\t", "");
                            valor = valor.replace("\n", "");
                            valor = valor.replace("\n", "");
                            valor = valor.replace("\n", "");
                            valor = valor.replace("\n", "");                             

                            $("input[name=valuni]").val("");
                            $("input[name=valuni3]").val("");
                            $("input[name=valtot]").val("");
                            $("span[name=qtdnoestoque]").text("");
                            $("input[name=qtd]").val("");
                            $("input[name=qtd2]").val("");
                            $("input[name=porcao_qtd]").val("");
                            $("select[name=porcao]").html("");

                            //Desabilita botão e campos quantidade 
                            var temp=document.forms["form1"].qtd; if (temp) document.forms["form1"].qtd.disabled = true;
                            var temp=document.forms["form1"].porcao_qtd; if (temp) document.forms["form1"].porcao_qtd.disabled = true;
                            document.forms["form1"].botao_incluir.disabled = true; 

                            valor=valor.replace("\n","");
                            valor=valor.split("/");
                            lote=valor[1];
                            qtdnoestoque_geral=valor[0];
                            //console.log ("Produto:"+produto+" Lote:"+lote+ " Qtd estoque:"+qtdnoestoque_geral);

                            //Se não há quantidade deste produto no estoque desabilita. Senão segue o fluxo
                            if (qtdnoestoque_geral=="") {
                                document.forms["form1"].porcao.disabled = true;
                                alert("Estoque vazio para este produto!");
                            } else {
                                document.forms["form1"].porcao.disabled = false;
                                //Popula porcoes
                                $.post("saidas_popula_porcoes.php", {
                                    produto: produto
                                }, function(valor) {
                                    //alert(valor);
                                    $("select[name=porcao]").html(valor);
                                }); 
                                $("input[name=porcao_qtd]").val("");


                                produtoelote_selecionado(produto,lote);
                                //$("span[name=tipocontagem]").text(valor);
                            }
                            
                        });
                    }        
                } else {
                    //Popula o Fornecedor        
                    if (produto!="") {
                        popular_fornecedor(produto,focoqtd);
                    }
                }
            }

        });


    });
 
}


function popular_fornecedor (produto,focoqtd) {
    $("select[name=fornecedor]").html('<option>Aguarde, carregando...</option>');
    $.post("saidas_popula_fornecedor.php", {
        produto: produto
    }, function(valor) {
        valor = valor.replace("\r", ""); 
        valor = valor.replace("\r", ""); 
        valor = valor.replace("\r", ""); 
        valor = valor.replace("\r", ""); 
        valor = valor.replace("\t", "");
        valor = valor.replace("\t", "");
        valor = valor.replace("\t", "");
        valor = valor.replace("\t", "");
        valor = valor.replace("\n", "");
        valor = valor.replace("\n", "");
        valor = valor.replace("\n", "");
        valor = valor.replace("\n", "");         
        //alert(valor);
        if (valor==0) {
            $("select[name=fornecedor]").html("<option value=''>Não há registros</option>");
        } else {
            $("select[name=fornecedor]").html(valor);
        }
        $.post("saidas_verifica_fornecedor_unico.php", {
            produto: produto
        }, function(fornecedor) {
            fornecedor=fornecedor.replace(/(\r\n|\n|\r)/gm,"");
            //alert(fornecedor);
            if (fornecedor!=0) { //Tem apenas um fornecedor
                selecionar_fornecedor(fornecedor,focoqtd,produto);
            } else { //Tem mais de um fornecedor
                //alert("Tem mais de um fornecedor");
                if (focoqtd==1) document.forms["form1"].elements["fornecedor"].focus();            
            }
        });
    });   
}



function selecionar_fornecedor (fornecedor,focoqtd,produto) {
    
    
    
    //Popula o Lote
    popular_lote(produto,fornecedor,focoqtd);

    //Zerar campos
    $("input[name=valuni]").val("");
    $("input[name=valuni3]").val("");
    $("input[name=valtot]").val("");
    $("span[name=qtdnoestoque]").text("");
    $("input[name=qtd]").val("");
    $("input[name=qtd2]").val("");
    $("input[name=porcao_qtd]").val("");
    $("select[name=porcao]").html("");

    //Habilita botão e campo quantidade
    var temp=document.forms["form1"].qtd; if (temp) document.forms["form1"].qtd.disabled = true;
    var temp=document.forms["form1"].porcao_qtd; if (temp) document.forms["form1"].porcao_qtd.disabled = true;
    document.forms["form1"].botao_incluir.disabled = true;    
}


function popular_lote (produto,fornecedor,focoqtd) {
    $("select[name=lote]").html('<option>Aguarde, carregando...</option>');
    $.post("saidas_popula_lote.php", {
        produto: produto,
        fornecedor: fornecedor
    }, function(valor) {
        valor = valor.replace("\r", ""); 
        valor = valor.replace("\r", ""); 
        valor = valor.replace("\r", ""); 
        valor = valor.replace("\r", ""); 
        valor = valor.replace("\t", "");
        valor = valor.replace("\t", "");
        valor = valor.replace("\t", "");
        valor = valor.replace("\t", "");
        valor = valor.replace("\n", "");
        valor = valor.replace("\n", "");
        valor = valor.replace("\n", "");
        valor = valor.replace("\n", "");         
        $("select[name=lote]").html(valor);
            $.post("saidas_verifica_lote_unico.php", {
            produto: produto,
            fornec: fornecedor
        }, function(lote) {
            valor = lote.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\n", "");
            valor = valor.replace("\n", "");
            valor = valor.replace("\n", "");
            lote = valor.replace("\n", "");             
            lote=lote.replace(/(\r\n|\n|\r)/gm,"");
            if (lote!=0) { //Só tem um lote
                selecionar_lote(lote,produto,focoqtd);
            } else { //tem mais de um lote
                document.forms["form1"].elements["lote"].focus();            
            }
        });
    });
    //alert(fornecedor);
}

function selecionar_lote (lote,produto,focoqtd) {
    
    //alert("Selecionando LOTE: "+lote);   
    if ((produto=="")||(!produto)) produto=$("select[name=produto]").val();
    //alert("O produto é: "+produto);   
    //alert("O focoqtd é: "+focoqtd);   
    if (focoqtd!='0') {
        focoqtd=1;
    } else {
        focoqtd=0;
    }
    //alert("O focoqtd no selecionar_lote é: "+focoqtd);   

    //Popula porcoes
    $.post("saidas_popula_porcoes.php", {
        produto: $("select[name=produto]").val()
    }, function(valor) {
        //alert(valor);
        $("select[name=porcao]").html(valor);
    }); 
    $("input[name=porcao_qtd]").val("");


    produtoelote_selecionado(produto,lote,focoqtd);

    
}


function produtoelote_selecionado(produto,lote,focoqtd) {
    
    if ((produto!="")&&(lote!="")) {
        //Popula valor unitário padrão
        $.post("saidas_valorunitario.php", {
            lote: lote,
            produto: produto
        }, function(valor) {
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\n", "");
            valor = valor.replace("\n", "");
            valor = valor.replace("\n", "");
            valor = valor.replace("\n", "");            
            //alert(valor);
            //alert("entrou");
            $("input[name=valuni]").val(valor);
            $("input[name=valuni2]").val(valor);
            $("input[name=valuni3]").val(valor);
        });
        $("input[name=valtot]").val("");

        //Verifica o tipo de contagem
        $.post("saidas_verifica_tipocontagem.php", {
            produto: $("select[name=produto]").val()
        }, function(valor) {
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\n", "");
            valor = valor.replace("\n", "");
            valor = valor.replace("\n", "");
            valor = valor.replace("\n", "");
            tipocontagem=valor;

            //Popula LABEL quantidade atual em estoque
            $.post("saidas_verifica_estoque.php", {
                lote: lote,
                produto: produto,
                ignorarlotes: $("input[name=ignorarlotes]").val()
            }, function(valor2) {
                valor2 = valor2.replace("\r", ""); 
                valor2 = valor2.replace("\r", ""); 
                valor2 = valor2.replace("\r", ""); 
                valor2 = valor2.replace("\r", ""); 
                valor2 = valor2.replace("\t", "");
                valor2 = valor2.replace("\t", "");
                valor2 = valor2.replace("\t", "");
                valor2 = valor2.replace("\t", "");
                valor2 = valor2.replace("\n", "");
                valor2 = valor2.replace("\n", "");
                valor2 = valor2.replace("\n", "");
                valor2 = valor2.replace("\n", "");                
                var etqatu = valor2;
                var estoqueatual = etqatu.replace(".", "");
                estoqueatual = estoqueatual.replace(",", ".");
                if (etqatu == "") {
                    $("span[name=qtdnoestoque]").text("");
                    $("input[name=qtd]").val("");
                    $("input[name=qtd2]").val("");
                    document.forms["form1"].botao_incluir.disabled = true;
                    var temp=document.forms["form1"].qtd; if (temp) document.forms["form1"].qtd.disabled = true;
                    var temp=document.forms["form1"].porcao_qtd; if (temp) document.forms["form1"].porcao_qtd.disabled = true;

                } else {
                    var temp=document.forms["form1"].qtd; if (temp) document.forms["form1"].qtd.disabled = false;
                    porcao2=$("select[name=porcao]").val();
                    if ((porcao2!="")&&(porcao2!= null)&&(porcao2!== undefined)&&(porcao2!= 0)) document.forms["form1"].porcao_qtd.disabled = false;
                    else document.forms["form1"].porcao_qtd.disabled = true;
                    etqatu_mostra=parseFloat(etqatu);
                    if ((valor == 2)||(valor==3)) {
                        etqatu_mostra=etqatu_mostra.toLocaleString('pt-BR', { minimumFractionDigits: 3 } ); //Substituto para o Priceformat
                    } else {
                        etqatu_mostra=etqatu_mostra.toLocaleString('pt-BR', { minimumFractionDigits: 0 } ); //Substituto para o Priceformat
                    }
                    $("span[name=qtdnoestoque]").text("(" + etqatu_mostra + " no estoque)");
                    if ((valor == 2)||(valor==3)) { //Se o tipo de contagem for 'kg' ou 'lt'
                        $("input[name=qtd]").val("");
                        $("input[name=qtd2]").val("");
                        document.forms["form1"].botao_incluir.disabled = true;
                    } else { //Se o tipo de contagem for por unidade
                        if (estoqueatual >= 1) {
                            $("input[name=qtd]").val("1");
                            $("input[name=qtd2]").val("1");
                            tot=$("input[name=valuni2]").val();
                            $("input[name=valtot]").val(tot);
                            document.forms["form1"].botao_incluir.disabled = false;
                            //alert("O foco é: "+focoqtd);
                            
                            
                        } else {
                            $("input[name=qtd]").val(etqatu);
                            $("input[name=qtd2]").val(etqatu);
                        }
                        saidas_qtd(lote);
                        document.forms["form1"].botao_incluir.disabled = false;

                    }
                    //Caso tenha vários lotes ou fornecedores o campo foco virá indefinido, por isso definimos ele como 0, para não setar o foco na quantidade
                    if (focoqtd!=0) focoqtd=1; 
                        //alert("O foco agoraaaaaa é:"+focoqtd);
                        if (focoqtd==1) {
                            //foco_quantidade();
                    } 
                }
                //Verifica se só tem uma porção, se sim auto-seleciona ela
                $.post("saidas_verifica_porcao_unico.php", {
                    produto: produto
                }, function(resposta) {
                    valor = resposta.replace("\r", ""); 
                    valor = valor.replace("\r", ""); 
                    valor = valor.replace("\r", ""); 
                    valor = valor.replace("\r", ""); 
                    valor = valor.replace("\t", "");
                    valor = valor.replace("\t", "");
                    valor = valor.replace("\t", "");
                    valor = valor.replace("\t", "");
                    valor = valor.replace("\n", "");
                    valor = valor.replace("\n", "");
                    valor = valor.replace("\n", "");
                    resposta = valor.replace("\n", "");
                    if ((resposta=='naotem')||(resposta=='temvarios')) {
                    //nao faz nada
                        //console.log("Tem 0 ou várias porcoes: "+resposta);
                    } else { 
                        //Só tem uma porção
                        selecionar_porcoes(resposta);
                    } 
                });
            });
        });
    
                
    }   
}


function foco_produto_referencia () {
    //alert("saiu do campo");
    produto=$("select[name=produto]").val();
    if (produto==null) produto="";
    fornecedor=$("select[name=fornecedor]").val();
    if (fornecedor==null) fornecedor="";
    lote=$("select[name=lote]").val();
    if (lote==null) lote="";
    //alert(produto+"/"+fornecedor+"/"+lote);
    if ((produto!="")&&(fornecedor!="")&&(lote!="")) {
        foco_quantidade();
    }
}


function foco_quantidade () {
    //alert("foco deverá ser na quantidade");
    document.forms["form1"].elements["qtd"].focus();
    document.forms["form1"].elements["qtd"].select();   
}


function pesoqtd() {
    //Atribui mascara
    $.post("saidas_verifica_tipocontagem.php", {
        produto: $("select[name=produto]").val()
    }, function(valor) {
        valor = valor.replace("\r", ""); 
        valor = valor.replace("\r", ""); 
        valor = valor.replace("\r", ""); 
        valor = valor.replace("\r", ""); 
        valor = valor.replace("\t", "");
        valor = valor.replace("\t", "");
        valor = valor.replace("\t", "");
        valor = valor.replace("\t", "");
        valor = valor.replace("\n", "");
        valor = valor.replace("\n", "");
        valor = valor.replace("\n", "");
        valor = valor.replace("\n", "");        
        if ((valor == 2)||(valor==3)) {
            $('#qtd').priceFormat({
                prefix: '',
                centsLimit: 3,
                centsSeparator: ',',
                thousandsSeparator: '.'
            });
            $('#qtd2').priceFormat({
                prefix: '',
                centsLimit: 3,
                centsSeparator: ',',
                thousandsSeparator: '.'
            });
            //alert("peso");
        } else {
            $('#qtd').priceFormat({
                prefix: '',
                centsLimit: 0,
                centsSeparator: '',
                thousandsSeparator: '.'
            });
            $('#qtd2').priceFormat({
                prefix: '',
                centsLimit: 0,
                centsSeparator: '',
                thousandsSeparator: '.'
            });
            //alert("unidade");
        }
    });

}

function saidas_qtd(lote) {
    //Verifica se há no estoque  
          
    $.post("saidas_verifica_estoque.php", {
        lote: $("select[name=lote]").val(),
        produto: $("select[name=produto]").val(),
        ignorarlotes: $("input[name=ignorarlotes]").val()
    }, function(valor) {
        //alert(valor);
        valor = valor.replace("\r", ""); 
        valor = valor.replace("\r", ""); 
        valor = valor.replace("\r", ""); 
        valor = valor.replace("\r", ""); 
        valor = valor.replace("\t", "");
        valor = valor.replace("\t", "");
        valor = valor.replace("\t", "");
        valor = valor.replace("\t", "");
        valor = valor.replace("\n", "");
        valor = valor.replace("\n", "");
        valor = valor.replace("\n", "");
        valor = valor.replace("\n", "");

        produto_controlar_estoque=$("input[name=produto_controlar_estoque]").val();
        qtddigitada = $("input[name=qtd]").val();
        if (produto_controlar_estoque==1) {

            //Verifica se a quantidade digitada é maior que a do estoque
            if (valor != "") {
                if (qtddigitada != "") {
                    qtddigitada = qtddigitada.replace(".", "");
                    qtddigitada = qtddigitada.replace(",", ".");
                    qtddigitada = parseFloat(qtddigitada);
                }
                qtdestoque = valor.replace("\n","");;
                qtdestoque = parseFloat(qtdestoque);
                //alert(qtddigitada +" ee " + qtdestoque);
                if (qtddigitada > qtdestoque) {
                    document.forms["form1"].botao_incluir.disabled = true;
                    alert("A quantidade digitada é maior que a quantidade disponível no estoque! A quantidade atual deste produto no estoque está descrito ao lado do campo!");

                    $("input[name=porcao_qtd]").val("");
                    $("input[name=qtd]").val("");
                    $("input[name=valtot]").val("");
                } else {
                    calcula_total(qtddigitada);
                }
                //alert("ddd"+qtddigitada+"ddd");            
            }
        } else { //Produto com estoque infinito
            //alert("estoque infinito! qtd");
            calcula_total(qtddigitada);
        }

    });
}

function calcula_total(qtddigitada) {

    //alert(qtddigitada);
    if (qtddigitada == 0) {
        $("input[name=valtot]").val("");
        document.forms["form1"].botao_incluir.disabled = true;
        //alert("desabilitar");
    } else {
        valuni=$("input[name=valuni]").val();
        $("input[name=valuni2]").val(valuni);
        $("input[name=valuni3]").val(valuni);
        valuni2=valuni.split(" ");
        valuni=valuni2[1];
        valuni=valuni.replace(".","");
        valuni=valuni.replace(",",".");
        qtd_porcao=$("input[name=porcao_qtd]").val();
        if (qtd_porcao=="") qtd=$("input[name=qtd]").val();
        else qtd=qtd_porcao;
        qtd=qtd.replace(".","");
        qtd=qtd.replace(",",".");
        valtot=valuni*qtd;
        valtot=valtot.toFixed(2);
        //alert(valuni+"*"+qtd+"="+valtot);
        $("input[name=valtot]").val(valtot).priceFormat({
            prefix: 'R$ ',
            centsLimit: 2,
            centsSeparator: ',',
            thousandsSeparator: '.'
        });
        document.forms["form1"].botao_incluir.disabled = false;
        //alert("habilitar");
    }


}

//Código de Barras Interno
function valida_etiqueta(campo) {
    //alert(campo);
    var qtd_caracteres;
    var digits = "0123456789"
    var campo_temp
    for (var i = 0; i < campo.value.length; i++) {
        campo_temp = campo.value.substring(i, i + 1)
        if (digits.indexOf(campo_temp) == -1) {
            campo.value = campo.value.substring(0, i);
        }
    }
    //Conta quantos caracteres foram digitados no código da etiqueta
    qtd_caracteres = document.forms["form1"].etiqueta.value.length;
    //Se a etiqueta não tem nada digitado então habilita-se o produto para escolhe-lo manualmente

    if (qtd_caracteres == 0) {
        $.post("saidas_popula_produto.php", {}, function(valor) {
            $("select[name=produto]").html(valor);
        });
        $("input[name=etiqueta2]").html("");
        $("select[name=fornecedor]").html("");
        $("select[name=lote]").html("");
        $("select[name=porcao]").html("");
        $("select[name=fornecedor]").html("");
        $("input[name=valuni]").val("");
        $("input[name=valuni3]").val("");
        $("input[name=valtot]").val("");
        $("span[name=tipocontagem]").text("");
        $("span[name=qtdnoestoque]").text("");
        document.forms["form1"].produto.disabled = false;
        document.forms["form1"].porcao.disabled = false;
        document.forms["form1"].produto_referencia.disabled = false;
        document.forms["form1"].fornecedor.disabled = false;
        document.forms["form1"].lote.disabled = false;
        document.forms["form1"].etiqueta2.disabled = false;
        document.forms["form1"].botao_incluir.disabled = true;
    }
    //Se a etiqueta está sendo preenchida então desabilitar tudo
    else if ((qtd_caracteres >= 1) && (qtd_caracteres <= 13)) {
        $("select[name=produto]").html("");
        $("input[name=produto_referencia]").html("");
        $("input[name=etiqueta2]").html("");
        $("select[name=fornecedor]").html("");
        $("select[name=lote]").html("");
        $("select[name=porcao]").html("");
        document.forms["form1"].produto.disabled = true;
        document.forms["form1"].produto_referencia.disabled = true;
        document.forms["form1"].fornecedor.disabled = true;
        document.forms["form1"].porcao.disabled = true;
        document.forms["form1"].etiqueta2.disabled = true;
        document.forms["form1"].lote.disabled = true;
        var temp=document.forms["form1"].qtd; if (temp) document.forms["form1"].qtd.disabled = true;
         var temp=document.forms["form1"].porcao_qtd; if (temp) document.forms["form1"].porcao_qtd.disabled = true;
        $("input[name=qtd]").val("");
        $("input[name=qtd2]").val("");
        $("input[name=valuni]").val("");
        $("input[name=valuni3]").val("");
        $("input[name=valtot]").val("");
        $("input[name=fornecedor]").val("");
        $("span[name=tipocontagem]").text("");
        $("span[name=qtdnoestoque]").text("");
        document.forms["form1"].botao_incluir.disabled = true;
    }
    //Ao terminar de digitar verifica o codigo digitado e depois faz todos os calculos
    else if (qtd_caracteres == 14) {
        //Se o usu�rio apertou qualquer outro bot�o que n�o seja numero n�o executar nada
        //-----
        $.post("saidas_valida_etiqueta.php", {
            etiqueta: $("input[name=etiqueta]").val()
        }, function(valor) {
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\n", "");
            valor = valor.replace("\n", "");
            valor = valor.replace("\n", "");
            valor = valor.replace("\n", "");            
            var x = valor;
            //Caso o numero da etiqueta n�o corresponta a um produto ou lote n�o existente no banco
            if (x == "invalida") {
                alert("Etiqueta Inválida");
                $("input[name=etiqueta]").val("");
                //$("input[name=etiqueta]").focus();
                
            }
            //O produto e lote existem, mas no estoque esse produto n�o est� incluido nesse lote.
            else if (x == "semestoque") {
                alert("Este produto não consta no estoque do sistema. Por favor, anote o número desta etiqueta para analisar depois");
                document.forms["form1"].botao_incluir.disabled = true;
            }
            //O código é valido
            else {

                //Preenche o campo Produto
                $.post("saidas_etiqueta_produto.php", {
                    etiqueta: $("input[name=etiqueta]").val()
                }, function(valor) {
                    valor = valor.replace("\r", ""); 
                    valor = valor.replace("\r", ""); 
                    valor = valor.replace("\r", ""); 
                    valor = valor.replace("\r", ""); 
                    valor = valor.replace("\t", "");
                    valor = valor.replace("\t", "");
                    valor = valor.replace("\t", "");
                    valor = valor.replace("\t", "");
                    valor = valor.replace("\n", "");
                    valor = valor.replace("\n", "");
                    valor = valor.replace("\n", "");
                    valor = valor.replace("\n", "");                    
                    $("select[name=produto]").html(valor);
                    //Preenche o campo oculto do produto com o codigo dele
                    $.post("saidas_etiqueta_produto_codigo.php", {
                        etiqueta: $("input[name=etiqueta]").val()
                    }, function(valor) {
                        produto=valor;
                        $("input[name=produto2]").val(valor);
                    });
                    //Preenche o campo Fornecedor
                    $.post("saidas_etiqueta_fornecedor.php", {
                        etiqueta: $("input[name=etiqueta]").val()
                    }, function(valor) {
                        $("select[name=fornecedor]").html(valor);
                        //Preenche o campo Lote
                        $.post("saidas_etiqueta_lote.php", {
                            etiqueta: $("input[name=etiqueta]").val()
                        }, function(valor) {
                            $("select[name=lote]").html(valor);
                            //Preenche o campo oculto do lote com o codigo dele
                            $.post("saidas_etiqueta_lote_codigo.php", {
                                etiqueta: $("input[name=etiqueta]").val()
                            }, function(valor) {
                                lote=valor;
                                $("input[name=lote2]").val(valor);
                                //Atualiza o tipo de contagem
                                $.post("saidas_verifica_tipocontagem_nome.php", {
                                    produto: $("select[name=produto]").val()
                                }, function(valor) {
                                    $("span[name=tipocontagem]").text(valor);
                                    lote=eliminar_zeros_a_esquerda(lote);
                                    //alert("vaiiii:"+produto+"/"+lote);
                                    produtoelote_selecionado(produto,lote,1);
                                });
                            });
                        });

                    });

                });

            }
        });
    } else {
        alert("Erro gravíssimo, o valor da etiqueta tem mais que 14 dígitos");
    }
}
//Código de Barras EAN 
function valida_etiqueta2(campo) {
    var qtd_caracteres;
    var digits = "0123456789"
    var campo_temp
    for (var i = 0; i < campo.value.length; i++) {
        campo_temp = campo.value.substring(i, i + 1)
        if (digits.indexOf(campo_temp) == -1) {
            campo.value = campo.value.substring(0, i);
        }
    }
    //Conta quantos caracteres foram digitados no código da etiqueta
    qtd_caracteres = document.forms["form1"].etiqueta2.value.length;
    //Se a etiqueta n�o tem nada digitado ent�o habilita-se o produto para escolhe-lo manualmente
    if (qtd_caracteres == 0) {
        $.post("saidas_popula_produto.php", {}, function(valor) {
            $("select[name=produto]").html(valor);
        });
        $("input[name=produto_quantidade]").val("");
        $("input[name=etiqueta]").val("");
        $("select[name=fornecedor]").html("");
        $("select[name=lote]").html("");
        $("select[name=porcao]").html("");
        $("select[name=fornecedor]").html("");
        $("input[name=valuni]").val("");
        $("input[name=valuni3]").val("");
        $("input[name=valtot]").val("");
        $("input[name=quantidade_referencia]").val("");
        $("span[name=tipocontagem]").text("");
        $("span[name=qtdnoestoque]").text("");
        document.forms["form1"].produto.disabled = false;
        document.forms["form1"].produto_referencia.disabled = false;
        document.forms["form1"].etiqueta.disabled = false;
        document.forms["form1"].fornecedor.disabled = false;
        document.forms["form1"].lote.disabled = false;
        document.forms["form1"].botao_incluir.disabled = true;
    }
    //Se a etiqueta está sendo preenchida então desabilitar tudo
    else if ((qtd_caracteres >= 1) && (qtd_caracteres <= 12)) {
        $("input[name=etiqueta]").val("");
        $("select[name=produto]").html("");
        $("input[name=produto_referencia]").val("");
        $("select[name=fornecedor]").html("");
        $("select[name=lote]").html("");
        $("select[name=porcao]").html("");
        document.forms["form1"].produto.disabled = true;
        document.forms["form1"].produto_referencia.disabled = true;
        document.forms["form1"].etiqueta.disabled = true;
        document.forms["form1"].fornecedor.disabled= true;        
        document.forms["form1"].lote.disabled= true;        
        var temp=document.forms["form1"].qtd; if (temp) document.forms["form1"].qtd.disabled = true;
        document.forms["form1"].porcao_qtd.disabled = true;
        document.forms["form1"].porcao.disabled = true;
        $("input[name=qtd]").val("");
        $("input[name=qtd2]").val("");
        $("input[name=valuni]").val("");
        $("input[name=valuni3]").val("");
        $("input[name=valtot]").val("");
        $("input[name=fornecedor]").val("");
        $("span[name=tipocontagem]").text("");
        $("span[name=qtdnoestoque]").text("");
        document.forms["form1"].botao_incluir.disabled = true;
    } else if (qtd_caracteres == 13) { //Ao terminar de digitar verifica o codigo digitado e depois faz todos os calculos
        //Se o usu�rio apertou qualquer outro bot�o que n�o seja numero n�o executar nada
        //-----
        //alert("tem 13 digitos");
        document.forms["form1"].produto.disabled = false;
        document.forms["form1"].produto_referencia.disabled = false;
        document.forms["form1"].etiqueta.disabled = false;
        document.forms["form1"].fornecedor.disabled= false;        
        document.forms["form1"].lote.disabled= false;        
        var temp=document.forms["form1"].qtd; if (temp) document.forms["form1"].qtd.disabled = false;
        document.forms["form1"].porcao_qtd.disabled = false;
        document.forms["form1"].porcao.disabled = false;
        
        $.post("saidas_valida_etiqueta2.php", {
            etiqueta2: $("input[name=etiqueta2]").val()
        }, function(valor) {
            //alert(valor);
            var x = valor;
            //Caso o numero da etiqueta não corresponda a um produto ou lote não existente no banco
            if (x == "invalida") {
                alert("Etiqueta Inválida");
                $("input[name=etiqueta2]").val("");
                $("input[name=etiqueta2]").focus();
                
            }
            //O codigo é valido
            else {
                //Preenche o campo Produto
                $.post("saidas_etiqueta_produto_codigo2.php", {
                    etiqueta2: $("input[name=etiqueta2]").val()
                }, function(valor) {
                    var produto=valor;
                    //alert("produto:"+produto);
                    $("input[name=produto2]").val(valor);
                    popular_produto(produto);
                    selecionar_produto(produto,1);
                    document.forms["form1"].elements["qtd"].focus();
                });
                

            }
        });
    } else {
        alert("Erro gravíssimo , o valor da etiqueta tem mais que 12 dígitos");
    }
}

function somente_numero(campo) {

    var digits = "0123456789"
    var campo_temp
    for (var i = 0; i < campo.value.length; i++) {
        campo_temp = campo.value.substring(i, i + 1)
        if (digits.indexOf(campo_temp) == -1) {
            campo.value = campo.value.substring(0, i);
        }
    }
}

function verifica_estoque(campo) {

    var digits = "0123456789"
    var campo_temp
    for (var i = 0; i < campo.value.length; i++) {
        campo_temp = campo.value.substring(i, i + 1)
        if (digits.indexOf(campo_temp) == -1) {
            campo.value = campo.value.substring(0, i);
        }
    }
}

function popula_lote_oculto(valor) {
    $("input[name=lote2]").html(valor);   
}


function porcoesqtd() {
    
    $.post("saidas_verifica_tipocontagem.php", {
        produto: $("select[name=produto]").val()
    }, function(valor) {
        valor = valor.replace("\r", ""); 
        valor = valor.replace("\r", ""); 
        valor = valor.replace("\r", ""); 
        valor = valor.replace("\r", ""); 
        valor = valor.replace("\t", "");
        valor = valor.replace("\t", "");
        valor = valor.replace("\t", "");
        valor = valor.replace("\t", "");
        valor = valor.replace("\n", "");
        valor = valor.replace("\n", "");
        valor = valor.replace("\n", "");
        valor = valor.replace("\n", "");        
        tipocontagem=valor; 
        qtd=$("input[name=porcao_qtd]").val();
        qtd=parseInt(qtd);

        //Calcula quantidade que será descontado do estoque
        porcao_oculto=$("input[name=porcao_oculto]").val();
        quantidade=porcao_oculto*qtd;
        
        if ((tipocontagem == 2)||(tipocontagem==3)) {
            quantidade=quantidade.toFixed(3);
            $("input[name=qtd]").val(quantidade).priceFormat({
                prefix: '',
                centsLimit: 3,
                centsSeparator: ',',
                thousandsSeparator: '.'
            });
            $("input[name=qtd2]").val(quantidade).priceFormat({
                prefix: '',
                centsLimit: 3,
                centsSeparator: ',',
                thousandsSeparator: '.'
            });

            //alert("peso");
        } else {
            quantidade=quantidade.toFixed(0);
            $("input[name=qtd]").val(quantidade).priceFormat({
                prefix: '',
                centsLimit: 0,
                centsSeparator: '',
                thousandsSeparator: '.'
            });
            $("input[name=qtd2]").val(quantidade).priceFormat({
                prefix: '',
                centsLimit: 0,
                centsSeparator: '',
                thousandsSeparator: '.'
            });

            //alert("unidade");
        }
        
        //calcula total da porcao
        valuni=$("input[name=valuni]").val();
        valuni2=valuni.split(" ");
        valuni=valuni2[1];
        valuni=valuni.replace(".","");
        valuni=valuni.replace(",",".");
        valtot=valuni*qtd;
        valtot=valtot.toFixed(2);
        //alert(valuni+"*"+qtd+"="+valtot);
        $("input[name=valtot]").val(valtot).priceFormat({
            prefix: 'R$ ',
            centsLimit: 2,
            centsSeparator: ',',
            thousandsSeparator: '.'
        });
        var temp=document.forms["form1"].qtd; if (temp) document.forms["form1"].qtd.disabled = true;
        document.forms["form1"].botao_incluir.disabled = false;
        
        //Verifica se a quantidade final a ser retirada do estoque é menor que a quantidade que tem no estoque. 
        $.post("saidas_verifica_estoque.php", {
            lote: $("select[name=lote]").val(),
            produto:  $("select[name=produto]").val(),
            ignorarlotes: $("input[name=ignorarlotes]").val()
        }, function(valor2) {
            valor = valor2.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\n", "");
            valor = valor.replace("\n", "");
            valor = valor.replace("\n", "");
            valor2 = valor.replace("\n", "");            
            //alert(valor2);
            qtd_estoque=valor2.replace("\n","");
            qtd_estoque=parseFloat(qtd_estoque);
            quantidade=parseFloat(quantidade);
            //alert (quantidade+ " > "+ qtd_estoque);
            if (quantidade > qtd_estoque) {
                document.forms["form1"].botao_incluir.disabled = true;
                $("input[name=porcao_qtd]").val("");
                $("input[name=qtd]").val("");
                $("input[name=valtot]").val("");                
                alert("A quantidade a ser retirada do estoque PARA ESTA QUANTIDADE DE PORCÃO é maior que a quantidade disponível no estoque!");
            } else {
                //alert("Pode!");
            }


        });

    });

    //

}

function seleciona_tipo_pessoa(valor) {
    if (valor==1) {
        $("input[id=cnpj]").hide();
        $("input[id=cpf]").show();
    } else if (valor==2){
        $("input[id=cnpj]").show();
        $("input[id=cpf]").hide();
    } else {
        alert("Erro de javascript: seleciona_tipo_pessoa");
    }
    //Popula consumidor conforme tipo de pessoa selecionado
    $.post("saidas_popula_consumidor_tipopessoa.php", {
        tipopessoa:valor
        }, function(valor3) {
            //alert(valor3);
            $("select[name=consumidor]").html(valor3);
    });
}


function verifica_cpf(valor) {

    valor = valor.replace("_", "");
    valor = valor.replace("_", "");
    valor = valor.replace("_", "");
    valor = valor.replace("_", "");
    valor = valor.replace("_", "");
    valor = valor.replace("_", "");
    valor = valor.replace("_", "");
    valor = valor.replace("_", "");
    valor = valor.replace("_", "");
    valor = valor.replace("_", "");
    valor = valor.replace("_", "");
    valor = valor.replace("-", "");
    valor = valor.replace(".", "");
    valor = valor.replace(".", "");
    valor = valor.replace(".", "");

    if (valor.length == 11) {
        if (valida_cpf(valor)) {
            $.post("saidas_verifica_cpf.php", {
                cpf: valor            
            }, function(valor3) {
                valor = valor3.replace("\r", ""); 
                valor = valor.replace("\r", ""); 
                valor = valor.replace("\r", ""); 
                valor = valor.replace("\r", ""); 
                valor = valor.replace("\t", "");
                valor = valor.replace("\t", "");
                valor = valor.replace("\t", "");
                valor = valor.replace("\t", "");
                valor = valor.replace("\n", "");
                valor = valor.replace("\n", "");
                valor = valor.replace("\n", "");
                valor3 = valor.replace("\n", "");
                if (valor3=="naocadastrado") {
                    //alert("Cadastrar");
                    $("select[name=consumidor]").hide();
                    $("input[name=cliente_nome]").show();
                    document.forms["form1"].cliente_nome.disabled = false;
                    document.forms["form1"].cliente_nome.focus();

                } else {
                    valor3=parseInt(valor3);
                    codigo_pessoa=valor3;
                    //alert("Selecionar");
                    $("select[id=consumidor]").show();
                    $("select[id=cliente_nome]").hide();
                    document.forms["form1"].cliente_nome.disabled = true;
                    select_selecionar("consumidor",codigo_pessoa);
                }
            });
        }
    } else {
        $("select[id=consumidor]").show();
        $("input[id=cliente_nome]").hide();
        document.forms["form1"].cliente_nome.disabled = true;
    }
}


function verifica_cnpj(valor) {
    
    valor = valor.replace("_", "");
    valor = valor.replace("_", "");
    valor = valor.replace("_", "");
    valor = valor.replace("_", "");
    valor = valor.replace("_", "");
    valor = valor.replace("_", "");
    valor = valor.replace("_", "");
    valor = valor.replace("_", "");
    valor = valor.replace("_", "");
    valor = valor.replace("_", "");
    valor = valor.replace("_", "");
    valor = valor.replace("-", "");
    valor = valor.replace("-", "");
    valor = valor.replace("-", "");
    valor = valor.replace(".", "");
    valor = valor.replace(".", "");
    valor = valor.replace("/", "");
    valor = valor.replace("-", "");
    
    if (valor.length == 14) {
        //alert(valor);
        if (valida_cnpj(valor)) {
            //alert("valido");
            $.post("saidas_verifica_cnpj.php", {
                cnpj: valor            
            }, function(valor3) {
                valor = valor3.replace("\r", ""); 
                valor = valor.replace("\r", ""); 
                valor = valor.replace("\r", ""); 
                valor = valor.replace("\r", ""); 
                valor = valor.replace("\t", "");
                valor = valor.replace("\t", "");
                valor = valor.replace("\t", "");
                valor = valor.replace("\t", "");
                valor = valor.replace("\n", "");
                valor = valor.replace("\n", "");
                valor = valor.replace("\n", "");
                valor3 = valor.replace("\n", "");                
                codigo_pessoa=valor3;
                //alert(valor3);
                if (valor3=="naocadastrado") {
                    //alert("Cadastrar");
                    $("select[name=consumidor]").hide();
                    $("input[name=cliente_nome]").show();
                    document.forms["form1"].cliente_nome.disabled = false;
                    document.getElementById("cliente_nome").focus(); 
                    

                } else {
                    //alert("Selecionar");
                    $("select[id=consumidor]").show();
                    $("select[id=cliente_nome]").hide();
                    document.forms["form1"].cliente_nome.disabled = true;
                    select_selecionar("consumidor",codigo_pessoa);
                }
            });
            
        } else {
            //alert("invalido");
            
        }
    } else {
        $("select[id=consumidor]").show();
        $("input[id=cliente_nome]").hide();
        document.forms["form1"].cliente_nome.disabled = true;
    }
}


function verifica_fone(valor) {

    //valor = valor.replace("-", "");
    //valor = valor.replace(".", "");
    //valor = valor.replace(".", "");
    if ((valor.length == 14 )||(valor.length == 13)) {
        $.post("saidas_verifica_fone.php", {
            fone: valor            
        }, function(valor3) {
            valor = valor3.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\n", "");
            valor = valor.replace("\n", "");
            valor = valor.replace("\n", "");
            valor3 = valor.replace("\n", "");            
            codigo_pessoa=valor3;
            //alert(valor3);
            if (valor3=="naocadastrado") {
                //alert("Cadastrar");
                $("select[name=consumidor]").hide();
                $("input[name=cliente_nome]").show();
                document.forms["form1"].cliente_nome.disabled = false;
                document.forms["form1"].cliente_nome.focus();
                document.forms["form1"].botao_incluir.disabled = false;

            } else {
                //alert("Selecionar");
                $("select[id=consumidor]").show();
                $("select[id=cliente_nome]").hide();
                document.forms["form1"].cliente_nome.disabled = true;
                select_selecionar("consumidor",codigo_pessoa);
                document.forms["form1"].botao_incluir.disabled = false;
                $("input[name=botao_incluir]").focus();
            }
        });
    } else {
        $("select[id=consumidor]").show();
        $("input[id=cliente_nome]").hide();
        document.forms["form1"].cliente_nome.disabled = true;
        if (valor.length != 0 ) {
            document.forms["form1"].botao_incluir.disabled = true;
            alert("Telefone inválido!");
            $("input[id=fone]").val("");
            document.forms["form1"].fone.focus();
        } else {
            document.forms["form1"].botao_incluir.disabled = false;
        }
    }
    select_selecionar("consumidor",0);
    select_selecionar("entrega","");
    verifica_entrega("0");

}

function verifica_fone_botao_incluir (valor) {
    if ((valor.length==0)) {
        document.forms["form1"].botao_incluir.disabled = false;
    } else {
        document.forms["form1"].botao_incluir.disabled = true;
    }

    if (valor.length<=13)  $("#fone").mask("(00)0000-00009");
    else $("#fone").mask("(00)00000-0000");
}


function verifica_produto_referencia(valor) {
    //console.log(valor);
    
    valor = remove_caracteres_especiais(valor);
    $("input[name=produto_referencia]").val(valor);
    
    if (valor!="") { //Preenche o campo produto referencia com a referencia do produto
        $.post("saidas_produto_referencia.php",  {
            referencia: valor
        }, function(produtos) {
            valor = produtos.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\n", "");
            valor = valor.replace("\n", "");
            valor = valor.replace("\n", "");
            produtos = valor.replace("\n", "");            
            //alert("Produtos encontrados a partir da referencia:"+produtos);
            produtos2=produtos.split("|");
            produto_codigo=produtos2[0];
            produto_nome=produtos2[1];
            if (produtos!=0) { //Se o produto encontrado
                $("select[name=produto]").html("<option value='"+produto_codigo+"'>"+produto_nome+" </option>");
                document.forms["form1"].produto.disabled = true;
                $("input[name=produto2]").val(produto_codigo);
                selecionar_produto(produto_codigo,0);                
            } else { //Produto não encontrado
                $.post("saidas_popula_produto.php", {}, function(valor) {
                    $("select[name=produto]").html(valor);
                    document.forms["form1"].produto.disabled = false;
                });
                $("select[name=lote]").html("");
                $("select[name=fornecedor]").html("");
                //selecionar_produto("",0);   
            }

        });
    } else {
        $.post("saidas_popula_produto.php", {}, function(valor) {
            $("select[name=produto]").html(valor);
            document.forms["form1"].produto.disabled = false;
        });
    }
    
}

function eliminar_zeros_a_esquerda(sStr){
   var i;
   for(i=0;i<sStr.length;i++)
      if(sStr.charAt(i)!='0')
         return sStr.substring(i);
   return sStr;
}


function atualizar_referencia () {
    produto=$("select[name=produto]").val();
    destino = "produto_editar_referencia.php?modal=1&codigo="+produto;
    window.open(destino, '_blank');
    //alert('Atualizar referencia"' + produto);
}

function verifica_comanda_duplicada (valor) {
    //console.log(valor);
    comanda=valor;
    if (comanda=="") {
        document.getElementById("validador_comanda_duplicada").src="../imagens/icones/geral/confirmar2.png";
        document.forms["form1"].botao_incluir.disabled = false;
        document.getElementById("texto_comanda_duplicada").classList.remove("correto2");
        document.getElementById("texto_comanda_duplicada").classList.remove("errado2");
        $("span[id=texto_comanda_duplicada]").text("");


    } else {
        saida=$("input[name=saida]").val();
        $.post("saidas_valida_comanda_duplicada.php",{
            comanda:comanda,
            saida:saida
        },function(valor2){
            valor = valor2.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\r", ""); 
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\t", "");
            valor = valor.replace("\n", "");
            valor = valor.replace("\n", "");
            valor = valor.replace("\n", "");
            valor2 = valor.replace("\n", "");            
            //console.log(valor2); 
            if (valor2=="emuso") { 
                //console.log("Em uso: "+valor);
                document.forms["form1"].botao_incluir.disabled = true;
                document.getElementById("validador_comanda_duplicada").src="../imagens/icones/geral/erro.png";
                document.getElementById("texto_comanda_duplicada").classList.remove("correto2");
                document.getElementById("texto_comanda_duplicada").classList.add("errado2");
                $("span[id=texto_comanda_duplicada]").text("Em uso!");
            } else {
                //console.log("Liberado: "+valor);
                document.forms["form1"].botao_incluir.disabled = false;
                document.getElementById("validador_comanda_duplicada").src="../imagens/icones/geral/confirmar.png";
                document.getElementById("texto_comanda_duplicada").classList.add("correto2");
                document.getElementById("texto_comanda_duplicada").classList.remove("errado2");
                $("span[id=texto_comanda_duplicada]").text("");
                
            }
        });   
        
    }
}