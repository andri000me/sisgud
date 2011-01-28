<?php include 'layouts/header.php'; ?>    
<?php include 'layouts/menu.php'; ?>
<?php include 'layouts/content_top.php' ?>    
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Supplier >> Tambah</h1>
            <?php echo form_open('suppliers/tambah') ?>
            <table class="table-form">
                <tr><td><span class="note">Yang bertanda *) wajib diisi.</span></td></tr>
                <tr>
                    <td>Kode Supplier</td><td>: <input type="text" class="ac_input text_field" name="sup_code" maxlength="4" id="sup_code" autocomplete="off"/> <span class="note">*) Harus 4 digit angka dan huruf</span></td>                                
                </tr>
                <tr>
                    <td>Nama Supplier</td><td>: <input type="text" name="sup_name" /> <span class="note">*)</span></td>                                
                </tr> 
                <tr>
                    <td>Asal </td><td>: 
                    <select name="sup_type" style="width:155px;margin: 2px;">
                        <option value="1">Medan</option>
                        <option value="2">Luar Medan</option>
                    </select> <span class="note">*)</span></td>                                
                </tr> 
                <tr>
                    <td>Telepon / HP</td><td>: <input type="text" name="sup_phone" /> <span class="note">*) Harus angka</span></td>                                
                </tr> 
                <tr>
                    <td>Alamat </td><td>: <textarea name="sup_address" style="vertical-align:text-top"></textarea> <span class="note">*)</span></td>                                
                </tr>
                <tr>
                    <td colspan="2">
                    <span class="button"><input class="button" type="submit" name="submit_tambah_supplier" value="Simpan"/></span>
                    <span class="button"><input class="button" type="reset" value="Batal"/></span>
                    </td>
                </tr>
            </table>            
            <?php echo form_close() ?>
            <p><?php if(isset($err_msg)) echo $err_msg ?></p>
      </div>                
        <div class="tempatemo_right_bottom">
        </div>
    </div><!-- End of Right Section -->
    
    <div class="cleaner"></div>

</div><!-- End Of Content area bottom -->
<?php include 'layouts/footer.php' ?>        
