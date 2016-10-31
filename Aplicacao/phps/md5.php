<script type="text/javascript" src="js/jquery-1.3.2.js"></script>
<script type="text/javascript">
    function converte_md5() {
        valor1=$("input[name=codigo]").val();
        //alert(valor1);
        $.post("converte_md5.php",{
            valor:valor1
        },function(valor){
            $("div[id=cpf_md5]").html(valor);
            //alert(valor);
        });        
    }
</script>
<input type="text" name="codigo" id="codigo">

<button onclick="converte_md5()">Converter</button>
<br>
<div id="cpf_md5"> </div>
