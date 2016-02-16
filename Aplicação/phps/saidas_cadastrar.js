
$(window).load(function() {

    //$("tr[id=linha_porcoes]").hide();
    //$("tr[id=linha_porcoes_qtd]").hide();

    //Popular Produto    
    $.post("saidas_popula_produto.php", {}, function(valor) {
        $("select[name=produto]").html(valor);
        document.forms["form1"].qtd.disabled = true;
        document.forms["form1"].porcao_qtd.disabled = true;
    });

    //Ao selecionar o produto
    $("select[name=produto]").change(function() {
        
        //Popula o Fornecedor        
        $("select[name=fornecedor]").html('<option>Aguarde, carregando...</option>');
        $.post("saidas_popula_fornecedor.php", {
            produto: $(this).val()
        }, function(valor) {
            $("select[name=fornecedor]").html(valor);
        });
        //Zerar campos
        $("input[name=etiqueta]").val("");
        $("input[name=qtd]").val("");
        $("select[name=lote]").html("");
        $("input[name=valuni]").val("");
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
        
        //Desabilita o campo quantidade e o botão de incluir
        document.forms["form1"].qtd.disabled = true;
        document.forms["form1"].porcao_qtd.disabled = true;
        document.forms["form1"].botao_incluir.disabled = true;
        
        //Popula porcoes
        $.post("saidas_popula_porcoes.php", {
            produto: $("select[name=produto]").val()
        }, function(valor) {
            //alert(valor);
            $("select[name=porcao]").html(valor);
        }); 
        
        //Define máscara para campo quantidade de porções
        $("input[name=porcao_qtd]").val("").priceFormat({
            prefix: '',
            centsSeparator: '',
            centsLimit: 0,
            thousandsSeparator: ''
        });  
    });

    //Ao selecionar o fornecedor
    $("select[name=fornecedor]").change(function() {
        //Popula o Lote
        $("select[name=lote]").html('<option>Aguarde, carregando...</option>');
        $.post("saidas_popula_lote.php", {
            produto: $("select[name=produto]").val(),
            fornecedor: $(this).val()
        }, function(valor) {
            $("select[name=lote]").html(valor);
        });
        
        //Zerar campos
        $("input[name=valuni]").val("");
        $("input[name=valtot]").val("");
        $("span[name=qtdnoestoque]").text("");
        $("input[name=qtd]").val("");
        $("input[name=porcao_qtd]").val("");
        
        //Habilita botão e campo quantidade
        document.forms["form1"].qtd.disabled = true;
        document.forms["form1"].porcao_qtd.disabled = true;
        document.forms["form1"].botao_incluir.disabled = true;
    });

    //Ao selecionar o lote
    $("select[name=lote]").change(function() {
        produto_selecionado();

    });
    
    //Ao selecionar porcoes
    $("select[name=porcao]").change(function() {
        $.post("saidas_popula_porcoesqtdoculto.php", {
            porcao: $("select[name=porcao]").val()
        }, function(valor) {
            //alert(valor);
            $("input[name=porcao_oculto]").val(valor);
        });
        
        porcao2=$("select[name=porcao]").val();
        if (porcao2=="") {
            document.forms["form1"].porcao_qtd.disabled = true;
        } else {
            lote2=$("select[name=lote]").val();
            //alert("Lote2="+lote2);
            if ((lote2!="")&&(lote2!= null)&&(lote2!== undefined)&&(lote2!= 0))             
                document.forms["form1"].porcao_qtd.disabled = false;
            else 
                document.forms["form1"].porcao_qtd.disabled = true;
        }

        
    });
});

function produto_selecionado() {
    //Popula valor unitário
    $.post("saidas_valorunitario.php", {
        lote: $("select[name=lote]").val(),
        produto: $("select[name=produto]").val()
    }, function(valor) {
        $("input[name=valuni]").val(valor);
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
                    document.forms["form1"].botao_incluir.disabled = true;
                } else { //Se o tipo de contagem for diferente de 'kg'
                    if (estoqueatual >= 1) {
                        $("input[name=qtd]").val("1");
                        //Etiqueta validada e campos preenchidos, joga o foco para a quantidade
                        document.forms["form1"].elements["qtd"].focus();
                        document.forms["form1"].elements["qtd"].select();
                    } else {
                        $("input[name=qtd]").val(etqatu);
                    }
                    calcula_totais();
                    document.forms["form1"].botao_incluir.disabled = false;

                }
            }
        });
    });
}

function calcula_totais() {

    //Popula valor total
    $.post("saidas_valortotal.php", {
        lote: $("select[name=lote]").val(),
        qtd: $("input[name=qtd]").val(),
        produto: $("select[name=produto]").val()
    }, function(valor) {
        $("input[name=valtot]").val("R$ " + valor);
    });
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
            //alert("peso");
        } else {
            $('#qtd').priceFormat({
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
        //alert(valor);
        //Verifica se a quantidade digitada é maior que a do estoque
        var qtddigitada = $("input[name=qtd]").val();
        //alert("fff"+qtddigitada+"fff");
        if (valor != "") {
            if (qtddigitada != "") {
                qtddigitada = qtddigitada.replace(".", "");
                qtddigitada = qtddigitada.replace(",", ".");
                qtddigitada = parseFloat(qtddigitada);
            }
            var qtdestoque = valor;
            //alert("c"+qtddigitada+"s");      
            //qtdestoque=qtdestoque2.replace(".","");
            //qtdestoque=qtdestoque.replace(",",".");
            qtdestoque = parseFloat(qtdestoque);
            //alert(qtddigitada + ">" + qtdestoque);                        
            //alert(parseFloat(qtddigitada)+">"+parseFloat(qtdestoque));
            if (qtddigitada > qtdestoque) {
                document.forms["form1"].botao_incluir.disabled = true;
                alert("A quantidade digitada é maior que a quantidade disponível no estoque! A quantidade atual deste produto no estoque está descrito ao lado do campo!");

                $("input[name=porcao_qtd]").val("");
                $("input[name=qtd]").val("");
                $("input[name=valtot]").val("");


            } else {
                //Calcula o total
                $.post("saidas_valortotal.php", {
                    lote: $("select[name=lote]").val(),
                    qtd: $("input[name=qtd]").val(),
                    produto: $("select[name=produto]").val()
                }, function(valor) {
                    //alert (qtddigitada);                   
                    if (qtddigitada == 0) {
                        document.forms["form1"].botao_incluir.disabled = true;
                        $("input[name=valtot]").val("");
                        //alert("desabilitar");
                    } else {
                        $("input[name=valtot]").val("R$ " + valor);
                        document.forms["form1"].botao_incluir.disabled = false;
                        //alert("habilitar");
                    }
                });
            }
            //alert("ddd"+qtddigitada+"ddd");            
        }

    });
}

//Etiqueta
function valida_etiqueta(campo) {
    var qtd_caracteres;
    var digits = "0123456789"
    var campo_temp
    for (var i = 0; i < campo.value.length; i++) {
        campo_temp = campo.value.substring(i, i + 1)
        if (digits.indexOf(campo_temp) == -1) {
            campo.value = campo.value.substring(0, i);
        }
    }
    //Conta quantos caracteres foram digitados no c�digo da etiqueta
    qtd_caracteres = document.forms["form1"].etiqueta.value.length;
    //Se a etiqueta n�o tem nada digitado ent�o habilita-se o produto para escolhe-lo manualmente
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
        $("input[name=valtot]").val("");
        $("span[name=tipocontagem]").text("");
        $("span[name=qtdnoestoque]").text("");
        document.forms["form1"].produto.disabled = false;
        document.forms["form1"].fornecedor.disabled = false;
        document.forms["form1"].lote.disabled = false;
        document.forms["form1"].etiqueta2.disabled = false;
        document.forms["form1"].botao_incluir.disabled = true;
    }
    //Se a etiqueta est� sendo preenchida ent�o desabilitar tudo
    else if ((qtd_caracteres >= 1) && (qtd_caracteres <= 13)) {
        $("select[name=produto]").html("");
        $("input[name=etiqueta2]").html("");
        $("select[name=fornecedor]").html("");
        $("select[name=lote]").html("");
        $("select[name=porcao]").html("");
        document.forms["form1"].produto.disabled = true;
        document.forms["form1"].fornecedor.disabled = true;
        document.forms["form1"].etiqueta2.disabled = true;
        document.forms["form1"].lote.disabled = true;
        document.forms["form1"].qtd.disabled = true;
        document.forms["form1"].porcao_qtd.disabled = true;
        $("input[name=qtd]").val("");
        $("input[name=valuni]").val("");
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
            }
            //O produto e lote existem, mas no estoque esse produto n�o est� incluido nesse lote.
            else if (x == "semestoque") {
                alert("Este produto não consta no estoque do sistema. Por favor, anote o número desta etiqueta para analisar depois");
                document.forms["form1"].botao_incluir.disabled = true;
            }
            //O c�digo � valido
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
                                $("input[name=lote2]").val(valor);
                                //Atualiza o tipo de contagem
                                $.post("saidas_verifica_tipocontagem_nome.php", {
                                    produto: $("select[name=produto]").val()
                                }, function(valor) {
                                    $("span[name=tipocontagem]").text(valor);
                                    produto_selecionado();
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
//Etiqueta
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
        $("input[name=etiqueta]").val("");
        $("select[name=fornecedor]").html("");
        $("select[name=lote]").html("");
        $("select[name=porcao]").html("");
        $("select[name=fornecedor]").html("");
        $("input[name=valuni]").val("");
        $("input[name=valtot]").val("");
        $("span[name=tipocontagem]").text("");
        $("span[name=qtdnoestoque]").text("");
        document.forms["form1"].produto.disabled = false;
        document.forms["form1"].etiqueta.disabled = false;
        document.forms["form1"].fornecedor.disabled = false;
        document.forms["form1"].lote.disabled = false;
        document.forms["form1"].botao_incluir.disabled = true;
    }
    //Se a etiqueta está sendo preenchida então desabilitar tudo
    else if ((qtd_caracteres >= 1) && (qtd_caracteres <= 12)) {
        $("input[name=etiqueta]").val("");
        $("select[name=produto]").html("");
        $("select[name=fornecedor]").html("");
        $("select[name=lote]").html("");
        $("select[name=porcao]").html("");
        document.forms["form1"].produto.disabled = true;
        document.forms["form1"].etiqueta.disabled = true;
        //document.forms["form1"].fornecedor.disabled= true;        
        //document.forms["form1"].lote.disabled= true;        
        document.forms["form1"].qtd.disabled = true;
        document.forms["form1"].porcao_qtd.disabled = true;
        $("input[name=qtd]").val("");
        $("input[name=valuni]").val("");
        $("input[name=valtot]").val("");
        $("input[name=fornecedor]").val("");
        $("span[name=tipocontagem]").text("");
        $("span[name=qtdnoestoque]").text("");
        document.forms["form1"].botao_incluir.disabled = true;
    }

    //Ao terminar de digitar verifica o codigo digitado e depois faz todos os calculos
    else if (qtd_caracteres == 13) {
        //Se o usu�rio apertou qualquer outro bot�o que n�o seja numero n�o executar nada
        //-----
        $.post("saidas_valida_etiqueta2.php", {
            etiqueta2: $("input[name=etiqueta2]").val()
        }, function(valor) {
            //alert(valor);
            var x = valor;
            //Caso o numero da etiqueta n�o corresponta a um produto ou lote n�o existente no banco
            if (x == "invalida") {
                alert("Etiqueta Inválida");
                $("input[name=etiqueta2]").val("");
                $("input[name=etiqueta2]").focus();
            }
            //O codigo é valido
            else {
                //Preenche o campo Produto
                $.post("saidas_etiqueta_produto2.php", {
                    etiqueta2: $("input[name=etiqueta2]").val()
                }, function(valor) {
                    //alert(valor);
                    $("select[name=produto]").html(valor);                                        
                    //Preenche o campo oculto do produto com o codigo dele
                    $.post("saidas_etiqueta_produto_codigo2.php", {
                        etiqueta2: $("input[name=etiqueta2]").val()
                    }, function(valor) {
                        $("input[name=produto2]").val(valor);
                    });
                    //Preenche o campo Fornecedor
                    $.post("saidas_etiqueta_fornecedor2.php", {
                        etiqueta2: $("input[name=etiqueta2]").val()
                    }, function(valor) {
                        //alert(valor);
                        $("select[name=fornecedor]").html(valor);
                        //Preenche o campo Lote
                    });
                    $.post("saidas_etiqueta_lote2.php", {
                        etiqueta2: $("input[name=etiqueta2]").val()
                    }, function(valor) {
                        $("select[name=lote]").html(valor);
                        $("select[name=lote]").focus();
                        //Preenche o campo oculto do lote com o codigo dele
                        produto_selecionado();
                    });

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


function porcoesqtd_popula_qtd() {
    
    tipocontagem=3; //ATENCAO ALTERAR
     var qtdref=$("input[name=porcao_oculto]").val();
     qtd=$("input[name=porcao_qtd]").val();
     var quantidade=qtdref*qtd;
     //alert(quantidade+"="+qtdref+"+"+qtd);
    if ((tipocontagem == 2)||(tipocontagem==3)) {
        quantidade=quantidade.toFixed(3);
        $("input[name=qtd]").val(quantidade).priceFormat({
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
        //alert("unidade");
    }

}
