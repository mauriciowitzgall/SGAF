O Emissor de Nota Fiscal Eletrônica está na fase de testes.

Para usar, inclua a classe EmissorNfe, arquivo emissorNfe.php, utilize da seguinte forma:

$configJson = json_encode($arr); // array de configuração 
$dadosNfeJson = json_encode($dadosNfe); // array dos dados da nota
$dadosNfeItensJson = json_encode($dadosNfeItens); // array dos itens
$emissor = new EmissorNFe($configJson, $dadosNfeJson, $dadosNfeItensJson); // construtor do emissor
$emissor->emiteNfe(); // invocação do método de geração da nota

Pode-se utilizar o arquivo example.php para ver o uso

