<?php

//Quebra de pagina
$tpl->block(BLOCK_QUEBRA_EMCIMA);

//Linhas horizontal acima
$tpl->LINHAHORINZONTAL_ALINHAMENTO="";
$tpl->LINHAHORINZONTAL_CLASSE="";
$tpl->block(BLOCK_LINHAHORIZONTAL_EMCIMA);

//Formulário
$tpl->FORM_LINK="";
$tpl->FORM_TARGET="";
$tpl->FORM_METODO="";
$tpl->FORM_NOME ="";
$tpl->block(BLOCK_FORM); 

//Coluna Botão
$tpl->COLUNA_TAMANHO="";
$tpl->COLUNA_ALINHAMENTO  ="";                
$tpl->COLUNA_LINK_ARQUIVO="";
$tpl->block(BLOCK_COLUNA_LINK_VOLTAR); 
$tpl->block(BLOCK_COLUNA_LINK_FECHAR); 
$tpl->COLUNA_LINK_CLASSE="";
$tpl->COLUNA_LINK_TARGET="";
$tpl->block(BLOCK_COLUNA_LINK);  
$tpl->BOTAO_TECLA="";
$tpl->BOTAO_TIPO="";
$tpl->BOTAO_VALOR ="";
$tpl->BOTAO_NOME="";
$tpl->BOTAO_ID="";
$tpl->BOTAO_DICA="";
$tpl->BOTAO_ONCLICK="";
$tpl->BOTAOPADRAO_CLASSE="";
$tpl->block(BLOCK_BOTAO_PADRAO); 
$tpl->BOTAO_CLASSE="";
$tpl->block(BLOCK_BOTAO_DINAMICO);                     
$tpl->block(BLOCK_BOTAO_DESABILITADO);  
$tpl->block(BLOCK_BOTAO_AUTOFOCO);  
$tpl->block(BLOCK_BOTAO);         
$tpl->block(BLOCK_BOTAOPADRAO_SIMPLES);  
$tpl->block(BLOCK_BOTAOPADRAO_SUBMIT);  
$tpl->BOTAOPADRAO_TECLA    ="";         
$tpl->BOTAOPADRAO_CLASSE="";
$tpl->block(BLOCK_BOTAOPADRAO_IMPRIMIR);
$tpl->BOTAOPADRAO_CLASSE="";
$tpl->block(BLOCK_BOTAOPADRAO_SALVAR);
$tpl->BOTAOPADRAO_CLASSE="";
$tpl->block(BLOCK_BOTAOPADRAO_CONTINUAR);
$tpl->BOTAOPADRAO_CLASSE="";
$tpl->block(BLOCK_BOTAOPADRAO_CANCELAR);
$tpl->BOTAOPADRAO_CLASSE="";
$tpl->block(BLOCK_BOTAOPADRAO_VOLTAR);
$tpl->BOTAOPADRAO_CLASSE="";
$tpl->block(BLOCK_BOTAOPADRAO_FECHAR);
$tpl->BOTAOPADRAO_CLASSE="";
$tpl->block(BLOCK_BOTAOPADRAO_PESQUISAR);               
$tpl->BOTAOPADRAO_CLASSE="";
$tpl->block(BLOCK_BOTAOPADRAO_LIMPAR);               
$tpl->block(BLOCK_BOTAOPADRAO_CADASTRAR);               
$tpl->block(BLOCK_BOTAOPADRAO_GERAR);               
$tpl->block(BLOCK_BOTAOPADRAO_DESABILITADO);  
$tpl->block(BLOCK_BOTAOPADRAO_AUTOFOCO);  
$tpl->ONCLICK="";
$tpl->block(BLOCK_BOTAOPADRAO);  
$tpl->block(BLOCK_COLUNA);
$tpl->block(BLOCK_LINHA);
$tpl->block(BLOCK_BOTOES);
$tpl->block(BLOCK_FECHARFORM);
$tpl->LINHAHORINZONTAL_ALINHAMENTO="";
$tpl->LINHAHORINZONTAL_CLASSE="";
$tpl->block(BLOCK_LINHAHORIZONTAL_EMBAIXO);

?>