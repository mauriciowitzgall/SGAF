<head>
    <title>SGAF Cadastre-se!</title>
    <html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <link rel="stylesheet" type="text/css" href="classes.css" />
        <link rel="stylesheet" type="text/css" href="templates/geral.css">
            <script type="text/javascript" src="js/jquery-1.3.2.js"></script>
            <script src="js/jquery.maskedinput-1.1.4.pack.js" type="text/javascript"></script>
            <script src="mascaras.js" type="text/javascript"></script>
            <script type="text/javascript" src="forcadasenha.js"></script>         
            <script src="js/jquery.pstrength-min.1.2.js" type="text/javascript"></script>

            <link href="js/_style/jquery.click-calendario-1.0.css" rel="stylesheet" type="text/css"/>
            <script type="text/javascript" src="js/_scripts/jquery.click-calendario-1.0-min.js"></script>		
            <script type="text/javascript" src="js/_scripts/exemplo-calendario.js"></script>        
            <script type="text/javascript" src="funcoes.js"></script>
            <script type="text/javascript">
                window.onload = function(){
                    $("input[name=cooperativa]").attr("required", false);
                    $("input[name=cooperativa]").hide();
                    $("input[name=cooperativa_sigla]").attr("required", false);
                    $("input[name=cooperativa_sigla]").hide();
                    $("span[name=cooperativadica]").hide();
                    $("input[name=pergunta]").attr("required", false);
                    $("input[name=resposta]").attr("required", false);
                    $("tr[id=tr_pergunta]").hide();
                    $("tr[id=tr_resposta]").hide();
                    document.getElementById("check_perguntasecreta").checked=true;
                    document.getElementById("check_perguntasecreta").disabled=true;
                    usarpergunta();            
                };
        
        
                //Ao selecionar o pais popular a lista de estados
                function popula_estados( ) {
                    $("select[name=estado]").html('<option>Carregando</option>');   
                    $("select[name=cidade]").html('<option>Selecione</option>');    
                    $.post("paisestado.php", {
                        pais:$("select[name=pais]").val()
                    }, function(valor) {
                        $("select[name=estado]").html(valor);
                    });
                }
        
                function popula_cidades() {            
                    $("select[name=cidade]").html('<option>Carregando</option>');
                    $.post("estadocidade.php", {
                        estado:$("select[name=estado]").val()
                    }, function(valor) {
                        $("select[name=cidade]").html(valor);
                    });
                }
                function popula_estadospv() {
                    $("select[name=estadopv]").html('<option>Carregando</option>');   
                    $("select[name=cidadepv]").html('<option>Selecione</option>');    
                    $.post("paisestado.php", {
                        pais:$("select[name=paispv]").val()
                    }, function(valor) {
                        $("select[name=estadopv]").html(valor);
                    });
                }
        
                function popula_cidadespv() {            
                    $("select[name=cidadepv]").html('<option>Carregando</option>');
                    $.post("estadocidade.php", {
                        estado:$("select[name=estadopv]").val()
                    }, function(valor) {
                        $("select[name=cidadepv]").html(valor);
                    });
                }
        
                function cooperativa1(valor) {
                    if (valor==0) {
                        $("input[name=cooperativa]").attr("required", false);
                        $("input[name=cooperativa]").hide();
                        $("input[name=cooperativa_sigla]").attr("required", false);
                        $("input[name=cooperativa]_sigla").hide();
                        $("span[name=cooperativadica]").hide();
                    } else {
                        $("input[name=cooperativa]").show();    
                        $("input[name=cooperativa]").attr("required", true);
                        $("input[name=cooperativa_sigla]").show();    
                        $("input[name=cooperativa_sigla]").attr("required", true);
                        $("span[name=cooperativadica]").show();    
                    }
                }        
                function possui_mesmo_endereco(valor) {
                    if (valor==1) {
                        //alert('Sim possui');
                        $("input[name=ceppv]").attr("required", false);
                        $("tr[id=tr_cep]").hide();
                        $("select[name=cidadepv]").attr("required", false);
                        $("select[name=estadopv]").attr("required", false);
                        $("select[name=paispv]").attr("required", false);
                        $("tr[id=tr_cidade]").hide();
                        $("input[name=bairropv]").attr("required", false);
                        $("tr[id=tr_bairro]").hide();
                        $("input[name=enderecopv]").attr("required", false);
                        $("input[name=numeropv]").attr("required", false);
                        $("tr[id=tr_endereco]").hide();
                        $("tr[id=tr_pontoref]").hide();                
                    } else if (valor==0) {
                        $("tr[id=tr_cep]").show();
                        $("input[name=ceppv]").attr("required", true);
                        $("tr[id=tr_cidade]").show();
                        $("select[name=cidadepv]").attr("required", true);
                        $("select[name=estadopv]").attr("required", true);
                        $("select[name=paispv]").attr("required", true);
                        $("tr[id=tr_bairro]").show();
                        $("input[name=bairropv]").attr("required", true);
                        $("tr[id=tr_endereco]").show();
                        $("input[name=enderecopv]").attr("required", true);
                        $("input[name=numeropv]").attr("required", true);
                        $("tr[id=tr_pontoref]").show();
                    }           
                }
                function email1 (valor) {
                    $("input[name=email_senha]").val(valor);
                    emailpergunta();
            
                }
                function usarpergunta () {
                    if (document.getElementById("check_perguntasecreta").checked) {
                        //alert("marcado");
                        $("tr[id=tr_pergunta]").show();
                        $("input[name=pergunta]").attr("required", true);
                        $("tr[id=tr_resposta]").show();
                        $("input[name=resposta]").attr("required", true);
                    } else {                
                        //alert("desmarcado");
                        $("input[name=pergunta]").attr("required", false);
                        $("tr[id=tr_pergunta]").hide();
                        $("input[name=resposta]").attr("required", false);
                        $("tr[id=tr_resposta]").hide();                
                    }
                }
                function emailpergunta () {
                    var senha= $("input[name=email_senha]").val();
                    //alert(senha);
                    //Verifica se o e-mail é válido! Se sim fazer
                    if (senha.length==0) {
                        //alert("Marcar");
                        document.getElementById("check_perguntasecreta").checked=true;
                        //alert("Desabilitar");
                        document.getElementById("check_perguntasecreta").disabled=true;
                        usarpergunta();
                    } else  {
                        //alert("Habilita");
                        document.getElementById("check_perguntasecreta").disabled=false;
                    }
            
                }
                function limpa_senha2 () {
                    $("input[name=senha2]").val("");
                }
                function verifica_senha () {            
                    var senha1 = $("input[name=senha]").val();
                    var senha2 = $("input[name=senha2]").val();
                    if (senha1 == senha2) {
                        //alert("Ta certo!");
                        //Verificar a força da senha
                    } else {
                        alert("As senhas não conferem! Digite novamente!");
                        $("input[name=senha]").val("");
                        $("input[name=senha2]").val("");
                        $("input[name=senha]").focus();
                    }
                }        
                function ValidaEmail(valor)  {
                    var obj = eval(valor);            
                    var txt = obj.value;
                    if ((txt.length != 0) && ((txt.indexOf("@") < 1) || (txt.indexOf('.') < 7)))
                    {
                        alert('Email incorreto');
                
                        obj.focus();
                    }
                }

            </script>
            </head>

            <body bgcolor="">        
                <div class="pagina" >
                    <?php
                    include ("templates/Template.class.php");
                    include "controle/conexao.php";
                    include "funcoes.php";
                    include "js/mascaras.php";
                    ?>
                    <div class="corpo">

                        <?php
                        $tpl = new Template("templates/tituloemlinha_2.html");
                        $tpl->block("BLOCK_TITULO");
                        $tpl->LISTA_TITULO = "DADOS PESSOAIS";
                        $tpl->block("BLOCK_QUEBRA2");
                        $tpl->show();


                        $tpl = new Template("templates/cadastro1.html");
                        $tpl->FORM_NOME = "form1";
                        $tpl->FORM_TARGET = "";
                        $tpl->FORM_LINK = "cadastre-se2.php";
                        $tpl->block("BLOCK_FORM");

                        //Texto descritivo Dados
                        $tpl->COLUNA_COLSPAN = "2";
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "";
                        $tpl->TEXTO_VALOR = "Ao cadastrar-se no sistema você fará a gestão de um ponto de venda (quiosque, tenda ...). Abaixo segue um formulário onde você é cadastrado com um supervisor (gestor) do seu ponto de venda. Após os cadastro basta logar (entrar) e começar a usar o sistema.";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");


                        //CPF
                        $tpl->COLUNA_COLSPAN = "0";
                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "210px";
                        $tpl->TITULO = "CPF";
                        $tpl->block("BLOCK_TITULO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "text";
                        $tpl->CAMPO_DICA = "";
                        $tpl->CAMPO_NOME = "cpf";
                        $tpl->CAMPO_ID = "cpf";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONKEYDOWN = "";
                        $tpl->CAMPO_ONBLUR = "valida_cpf(this.value); verifica_cpf_cadastro(this.value,1,'','1');";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "11";
                        $tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");
                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "dicacampo";
                        $tpl->TEXTO_VALOR = "";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");

                        //Nome
                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->TITULO = "Nome";
                        $tpl->block("BLOCK_TITULO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "text";
                        $tpl->CAMPO_DICA = "";
                        $tpl->CAMPO_NOME = "nome";
                        $tpl->CAMPO_ID = "nome";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "35";
                        $tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");
                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "dicacampo";
                        $tpl->TEXTO_VALOR = "";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");


                        //Data Nascimento
                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->TITULO = "Data Nascimento";
                        $tpl->block("BLOCK_TITULO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "text";
                        $tpl->CAMPO_DICA = "";
                        $tpl->CAMPO_NOME = "data";
                        $tpl->CAMPO_ID = "data_5";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "verifica_maioridade(this.value);";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "8";
                        $tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");

                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "dicacampo";
                        $tpl->TEXTO_VALOR = "";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");

                        //CEP
                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->TITULO = "CEP";
                        $tpl->block("BLOCK_TITULO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "text";
                        $tpl->CAMPO_DICA = "";
                        $tpl->CAMPO_NOME = "cep";
                        $tpl->CAMPO_ID = "cep";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "8";
                        //$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");

                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "dicacampo";
                        $tpl->TEXTO_VALOR = "";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");


                        //Pais Estado Cidade
                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->TITULO = "Cidade";
                        $tpl->block("BLOCK_TITULO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->SELECT_NOME = "pais";
                        $tpl->SELECT_ID = "pais";
                        $tpl->SELECT_TAMANHO = "";
                        $tpl->SELECT_AOTROCAR = "popula_estados()";
                        //$tpl->SELECT_AOCLICAR="";
                        //$tpl->block("BLOCK_SELECT_AUTOFOCO");
                        //$tpl->block("BLOCK_SELECT_DESABILITADO");
                        $tpl->block("BLOCK_SELECT_OBRIGATORIO");
                        $tpl->block("BLOCK_SELECT_PADRAO");
                        //$tpl->block("BLOCK_SELECT_DINAMICO");
                        $tpl->block("BLOCK_OPTION_PADRAO");
                        //$tpl->block("BLOCK_OPTION_TODOS");
                        $sql = "
                select pai_codigo,pai_nome
                from paises
                order by pai_nome
            ";
                        $query = mysql_query($sql);
                        if (!$query)
                            die("Erro SQL:" . mysql_error());
                        while ($dados = mysql_fetch_array($query)) {
                            $tpl->OPTION_VALOR = $dados[0];
                            $paisCodigo = $dados[0];
                            if ($paisCodigo == 1)
                                $tpl->block("BLOCK_OPTION_SELECIONADO");
                            $tpl->OPTION_TEXTO = $dados[1];
                            $tpl->block("BLOCK_OPTION");
                        }
                        $tpl->block("BLOCK_SELECT");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->SELECT_NOME = "estado";
                        $tpl->SELECT_ID = "estado";
                        $tpl->SELECT_TAMANHO = "";
                        $tpl->SELECT_AOTROCAR = "popula_cidades()";
                        //$tpl->SELECT_AOCLICAR = "";
                        //$tpl->block("BLOCK_SELECT_AUTOFOCO");
                        //$tpl->block("BLOCK_SELECT_DESABILITADO");
                        $tpl->block("BLOCK_SELECT_OBRIGATORIO");
                        $tpl->block("BLOCK_SELECT_PADRAO");
                        //$tpl->block("BLOCK_SELECT_DINAMICO");
                        $tpl->block("BLOCK_OPTION_PADRAO");
                        //$tpl->block("BLOCK_OPTION_TODOS");
                        $sql = "
                select est_codigo,est_sigla
                from estados
                join paises on (est_pais=pai_codigo)
                where est_pais=1
                order by est_sigla
            ";
                        $query = mysql_query($sql);
                        if (!$query)
                            die("Erro SQL:" . mysql_error());
                        while ($dados = mysql_fetch_array($query)) {
                            //$tpl->block("BLOCK_OPTION_SELECIONADO");
                            $tpl->OPTION_VALOR = $dados[0];
                            $tpl->OPTION_TEXTO = $dados[1];
                            $tpl->block("BLOCK_OPTION");
                        }
                        $tpl->block("BLOCK_SELECT");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->SELECT_NOME = "cidade";
                        $tpl->SELECT_ID = "cidade";
                        $tpl->SELECT_TAMANHO = "";
                        $tpl->SELECT_AOTROCAR = "";
                        //$tpl->SELECT_AOCLICAR="";
                        //$tpl->block("BLOCK_SELECT_AUTOFOCO");
                        //$tpl->block("BLOCK_SELECT_DESABILITADO");
                        $tpl->block("BLOCK_SELECT_OBRIGATORIO");
                        $tpl->block("BLOCK_SELECT_PADRAO");
                        //$tpl->block("BLOCK_SELECT_DINAMICO");
                        $tpl->block("BLOCK_OPTION_PADRAO");
                        //$tpl->block("BLOCK_OPTION_TODOS");
                        $tpl->block("BLOCK_SELECT");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");

                        //Bairro
                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->TITULO = "Bairro";
                        $tpl->block("BLOCK_TITULO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "text";
                        $tpl->CAMPO_DICA = "";
                        $tpl->CAMPO_NOME = "bairro";
                        $tpl->CAMPO_ID = "bairro";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "35";
                        $tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");

                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "dicacampo";
                        $tpl->TEXTO_VALOR = "";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");

                        //Endereço
                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->TITULO = "Endereço";
                        $tpl->block("BLOCK_TITULO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "text";
                        $tpl->CAMPO_DICA = "";
                        $tpl->CAMPO_NOME = "endereco";
                        $tpl->CAMPO_ID = "endereco";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "38";
                        $tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");

                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "text";
                        $tpl->CAMPO_DICA = "Nº";
                        $tpl->CAMPO_NOME = "numero";
                        $tpl->CAMPO_ID = "numero";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "10";
                        //$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");

                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "text";
                        $tpl->CAMPO_DICA = "Complemento";
                        $tpl->CAMPO_NOME = "complemento";
                        $tpl->CAMPO_ID = "complemento";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "20";
                        //$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");

                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "dicacampo";
                        $tpl->TEXTO_VALOR = "";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");

                        //Ponto de referencia
                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->TITULO = "Ponto de referencia";
                        $tpl->block("BLOCK_TITULO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "text";
                        $tpl->CAMPO_DICA = "";
                        $tpl->CAMPO_NOME = "pontoref";
                        $tpl->CAMPO_ID = "pontoref";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "40";
                        //$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");

                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "dicacampo";
                        $tpl->TEXTO_VALOR = "";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");


                        //Telefone 01
                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->TITULO = "Telefone 01";
                        $tpl->block("BLOCK_TITULO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "text";
                        $tpl->CAMPO_DICA = "";
                        $tpl->CAMPO_NOME = "telefone1";
                        $tpl->CAMPO_ID = "telefone1";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "12";
                        //$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");
                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "dicacampo";
                        $tpl->TEXTO_VALOR = "É o teu telefone. Não o do ponto de venda!";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");

                        //Telefone 02
                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->TITULO = "Telefone 02";
                        $tpl->block("BLOCK_TITULO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "text";
                        $tpl->CAMPO_DICA = "";
                        $tpl->CAMPO_NOME = "telefone2";
                        $tpl->CAMPO_ID = "telefone2";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "12";
                        //$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");
                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "dicacampo";
                        $tpl->TEXTO_VALOR = "É o teu telefone. Não o do ponto de venda!";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");

                        //E-mail
                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->TITULO = "E-mail";
                        $tpl->block("BLOCK_TITULO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "mail";
                        $tpl->CAMPO_DICA = "";
                        $tpl->CAMPO_NOME = "email";
                        $tpl->CAMPO_ID = "email";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "email1(this.value); ValidaEmail(document.forms[0].email);";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "25";
                        //$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");

                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "dicacampo";
                        $tpl->TEXTO_VALOR = "";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");

                        $tpl->block("BLOCK_BR2");
                        $tpl->show();




                        //DADOS DO PONTO DE VENDA
                        $tpl = new Template("templates/tituloemlinha_2.html");
                        $tpl->block("BLOCK_TITULO");
                        $tpl->LISTA_TITULO = "DADOS DO PONTO DE VENDA";
                        $tpl->block("BLOCK_QUEBRA2");
                        $tpl->show();

                        $tpl = new Template("templates/cadastro1.html");

                        //Nome Ponto de Venda
                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "210px";
                        $tpl->TITULO = "Nome do Ponto de Venda";
                        $tpl->block("BLOCK_TITULO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "text";
                        $tpl->CAMPO_DICA = "";
                        $tpl->CAMPO_NOME = "nomepv";
                        $tpl->CAMPO_ID = "nomepv";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "35";
                        $tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");
                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "dicacampo";
                        $tpl->TEXTO_VALOR = "";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");

                        //Telefone 01 Ponto de Venda
                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->TITULO = "Telefone 01";
                        $tpl->block("BLOCK_TITULO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "text";
                        $tpl->CAMPO_DICA = "";
                        $tpl->CAMPO_NOME = "telefone1pv";
                        $tpl->CAMPO_ID = "telefone1pv";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "12";
                        //$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");
                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->CAMPO_TIPO = "text";
                        $tpl->CAMPO_DICA = "Ramal";
                        $tpl->CAMPO_NOME = "telefone1pvramal";
                        $tpl->CAMPO_ID = "telefone1pvramal";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "12";
                        //$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");
                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "dicacampo";
                        $tpl->TEXTO_VALOR = "É importante colocar um telefone, pois é através dele que muitos interessados irão fazer contato";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");


                        //Telefone 02 Ponto de Venda
                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->TITULO = "Telefone 02";
                        $tpl->block("BLOCK_TITULO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "text";
                        $tpl->CAMPO_DICA = "";
                        $tpl->CAMPO_NOME = "telefone2pv";
                        $tpl->CAMPO_ID = "telefone2pv";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "12";
                        //$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");
                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->CAMPO_TIPO = "text";
                        $tpl->CAMPO_DICA = "Ramal";
                        $tpl->CAMPO_NOME = "telefone2pvramal";
                        $tpl->CAMPO_ID = "telefone2pvramal";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "12";
                        //$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");
                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "dicacampo";
                        $tpl->TEXTO_VALOR = "";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");

                        //E-mail Insitucional
                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->TITULO = "E-mail do Ponto de venda";
                        $tpl->block("BLOCK_TITULO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "mail";
                        $tpl->CAMPO_DICA = "";
                        $tpl->CAMPO_NOME = "emailpv";
                        $tpl->CAMPO_ID = "emailpv";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "ValidaEmail(document.forms[0].emailpv);";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "25";
                        //("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");
                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "dicacampo";
                        $tpl->TEXTO_VALOR = "Digite caso seu ponto de venda tenha um e-mail próprio";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");

                        //Possui mesmo endereço?
                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->TITULO = "Possui mesmo endereço?";
                        $tpl->block("BLOCK_TITULO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->SELECT_NOME = "mesmoendereco";
                        $tpl->SELECT_ID = "mesmoendereco";
                        $tpl->SELECT_TAMANHO = "";
                        $tpl->SELECT_AOTROCAR = "possui_mesmo_endereco(this.value)";
                        //$tpl->SELECT_AOCLICAR="";
                        //$tpl->block("BLOCK_SELECT_AUTOFOCO");
                        //$tpl->block("BLOCK_SELECT_DESABILITADO");
                        $tpl->block("BLOCK_SELECT_OBRIGATORIO");
                        $tpl->block("BLOCK_SELECT_PADRAO");
                        //$tpl->block("BLOCK_SELECT_DINAMICO");
                        $tpl->block("BLOCK_OPTION_PADRAO");
                        //$tpl->block("BLOCK_OPTION_TODOS");
                        $tpl->OPTION_VALOR = "0";
                        $tpl->OPTION_TEXTO = "Não";
                        $tpl->block("BLOCK_OPTION_SELECIONADO");
                        $tpl->block("BLOCK_OPTION");
                        $tpl->OPTION_VALOR = "1";
                        $tpl->OPTION_TEXTO = "Sim";
                        $tpl->block("BLOCK_OPTION");
                        $tpl->block("BLOCK_SELECT");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "dicacampo";
                        $tpl->TEXTO_VALOR = "Se o endereço do ponto de venda for o mesmo que os acima digitados então marque Sim";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");

                        //CEP
                        $tpl->TR_ID = "tr_cep";
                        $tpl->block("BLOCK_TR");
                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->TITULO = "CEP";
                        $tpl->block("BLOCK_TITULO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "text";
                        $tpl->CAMPO_DICA = "";
                        $tpl->CAMPO_NOME = "ceppv";
                        $tpl->CAMPO_ID = "cep2";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "8";
                        //$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");

                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "dicacampo";
                        $tpl->TEXTO_VALOR = "";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");


                        //Pais Estado Cidade
                        $tpl->TR_ID = "tr_cidade";
                        $tpl->block("BLOCK_TR");
                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->TITULO = "Cidade";
                        $tpl->block("BLOCK_TITULO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->SELECT_NOME = "paispv";
                        $tpl->SELECT_ID = "pais";
                        $tpl->SELECT_TAMANHO = "";
                        $tpl->SELECT_AOTROCAR = "popula_estadospv()";
                        //$tpl->SELECT_AOCLICAR="";
                        //$tpl->block("BLOCK_SELECT_AUTOFOCO");
                        //$tpl->block("BLOCK_SELECT_DESABILITADO");
                        $tpl->block("BLOCK_SELECT_OBRIGATORIO");
                        $tpl->block("BLOCK_SELECT_PADRAO");
                        //$tpl->block("BLOCK_SELECT_DINAMICO");
                        $tpl->block("BLOCK_OPTION_PADRAO");
                        //$tpl->block("BLOCK_OPTION_TODOS");
                        $sql = "
                select pai_codigo,pai_nome
                from paises
                order by pai_nome
            ";
                        $query = mysql_query($sql);
                        if (!$query)
                            die("Erro SQL:" . mysql_error());
                        while ($dados = mysql_fetch_array($query)) {
                            $tpl->OPTION_VALOR = $dados[0];
                            $paisCodigo = $dados[0];
                            if ($paisCodigo == 1)
                                $tpl->block("BLOCK_OPTION_SELECIONADO");
                            $tpl->OPTION_TEXTO = $dados[1];
                            $tpl->block("BLOCK_OPTION");
                        }
                        $tpl->block("BLOCK_SELECT");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->SELECT_NOME = "estadopv";
                        $tpl->SELECT_ID = "estado";
                        $tpl->SELECT_TAMANHO = "";
                        $tpl->SELECT_AOTROCAR = "popula_cidadespv()";
                        //$tpl->SELECT_AOCLICAR = "";
                        //$tpl->block("BLOCK_SELECT_AUTOFOCO");
                        //$tpl->block("BLOCK_SELECT_DESABILITADO");
                        $tpl->block("BLOCK_SELECT_OBRIGATORIO");
                        $tpl->block("BLOCK_SELECT_PADRAO");
                        //$tpl->block("BLOCK_SELECT_DINAMICO");
                        $tpl->block("BLOCK_OPTION_PADRAO");
                        //$tpl->block("BLOCK_OPTION_TODOS");
                        $sql = "
                select est_codigo,est_sigla
                from estados
                join paises on (est_pais=pai_codigo)
                where est_pais=1
                order by est_sigla
            ";
                        $query = mysql_query($sql);
                        if (!$query)
                            die("Erro SQL:" . mysql_error());
                        while ($dados = mysql_fetch_array($query)) {
                            //$tpl->block("BLOCK_OPTION_SELECIONADO");
                            $tpl->OPTION_VALOR = $dados[0];
                            $tpl->OPTION_TEXTO = $dados[1];
                            $tpl->block("BLOCK_OPTION");
                        }
                        $tpl->block("BLOCK_SELECT");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->SELECT_NOME = "cidadepv";
                        $tpl->SELECT_ID = "cidade";
                        $tpl->SELECT_TAMANHO = "";
                        $tpl->SELECT_AOTROCAR = "";
                        //$tpl->SELECT_AOCLICAR="";
                        //$tpl->block("BLOCK_SELECT_AUTOFOCO");
                        //$tpl->block("BLOCK_SELECT_DESABILITADO");
                        $tpl->block("BLOCK_SELECT_OBRIGATORIO");
                        $tpl->block("BLOCK_SELECT_PADRAO");
                        //$tpl->block("BLOCK_SELECT_DINAMICO");
                        $tpl->block("BLOCK_OPTION_PADRAO");
                        //$tpl->block("BLOCK_OPTION_TODOS");
                        $tpl->block("BLOCK_SELECT");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");

                        //Bairro
                        $tpl->TR_ID = "tr_bairro";
                        $tpl->block("BLOCK_TR");
                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->TITULO = "Bairro";
                        $tpl->block("BLOCK_TITULO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "text";
                        $tpl->CAMPO_DICA = "";
                        $tpl->CAMPO_NOME = "bairropv";
                        $tpl->CAMPO_ID = "bairropv";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "35";
                        $tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");

                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "dicacampo";
                        $tpl->TEXTO_VALOR = "";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");

                        //Endereço
                        $tpl->TR_ID = "tr_endereco";
                        $tpl->block("BLOCK_TR");
                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->TITULO = "Endereço";
                        $tpl->block("BLOCK_TITULO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "text";
                        $tpl->CAMPO_DICA = "";
                        $tpl->CAMPO_NOME = "enderecopv";
                        $tpl->CAMPO_ID = "enderecopv";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "38";
                        $tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");
                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "text";
                        $tpl->CAMPO_DICA = "Nº";
                        $tpl->CAMPO_NOME = "numeropv";
                        $tpl->CAMPO_ID = "numeropv";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "10";
                        $tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");
                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "text";
                        $tpl->CAMPO_DICA = "Complemento";
                        $tpl->CAMPO_NOME = "complementopv";
                        $tpl->CAMPO_ID = "complemento";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "20";
                        //$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");
                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "dicacampo";
                        $tpl->TEXTO_VALOR = "";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");

                        //Ponto de referencia
                        $tpl->TR_ID = "tr_pontoref";
                        $tpl->block("BLOCK_TR");
                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->TITULO = "Ponto de referencia";
                        $tpl->block("BLOCK_TITULO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "text";
                        $tpl->CAMPO_DICA = "";
                        $tpl->CAMPO_NOME = "pontorefpv";
                        $tpl->CAMPO_ID = "pontorefpv";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "40";
                        //$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");

                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "dicacampo";
                        $tpl->TEXTO_VALOR = "";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");

                        //Pertence a uma cooperativa
                        $tpl->TEXTO_VALOR = " ";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "";
                        $tpl->TEXTO_VALOR = "O Ponto de venda pertence a uma cooperativa/grupo/rede?";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");
                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->TITULO = "";
                        //$tpl->block("BLOCK_TITULO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->SELECT_NOME = "possuicooperativa";
                        $tpl->SELECT_ID = "possuicooperativa";
                        $tpl->SELECT_TAMANHO = "";
                        $tpl->SELECT_AOTROCAR = "cooperativa1(this.value)";
                        //$tpl->SELECT_AOCLICAR="";
                        //$tpl->block("BLOCK_SELECT_AUTOFOCO");
                        //$tpl->block("BLOCK_SELECT_DESABILITADO");
                        $tpl->block("BLOCK_SELECT_OBRIGATORIO");
                        $tpl->block("BLOCK_SELECT_PADRAO");
                        //$tpl->block("BLOCK_SELECT_DINAMICO");
                        //$tpl->block("BLOCK_OPTION_PADRAO");
                        //$tpl->block("BLOCK_OPTION_TODOS");
                        $tpl->OPTION_VALOR = "0";
                        $tpl->OPTION_TEXTO = "Não";
                        $tpl->block("BLOCK_OPTION_SELECIONADO");
                        $tpl->block("BLOCK_OPTION");
                        $tpl->OPTION_VALOR = "1";
                        $tpl->OPTION_TEXTO = "Sim";
                        $tpl->block("BLOCK_OPTION");
                        $tpl->block("BLOCK_SELECT");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->CAMPO_TIPO = "text";
                        $tpl->CAMPO_DICA = "Nome completo";
                        $tpl->CAMPO_NOME = "cooperativa";
                        $tpl->CAMPO_ID = "cooperativa";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "28";
                        $tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");
                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->CAMPO_TIPO = "text";
                        $tpl->CAMPO_DICA = "Abreviação/Sigla";
                        $tpl->CAMPO_NOME = "cooperativa_sigla";
                        $tpl->CAMPO_ID = "cooperativa_sigla";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "18";
                        $tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");
                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->TEXTO_NOME = "cooperativadica";
                        $tpl->TEXTO_ID = "cooperativadica";
                        $tpl->TEXTO_CLASSE = "dicacampo";
                        $tpl->TEXTO_VALOR = "Ao digitar, se a sua cooperativa/grupo/rede aparecer na lista, selecione!";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");
                        $tpl->block("BLOCK_BR2");
                        $tpl->show();


                        /*
                          //DADOS DE LOCALIZAÇÃO (POSIÇÃO NO MAPA)
                          $tpl = new Template("templates/tituloemlinha_2.html");
                          $tpl->block("BLOCK_TITULO");
                          $tpl->LISTA_TITULO = "DADOS DE LOCALIZAÇÃO (POSIÇÃO NO MAPA)";
                          $tpl->block("BLOCK_QUEBRA2");
                          $tpl->show();

                          $tpl = new Template("templates/cadastro1.html");

                          //Atenção! Leia ao lado
                          $tpl->COLUNA_ALINHAMENTO = "right";
                          $tpl->COLUNA_TAMANHO = "210px";
                          $tpl->ICONE_ARQUIVO = "../imagens/icones/geral/atencao.png";
                          $tpl->ICONE_TAMANHO = "40px";
                          //$tpl->block("BLOCK_ICONE_TAMANHOPADRAO");
                          $tpl->ICONE_DICA = "Por favor, leia ao lado!";
                          $tpl->ICONE_ALTERNATIVO = "Atenção";
                          //$tpl->ICONE_ID = "";
                          //$tpl->ICONE_AOCLICAR = "";
                          $tpl->block("BLOCK_ICONE");
                          $tpl->block("BLOCK_CONTEUDO");
                          $tpl->block("BLOCK_COLUNA");
                          $tpl->COLUNA_ALINHAMENTO = "left";
                          $tpl->COLUNA_TAMANHO = "";
                          $tpl->TEXTO_NOME = "";
                          $tpl->TEXTO_ID = "";
                          $tpl->TEXTO_CLASSE = "";
                          $tpl->TEXTO_VALOR = "
                          <b>É muito importante escolher no mapa a localização correta de sua localidade!</b><br>
                          Se um dia você tiver interesse em disponibilizar seu estoque no nosso site de Busca essa informação será de suma importância para podermos calcular a distância entre seu ponto de venda e o local dos interessados em seus produtos.
                          ";
                          $tpl->block("BLOCK_TEXTO");
                          $tpl->block("BLOCK_CONTEUDO");
                          $tpl->block("BLOCK_CONTEUDO");
                          $tpl->block("BLOCK_COLUNA");
                          $tpl->block("BLOCK_LINHA");

                          //Localizar pelo endereço (Texo e Botão)
                          $tpl->COLUNA_ALINHAMENTO = "right";
                          $tpl->COLUNA_TAMANHO = "";
                          $tpl->TITULO = "Localizar pelo endereço";
                          $tpl->block("BLOCK_TITULO");
                          $tpl->block("BLOCK_CONTEUDO");
                          $tpl->block("BLOCK_COLUNA");
                          $tpl->COLUNA_ALINHAMENTO = "left";
                          $tpl->COLUNA_TAMANHO = "";
                          $tpl->TEXTO_NOME = "";
                          $tpl->TEXTO_ID = "";
                          $tpl->TEXTO_CLASSE = "";
                          $tpl->TEXTO_VALOR = "
                          Clique no botão abaixo para solicitar que o sistema procura uma posição próxima de onde fica seu ponto de venda de acordo com os dados de endereço digitados acima!
                          ";
                          $tpl->block("BLOCK_TEXTO");
                          $tpl->block("BLOCK_BR");
                          $tpl->block("BLOCK_CONTEUDO");
                          $tpl->block("BLOCK_COLUNA");
                          $tpl->block("BLOCK_LINHA");

                          $tpl->block("BLOCK_CONTEUDO");
                          $tpl->block("BLOCK_COLUNA");
                          $tpl->BOTAO_TIPO = "button";
                          $tpl->BOTAO_VALOR = "Procurar";
                          $tpl->BOTAO_NOME = "localizar_mapa";
                          //$tpl->BOTAO_ID="";
                          $tpl->BOTAO_DICA = "";
                          $tpl->BOTAO_ONCLICK = "";
                          //$tpl->BOTAO_CLASSE="";
                          $tpl->block("BLOCK_BOTAO_PADRAO");
                          //$tpl->block("BLOCK_BOTAO_DINAMICO");
                          //$tpl->block("BLOCK_BOTAO_DESABILITADO");
                          //$tpl->block("BLOCK_BOTAO_AUTOFOCO");
                          $tpl->block("BLOCK_BOTAO");
                          $tpl->block("BLOCK_CONTEUDO");
                          $tpl->TEXTO_NOME = "";
                          $tpl->TEXTO_ID = "";
                          $tpl->TEXTO_CLASSE = "dicacampo";
                          $tpl->TEXTO_VALOR = "Tentar procurar no mapa um lugar próximo";
                          $tpl->block("BLOCK_TEXTO");
                          $tpl->block("BLOCK_CONTEUDO");
                          $tpl->block("BLOCK_COLUNA");
                          $tpl->block("BLOCK_LINHA");


                          //Mapa
                          $tpl->COLUNA_ALINHAMENTO = "right";
                          $tpl->COLUNA_TAMANHO = "";
                          $tpl->block("BLOCK_CONTEUDO");
                          $tpl->block("BLOCK_COLUNA");
                          $tpl->COLUNA_ALINHAMENTO = "";
                          $tpl->COLUNA_TAMANHO = "";
                          $tpl->TEXTO_NOME = "";
                          $tpl->TEXTO_ID = "";
                          $tpl->TEXTO_CLASSE = "";
                          $tpl->TEXTO_VALOR = "MAPA";
                          $tpl->block("BLOCK_TEXTO");
                          $tpl->block("BLOCK_CONTEUDO");
                          $tpl->block("BLOCK_COLUNA");
                          $tpl->block("BLOCK_LINHA");
                          $tpl->COLUNA_ALINHAMENTO = "right";
                          $tpl->COLUNA_TAMANHO = "";
                          $tpl->block("BLOCK_CONTEUDO");
                          $tpl->block("BLOCK_COLUNA");
                          $tpl->COLUNA_ALINHAMENTO = "";
                          $tpl->COLUNA_TAMANHO = "";
                          $tpl->TEXTO_NOME = "";
                          $tpl->TEXTO_ID = "";
                          $tpl->TEXTO_CLASSE = "";
                          $tpl->TEXTO_VALOR = "Arraste o ponto vermelho para o lugar mais próximo de onde é seu ponto de venda!";
                          $tpl->block("BLOCK_TEXTO");
                          $tpl->block("BLOCK_CONTEUDO");
                          $tpl->block("BLOCK_COLUNA");
                          $tpl->block("BLOCK_LINHA");
                          $tpl->block("BLOCK_BR2");
                          $tpl->show();
                         */

                        //DADOS DE ACESSO
                        $tpl = new Template("templates/tituloemlinha_2.html");
                        $tpl->block("BLOCK_TITULO");
                        $tpl->LISTA_TITULO = "DADOS DE ACESSO";
                        $tpl->block("BLOCK_QUEBRA2");
                        $tpl->show();

                        $tpl = new Template("templates/cadastro1.html");

                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "210px";
                        $tpl->TITULO = "Senha";
                        $tpl->block("BLOCK_TITULO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "password";
                        $tpl->CAMPO_DICA = "";
                        $tpl->CAMPO_NOME = "senha";
                        $tpl->CAMPO_ID = "senha";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "limpa_senha2()";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "15";
                        $tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");

                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "dicacampo";
                        $tpl->TEXTO_VALOR = "A senha deve ter de 6 á 20 dígito e conter apenas letras, número, _ ou -";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");

                        //Redigite a senha
                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "210px";
                        $tpl->TITULO = "Redigite a senha";
                        $tpl->block("BLOCK_TITULO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "password";
                        $tpl->CAMPO_DICA = "";
                        $tpl->CAMPO_NOME = "senha2";
                        $tpl->CAMPO_ID = "senha2";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "verifica_senha();";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "15";
                        $tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");

                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "dicacampo";
                        $tpl->TEXTO_VALOR = "Digite novamente a senha para garantir que não escreveu errado";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");

                        //E-mail para recuperação de senha
                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->TITULO = "E-mail para recuperação de senha";
                        $tpl->block("BLOCK_TITULO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "mail";
                        $tpl->CAMPO_DICA = "";
                        $tpl->CAMPO_NOME = "email_senha";
                        $tpl->CAMPO_ID = "email_senha";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "emailpergunta(); ValidaEmail(document.forms[0].email_senha);";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "30";
                        //$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");

                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "dicacampo";
                        $tpl->TEXTO_VALOR = "Caso um dia você esqueça sua senha, enviaremos orientações de recuperação para este e-mail";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");

                        //Usar pergunta e resposta secreta
                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "left";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "checkbox";
                        $tpl->CAMPO_DICA = "";
                        $tpl->CAMPO_NOME = "check_perguntasecreta";
                        $tpl->CAMPO_ID = "check_perguntasecreta";
                        $tpl->CAMPO_AOCLICAR = "usarpergunta()";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "";
                        //$tpl->block("BLOCK_CAMPO_MARCADO");
                        //$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        //$tpl->block("BLOCK_CAMPO_PADRAO");
                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "";
                        $tpl->TEXTO_VALOR = "Usar método de recuperação de senha através de pergunta e resposta secreta!";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");


                        //Pergunta Secreta
                        $tpl->TR_ID = "tr_pergunta";
                        $tpl->block("BLOCK_TR");
                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->TITULO = "Pergunta Secreta";
                        $tpl->block("BLOCK_TITULO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "text";
                        $tpl->CAMPO_DICA = "";
                        $tpl->CAMPO_NOME = "pergunta";
                        $tpl->CAMPO_ID = "pergunta";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "40";
                        $tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");
                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "dicacampo";
                        $tpl->TEXTO_VALOR = "Digite uma pergunta que só você saiba a resposta";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");

                        //Resposta Secreta
                        $tpl->TR_ID = "tr_resposta";
                        $tpl->block("BLOCK_TR");
                        $tpl->COLUNA_ALINHAMENTO = "right";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->TITULO = "Resposta Secreta";
                        $tpl->block("BLOCK_TITULO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->COLUNA_ALINHAMENTO = "";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "text";
                        $tpl->CAMPO_DICA = "";
                        $tpl->CAMPO_NOME = "resposta";
                        $tpl->CAMPO_ID = "resposta";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "40";
                        $tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        $tpl->block("BLOCK_CAMPO_PADRAO");

                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "dicacampo";
                        $tpl->TEXTO_VALOR = "Digite a resposta de sua pergunta secreta";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");
                        $tpl->block("BLOCK_BR2");
                        $tpl->show();


                        //TERMOS
                        $tpl = new Template("templates/tituloemlinha_2.html");
                        $tpl->block("BLOCK_TITULO");
                        $tpl->LISTA_TITULO = "TERMOS DE USO DO SISTEMA";
                        $tpl->block("BLOCK_QUEBRA2");
                        $tpl->show();

                        $tpl = new Template("templates/cadastro1.html");

                        $tpl->COLUNA_ALINHAMENTO = "left";
                        //$tpl->COLUNA_TAMANHO = "";            
                        $tpl->TEXTAREA_TAMANHO = "120";
                        $tpl->TEXTAREA_NOME = "termos";
                        $tpl->TEXTAREA_ID = "termos";
                        $tpl->TEXTAREA_QTDCARACTERES = "";
                        //$tpl->TEXTAREA_ONKEYUP="";
                        //$tpl->TEXTAREA_ONKEYDOWN="";
                        //$tpl->TEXTAREA_ONKEYPRESS="";
                        //$tpl->TEXTAREA_DICA="";
                        //$tpl->block("BLOCK_TEXTAREA_AUTOSELECIONAR");
                        //$tpl->TEXTAREA_AOCLICAR="";
                        //$tpl->block("BLOCK_TEXTAREA_DESABILITADO");
                        //$tpl->block("BLOCK_TEXTAREA_AUTOFOCO");
                        //$tpl->block("BLOCK_TEXTAREA_OBRIGATORIO");
                        //$tpl->TEXTAREA_CLASSE="";
                        $tpl->block("BLOCK_TEXTAREA_PADRAO");
                        //$tpl->block("BLOCK_TEXTAREA_DINAMICO");
                        $tpl->TEXTAREA_TEXTO = "Aqui vai escrito as normas e regras de funcionamento do projeto. Ao clicar no aceito o usuário concorda com essas regras de funcionamento.";
                        $tpl->block("BLOCK_TEXTAREA");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");

                        //Eu aceito os termos
                        $tpl->COLUNA_ALINHAMENTO = "left";
                        $tpl->COLUNA_TAMANHO = "";
                        $tpl->CAMPO_TIPO = "checkbox";
                        $tpl->CAMPO_DICA = "";
                        $tpl->CAMPO_NOME = "check_termo";
                        $tpl->CAMPO_ID = "check_termo";
                        $tpl->CAMPO_AOCLICAR = "";
                        $tpl->CAMPO_ONKEYUP = "";
                        $tpl->CAMPO_ONBLUR = "";
                        $tpl->CAMPO_VALOR = "";
                        $tpl->CAMPO_TAMANHO = "";
                        //$tpl->block("BLOCK_CAMPO_MARCADO");
                        $tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                        //$tpl->block("BLOCK_CAMPO_PADRAO");

                        $tpl->block("BLOCK_CAMPO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->TEXTO_NOME = "";
                        $tpl->TEXTO_ID = "";
                        $tpl->TEXTO_CLASSE = "";
                        $tpl->TEXTO_VALOR = "Eu aceitos os termos acima descritos";
                        $tpl->block("BLOCK_TEXTO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl->block("BLOCK_COLUNA");
                        $tpl->block("BLOCK_LINHA");


                        $tpl->show();



                        //BOTÕES DO FINAL DO FORMULÁRIO
                        $tpl2 = new Template("templates/botoes1.html");
                        $tpl2->block("BLOCK_LINHAHORIZONTAL_EMCIMA");
                        //Salvar
                        $tpl2->block("BLOCK_BOTAOPADRAO_SUBMIT");
                        $tpl2->block("BLOCK_BOTAOPADRAO_SALVAR");
                        $tpl2->block("BLOCK_BOTAOPADRAO");
                        $tpl->block("BLOCK_CONTEUDO");
                        $tpl2->block("BLOCK_COLUNA");
                        //Cancelar
                        $tpl2->COLUNA_LINK_ARQUIVO = "estoque_qtdideal.php";
                        $tpl2->block("BLOCK_COLUNA_LINK");
                        $tpl2->block("BLOCK_BOTAOPADRAO_SIMPLES");
                        $tpl2->block("BLOCK_BOTAOPADRAO_CANCELAR");
                        $tpl2->block("BLOCK_BOTAOPADRAO");
                        $tpl2->block("BLOCK_COLUNA");
                        $tpl2->block("BLOCK_LINHA");
                        $tpl2->block("BLOCK_BOTOES");
                        $tpl2->show();
                        ?>
                    </div>        
                    <div class="rodape"> </div>
                </div>
            </body>

