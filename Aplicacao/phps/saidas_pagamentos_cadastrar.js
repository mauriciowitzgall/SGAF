window.onload = function(){
	valortela=$("input[name=valor]").val();
	if (valortela=="") valortela="R$ 0,00";
	console.log("afasdfas");
	$("input[name=valor]").val(valortela).priceFormat({prefix: 'R$ ', centsSeparator: ',', thousandsSeparator: '.'});
}