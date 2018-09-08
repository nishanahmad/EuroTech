function numberRefresh()
{
	var count = event.target.id.match(/\d+/)[0]; 				
	var numberId  = '#'.concat(event.target.id);
	var number = $(numberId).val();

	var itemId = '#itemCode'.concat(count);
	var itemCode = itemCodeArray[number];
	$(itemId).val(itemCode);
	
	var priceId = '#price'.concat(count);
	var price = priceArray[number];
	$(priceId).val(price);
	
	var descriptionId = '#description'.concat(count);
	var description = encodeURIComponent(descriptionArray[number]);				
	$(descriptionId).val(decodeURIComponent(description));
	
	var qty = $('#qty'.concat(count)).val();
	var totalId = '#total'.concat(count);	
	$(totalId).val( (qty* price).toFixed(2));	
}								

function itemRefresh()
{
	var count = event.target.id.match(/\d+/)[0]; 				
	var itemId  = '#'.concat(event.target.id);
	var itemCode = $(itemId).val();

	var priceId = '#price'.concat(count);
	var price = priceArray[itemCode];
	$(priceId).val(price);
	
	var numberId = '#number'.concat(count);
	var number = numberArray[itemCode];
	$(numberId).val(number);				
	
	var descriptionId = '#description'.concat(count);
	var description = encodeURIComponent(descriptionArray[itemCode]);				
	$(descriptionId).val(decodeURIComponent(description));
	
	var qty = $('#qty'.concat(count)).val();
	var totalId = '#total'.concat(count);	
	$(totalId).val( (qty* price).toFixed(2));	
}		

function descriptionRefresh()
{
	var count = event.target.id.match(/\d+/)[0]; 				
	var descriptionId  = '#'.concat(event.target.id);
	var description = $(descriptionId).val();

	var numberId = '#number'.concat(count);
	var number = numberArray[description];
	$(numberId).val(number);				
	
	var itemId = '#itemCode'.concat(count);
	var itemCode = itemCodeArray[description];
	$(itemId).val(itemCode);
	
	var priceId = '#price'.concat(count);
	var price = priceArray[description];
	$(priceId).val(price);
	
	var qty = $('#qty'.concat(count)).val();
	var totalId = '#total'.concat(count);	
	$(totalId).val( (qty* price).toFixed(2));
	
}								

function qtyRefresh()
{
	var count = event.target.id.match(/\d+/)[0]; 				
	var qtyId  = '#'.concat(event.target.id);
	var qty = $(qtyId).val();
	
	var priceId = '#price'.concat(count);
	var price = $(priceId).val();
	
	var totalId = '#total'.concat(count);
	$(totalId).val( (qty* price).toFixed(2));
}					

function discountRefresh()
{
	var discount = $("#discount").val();
	var total = $("#total").val();
	var discountedTotal = (total - (total * discount/100)).toFixed(2);
	$("#discountedTotal").val(discountedTotal);
	
	var discountAmount = (total * discount/100).toFixed(2);
	$("#discountAmount").val(discountAmount);
	
}							

function updateTotal()
{
	var total = 0;
	for(var i=1;i<50;i++)
	{
		if($('#total'.concat(i)).val())
			total = total + parseFloat($('#total'.concat(i)).val());	
	}	
	$("#total").val(total.toFixed(2));
}								