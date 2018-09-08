var localCount = 0;
function addField( table, count )
{
	if(localCount ==0)
		localCount = count;
	else
		++localCount;
	var tableRef = document.getElementById(table);
	var newRow   = tableRef.insertRow(-1);
	if(localCount % 2 != 0)
		newRow.setAttribute("class", "odd");

	var newCell  = newRow.insertCell(0);
	var newElem = document.createElement( 'input' );
	newElem.setAttribute("name", "number".concat(localCount));
	newElem.setAttribute("id", "number".concat(localCount));
	newElem.setAttribute("onChange", "numberRefresh();");
	newElem.setAttribute("type", "text");
	newCell.appendChild(newElem);
	
	newCell  = newRow.insertCell(1);
	newElem = document.createElement( 'input' );
	newElem.setAttribute("name", "itemCode".concat(localCount));
	newElem.setAttribute("id", "itemCode".concat(localCount));
	newElem.setAttribute("onChange", "itemRefresh();");
	newElem.setAttribute("type", "text");
	newCell.appendChild(newElem);
	
	newCell = newRow.insertCell(2);
	newElem = document.createElement( 'input' );
	newElem.setAttribute("name", "description".concat(localCount));
	newElem.setAttribute("id", "description".concat(localCount));
	newElem.setAttribute("onChange", "descriptionRefresh();");
	newElem.setAttribute("type", "text");
	newCell.appendChild(newElem);
	
	newCell = newRow.insertCell(3);
	newElem = document.createElement( 'input' );
	newElem.setAttribute("id", "totalQty".concat(localCount));
	newElem.setAttribute("type", "text");
	newCell.appendChild(newElem);	

	newCell = newRow.insertCell(4);
	newElem = document.createElement( 'input' );
	newElem.setAttribute("name", "qty".concat(localCount));
	newElem.setAttribute("id", "qty".concat(localCount));
	newElem.setAttribute("onChange", "qtyRefresh();");
	newElem.setAttribute("type", "text");
	newCell.appendChild(newElem);

	newCell = newRow.insertCell(5);
	newElem = document.createElement( 'input' );
	newElem.setAttribute("name", "price".concat(localCount));
	newElem.setAttribute("id", "price".concat(localCount));
	newElem.setAttribute("type", "text");
	newCell.appendChild(newElem);
	
	newCell = newRow.insertCell(6);
	newElem = document.createElement( 'input' );
	newElem.setAttribute("name", "total".concat(localCount));
	newElem.setAttribute("id", "total".concat(localCount));
	newElem.setAttribute("type", "text");
	newCell.appendChild(newElem);				
}			