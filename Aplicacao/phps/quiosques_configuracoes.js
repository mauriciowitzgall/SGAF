function usa_modulo_fiscal (valor) {
    if (valor==1) {
        document.form1.crtnfe.required=true;
        document.form1.serienfe.required=true;
        document.form1.tipoimpressaodanfe.required=true;
        document.form1.ambientenfe.required=true;
        document.form1.versaonfe.required=true;
        $("tr[id=linha_crtnfe]").show(); 
        $("tr[id=linha_serienfe]").show(); 
        $("tr[id=linha_tipoimpressaodanfe]").show(); 
        $("tr[id=linha_ambientenfe]").show();
        $("tr[id=linha_ultimanfe]").show();
        $("tr[id=linha_versaonfe]").show();
    } else {
        document.form1.crtnfe.required=false;
        document.form1.serienfe.required=false;
        document.form1.tipoimpressaodanfe.required=false;
        document.form1.ambientenfe.required=false;
        document.form1.versaonfe.required=false;
        $("tr[id=linha_crtnfe]").hide(); 
        $("tr[id=linha_serienfe]").hide(); 
        $("tr[id=linha_tipoimpressaodanfe]").hide(); 
        $("tr[id=linha_ambientenfe]").hide(); 
        $("tr[id=linha_ultimanfe]").hide(); 
        $("tr[id=linha_versaonfe]").hide(); 
    }
  
}
