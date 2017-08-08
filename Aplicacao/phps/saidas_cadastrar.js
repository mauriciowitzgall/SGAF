
$(window).load(function() {

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


    //Popular Produto    
    popular_produto();

    //Define o foco padrão
    document.forms["form1"].elements["produto_referencia"].focus();
    
    //Ao selecionar porcoes
    $("select[name=porcao]").change(function() {
        
        $("input[name=qtd]").val("");
        $("input[name=qtd2]").val("");
        $("input[name=porcao_qtd]").val("");
        $("input[name=valtot]").val("");

        //Popula a quantidade da porção (campo oculto)
        $.post("saidas_popula_porcoesqtdoculto.php", {
            porcao: $("select[name=porcao]").val()
        }, function(valor1) {
            //alert(valor);
            $("input[name=porcao_oculto]").val(valor1);
            
            //Popula o valor unitário referencial caso tenha
            
            porcao=$("select[name=porcao]").val();
            if (porcao=="") {
                porcao=0;
                $("input[name=porcao_qtd]").val("");
                document.forms["form1"].qtd.disabled = false;

            }
            $.post("saidas_popula_valuniref.php", {
                porcao: porcao
            }, function(valor2) {
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
                
            });      
        });
        porcao2=$("select[name=porcao]").val();
        if (porcao2=="") {
            document.forms["form1"].porcao_qtd.disabled = true;
        } else {
            lote2=$("select[name=lote]").val();
            //alert("Lote2="+lote2);
            if ((lote2!="")&&(lote2!= null)&&(lote2!== undefined)&&(lote2!= 0)) {
                document.forms["form1"].qtd.disabled = true;
                document.forms["form1"].porcao_qtd.disabled = false;
            }             
            else {
                document.forms["form1"].qtd.disabled = false;
                document.forms["form1"].porcao_qtd.disabled = true;
            } 
        }
    });
});

function popular_produto (produto) {
    //alert(produto);
    $.post("saidas_popula_produto.php", { produto: produto }, function(valor) {
        $("select[name=produto]").html(valor);
        document.forms["form1"].qtd.disabled = true;
        document.forms["form1"].porcao_qtd.disabled = true;
    });
        
}

function selecionar_produto (produto,focoqtd) {
    
    //alert("Selecionando PRODUTO: "+produto);
       

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
        $("span[name=tipocontagem]").text(valor);
    });

    //Popula a Referencia do produto
    /*
    if (produto!="") {
        $("input[name=produto_referencia]").val("");
        $.post("saidas_popula_produto_referencia.php", {
            produto: produto
        }, function(valor) {
            //alert("produto referencia:"+valor);
            $("input[name=produto_referencia]").val(valor);
        });
    }*/

    //Desabilita o campo quantidade e o botão de incluir
    document.forms["form1"].qtd.disabled = true;
    document.forms["form1"].porcao_qtd.disabled = true;
    document.forms["form1"].botao_incluir.disabled = true;

    //Define máscara para campo quantidade de porções
    $("input[name=porcao_qtd]").val("").priceFormat({
        prefix: '',
        centsSeparator: '',
        centsLimit: 0,
        thousandsSeparator: ''
    }); 
    
    //Popula o Fornecedor        
    if (produto!="") {
        popular_fornecedor(produto,focoqtd);
    }


}

function selecionar_fornecedor (fornecedor,focoqtd) {
    
    //alert("Selecionando FORNECEDOR: "+fornecedor);    
    
    produto=$("select[name=produto]").val();
    
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
    document.forms["form1"].qtd.disabled = true;
    document.forms["form1"].porcao_qtd.disabled = true;
    document.forms["form1"].botao_incluir.disabled = true;    
    
    
}

function popular_fornecedor (produto,focoqtd) {
    $("select[name=fornecedor]").html('<option>Aguarde, carregando...</option>');
    $.post("saidas_popula_fornecedor.php", {
        produto: produto
    }, function(valor) {
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
                selecionar_fornecedor(fornecedor,focoqtd);
            } else { //Tem mais de um fornecedor
                //alert("Tem mais de um fornecedor");
                if (focoqtd==1) document.forms["form1"].elements["fornecedor"].focus();            
            }
        });
    });   
}

function popular_lote (produto,fornecedor,focoqtd) {
    $("select[name=lote]").html('<option>Aguarde, carregando...</option>');
    $.post("saidas_popula_lote.php", {
        produto: produto,
        fornecedor: fornecedor
    }, function(valor) {
        $("select[name=lote]").html(valor);
            $.post("saidas_verifica_lote_unico.php", {
            produto: produto,
            fornec: fornecedor
        }, function(lote) {
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

    
    $("input[name=porcao_qtd]").val("");
    produtoelote_selecionado(produto,lote,focoqtd);

    //Popula porcoes
    $.post("saidas_popula_porcoes.php", {
        produto: $("select[name=produto]").val()
    }, function(valor) {
        //alert(valor);
        $("select[name=porcao]").html(valor);
    }); 
    
}


function produtoelote_selecionado(produto,lote,focoqtd) {
    
    if ((produto!="")&&(lote!="")) {
        //alert("Populando valor unitário... produto:"+produto+" lote:"+lote+" focoqtd:"+focoqtd);
        //Popula valor unitário padrão
        $.post("saidas_valorunitario.php", {
            lote: lote,
            produto: produto
        }, function(valor) {
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
            //Popula quantidade em estoque
            $.post("saidas_verifica_estoque.php", {
                lote: $("select[name=lote]").val(),
                produto: $("select[name=produto]").val()
            }, function(valor2) {
                var etqatu = valor2;
                var estoqueatual = etqatu.replace(".", "");
                estoqueatual = estoqueatual.replace(",", ".");
                if (etqatu == "") {
                    $("span[name=qtdnoestoque]").text("");
                    $("input[name=qtd]").val("");
                    $("input[name=qtd2]").val("");
                    document.forms["form1"].botao_incluir.disabled = true;
                    document.forms["form1"].qtd.disabled = true;
                    document.forms["form1"].porcao_qtd.disabled = true;
                } else {
                    document.forms["form1"].qtd.disabled = false;
                    porcao2=$("select[name=porcao]").val();
                    if ((porcao2!="")&&(porcao2!= null)&&(porcao2!== undefined)&&(porcao2!= 0)) document.forms["form1"].porcao_qtd.disabled = false;
                    else document.forms["form1"].porcao_qtd.disabled = true;
                    $("span[name=qtdnoestoque]").text("(" + etqatu + " no estoque)");
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
                        saidas_qtd();
                        document.forms["form1"].botao_incluir.disabled = false;

                    }
                    //Caso tenha vários lotes ou fornecedores o campo foco virá indefinido, por isso definimos ele como 0, para não setar o foco na quantidade
                    if (focoqtd!=0) focoqtd=1; 
                        //alert("O foco agoraaaaaa é:"+focoqtd);
                        if (focoqtd==1) {
                            foco_quantidade();
                    } 
                }
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

function saidas_qtd() {
    //Verifica se há no estoque        
    $.post("saidas_verifica_estoque2.php", {
        lote: $("select[name=lote]").val(),
        produto: $("select[name=produto]").val()
    }, function(valor) {
        //Verifica se a quantidade digitada é maior que a do estoque
        var qtddigitada = $("input[name=qtd]").val();
        if (valor != "") {
            if (qtddigitada != "") {
                qtddigitada = qtddigitada.replace(".", "");
                qtddigitada = qtddigitada.replace(",", ".");
                qtddigitada = parseFloat(qtddigitada);
            }
            var qtdestoque = valor;
            qtdestoque = parseFloat(qtdestoque);
            if (qtddigitada > qtdestoque) {
                document.forms["form1"].botao_incluir.disabled = true;
                alert("A quantidade digitada é maior que a quantidade disponível no estoque! A quantidade atual deste produto no estoque está descrito ao lado do campo!");

                $("input[name=porcao_qtd]").val("");
                $("input[name=qtd]").val("");
                $("input[name=valtot]").val("");
            } else {
                //Calcula o total
                //alert(qtddigitada);
                if (qtddigitada == 0) {
                    $("input[name=valtot]").val("");
                    document.forms["form1"].botao_incluir.disabled = true;
                    //alert("desabilitar");
                } else {
                    valuni=$("input[name=valuni]").val();
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
            //alert("ddd"+qtddigitada+"ddd");            
        }

    });
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
        document.forms["form1"].etiqueta2.disabled = true;
        document.forms["form1"].lote.disabled = true;
        document.forms["form1"].qtd.disabled = true;
        document.forms["form1"].porcao_qtd.disabled = true;
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
        document.forms["form1"].qtd.disabled = true;
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
        document.forms["form1"].qtd.disabled = false;
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
    
    tipocontagem=3; //ATENCAO ALTERAR
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
    document.forms["form1"].qtd.disabled = true;
    document.forms["form1"].botao_incluir.disabled = false;
    
    
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
                codigo_pessoa=valor3;
                //alert(valor3);
                if (valor3=="naocadastrado") {
                    //alert("Cadastrar");
                    $("select[name=consumidor]").hide();
                    $("input[name=cliente_nome]").show();
                    document.forms["form1"].cliente_nome.disabled = false;

                } else {
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

function verifica_produto_referencia(valor) {
    
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
    $("input[name=produto_referencia]").val(valor);
    
    if (valor!="") { //Preenche o campo produto referencia com a referencia do produto
        $.post("saidas_produto_referencia.php", {
            referencia: valor
        }, function(produtos) {
            //alert("Produtos encontrados a partir da referencia:"+produtos);
            produtos2=produtos.split("|");
            produto_codigo=produtos2[0];
            produto_nome=produtos2[1];
            if (produtos!=0) { //Se o produto é valido e uma referencia foi encontrada então popular
                $("select[name=produto]").html("<option value='"+produto_codigo+"'>"+produto_nome+" </option>");
                document.forms["form1"].produto.disabled = true;
                $("input[name=produto2]").val(produto_codigo);
                selecionar_produto(produto_codigo,0);                
            } else {
                $.post("saidas_popula_produto.php", {}, function(valor) {
                    $("select[name=produto]").html(valor);
                    document.forms["form1"].produto.disabled = false;
                });
                $("select[name=lote]").html("");
                $("select[name=fornecedor]").html("");
                selecionar_produto("",0);   
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
