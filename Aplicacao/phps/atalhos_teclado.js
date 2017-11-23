//Voltar para a tela anterior
shortcut.add("Esc",function() 
{
	javascript:window.history.go(-1);
});

//Pessoas
shortcut.add("Ctrl+P", function() {
   window.location = "pessoas.php"
});

//Produtos
shortcut.add("Ctrl+R", function() {
   window.location = "produtos.php"
});

//Estoque
shortcut.add("Ctrl+S", function() {
   window.location = "estoque.php"
});

//Entradas
shortcut.add("Ctrl+E", function() {
   window.location = "entradas.php"
});

//Saidas
shortcut.add("Ctrl+V", function() {
   window.location = "saidas.php"
});

//Acertos
shortcut.add("Ctrl+A", function() {
   window.location = "acertos.php"
});



