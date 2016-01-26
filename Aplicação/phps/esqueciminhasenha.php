<title>SGAF Esquecí minha senha</title>
<head>
    <html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br"></html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="classes.css" />
    <link rel="stylesheet" type="text/css" href="templates/geral.css">
        <script type="text/javascript" src="controle/google_analytics.js"></script>
        <script type="text/javascript" src="js/jquery-1.3.2.js"></script>
        <script src="js/jquery.maskedinput-1.1.4.pack.js" type="text/javascript"></script>
        <script src="mascaras.js" type="text/javascript"></script>    
        <script src="funcoes.js" type="text/javascript"></script>    
        <style>
            .aparece {display: block;}
            .some {display: none;}
        </style>        
        <script type="text/javascript">
            window.onload = function(){
               // $("tr[id=tr_email]").hide();             
               // $("tr[id=tr_pergunta]").hide();             
               // $("tr[id=tr_resposta]").hide();  
                //document.getElementById("tr_pergunta").className = "some";
            };
            function conta_cpf(cpf) {
                cpf = cpf.replace("-", "");
                cpf = cpf.replace(".", ""); //NAO excluir
                cpf = cpf.replace(".", ""); //tem que ser duas vezes
                cpf = cpf.replace("_", ""); 
                cpf = cpf.replace("_", ""); 
                cpf = cpf.replace("_", ""); 
                cpf = cpf.replace("_", ""); 
                cpf = cpf.replace("_", ""); 
                cpf = cpf.replace("_", ""); 
                cpf = cpf.replace("_", ""); 
                cpf = cpf.replace("_", ""); 
                cpf = cpf.replace("_", ""); 
                cpf = cpf.replace("_", ""); 
                cpf = cpf.replace("_", ""); 
                //alert(cpf);
                var cpf_cont=cpf.length;
                if (cpf_cont==11) {
                    valida_cpf(cpf);
                    verifica_cpf_cadastro(cpf,2);
                }
            }
        </script>
</head>
<body>        
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
            $tpl->LISTA_TITULO = "ESQUECÍ MINHA SENHA";
            $tpl->block("BLOCK_QUEBRA2");
            $tpl->show();


            $tpl = new Template("templates/cadastro1.html");
            $tpl->FORM_NOME = "form1";
            $tpl->FORM_TARGET = "";
            $tpl->FORM_LINK = "esqueciminhasenha2.php";
            $tpl->block("BLOCK_FORM");

            //Texto descritivo Dados
            $tpl->COLUNA_COLSPAN = "2";
            $tpl->TEXTO_NOME = "";
            $tpl->TEXTO_ID = "";
            $tpl->TEXTO_CLASSE = "";
            $tpl->TEXTO_VALOR = "O sistema oferece duas formas de recuperar a sua senha caso a tenha esquecido, por e-mail ou por pergunta e resposta secreta. Digite seu CPF e escolha uma das opções. Só aparecerá as opções de recuperação que o usuário escolheu quando cadastrou-se no sistema.";
            $tpl->block("BLOCK_TEXTO");
            $tpl->block("BLOCK_CONTEUDO");
            $tpl->block("BLOCK_TR_OCULTA");
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
            $tpl->CAMPO_ONKEYUP = "conta_cpf(this.value)";
            $tpl->CAMPO_ONKEYDOWN = "";
            $tpl->CAMPO_ONBLUR = "valida_cpf(this.value); verifica_cpf_cadastro(this.value,2,'',1);";
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

            //Método
            $tpl->COLUNA_ALINHAMENTO = "right";
            $tpl->COLUNA_TAMANHO = "";
            $tpl->TITULO = "Método";
            $tpl->block("BLOCK_TITULO");
            $tpl->block("BLOCK_CONTEUDO");
            $tpl->block("BLOCK_COLUNA");
            $tpl->COLUNA_ALINHAMENTO = "";
            $tpl->COLUNA_TAMANHO = "";
            $tpl->SELECT_NOME = "metodo";
            $tpl->SELECT_ID = "metodo";
            $tpl->SELECT_TAMANHO = "";
            $tpl->SELECT_AOTROCAR = "verifica_metodo(this.value);";
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

            //Pergunta Secreta
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
            $tpl->CAMPO_TAMANHO = "45";
            //$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
            $tpl->block("BLOCK_CAMPO_DESABILITADO");
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
            $tpl->TR_ID = "tr_pergunta";
            $tpl->block("BLOCK_TR_OCULTA");                   
            $tpl->block("BLOCK_TR");
            $tpl->block("BLOCK_LINHA");

            //Resposta Secreta
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
            $tpl->CAMPO_TAMANHO = "45";
            $tpl->block("BLOCK_CAMPO_OBRIGATORIO");
            //$tpl->block("BLOCK_CAMPO_DESABILITADO");
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
            $tpl->TR_ID = "tr_resposta";
            $tpl->block("BLOCK_TR_OCULTA");                               
            $tpl->block("BLOCK_TR");
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
            $tpl->CAMPO_ONBLUR = "";
            $tpl->CAMPO_VALOR = "";
            $tpl->CAMPO_TAMANHO = "35";
            $tpl->block("BLOCK_CAMPO_OBRIGATORIO");
            //$tpl->block("BLOCK_CAMPO_DESABILITADO");
            $tpl->block("BLOCK_CAMPO_PADRAO");
            $tpl->block("BLOCK_CAMPO");
            $tpl->block("BLOCK_CONTEUDO");
            $tpl->TEXTO_NOME = "";
            $tpl->TEXTO_ID = "";
            $tpl->TEXTO_CLASSE = "dicacampo";
            $tpl->TEXTO_VALOR = "E-mail cadastrado para recuperação de senha";
            $tpl->block("BLOCK_TEXTO");
            $tpl->block("BLOCK_CONTEUDO");
            $tpl->block("BLOCK_COLUNA");
            $tpl->TR_ID = "tr_email";
            $tpl->block("BLOCK_TR_OCULTA");                               
            $tpl->block("BLOCK_TR");
            $tpl->block("BLOCK_LINHA");

            $tpl->show();

            //BOTÕES DO FINAL DO FORMULÁRIO
            $tpl2 = new Template("templates/botoes1.html");
            $tpl2->block("BLOCK_LINHAHORIZONTAL_EMCIMA");
            //Salvar
            $tpl2->block("BLOCK_BOTAOPADRAO_SUBMIT");
            $tpl2->block("BLOCK_BOTAOPADRAO_CONTINUAR");
            $tpl2->block("BLOCK_BOTAOPADRAO");
            $tpl->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");
            //Cancelar
            $tpl2->COLUNA_LINK_ARQUIVO = "../index.html";
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

    </div>
</body>
