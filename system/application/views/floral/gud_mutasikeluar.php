<?php include 'layouts/header.php'; ?>    
<?php include 'layouts/menu.php'; ?>
<?php include 'layouts/content_top.php' ?>       
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Gudang >> Mutasi Keluar</h1>
            <?php echo form_open('gudang/mutasi/'.$opsi) ?>            
            <p><?php
            if($this->session->userdata('form_notify'))
            {
                echo $this->session->userdata('form_notify');
                $this->session->unset_userdata('form_notify');
            }
            ?></p>
            <div class="table-container">
            <table class="table-data">
                <tr>
                    <td rowspan="2" class="header">No</td>
                    <td rowspan="2"class="header">Kode Barang</td>
                    <td rowspan="2"class="header">Nama Barang</td>
                    <td rowspan="2"class="header">Qty Awal</td>
                    <td colspan="<?php echo $shop_count ?>" class="header">Distribusi Toko</td>
                    <td rowspan="2"class="header">Qty Akhir</td>
                    <td rowspan="2"class="header">Harga Jual</td>
                    <td rowspan="2"class="header">Disc %</td>
                </tr>
                <tr>
                    <?php echo $shop_name ?>
                </tr>
                <?php 
                    for($i=0;$i<15;) 
                    { 
                        echo '<tr class="row-data">
                                <td>'.++$i.'</td>
                                <td>
                                    <input type="text" class="item_code ac_input" name="item_code[]" maxlength="10" style="width:80px" onkeyup="setFocus('.$i.')"/>
                                    <span id="item_code_'.$i.'" style="display:none"></span>
                                </td>
                                <td><input type="text" id="item_name_'.$i.'" style="width:130px" readonly="readonly"/></td>
                                <td><input type="text" id="qty_first_'.$i.'" style="width: 25px;" readonly="readonly"/></td>                                
                                '.str_replace('#',$i,$row_qty).'
                                <td><input type="text" name="qty_stok[]" id="qty_stok_'.$i.'" style="width: 25px;" readonly="yes"/></td>
                                <td><input type="hidden" id="item_hp_'.$i.'"/><input type="text" name="item_hj[]" id="hj_'.$i.'" style="width:80px" onblur="checkItemHj('.$i.')" onkeyup="setFocus('.$i.')"/></td>
                                <td><input type="text" name="item_disc[]" id="disc_'.$i.'" style="width:80px"/></td>
                            </tr>';
                    }
                ?>                
            </table>            
            </div>
            <p>
                <span class="button"><input type="submit" name="submit_mutasi_keluar" value="Simpan" class="button"/></span>
                <span class="button"><input type="button" value="Lagi" class="button"/></span>
                <span class="button"><input type="reset" value="Batal" class="button"/></span>
            </p>
            <?php echo form_close() ?>
            <script type="text/javascript"><!--// 
            function countStok(line) {
                var shop = <?php echo json_encode($shop_initial) ?>;
                var total = 0;
                for(i=0;i<shop.length;i++) {
                    selector = '#qty_'+shop[i]+'_'+line;
                    temp = $(selector).val();                    
                    if(temp == "") {
                        temp = 0;
                    }
                    else {
                        temp = parseInt(temp);
                    }
                    total += temp;
                }
                var qty_first = $('#qty_first_'+line).val();
                if(qty_first=="") {
                    qty_first = 0;
                    $('#warning_msg').html('Silahkan isi kode label terlebih dahulu');
                    $('#dialog_msg').dialog({
                        autoOpen: true,
                        modal: true,
                        buttons: {                        
                            OK : function() {
                                $(this).dialog('close');
                                var idx_row = 2+line;
                                $('.table-data tr:nth-child('+idx_row+') input:text').val('');                                
                                $('.table-data tr:nth-child('+idx_row+') td:nth-child(2) input').focus();                            
                            }
                        }
                    });
                    var idx_row = 2+line;
                    $('.table-data tr:nth-child('+idx_row+') input:text').val('');
                }
                else {
                    qty_first = parseInt(qty_first);
                
                    var qty_stok = qty_first - total;
                    if(qty_stok < 0) {                        
                        $('#warning_msg').html('Jumlah barang yang didistribusikan melebihi stok gudang. Ulangi lagi!');
                        $('#dialog_msg').dialog({
                            autoOpen: true,
                            modal: true,
                            buttons: {                        
                                OK : function() {
                                    $(this).dialog('close');
                                    var idx_row = 2+line;
                                    $('.table-data tr:nth-child('+idx_row+') input:text').val('');
                                    $('#item_code_'+line).html('');
                                    $('.table-data tr:nth-child('+idx_row+') td:nth-child(2) input:text').focus();
                                }
                            }
                        });                        
                    }
                    else {
                        $('#qty_stok_'+line).val(qty_stok);
                    }
                }
            }
            //--></script>
            <div id="dialog_msg" title="Peringatan" style="display:none">
            <span id="warning_msg"></span>
            </div>
      </div>                
        <div class="tempatemo_right_bottom">
        </div>
    </div><!-- End of Right Section -->
    
    <div class="cleaner"></div>

</div><!-- End Of Content area bottom -->
<?php include 'layouts/footer.php' ?>        