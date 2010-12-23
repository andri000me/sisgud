<?php include 'layouts/header.php'; ?>    
<?php include 'layouts/menu.php'; ?>
<?php include 'layouts/content_top.php' ?>    
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Gudang >> Retur Barang</h1>
            <?php echo form_open('gudang/retur/tambah/') ?>
            <table class="table-form">
                <tr><td>Kode Toko</td><td>: <input type="text" name="shop_code" id="shop_code" class="text_field" readonly="readonly"/></td></tr>                
                <tr><td>Nama Toko</td><td>: <input class="ac_input text_field" type="text" id="shop_name"   value="" /></td></tr>                
                <tr><td>Tanggal Retur</td><td>: <input type="text" id="date_bon" name="tgl_retur" value="" readonly="readonly"/></td></tr>                
            </table>
            <p><?php
            if($this->session->userdata('form_notify'))
            {
                echo $this->session->userdata('form_notify');
                $this->session->unset_userdata('form_notify');
            }
            ?></p>
            <table class="table-data">
                <tr>
                    <td class="header">No</td>
                    <td class="header">Kode Label</td>
                    <td class="header">Nama Barang</td>
                    <td class="header">Supplier</td>
                    <td class="header">Harga (Rupiah)</td>
                    <td class="header">Qty Retur <input type="hidden" id="current_page" value="retur"/></td>
                </tr>
                <?php 
                    for($i=0;$i<15;) 
                    { 
                        echo '<tr>
                                <td>'.++$i.'</td>
                                <td>
                                    <input type="text" class="item_code_retur ac_input" name="item_code[]" maxlength="8" style="width:80px" onkeyup="setFocus('.$i.')"/>
                                    <span id="item_code_'.$i.'" style="display:none"></span>
                                </td>
                                <td><span id="item_name_'.$i.'" style="width:130px"></span></td>
                                <td><span id="sup_name_'.$i.'"></span></td>
                                <td><span id="item_hj_'.$i.'"></span></td>
                                <td><input type="text" name="qty_retur[]" size="5"/></td>
                            </tr>';
                    } 
                ?>                
            </table>
            <p>
                <span class="button"><input type="submit" name="submit_simpan_retur" value="Simpan" class="button"/></span>
                <span class="button"><input type="button" value="Lagi" class="button"/></span>
                <span class="button"><input type="reset" value="Batal" class="button"/></span>
            </p>
            <?php echo form_close() ?>
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