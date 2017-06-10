var focusStatus;
$(document).ready(function(){    
    //tanggal mutasi masuk
    $('#date_bon').datepicker({dateFormat: 'yy-mm-dd'});
    $('.date').datepicker({dateFormat: 'yy-mm-dd'});
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
    $(".sup_name").autocomplete(
        baseUrl+"autocomplete/autocomplete_supplier",
        {
            delay:10,
            minChars:1,
            matchSubset:2,
            matchContains:1,
            cacheLength:10,
            onItemSelect:selectItem,
            onFindValue:findValue,
            formatItem:formatItem,
            autoFill:false
        }
    );
    $("#sup_code").autocomplete(
        baseUrl+"autocomplete/autocomplete_sup_add",
        {
            delay:10,
            minChars:1,
            matchSubset:2,
            matchContains:1,
            cacheLength:10,
            onItemSelect:selectItem,
            onFindValue:findValue,
            formatItem:formatItem,
            autoFill:false
        }
    );
    /**
    *autocomplete shop
    */
    $("#shop_name").autocomplete(
        baseUrl+"autocomplete/autocomplete_shop",
        {
            delay:10,
            minChars:2,
            matchSubset:2,
            matchContains:1,
            cacheLength:10,
            onItemSelect:selectItemShop,
            onFindValue:findValueShop,
            formatItem:formatItemShop,
            autoFill:false
        }
    );
    /**
    *autocomplete item
    */
    $(".item_code").autocomplete(
        baseUrl+"autocomplete/autocomplete_item",
        {
            delay:10,
            minChars:3,
            matchSubset:2,
            matchContains:1,
            cacheLength:10,
            onItemSelect:selectItemMutasi,
            onFindValue:findValueMutasi,
            formatItem:formatItemMutasi,
            autoFill:false
        }
    );
    /**
    *autocomplete hadiah
    */
    $(".item_hadiah").autocomplete(
        baseUrl+"autocomplete/autocomplete_hadiah",
        {
            delay:10,
            minChars:3,
            matchSubset:2,
            matchContains:1,
            cacheLength:10,
            onItemSelect:selectItemMutasi,
            onFindValue:findValueMutasi,
            formatItem:formatItemMutasi,
            autoFill:false
        }
    );
    /**
    *autocomplete item retur
    */
    $(".item_code_retur").autocomplete(
        baseUrl+"autocomplete/autocomplete_retur",
        {
            delay:10,
            minChars:3,
            matchSubset:2,
            matchContains:1,
            cacheLength:10,
            onItemSelect:selectItemMutasi,
            onFindValue:findValueMutasi,
            formatItem:formatItemMutasi,
            autoFill:false
        }
    );
    /**
    * Retur yang pake scanner
    */
    $('.item_code_retur').keypress(function(event){        
        if(event.keyCode == 13) {
            event.preventDefault();            
            var line = checkFocus();
            var idx_row = line+1;
            var input = $('.table-data tr:nth-child('+idx_row+') td:nth-child(2) input:text');
            var item_code = input.val();            
            $.post(
                baseUrl+"autocomplete/autocomplete_retur",
                {'item_code':item_code}, 
                function(data){
                    if(data == 0) {
                        $('#warning_msg').html('Barang tidak ditemukan atau belum pernah dikirim ke toko');
                        $('#dialog_msg').dialog({
                            autoOpen: true,
                            modal: true,
                            buttons: {                        
                                OK : function() {
                                    $(this).dialog('close');
                                    $('.table-data tr:nth-child('+idx_row+') td:nth-child(2) input:text').val('');
                                    $('.table-data tr:nth-child('+idx_row+') td:nth-child(2) input:text').focus();
                                }
                            }
                        });
                    }
                    else {
                        var res = item_exist(line,'retur');
                        if(res == 0) {
                            $('#item_code_'+line).html(data.item_code);
                            $('#item_name_'+line).html(data.item_name);
                            $('#sup_name_'+line).html(data.sup_name);
                            $('#item_hj_'+line).html(data.item_hj);
                            $('#qty_retur_'+line).focus();
                        }
                        else {
                            $('#warning_msg').html('Barang sudah pernah ditambahkan di baris ke-'+res);
                            $('#dialog_msg').dialog({
                                autoOpen: true,
                                modal: true,
                                buttons: {                        
                                    OK : function() {
                                        $(this).dialog('close');
                                        $('.table-data tr:nth-child('+idx_row+') td:nth-child(2) input:text').val('');
                                        $('#qty_retur_'+res).focus();
                                    }
                                }
                            });                            
                        }
                    }                    
                },
                "json"
            );
        }
    });
    /**
    * Mutasi rusak dan mutasi rusak pake scanner
    */
    $('.item_code').keypress(function(event){
        if(event.keyCode == 13) {            
            var line = checkFocus();
            var idx_row = line+2;
            var input = $('.table-data tr:nth-child('+idx_row+') td:nth-child(2) input:text');
            var item_code = input.val();
            $.post(
                baseUrl+"autocomplete/autocomplete_item",
                {'item_code':item_code},
                function(data){
                    if(data == 0) {
                        $('#warning_msg').html('Data tidak ditemukan');
                        $('#dialog_msg').dialog({
                            autoOpen: true,
                            modal: true,
                            buttons: {                        
                                OK : function() {
                                    $(this).dialog('close');
                                    $('.table-data tr:nth-child('+idx_row+') td:nth-child(2) input:text').val('');
                                    $('.table-data tr:nth-child('+idx_row+') td:nth-child(2) input:text').focus();
                                }
                            }
                        });
                    }
                    else {
                        $('#item_name_'+line).val(data.item_name);
                        $('#qty_first_'+line).val(data.item_qty_stock);
                        $('#hj_'+line).val(data.item_hj);
                        $('.table-data tr:nth-child('+idx_row+') td:nth-child(5) input:text').focus();
                    }
                },
                "json"
            );
        }
    });
    /**-----------------------------------------------------------
    *Check for enter enter
    */
    //textboxes = $(".table-data input.item_code");    
    
    //if ($.browser.mozilla) {
    //    $(textboxes).keypress(checkForEnter);
    //} else {
    //  $(textboxes).keydown(checkForEnter);
    //}
    //----------------------------------------------------------
    $('#submit_mutasi_masuk').click(function(event){
        if($('#date_bon').val() == "" && $('#sup_code').val()=="") {
            event.preventDefault();
            $('#warning_msg').html('Kode supplier dan tanggal bon tidak boleh kosong');
            $('#dialog_msg').dialog({
                autoOpen: true,
                modal: true,
                buttons: {                        
                    OK : function() {
                        $(this).dialog('close');                       
                    }
                }
            });       
        }
    });
    
    /**
     * Ubah status cetak label kembali ke nol, untuk pencetakan ulang
     */
    $('#submit_ubah_status').click(function(event){
    	if(!confirm('Apakah anda yakin untuk mencetak ulang ?'))
    		return false;
    	var checked = $('input.checkbox_status:checked');
    	var sup_code = new Array();
    	if(checked.length > 0) {
	    	for(var i=0;i<checked.length;i++)
	    		sup_code[i] = checked[i].value;
    	
	    	//do ajax updater
	    	var dist_out = $('#dist_out').val();
	    	var shop_code = $('[name=shop_code]').val();
	        $.post(
	            baseUrl+"gudang/label",
	            {'submit_ubah_status':1,'sup_code[]':sup_code,'dist_out':dist_out,'shop_code':shop_code}, 
	            function(data){                
	                window.location.reload();
	            }
	        );        
    	}
    	
    	else alert('Anda belum memilih supplier');
    	
    	
    });

    /*validate harga jual khusus*/
    $('.harga_marked').blur(function(event){
       var line = $(this).parent().parent().children(':first-child').html();
       var obj = this;

       var hp = $('#item_hp_'+line).val();
       if(hp.length > 0) {
           if(hp == '')
               hp = 0;
           else hp = parseInt(hp);

           var hjk = $(this).val();
           if(hjk == '')
               hjk = 0;
           else hjk = parseInt(hjk);

           var hj = $('#hj_'+line).val();
           if(hj == '')
               hj = 0;
           else hj = parseInt(hj);

           if(hjk > 0 && hjk > hp ) {
               if(hjk > hj)
                   $('#hj_'+line).val(hjk);
           }
           else {
               $('#warning_msg').html('Kesalahan data !! Harga jual harus lebih besar dari harga modal');
               $('#dialog_msg').dialog({
                   autoOpen: true,
                   modal: true,
                   buttons: {
                       OK : function() {
                           $(this).dialog('close');
                           $(obj).val('');
                           $(obj).focus();
                       }
                   }
               });
           }
       }

    });

});
//check enter untuk di text box yang di set method checknya
function check(event) {
    if(event.keyCode == 13) {
        event.preventDefault();
        var line = checkFocus();
        var idx_row = line+2;        
        $('.table-data tr:nth-child('+idx_row+') td:nth-child(2) input:text').focus();
    }
}
//ini fungsi untuk check apakah item yang diinput sudah ada di baris sebelumnya
function item_exist(line,page) {
    var idx_row = line+1;
    var item_code = $('.table-data tr:nth-child('+idx_row+') td:nth-child(2) input:text').val();
    if(page == 'retur') {
        var line_exist = $('.table-data tr:contains("'+item_code+'") td:first-child').html();            
        if(line_exist == null) {                
            return 0;
        }
        else {
            return line_exist;
        } 
    }
}
//fungsi untuk validasi form retur
function validateFormRetur() {
    var toko = $('#shop_code').val();
    var tgl = $('#date_bon').val();
    var kode = $('#retur_code').val();
    if(toko == '' && tgl == '' && kode == '') {
        event.preventDefault();
        $('#warning_msg').html('Kode Toko, Tanggal Retur dan Kode Retur tidak boleh dikosongkan');
        $('#dialog_msg').dialog({
            autoOpen: true,
            modal: true,
            buttons: {                        
                OK : function() {
                    $(this).dialog('close');                    
                }
            }
        }); 
    }    
}
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
        $("#sup_id").val(sValue);
        //$('#date_bon').focus();
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
/**
* Untuk autocomplete item pada saat mutasi keluar
*/
function findValueMutasi(li) {
    if( li == null ) {
        return alert("No match!");
    }
    // if coming from an AJAX call, let's use the CityId as the value
    if( !!li.extra ) {        
        var line = checkFocus();
        var page = $('#current_page').val()
        //autocomplete item pada fungsi mutasi keluar
        if(page == null) {            
            var line_exist = $('.table-data tr:contains("'+li.selectValue+'") td:first-child').html();
            if(line_exist == null) {
                //update value
                $('#item_code_'+line).html(li.selectValue);
                $('#item_name_'+line).val(li.extra[0]);
                $('#qty_first_'+line).val(li.extra[1]);
                $('#hj_'+line).val(li.extra[5]);            
                $('#item_hp_'+line).val(li.extra[4]);            
                var idx_row = 2 + line;
                $('.table-data tr:nth-child('+idx_row+') td:nth-child(5) input:first-child').focus();
            }
            else {
                var idx_row = 2 + parseInt(line_exist);
                var idx_clear = 2 + line;
                
                $('#warning_msg').html('Anda sudah menambahkan kode barang ini di baris ke-'+line_exist);
                $('#dialog_msg').dialog({
                    autoOpen: true,
                    modal: true,
                    buttons: {                        
                        OK : function() {
                            $(this).dialog('close');
                            $('.table-data tr:nth-child('+idx_clear+') input').val('');
                            $('.table-data tr:nth-child('+idx_row+') td:nth-child(5) input').focus();                            
                        }
                    }
                });   
            }            
        }
        //autocomplete item pada fungsi retur
        else if(page == 'retur') {
            var line_exist = $('.table-data tr:contains("'+li.selectValue+'") td:first-child').html();            
            if(line_exist == null) {                
                $('#item_code_'+line).html(li.selectValue);
                $('#item_name_'+line).html(li.extra[0]);
                $('#sup_name_'+line).html(li.extra[2]);
                $('#item_hj_'+line).html(li.extra[3]);
                var idx_row = 1 + line;                
                $('#qty_retur_'+line).focus();
            }
            else {                
                var idx_row = parseInt(line_exist);
                var idx_clear = 1 + line;
                $('#warning_msg').html('Anda sudah menambahkan kode barang ini di baris ke-'+line_exist);
                $('#dialog_msg').dialog({
                    autoOpen: true,
                    modal: true,
                    buttons: {                        
                        OK : function() {
                            $(this).dialog('close');
                            $('.table-data tr:nth-child('+idx_clear+') input').val('');
                            $('.table-data tr:nth-child('+idx_clear+') span').html('');
                            $('#qty_retur_'+idx_row).focus();                            
                        }
                    }
                });                
            }   
                
        }
    }    
}

function selectItemMutasi(li) {
    findValueMutasi(li);
}
function formatItemMutasi(row) {
    return row[0] ;
}
/**
*Untuk Autocomplete shop
*/
function findValueShop(li) {
    if( li == null ) {
        return alert("No match!");
    }
    // if coming from an AJAX call, let's use the CityId as the value
    if( !!li.extra ) {
        var sValue = li.extra[0];
        $("#shop_code").val(sValue);
        $('#date_bon').focus();
    }
    // otherwise, let's just display the value in the text box
    else {
        var sValue = li.selectValue;
    }
}

function selectItemShop(li) {
    findValueShop(li);
}

function formatItemShop(row) {
    return row[0];
}
/**
*set focus
*/
function setFocus(line) {
    focusStatus = new Array();
    focusStatus[line] = true;
    //alert(focusStatus);
}
/**
*Check kursor sedang fokus ke baris berapa
*/
function checkFocus() {    
    var num = focusStatus.length;
    var index = 0;
    for(i=1;i<=num;i++) {
        if(focusStatus[i] == true) {
            index = i;
        }
    }
    return index;
}

/**
*check apabila tekan enter
* page 0 -> mutasimaasuk
* page 1 -> mutasi keluar
*/
function checkForEnter(page,event,self) {
	if(page == 0) {
		textboxes = $(".table-data input"); 
		if (event.keyCode == 13) {
			currentTextboxNumber = textboxes.index(self);		
			if (textboxes[currentTextboxNumber + 1] != null) {
			   nextTextbox = textboxes[currentTextboxNumber + 1];
			  //nextTextbox.select();
			  nextTextbox.focus();
			}

			event.preventDefault();
			return false;
		}	    
	}
	else if(page == 1) {
	    textboxes = $('.table-data input[name ^= "qty"]');
	    if (event.keyCode == 13) {
		currentTextboxNumber = textboxes.index(self);		
		if (textboxes[currentTextboxNumber + 1] != null) {
		   nextTextbox = textboxes[currentTextboxNumber + 1];
		  //nextTextbox.select();
		  nextTextbox.focus();
		}

		event.preventDefault();
		return false;
	    }
	}

	else if(page == 2) {
        textboxes = $('.table-special input[type=text]');
        if (event.keyCode == 13) {
            currentTextboxNumber = textboxes.index(self);
            if (textboxes[currentTextboxNumber + 1] != null) {
                nextTextbox = textboxes[currentTextboxNumber + 1];
                //nextTextbox.select();
                nextTextbox.focus();
            }

            event.preventDefault();
            return false;
        }
    }
}
/**
* Destroy enter
*/
function destroyEnter(event) {
	if(event.keyCode == 13) {
		event.preventDefault();
		return false;
	}
}
/**
* Check harga jual tidak boleh kurang dari harga product (HM per item)
*/
function checkItemHj(line) {    
    var item_hj = $('#hj_'+line).val();
    if(item_hj == "") {
        item_hj = 0;
    }
    else {
        var numOfDigitHj = item_hj.length;
        item_hj = parseFloat(item_hj);        
    }
    var item_hp = $('#item_hp_'+line).val();
    if(item_hp == "") {
        item_hp = 0;
    }
    else {
        var numOfDigitHp = item_hp.length;
        item_hp = parseFloat(item_hp);       
    }
   
    //lakukan pengecekkan    
    if(item_hj >= 0 && item_hj < item_hp) {
        $('#warning_msg').html('Kesalahan data !! Harga jual harus lebih besar dari harga modal');
        $('#dialog_msg').dialog({
            autoOpen: true,
            modal: true,
            buttons: {                        
                OK : function() {
                    $(this).dialog('close');
                    $('#hj_'+line).val('');
                    $('#hj_'+line).focus();
                }
            }
        });
    }
}

/**
*cetak mutasi dari rekap
*/
function cetakMutasi(kode) {
    var url = baseUrl + 'gudang/mutasi/rekap/'+ kode;
    var param = '';
    window.open(url,'Cetak Mutasi',param);
    //window.location.replace('rekap/'+kode);
}
/**
*view retur data
*/
function viewRetur(kode) {
    window.location.replace(kode);
}
/**
* cetak label
*/
function cetakLabel(kode) {
    window.location.replace('label/'+kode);
}
/**
* appendSearch untu rekap mutasi keluar
*/
function appendSearch()
{
    var opsi = $('#type-search').val();
    if(opsi == '1') {
        $('.tgl_mutasi').css('display','table-row');
        $('.tgl_bon').css('display','none');
        $('.supplier').css('display','none');
    }
    else if(opsi == '2') {
        $('.tgl_mutasi').css('display','none');
        $('.tgl_bon').css('display','table-row');
        $('.supplier').css('display','none');
    }
    else if(opsi == '3') {
        $('.tgl_mutasi').css('display','none');
        $('.tgl_bon').css('display','none');
        $('.supplier').css('display','table-row');
    }
}
/**
* insert atau delete sisa mutasi
* checked = insert
* uncked = delete
*/
function addRemoveMutasi(line) {
    //alert(line);
    var check = $('.table-data tr:nth-child('+(line+1)+') td:last-child input').is(':checked');
    //ambil data
    var item_code = $('.table-data tr:nth-child('+(line+1)+') td:nth-child(2)').html();
    var sup_code = $('.table-data tr:nth-child('+(line+1)+') td:nth-child(5)').html();
    var qty = $('.table-data tr:nth-child('+(line+1)+') td:nth-child(7)').html();
    //if check is true, insert
    if(check == true) {        
        var opsi = 1;        
    }
    //delete from sisa
    else {
        var opsi = 2;
    }    
    //do ajax updater
    $.post(
        baseUrl+"gudang/sisa",
        {'opsi':opsi,'item_code':item_code,'sup_code':sup_code,'qty':qty}, 
        function(data){                
            if(data == 1) {
                //alert('success insert');
            }
            else if(data == 2) {
                //alert('sukses delete');
            }
            else if(data == 0) {
                //alert('gagal');
            }
        }
    );
}
/**
* Fungsi untuk hapus toko 
*/
function hapusToko(shop_code,line,shop_name)
{    
    $('#msg').html('<span>Yakin akan menghapus toko <b>'+shop_name+'</b> ?</span>');
    $('#dialog-msg').dialog({
        autoOpen: true,
        modal: true,
        buttons: {                        
            OK : function() {
                $(this).dialog('close');
                //do ajax updater
                $.post(
                    baseUrl+"toko/hapus",
                    {'shop_code': shop_code}, 
                    function(data){                
                        if(data == 1) {
                            $('#msg').html('<span>Toko telah dihapus</span>');
                            $('#dialog-msg').dialog({
                                autoOpen: true,
                                modal: true,
                                buttons: {                        
                                    OK : function() {
                                        $(this).dialog('close');
                                        $('.table-data tr:nth-child('+(line+1)+')').hide('slow');
                                    }
                                }
                            });
                        }            
                        else if(data == 0) {
                            $('#msg').html('<span>Gagal menghapus</span>');
                            $('#dialog-msg').dialog({
                                autoOpen: true,
                                modal: true,
                                buttons: {                        
                                    OK : function() {
                                        $(this).dialog('close');                           
                                    }
                                }
                            });
                        }
                    }
                );
            },
            Batal: function() {
                $(this).dialog('close');
                
            }
        }
    });    
}
/**
*tampilin pdf dalam iframe dialogue
*/
function cetakPDFIframe() {
    $('#dialog-iframe').dialog({
        autoOpen: true,
        modal: true,
        width: 800,
        height: 600,
        buttons: {                        
            OK : function() {
                $(this).dialog('close');                
            }
        }
    });
}
/**
* remove item, 
*/
function removeItem(item_code) {
    var url = baseUrl+'gudang/hapus/'+item_code;
    $('#dialog-confirm').html('<span>Yakin akan menghapus kode barang <b>'+item_code+'</b></span>');
    $('#dialog-confirm').dialog({
        autoOpen: true,
        modal: true,
        buttons: {                        
            Hapus : function() {
                window.location.replace(url);           
            },
            Batal : function() {
                $(this).dialog('close');   
            }
        }
    });    
}
/**
* Add or remove cetak label
* checked = ubah status di tabel item_distribution jadi 2
* unchecked = ubah status di table item_distribution jadi 0
*/
function addRemoveLabel(line) {
    var check = $('.table-data tr:nth-child('+(line+1)+') td:last-child input').is(':checked');
    var item_code = $('.table-data tr:nth-child('+(line+1)+') td:nth-child(2)').html();
    if(check==true) {
        var status = 2;
    }
    else {
        var status = 0;
    }
    //do ajax updater
    $.post(
        baseUrl+"gudang/acc_print_label",
        {'item_code':item_code,'status':status}, 
        function(data){                
            if(data == 1) {
                //alert('success ubah status ke '+status);
            }            
            else if(data == 0) {
                //alert('gagal');
            }
        }
    );
}

/**
 * Print mutasi khusus
 */
function print_mutasi_khusus() {
    var sup = $('#list_sup').val();
    window.location.replace(baseUrl+'gudang/print_mutasi_khusus/'+ sup);
}
