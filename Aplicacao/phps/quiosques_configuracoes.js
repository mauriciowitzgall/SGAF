function usa_modulo_fiscal (valor) {
    if (valor==1) {
        document.form1.crtnfe.required=true;
        document.form1.cstnfe.required=true;
        document.form1.csosnnfe.required=true;
        document.form1.serienfe.required=true;
        document.form1.tipoimpressaodanfe.required=true;
        document.form1.ambientenfe.required=true;
        document.form1.versaonfe.required=true;
        document.form1.csctoken.required=true;
        document.form1.csctokenid.required=true;
        document.form1.ie.required=true;
        document.form1.tipopessoanfe.required=true;
        document.form1.endereco.required=true;
        document.form1.endereco_numero.required=true;
        document.form1.bairro.required=true;
        document.form1.cep.required=true;
        //document.form1.certificadodigital.required=true;
        tipopessoa=$("select[name=tipopessoanfe]").val();
        if(tipopessoa==1) {
            document.form1.cpf.required=true;
            document.form1.cnpj.required=false;
            document.form1.razaosocial.required=false;
        } else if (tipopessoa==2) {
            document.form1.cpf.required=false;
            document.form1.cnpj.required=true;  
            document.form1.razaosocial.required=true;            
        } else {
            document.form1.tipopessoanfe.required=true;
        }
        $("tr[id=linha_crtnfe]").show(); 
        $("tr[id=linha_cstnfe]").show(); 
        $("tr[id=linha_csosnnfe]").show(); 
        $("tr[id=linha_faturamentoanualatual]").show(); 
        $("tr[id=linha_icmsatual]").show(); 
        $("tr[id=linha_csctoken]").show(); 
        $("tr[id=linha_csctokenid]").show(); 
        $("tr[id=linha_serienfe]").show(); 
        $("tr[id=linha_endereco]").show(); 
        $("tr[id=linha_bairro]").show(); 
        $("tr[id=linha_cep]").show(); 
        $("tr[id=linha_tipoimpressaodanfe]").show(); 
        $("tr[id=linha_ambientenfe]").show();
        $("tr[id=linha_ultimanfe]").show();
        $("tr[id=linha_versaonfe]").show();
        $("tr[id=linha_certificadodigital]").show();
        $("tr[id=linha_ie]").show();
        $("tr[id=linha_tipopessoanfe]").show(); 
        if (tipopessoa==1) {
            $("tr[id=linha_cnpj]").hide();
            $("tr[id=linha_razaosocial]").hide();
            $("tr[id=linha_im]").hide();
            $("tr[id=linha_cpf]").show();
        } else if (tipopessoa==2){
            $("tr[id=linha_cnpj]").show();
            $("tr[id=linha_razaosocial]").show();
            $("tr[id=linha_im]").show();
            $("tr[id=linha_cpf]").hide();
        }  else {
            $("tr[id=linha_cnpj]").hide();
            $("tr[id=linha_razaosocial]").hide();
            $("tr[id=linha_im]").hide();
            $("tr[id=linha_cpf]").hide();            
        }
    } else {
        document.form1.crtnfe.required=false;
        document.form1.cstnfe.required=false;
        document.form1.csosnnfe.required=false;
        document.form1.serienfe.required=false;
        document.form1.csctoken.required=false;
        document.form1.csctokenid.required=false;
        document.form1.certificadodigital.required=false;
        document.form1.tipoimpressaodanfe.required=false;
        document.form1.ambientenfe.required=false;
        document.form1.endereco.required=false;
        document.form1.endereco_numero.required=false;
        document.form1.bairro.required=false;
        document.form1.cep.required=false;
        document.form1.versaonfe.required=false;
        document.form1.tipopessoanfe.required=false;
        document.form1.cnpj.required=false;
        document.form1.cpf.required=false;
        document.form1.razaosocial.required=false;
        document.form1.ie.required=false;
        $("tr[id=linha_crtnfe]").hide(); 
        $("tr[id=linha_cstnfe]").hide(); 
        $("tr[id=linha_csosnnfe]").hide(); 
        $("tr[id=linha_faturamentoanualatual]").hide(); 
        $("tr[id=linha_icmsatual]").hide(); 
        $("tr[id=linha_serienfe]").hide(); 
        $("tr[id=linha_csctoken]").hide(); 
        $("tr[id=linha_csctokenid]").hide(); 
        $("tr[id=linha_tipoimpressaodanfe]").hide(); 
        $("tr[id=linha_ambientenfe]").hide(); 
        $("tr[id=linha_ultimanfe]").hide();
        $("tr[id=linha_endereco]").hide(); 
        $("tr[id=linha_certificadodigital]").hide(); 
        $("tr[id=linha_bairro]").hide(); 
        $("tr[id=linha_cep]").hide();         
        $("tr[id=linha_versaonfe]").hide(); 
        $("tr[id=linha_tipopessoanfe]").hide(); 
        $("tr[id=linha_cpf]").hide(); 
        $("tr[id=linha_cnpj]").hide(); 
        $("tr[id=linha_razaosocial]").hide(); 
        $("tr[id=linha_ie]").hide(); 
        $("tr[id=linha_im]").hide(); 
    }
  
}

function valida_ie (valor) {
    estado = $("input[name=quiosque_estado]").val();
    //alert("mascara im conforme estado: "+estado); 
    pesquisa_ie(valor,estado);
}

function tipo_pessoa(valor) {
    if (valor==1) {
        document.form1.cnpj.required=false;
        document.form1.razaosocial.required=false;
        document.form1.cpf.required=true;
        $("tr[id=linha_cnpj]").hide();
        $("tr[id=linha_razaosocial]").hide();
        $("tr[id=linha_im]").hide();
        $("tr[id=linha_cpf]").show();        
    } else if (valor==2) {
        document.form1.cnpj.required=true;
        document.form1.razaosocial.required=true;
        document.form1.cpf.required=false;
        $("tr[id=linha_cnpj]").show();
        $("tr[id=linha_razaosocial]").show();
        $("tr[id=linha_im]").show();
        $("tr[id=linha_cpf]").hide();         
    } else {
        $("tr[id=linha_cnpj]").hide();
        $("tr[id=linha_razaosocial]").hide();
        $("tr[id=linha_im]").hide();
        $("tr[id=linha_cpf]").hide();         
        $("tr[id=linha_endereco]").hide();         
        $("tr[id=linha_bairro]").hide();         
        $("tr[id=linha_cep]").hide();         
    }

}

function valida_crt(valor) {

    if ((valor!=1)&&(valor!="")) {
        alert("Esta versão do sistema aceita apenas empresas do SIMPLES NACIONAL. Para informações sobre outras versões, entre em contato pelo endereço de e-mail contato@titotec.com.br ");
        if (valor!="")select_selecionar("crtnfe","1");
    }

}

