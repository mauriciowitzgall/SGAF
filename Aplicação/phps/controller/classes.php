<?php

class banco {
    
    private $host = 'localhost';
    private $user = 'root';
    private $pass = 'mautito';
    private $banco = 'cooesperanca';

    function conectar() {
        $con = mysql_connect($this->host, $this->user, $this->pass) or die($this->erro(mysql_error()));
        return $con;
    }

    function desconecta() {
        mysql_close();
    }

    function selecionarDB($banco) {
        $sel = mysql_select_db($banco) or die($this->erro(mysql_error()));
        if ($sel) {
            return true;
        } else {
            return false;
        }
    }

    function query($sql) {
        $sql = $this->nosql($sql);
        //echo "((($sql)))";
        $this->conectar();
        $this->acentos();
        $this->selecionarDB($this->banco);
        $qry = mysql_query($sql) or
                die(
                        $this->erro("
                <br><b>Erro de SQL</b> 
                <br> SQL: " . $sql . "
                <br>DESCRIÇÃO: " . mysql_error() . "<br>
            ")
        );
        $this->desconecta();
        return $qry;
    }

    function query_semconexao($sql) {
        $this->acentos();
        $this->selecionarDB($this->banco);
        $qry = mysql_query($sql) or
                die(
                        $this->erro("
                <br><b>Erro de SQL</b> 
                <br> SQL: " . $sql . "
                <br>DESCRIÇÃO: " . mysql_error() . "<br>
            ")
        );
        return $qry;
    }

    function ultimo_registro() {
        $this->acentos();
        $this->selecionarDB($this->banco);
        return mysql_insert_id();
    }

    function dados($sql) {
        $this->conectar();
        $this->acentos();
        $this->selecionarDB($this->banco);
        $dados = mysql_fetch_array($this->query($sql));
        $this->desconecta();
        return $dados;
    }

    function set($prop, $value) {
        $this->$prop = $value;
    }

    function erro($erro) {
        echo $erro;
    }

    function acentos() {
        mysql_query("SET NAMES 'utf8'");
        mysql_query('SET character_set_connection=utf8');
        mysql_query('SET character_set_client=utf8');
        mysql_query('SET character_set_results=utf8');
    }

    function nosql($string) {
        //$string = str_replace("'","",$string); //aqui retira aspas simples <’>
        $string = str_replace("\\", "", $string); //aqui retira barra invertida<\\>
        $string = str_replace("UNION", "", $string); //aqui retiro o comando UNION <UNION>
        //$string = mysql_real_escape_string($string);
        return $string;
    }

    function ehnumerico($valor) {
        if (is_numeric($valor) == false) { //É string
            //print 'ERRO_NAO_NUMERICO';
            echo "<meta HTTP-EQUIV='Refresh' CONTENT='0;URL=error.php'>";
            exit;
        } else {
            $cod = get_magic_quotes_gpc() ? stripslashes($valor) : $valor; // Se o Magic Quotes está desativado deve-se usar o stripslashes para colocar uma barra antes do apóstrofe
            $cod = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($cod) : mysql_escape_string($cod);
            $cod = (int) $cod;
            return $cod;
        }
    }

    function ehstring($valor) {
        if (is_numeric($valor) == true) { //É string
            //print 'ERRO_NAO_NUMERICO';
            echo "<meta HTTP-EQUIV='Refresh' CONTENT='0;URL=error.php'>";
            exit;
        } else {
            $cod = get_magic_quotes_gpc() ? stripslashes($valor) : $valor; // Se o Magic Quotes está desativado deve-se usar o stripslashes para colocar uma barra antes do apóstrofe
            $cod = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($cod) : mysql_escape_string($cod);
            $cod = (int) $cod;
            return $cod;
        }
    }
   

}

?>
