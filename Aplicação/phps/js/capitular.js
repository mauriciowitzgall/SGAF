		
// Retorna um objeto pelo seu ID
function pID(id){
    return document.getElementById(id);
}
		
// Objetivo	::	Tornar as letras iniciais das palavras em maiúsculas exceto nos casos das palavras: de, do, da, dos ou das.
function capitalize(){
    var palavras="";
    var strPalavras = "";
			
    // Pega a string a ser capitalizada
    var sString = pID('capitalizar');
    var string = sString.value.toLowerCase();
			
    // Quebra a string em um vetor composto pelas palavras da frase
    var array = string.split(" ");

    // Laço para capitalizar cada palavra individualmente
    for(var i=0; i<array.length; i++){
        var word = array[i].toLowerCase();
				
        // Aqui basta digitar as palavras que não devem ser capitalizadas, exemplo: word == "dos" basta colocar como o modelo abaixo
        if(word == "de" || word == "do" || word == "da" || word == "dos"){
            strPalavras += " " + word;
        }
        else
        {					
            var inicial = array[i].charAt(0).toUpperCase();
            var restante = array[i].substring(1,array[i].length);
            strPalavras += " " + inicial + restante;
        }
    }
			
    // Verifica se a primeira letra da frase formada é um espaço, se for deleta-o da palavra
    for(var j=0; j<strPalavras.length;j++){
        var primeiroCaracter = strPalavras.charAt(0);
        if(primeiroCaracter == " "){
            strPalavras = strPalavras.substring(1, strPalavras.length);
        }
        else{
				
            // Se o primeiro cacter não for um espaço então apenas o copia para a variável
            strPalavras = strPalavras;
        }
    }
			
    // Altera o conteúdo do campo
    sString.value = strPalavras;
}
		
		//-->
		