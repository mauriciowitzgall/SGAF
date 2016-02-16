function valida_filtro_produto_codigo() {  
    if(document.form_filtro.filtro_codigoproduto.value != "") {        
        $("input[name=filtro_nomeproduto]").val("");
    }
}
function valida_filtro_produto_nome() {  
    if(document.form_filtro.filtro_nomeproduto.value != "") {        
        $("input[name=filtro_codigoproduto]").val("");
    } 
}
