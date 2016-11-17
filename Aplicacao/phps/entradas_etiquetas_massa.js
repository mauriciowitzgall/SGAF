function etiqueta_escolhida(tipoetiqueta,lote) {
    //alert ("trocou de etiqueta: "+tipoetiqueta);
    if (tipoetiqueta==1) {
        document.form2.action = "entradas_etiquetinha.php"+"?lote="+lote+"&massa=1";
    } else if (tipoetiqueta==2) {
        document.form2.action = "entradas_etiquetao.php"+"?lote="+lote+"&massa=1";
    } else if (tipoetiqueta==3) {
        document.form2.action = "entradas_etiquetagranel.php"+"?lote="+lote+"&massa=1";
    } else if (tipoetiqueta==4) {
        document.form2.action = "entradas_etiqueta_compacta.php"+"?lote="+lote+"&massa=1";
    } else {
        alert("Ocorreu um erro! Nada grave, porém, se persistir, favor contatar equipe de suporte!");
    }
  
}


