<?php include 'layouts/header.php'; ?>    
<?php include 'layouts/menu.php'; ?>
<?php include 'layouts/content_top.php' ?>    
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Kelompok Barang >> Tambah</h1>
            <?php echo form_open('kategori/tambah') ?>
            <table class="table-form">
                <tr><td><span class="note">Yang bertanda *) wajib diisi.</span></td></tr>
                <tr>
                    <td>Kode Kelompok Barang</td><td>: <input type="text" name="cat_code" maxlength="3"/> <span class="note">*) Harus 3 digit angka</span></td>                                
                </tr>
                <tr>
                    <td>Nama Kelompok Barang</td><td>: <input type="text" name="cat_name" /> <span class="note">*)</span></td>                                
                </tr><tr>
                    <td>Keterangan</td><td>: <textarea name="cat_desc" style="vertical-align:text-top"></textarea> </td>                                
                </tr>
                <tr>
                    <td colspan="2">
                    <span class="button"><input class="button" type="submit" name="submit_tambah_kategori" value="Simpan"/></span>
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
