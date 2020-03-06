function paginacao_retroceder() {
    var pagatu=$("input[name=paginaatual]").val();
    pagatu=parseInt(pagatu);
    var pagtot=$("input[name=paginas]").val();
    pagtot=parseInt(pagtot);
    if (pagatu>1) {
        var pag=pagatu-1;
        pag=parseInt(pag);
        $("input[name=paginaatual]").val(pag);
    } else {
        $("input[name=paginaatual]").val(pagatu);
    }
}
function paginacao_avancar() {
    var pagatu=$("input[name=paginaatual]").val();
    pagatu=parseInt(pagatu);
    var pagtot=$("input[name=paginas]").val();
    pagtot=parseInt(pagtot);
    if (pagatu<pagtot) {
        var pag=pagatu+1;
        pag=parseInt(pag);
        $("input[name=paginaatual]").val(pag);
    } else {
        $("input[name=paginaatual]").val(pagatu);
    }
}
//function paginacao_recomecar() {
//    $("input[name=paginaatual]").val('1');
//}


