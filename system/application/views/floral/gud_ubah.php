<?php include 'layouts/header.php'; ?>    
<?php include 'layouts/menu.php'; ?>
<?php include 'layouts/content_top.php' ?>    
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Gudang >> Ubah Data Barang</h1>
            <?php if(isset($item)) { ?>
            <?php echo form_open('gudang/ubah') ?>
            <table class="table-form" cellspacing="2" cellpadding="2">
                <tr><td colspan="2"><span class="note">Yang bertanda *) tidak boleh dikosongkan</span></td></tr>
                <tr><td><span class="note">Informasi Barang</span></td></tr>
                <tr>
                    <td>Kode Barang</td><td>: <input type="text" name="item_code" readonly="readonly" value="<?php echo $item->item_code ?>"/> <span class="note">*) </span></td>                                
                </tr>
                <tr>
                    <td>Nama Barang</td><td>: <input type="text" name="item_name" value="<?php echo $item->item_name ?>"/> <span class="note"> *) </span></td>                                
                </tr> 
                <tr>
                    <td>Kode Supplier</td><td>: <input type="text" name="sup_code" value="<?php echo $item->sup_code ?>" /> <span class="note"> *)</span></td>                                
                </tr>
                <tr>
                    <td>Harga Modal</td><td>: <input type="text" name="item_hp" value="<?php echo $item->item_hp ?>" <?php if($item->item_hj > 0) echo 'readonly="readonly"' ?>/> <span class="note"> *)</span></td>                                
                </tr>
                <?php if($this->session->userdata('p_role') == 'supervisor') { ?>
                <tr>
                    <td>Harga Jual </td><td>: <input type="text" name="item_hj" value="<?php echo $item->item_hj ?>" <?php if($item->item_hj == 0) echo 'readonly="readonly"' ?>/> <span class="note"> *) </span></td>                                
                </tr>                
                <?php } ?>
                <tr>
                    <td>Quantity </td><td>: <input type="text" name="quantity" value="<?php echo $item->item_qty_stock ?>" <?php if($item->item_hj > 0) echo 'readonly="readonly"' ?>/> <span class="note"> *) </span></td>                                
                </tr>
                <tr>
                    <td colspan="2">
                    <span class="button"><input class="button" type="submit" name="submit_ubah_barang" value="Simpan"/></span>
                    <span class="button"><input class="button" type="reset" value="Batal"/></span>
                    </td>
                </tr>                
            </table>            
            <?php echo form_close() ?>
            <p><?php if(isset($err_msg)) echo $err_msg ?></p>            
            <?php } else { ?>
            <p style="color:red">Gunakan tombol ubah pada halaman stok gudang</p>
            <?php } ?>
      </div>                
        <div class="tempatemo_right_bottom">
        </div>
    </div><!-- End of Right Section -->
    
    <div class="cleaner"></div>

</div><!-- End Of Content area bottom -->
<?php include 'layouts/footer.php' ?>        