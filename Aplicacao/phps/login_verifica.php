<?php

session_cache_expire(180);
session_start();
date_default_timezone_set('America/Sao_Paulo');
    
error_reporting(E_ERROR | E_PARSE);

include "controle/conexao.php";
//include "controle/conexao_tipo.php";
//Variaveis globais
$icones = "../imagens/icones/geral/";
$icones2 = "../imagens/icones/pessoas/";
$usuario_paginacao = 15;

// Verifica se existe os dados da sessão de login
$usuario_nome = $_SESSION["usuario_nome"];
$usuario_cpf = $_SESSION["usuario_cpf"];
$usuario_codigo = $_SESSION["usuario_codigo"];
$usuario_grupo = $_SESSION["usuario_grupo"];
$usuario_cooperativa = $_SESSION["usuario_cooperativa"];
$usuario_cooperativanomecompleto = $_SESSION["usuario_cooperativanomecompleto"];
$usuario_cooperativaabreviacao = $_SESSION["usuario_cooperativaabreviacao"];
$usuario_quiosque = $_SESSION["usuario_quiosque"];
$usuario_quiosquenome = $_SESSION["usuario_quiosquenome"];
$usuario_cidade = $_SESSION["usuario_cidade"];
$usuario_estado = $_SESSION["usuario_estado"];
$usuario_pais = $_SESSION["usuario_pais"];



if (!isset($_SESSION["usuario_cpf"]) 
|| !isset($_SESSION["usuario_nome"]) 
|| !isset($_SESSION["usuario_grupo"])
||(!isset($_SESSION["usuario_cooperativa"]))
) {
    //Sem sessão! Sair do sistema!";
    echo "Não é possível criar sessão!";
    header("Location: ../index.html");
    exit;
} else {
    //Sessão verificada e confirmada
    $sql = "SELECT * FROM grupo_permissoes WHERE gruper_codigo=$usuario_grupo";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro de sql 1:" . mysql_error());
    $dados = mysql_fetch_assoc($query);
    $permissao_nome = $dados["gruper_nome"];
    $permissao_cooperativa_ver = $dados["gruper_cooperativa_ver"];
    $permissao_cooperativa_cadastrar = $dados["gruper_cooperativa_cadastrar"];
    $permissao_cooperativa_editar = $dados["gruper_cooperativa_editar"];
    $permissao_cooperativa_excluir = $dados["gruper_cooperativa_excluir"];
    $permissao_quiosque_ver = $dados["gruper_quiosque_ver"];
    $permissao_quiosque_cadastrar = $dados["gruper_quiosque_cadastrar"];
    $permissao_quiosque_editar = $dados["gruper_quiosque_editar"];
    $permissao_quiosque_excluir = $dados["gruper_quiosque_excluir"];
    $permissao_quiosque_definirsupervisores = $dados["gruper_quiosque_definirsupervisores"];
    $permissao_quiosque_definirvendedores = $dados["gruper_quiosque_definirvendedores"];
    $permissao_quiosque_definircaixas = $dados["gruper_quiosque_definircaixas"];
    $permissao_quiosque_definircooperativa = $dados["gruper_quiosque_definircooperativa"];
    $permissao_pessoas_alterar_cooperativa = $dados["gruper_pessoas_alterar_cooperativa"];
    $permissao_pessoas_cadastrar = $dados["gruper_pessoas_cadastrar"];
    $permissao_pessoas_cadastrar_administradores = $dados["gruper_pessoas_cadastrar_administradores"];
    $permissao_pessoas_cadastrar_gestores = $dados["gruper_pessoas_cadastrar_gestores"];
    $permissao_pessoas_cadastrar_supervisores = $dados["gruper_pessoas_cadastrar_supervisores"];
    $permissao_pessoas_cadastrar_vendedores = $dados["gruper_pessoas_cadastrar_vendedores"];
    $permissao_pessoas_cadastrar_caixas = $dados["gruper_pessoas_cadastrar_caixas"];
    $permissao_pessoas_cadastrar_fornecedores = $dados["gruper_pessoas_cadastrar_fornecedores"];
    $permissao_pessoas_cadastrar_consumidores = $dados["gruper_pessoas_cadastrar_consumidores"];
    $permissao_pessoas_excluir = $dados["gruper_pessoas_excluir"];
    $permissao_pessoas_excluir_administradores = $dados["gruper_pessoas_excluir_administradores"];
    $permissao_pessoas_excluir_gestores = $dados["pessoas_gruper_excluir_gestores"];
    $permissao_pessoas_excluir_supervisores = $dados["gruper_pessoas_excluir_supervisores"];
    $permissao_pessoas_excluir_vendedores = $dados["gruper_pessoas_excluir_vendedores"];
    $permissao_pessoas_excluir_caixas = $dados["gruper_pessoas_excluir_caixas"];
    $permissao_pessoas_excluir_fornecedores = $dados["gruper_pessoas_excluir_fornecedores"];
    $permissao_pessoas_excluir_consumidores = $dados["gruper_pessoas_excluir_consumidores"];
    $permissao_pessoas_ver = $dados["gruper_pessoas_ver"];
    $permissao_pessoas_ver_gestores = $dados["gruper_pessoas_ver_gestores"];
    $permissao_pessoas_ver_supervisores = $dados["gruper_pessoas_ver_supervisores"];
    $permissao_pessoas_ver_vendedores = $dados["gruper_pessoas_ver_vendedores"];
    $permissao_pessoas_ver_caixas = $dados["gruper_pessoas_ver_caixas"];
    $permissao_pessoas_ver_fornecedores = $dados["gruper_pessoas_ver_fornecedores"];
    $permissao_pessoas_ver_consumidores = $dados["gruper_pessoas_ver_consumidores"];
    $permissao_pessoas_ver_administradores = $dados["gruper_pessoas_ver_administradores"];
    $permissao_pessoas_criarusuarios = $dados["gruper_pessoas_criarusuarios"];
    $permissao_pessoas_definir_grupo_administradores = $dados["gruper_pessoas_definir_grupo_administradores"];
    $permissao_pessoas_definir_grupo_gestores = $dados["gruper_pessoas_definir_grupo_gestores"];
    $permissao_pessoas_definir_grupo_supervisores = $dados["gruper_pessoas_definir_grupo_supervisores"];
    $permissao_pessoas_definir_grupo_vendedores = $dados["gruper_pessoas_definir_grupo_vendedores"];
    $permissao_pessoas_definir_grupo_caixas = $dados["gruper_pessoas_definir_grupo_caixas"];
    $permissao_pessoas_definir_grupo_fornecedores = $dados["gruper_pessoas_definir_grupo_fornecedores"];
    $permissao_pessoas_definir_grupo_consumidores = $dados["gruper_pessoas_definir_grupo_consumidores"];
    $permissao_pessoas_definir_quiosqueusuario = $dados["gruper_pessoas_definir_quiosqueusuario"];
    $permissao_produtos_ver = $dados["gruper_produtos_ver"];
    $permissao_produtos_cadastrar = $dados["gruper_produtos_cadastrar"];
    $permissao_produtos_editar = $dados["gruper_produtos_editar"];
    $permissao_produtos_excluir = $dados["gruper_produtos_excluir"];
    $permissao_paises_ver = $dados["gruper_paises_ver"];
    $permissao_paises_cadastrar = $dados["gruper_paises_cadastrar"];
    $permissao_paises_editar = $dados["gruper_paises_editar"];
    $permissao_paises_excluir = $dados["gruper_paises_excluir"];
    $permissao_estados_ver = $dados["gruper_estados_ver"];
    $permissao_estados_cadastrar = $dados["gruper_estados_cadastrar"];
    $permissao_estados_editar = $dados["gruper_estados_editar"];
    $permissao_estados_excluir = $dados["gruper_estados_excluir"];
    $permissao_cidades_ver = $dados["gruper_cidades_ver"];
    $permissao_cidades_cadastrar = $dados["gruper_cidades_cadastrar"];
    $permissao_cidades_editar = $dados["gruper_cidades_editar"];
    $permissao_cidades_excluir = $dados["gruper_cidades_excluir"];
    $permissao_categorias_ver = $dados["gruper_categorias_ver"];
    $permissao_categorias_cadastrar = $dados["gruper_categorias_cadastrar"];
    $permissao_categorias_editar = $dados["gruper_categorias_editar"];
    $permissao_categorias_excluir = $dados["gruper_categorias_excluir"];
    $permissao_tipocontagem_ver = $dados["gruper_tipocontagem_ver"];
    $permissao_tipocontagem_cadastrar = $dados["gruper_tipocontagem_cadastrar"];
    $permissao_tipocontagem_editar = $dados["gruper_tipocontagem_editar"];
    $permissao_tipocontagem_excluir = $dados["gruper_tipocontagem_excluir"];
    $permissao_estoque_ver = $dados["gruper_estoque_ver"];
    $permissao_estoque_qtdide_definir = $dados["gruper_estoque_qtdide_definir"];
    $permissao_entradas_ver = $dados["gruper_entradas_ver"];
    $permissao_entradas_cadastrar = $dados["gruper_entradas_cadastrar"];
    $permissao_entradas_editar = $dados["gruper_entradas_editar"];
    $permissao_entradas_excluir = $dados["gruper_entradas_excluir"];
    $permissao_entradas_etiquetas = $dados["gruper_entradas_etiquetas"];
    $permissao_entradas_cancelar = $dados["gruper_entradas_cancelar"];
    $permissao_saidas_ver = $dados["gruper_saidas_ver"];
    $permissao_saidas_cadastrar = $dados["gruper_saidas_cadastrar"];
    $permissao_saidas_excluir = $dados["gruper_saidas_excluir"];
    $permissao_saidas_editar = $dados["gruper_saidas_editar"];
    $permissao_saidas_ver_devolucao = $dados["gruper_saidas_ver_devolucao"];
    $permissao_saidas_cadastrar_devolucao = $dados["gruper_saidas_cadastrar_devolucao"];
    $permissao_saidas_editar_devolucao = $dados["gruper_saidas_editar_devolucao"];
    $permissao_saidas_excluir_devolucao = $dados["gruper_saidas_excluir_devolucao"];
    $permissao_relatorios_ver = $dados["gruper_relatorios_ver"];
    $permissao_relatorios_cadastrar = $dados["gruper_relatorios_cadastrar"];
    $permissao_relatorios_editar = $dados["gruper_relatorios_editar"];
    $permissao_relatorios_excluir = $dados["gruper_relatorios_excluir"];
    $permissao_acertos_cadastrar = $dados["gruper_acertos_cadastrar"];
    $permissao_acertos_editar = $dados["gruper_acertos_editar"];
    $permissao_acertos_ver = $dados["gruper_acertos_ver"];
    $permissao_acertos_excluir = $dados["gruper_acertos_excluir"];
    $permissao_taxas_cadastrar = $dados["gruper_taxas_cadastrar"];
    $permissao_taxas_editar = $dados["gruper_taxas_editar"];
    $permissao_taxas_ver = $dados["gruper_taxas_ver"];
    $permissao_taxas_excluir = $dados["gruper_taxas_excluir"];
    $permissao_taxas_aplicar = $dados["gruper_taxas_aplicar"];
    $permissao_quiosque_versupervisores = $dados["gruper_quiosque_versupervisores"];
    $permissao_quiosque_vervendedores = $dados["gruper_quiosque_vervendedores"];
    $permissao_quiosque_vercaixas = $dados["gruper_quiosque_vercaixas"];
    $permissao_quiosque_vertaxas = $dados["gruper_quiosque_vertaxas"];
    $permissao_cooperativa_gestores_ver = $dados["gruper_cooperativa_gestores_ver"];
    $permissao_cooperativa_gestores_gerir = $dados["gruper_cooperativa_gestores_gerir"];
    $permissao_caixas_ver = $dados["gruper_caixas_ver"];
    $permissao_caixas_cadastrar = $dados["gruper_caixas_cadastrar"];
    $permissao_caixas_editar = $dados["gruper_caixas_editar"];
    $permissao_caixas_excluir = $dados["gruper_caixas_excluir"];
    $permissao_caixas_operadores_gerir = $dados["gruper_caixas_operadores_gerir"];
    $permissao_caixas_operacoes_ver = $dados["gruper_caixas_operacoes_ver"];
    $permissao_caixas_operacoes_abrir = $dados["gruper_caixas_operacoes_abrir"];
    $permissao_caixas_operacoes_encerrar = $dados["gruper_caixas_operacoes_encerrar"];
    $permissao_caixas_trocar = $dados["gruper_caixas_trocar"];
    //$permissao_consumidores_grupos_ver = $dados["gruper_consumidores_grupos_ver"];
    $permissao_consumidores_grupos_ver = 1;
    $permissao_consumidores_grupos_editar = 1;
    $permissao_consumidores_grupos_excluir = 1;
    $permissao_consumidores_grupos_cadastrar = 1;

}

//Verifica os tipos de negociação do quiosque
$sql11 = "SELECT quitipneg_tipo FROM quiosques_tiponegociacao WHERE quitipneg_quiosque=$usuario_quiosque";
$query11 = mysql_query($sql11);
if (!$query11)
    die("Erro: " . mysql_error());
$quiosque_consignacao=0;
$quiosque_revenda=0;
while ($dados11 = mysql_fetch_array($query11)) {
    $tipon = $dados11[0];
    if ($tipon == 1)
        $quiosque_consignacao = 1;
    IF ($tipon == 2)
        $quiosque_revenda = 1;
}
?>
