$(document).ready(function(){    
    //tanggal mutasi masuk
    $('#date_bon').datepicker({dateFormat: 'yy-mm-dd'});
    /**
    *autocomplete supplier
    */
    $("#sup_name").autocomplete(
        baseUrl+"autocomplete/autocomplete_supplier",
        {
            delay:10,
            minChars:2,
            matchSubset:2,
            matchContains:1,
            cacheLength:10,
            onItemSelect:selectItem,
            onFindValue:findValue,
            formatItem:formatItem,
            autoFill:false
        }
    );		
});
/**
*Autocomplete supplier
*/
function findValue(li) {
    if( li == null ) {
        return alert("No match!");
    }
    // if coming from an AJAX call, let's use the CityId as the value
    if( !!li.extra ) {
        var sValue = li.extra[0];
        $("#sup_code").val(sValue);
    }
    // otherwise, let's just display the value in the text box
    else {
        var sValue = li.selectValue;
    }
}
function selectItem(li) {
    findValue(li);
}

function formatItem(row) {
    return row[0] + " (id: " + row[1] + ")";
}

function lookupAjax(){
    var oSuggest = $("#sup_name")[0].autocompleter;

    oSuggest.findValue();

    return false;
}
//append row di mutasi masuk
function appendRow()
{
    var tbody = document.getElementById("search").getElementsByTagName("tbody")[0];
    var row = document.createElement("tr");
    var cell1 = document.createElement("td");
    var item_num = document.getElementsByName("item_num");    
    var new_num = item_num.length + 1;
    cell1.innerHTML = '<input type="text" name="item_num" size="3" value="'+ new_num +'" readonly="yes"/>';
    var cell2 = document.createElement("td");
    cell2.innerHTML = '<input type="text" name="item_code[]" size="12" maxlength="10"/>';
    var cell3 = document.createElement("td");
    cell3.innerHTML = '<input type="text" name="item_name[]" size="50"/>';
    var cell4 = document.createElement("td");
    cell4.innerHTML = '<input type="text" name="item_hm[]" size="20"/>';
    var cell5 = document.createElement("td");
    cell5.innerHTML = '<input type="text" name="item_qty[]" size="5"/>';
    row.appendChild(cell1);
    row.appendChild(cell2);
    row.appendChild(cell3);
    row.appendChild(cell4);
    row.appendChild(cell5);
    tbody.appendChild(row);
}
//append row di mutasi keluar
function appendRowDynamic(input_str,length)
{
    var tbody = document.getElementById("search").getElementsByTagName("tbody")[0];
    var row = document.createElement("tr");
    ///    
    var cell1 = document.createElement("td");
    cell1.innerHTML = '<input type="hidden" name="item_num"><input type="text" name="item_code[]" size="9" onKeyPress="checkEnter(event)"/>';
    row.appendChild(cell1);
    ///
    var cell2 = document.createElement("td");
    cell2.innerHTML = '<input type="text" name="item_name[]" size="18"/>';
    row.appendChild(cell2);
    ///
    var cell3 = document.createElement("td");
    cell3.innerHTML = '<input type="text" name="item_qty_first[]" size="3"/>';
    row.appendChild(cell3);
    ///
    var input = '';    
    for(var i=0;i<length;i++)
    {
        var cell = document.createElement("td");
        cell.innerHTML = '<input type="text" name="qty_'+ input_str[i] +'[]" size="1" />';
        row.appendChild(cell);        
    }
    ///
    var cell4 = document.createElement("td");
    cell4.innerHTML = '<input type="text" name="item_qty_stock" size="3"/>';
    row.appendChild(cell4);
    ///
    var cell5 = document.createElement("td");
    cell5.innerHTML = '<input type="text" name="item_hj[]" size="10"/>';
    row.appendChild(cell5);
    ///
    tbody.appendChild(row);
}
//cek biar ga tekan enter
function checkEnter(e)  //e is event object passed from function invocation var characterCode literal character code will be stored in this variable
{
    if(e && e.which) //if which property of event object is supported (NN4)
    { 
        characterCode = e.which; //character code is contained in NN4's which property
    }
    else if(window.event)     
	{
		e = window.event;
		characterCode = e.keyCode; //character code is contained in IE's keyCode property
    }

    if(characterCode == 13) //if generated character code is equal to ascii 13 (if enter key)
		return false;
    else
		return true;
}
//set value di mutasi masuk
function setValue(form)
{
    form.sup_code.value = form.sup_name.value;
}
//set value di mutasi keluarqty
function updateValue(num,item_code,qty_stock,item_hp)
{
    var item_name = document.getElementById(item_code);
    //alert (item_name);
    //ngupdate value
    document.getElementById('item_name_'+num).value = item_name.value;
    document.getElementById('item_qty_first_'+num).value = qty_stock;
    //document.getElementById('item_hp_'+num).innerHTML = item_hp +',00';
}
//clear field
function clearField(num)
{
    document.getElementById('item_name_'+num).value = '';
    document.getElementById('item_qty_first_'+num).value = '';
    //document.getElementById('item_hp_'+num).innerHTML = '';
}
//hitung sisa stock
function countStock(shop_initial,total,num)
{
    var sum = 0;
    for(var i=0;i<total;i++)
    {
        var id = shop_initial[i]+'_'+num;
        if(document.getElementById(id).value != '')
        {
            sum += parseInt(document.getElementById(id).value);
        }
    }
    document.getElementById('item_qty_stock_'+num).value = document.getElementById('item_qty_first_'+num).value - sum;
}
