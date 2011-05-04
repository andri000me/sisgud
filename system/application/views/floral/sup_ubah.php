<?php include 'layouts/header.php'; ?>    
<?php include 'layouts/menu.php'; ?>
<?php include 'layouts/content_top.php' ?>    
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Supplier >> Ubah</h1>
            <?php if(isset($supplier)) { ?>
            <?php echo form_open('suppliers/ubah') ?>
            <table class="table-form">
                <tr><td><span class="note">Yang bertanda *) wajib diisi.</span></td></tr>
                <tr>
                    <td>Kode Supplier</td><td>: <input type="text" name="sup_code" value="<?php echo $supplier->sup_code?>" readonly="readonly"/> <span class="note">*) Harus 3 digit angka dan huruf</span></td>                                
                </tr>
                <tr>
                    <td>Nama Supplier</td><td>: <input type="text" name="sup_name" value="<?php echo $supplier->sup_name?>"/> <span class="note">*)</span></td>                                
                </tr>                
                <tr>
                    <td>Telepon / HP</td><td>: <input type="text" name="sup_phone" value="<?php echo $supplier->sup_phone?>"/> <span class="note">*) Harus angka</span></td>                                
                </tr> 
                <tr>
                    <td>Alamat </td><td>: <textarea name="sup_address" style="vertical-align:text-top"><?php echo $supplier->sup_address?></textarea> <span class="note">*)</span></td>                                
                </tr>
                <tr>
                    <td>Asal </td><td>: 
                    <select name="sup_type" style="width:155px;margin: 2px;">
                        <option value="1" <?php if($supplier->sup_type == 'medan') echo 'selected="selected"'?>>Medan</option>
                        <option value="2" <?php if($supplier->sup_type == 'luar medan') echo 'selected="selected"'?>>Luar Medan</option>
                    </select> <span class="note">*)</span></td>                                
                </tr> 
                <tr>
                    <td colspan="2">
                    <span class="button"><input class="button" type="submit" name="submit_ubah_supplier" value="Simpan"/></span>
                    <span class="button"><input class="button" type="reset" value="Batal"/></span>
                    </td>
                </tr>
            </table>            
            <?php echo form_close() ?>
            <?php } else { ?>
            <p><span style="color:red">Gunakan tombol ubah pada halaman cari supplier</span></p>
            <?php } ?>
            <p><?php if(isset($err_msg)) echo $err_msg ?></p>
      </div>                
        <div class="tempatemo_right_bottom">
        </div>
    </div><!-- End of Right Section -->
    
    <div class="cleaner"></div>

</div><!-- End Of Content area bottom -->
<?php include 'layouts/footer.php' ?>        