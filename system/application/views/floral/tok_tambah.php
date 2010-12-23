<?php include 'layouts/header.php'; ?>    
<?php include 'layouts/menu.php'; ?>
<?php include 'layouts/content_top.php' ?>    
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Toko >> Tambah</h1>
            <?php echo form_open_multipart('toko/tambah') ?>
            <table class="table-form">
                <tr><td><span class="note">Yang bertanda *) wajib diisi.</span></td></tr>
                <tr>
                    <td>Kode Toko</td><td>: <input type="text" name="shop_code" /> <span class="note">*) Dua digit angka</span></td>                                
                </tr>
                <tr>
                    <td>Nama Toko</td><td>: <input type="text" name="shop_name" /> <span class="note">*)</span></td>                                
                </tr>                
                <tr>
                    <td>Initial Toko</td><td>: <input type="text" name="shop_initial" /> <span class="note">*)</span></td>                                
                </tr> 
                <tr>
                    <td>Telepon</td><td>: <input type="text" name="shop_phone" /> <span class="note">*) Harus angka</span></td>                                
                </tr>
                <tr>
                    <td>Supervisor</td><td>: <input type="text" name="shop_supervisor" /> <span class="note">*) Harus angka</span></td>                                
                </tr> 
                <tr>
                    <td>Alamat </td><td>: <textarea name="shop_address" style="vertical-align:text-top"></textarea> <span class="note">*)</span></td>                                
                </tr>
                <tr>
                    <td><span class="note">Upload foto / gambar toko</span></td>
                </tr>
                <tr>
                    <td>Foto / Gambar</td><td>: <input type="file" name="shop_img" /></td>
                </tr>
                <tr>
                    <td colspan="2">
                    <span class="button"><input class="button" type="submit" name="submit_tambah_toko" value="Simpan"/></span>
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