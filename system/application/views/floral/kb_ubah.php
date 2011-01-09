<?php include 'layouts/header.php'; ?>    
<?php include 'layouts/menu.php'; ?>
<?php include 'layouts/content_top.php' ?>    
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Kelompok Barang >> Ubah</h1>
            <?php if(isset($kategori)) { ?>
            <?php echo form_open('kategori/ubah') ?>
            <table class="table-form">
                <tr><td><span class="note">Yang bertanda *) wajib diisi.</span></td></tr>
                <tr>
                    <td>Kode Kelompok Barang</td><td>: <input type="text" name="cat_code" readonly="readonly" value="<?php echo $kategori->cat_code ?>"/></td>                                
                </tr>
                <tr>
                    <td>Nama Kelompok Barang</td><td>: <input type="text" name="cat_name" value="<?php echo $kategori->cat_name ?>"/> *)</td>                                
                </tr><tr>
                    <td>Keterangan</td><td>: <textarea name="cat_desc" style="vertical-align:text-top"><?php echo $kategori->cat_desc ?></textarea> </td>                                
                </tr>
                <tr>
                    <td colspan="2">
                    <span class="button"><input class="button" type="submit" name="submit_ubah_kategori" value="Simpan"/></span>
                    <span class="button"><input class="button" type="reset" value="Batal"/></span>
                    </td>
                </tr>
            </table>            
            <?php echo form_close() ?>
            <?php } else { ?>
            <p><span style="color:red">Gunakan tombol ubah pada halaman cari kelompok barang.</span></p>
            <?php } ?>
            <p><?php if(isset($err_msg)) echo $err_msg ?></p>
      </div>                
        <div class="tempatemo_right_bottom">
        </div>
    </div><!-- End of Right Section -->
    
    <div class="cleaner"></div>

</div><!-- End Of Content area bottom -->
<?php include 'layouts/footer.php' ?>        