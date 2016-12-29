<title>SGAF Esquecí minha senha</title>
<head>
    <html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br"></html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="classes.css" />
    <link rel="stylesheet" type="text/css" href="templates/geral.css">
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
            };
            function confere_senha() {
                var senha1 = $("input[name=senha1]").val();                    
                var senha2 = $("input[name=senha2]").val();                                    
                if (senha1!=senha2) {
                    alert("As senhas não conferem! Digite novamente para garantir que você não tenha digito algum caracter errado sem querer!");
                    $("input[name=senha1]").val("");                                    
                    $("input[name=senha2]").val(""); 
                    $("input[name=senha1]").focus();
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
            $resposta = $_GET["resposta"];
            $cpf = $_GET["cpf"];
            $emailmd5 = $_GET["par"];
            $passa = 0;
            
            if ($emailmd5 == "") { //Se não for recuperação oriunda de email
                //Verifica se a resposta bate com o cpf, no banco!
                $sql = "select pes_respostasecreta from pessoas where pes_cpf like '$cpf'";
                if (!$query = mysql_query($sql))
                    die("ERRO SQL" . mysql_error());
                $dados = mysql_fetch_array($query);
                $resposta_banco = $dados[0];
                $passa = 1;
            } else { //É recuperação por link de email
                //Verifica se a resposta bate com o cpf, no banco!
                $sql = "select pes_email_senha from pessoas where pes_cpf like '$cpf'";
                if (!$query = mysql_query($sql))
                    die("ERRO SQL" . mysql_error());
                $dados = mysql_fetch_array($query);
                $emailmd5_banco = $dados[0];
                $emailmd5_banco = md5($emailmd5_banco);
                if ($emailmd5==$emailmd5_banco)
                    $passa=1;
            }

            if ($passa==1) {

                $tpl = new Template("templates/tituloemlinha_2.html");
                $tpl->block("BLOCK_TITULO");
                $tpl->LISTA_TITULO = "NOVA SENHA";
                $tpl->block("BLOCK_QUEBRA2");
                $tpl->show();


                $tpl = new Template("templates/cadastro1.html");
                $tpl->FORM_NOME = "form1";
                $tpl->FORM_TARGET = "";
                $tpl->FORM_LINK = "novasenha2.php";
                $tpl->block("BLOCK_FORM");

                //Nova senha
                $tpl->COLUNA_COLSPAN = "0";
                $tpl->COLUNA_ALINHAMENTO = "right";
                $tpl->COLUNA_TAMANHO = "210px";
                $tpl->TITULO = "Nova Senha";
                $tpl->block("BLOCK_TITULO");
                $tpl->block("BLOCK_CONTEUDO");
                $tpl->block("BLOCK_COLUNA");
                $tpl->COLUNA_ALINHAMENTO = "";
                $tpl->COLUNA_TAMANHO = "";
                $tpl->CAMPO_TIPO = "password";
                $tpl->CAMPO_DICA = "";
                $tpl->CAMPO_NOME = "senha1";
                $tpl->CAMPO_ID = "senha1";
                $tpl->CAMPO_AOCLICAR = "";
                $tpl->CAMPO_ONKEYUP = "";
                $tpl->CAMPO_ONKEYDOWN = "";
                $tpl->CAMPO_ONBLUR = "";
                $tpl->CAMPO_VALOR = "";
                $tpl->CAMPO_TAMANHO = "15";
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

                //Nova senha 02
                $tpl->COLUNA_COLSPAN = "0";
                $tpl->COLUNA_ALINHAMENTO = "right";
                $tpl->COLUNA_TAMANHO = "210px";
                $tpl->TITULO = "Digite novamente a senha";
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
                $tpl->CAMPO_ONKEYDOWN = "";
                $tpl->CAMPO_ONBLUR = "confere_senha();";
                $tpl->CAMPO_VALOR = "";
                $tpl->CAMPO_TAMANHO = "15";
                $tpl->block("BLOCK_CAMPO_OBRIGATORIO");
                $tpl->block("BLOCK_CAMPO_PADRAO");
                $tpl->block("BLOCK_CAMPO");
                $tpl->block("BLOCK_CONTEUDO");
                $tpl->TEXTO_NOME = "";
                $tpl->TEXTO_ID = "";
                $tpl->TEXTO_CLASSE = "dicacampo";
                $tpl->TEXTO_VALOR = "Serve para conferir se não digitou algo errado acidentalmente";
                $tpl->block("BLOCK_TEXTO");
                $tpl->block("BLOCK_CONTEUDO");
                $tpl->block("BLOCK_COLUNA");
                $tpl->block("BLOCK_LINHA");

                //Cpf
                $tpl->CAMPOOCULTO_NOME = "c";
                $tpl->CAMPOOCULTO_VALOR = "$cpf";
                $tpl->block("BLOCK_CAMPOOCULTO");
                //Resposta
                $tpl->CAMPOOCULTO_NOME = "r";
                $tpl->CAMPOOCULTO_VALOR = "$resposta";
                $tpl->block("BLOCK_CAMPOOCULTO");
                //E-mail md5
                $tpl->CAMPOOCULTO_NOME = "e";
                $tpl->CAMPOOCULTO_VALOR = "$emailmd5";
                $tpl->block("BLOCK_CAMPOOCULTO");

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

                $tpl2->block("BLOCK_LINHA");
                $tpl2->block("BLOCK_BOTOES");
                $tpl2->show();
            } else {
                echo 'Por favor, não tente recuperar a senha através métodos não padrões!';
            }
            ?>
        </div>        

    </div>
</body>
