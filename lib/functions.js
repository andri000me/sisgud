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
    *Check enter
    */
    textboxes = $(".table-data input:text");

    if ($.browser.mozilla) {
        $(textboxes).keypress(checkForEnter);
    } else {
      $(textboxes).keydown(checkForEnter);
    }
    $('#submit_mutasi_masuk').click(function(event){
        if($('#date_bon').val() == "") {
            event.preventDefault();
            alert('Belum memasukkan tanggal');
            $('#date_bon').focus();
        }
    });
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
                $('.table-data tr:nth-child('+idx_row+') td:nth-child(5) input').focus();
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
                $('.table-data tr:nth-child('+idx_row+') td:nth-child(6) input').focus();
            }
            else {
                var idx_row = 1 + parseInt(line_exist);
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
                            $('.table-data tr:nth-child('+idx_row+') td:nth-child(6) input').focus();                            
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
*/
function checkForEnter(event) {
    if (event.keyCode == 13) {
        currentTextboxNumber = textboxes.index(this);

        if (textboxes[currentTextboxNumber + 1] != null) {
           nextTextbox = textboxes[currentTextboxNumber + 1];
           nextTextbox.select();
        }

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
