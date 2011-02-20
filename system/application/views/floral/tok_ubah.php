<?php include 'layouts/header.php'; ?>    
<?php include 'layouts/menu.php'; ?>
<?php include 'layouts/content_top.php' ?>    
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Toko >> Ubah</h1>
            <?php echo form_open_multipart('toko/ubah') ?>
            <table class="table-form">
                <tr><td><span class="note">Yang bertanda *) wajib diisi.</span></td></tr>
                <tr>
                    <td>Kode Toko</td><td>: <input type="text" name="shop_code" readonly="readonly" value="<?php echo $shop->shop_code ?>"/> <span class="note">*) Dua digit angka</span></td>                                
                </tr>
                <tr>
                    <td>Nama Toko</td><td>: <input type="text" name="shop_name" value="<?php echo $shop->shop_name ?>"/> <span class="note">*)</span></td>                                
                </tr>                
                <tr>
                    <td>Initial Toko</td><td>: <input type="text" name="shop_initial" value="<?php echo $shop->shop_initial ?>"/> <span class="note">*)</span></td>                                
                </tr>
                <tr>
                    <td>Cabang</td><td>:
                    <select name="shop_cat" style="width:155px;margin:2px;">
                        <option value="1" <?php if($shop->shop_cat == 'MODE') echo 'selected="selected"'; ?> >MODE</option>
                        <option value="2" <?php if($shop->shop_cat == 'MODIEST') echo 'selected="selected"'; ?> >MODIEST</option>                        
                        <option value="3" <?php if($shop->shop_cat == 'OBRAL') echo 'selected="selected"'; ?> >OBRAL</option>                        
                        <option value="4" <?php if($shop->shop_cat == 'RUSAK') echo 'selected="selected"'; ?> >RUSAK</option>
                    </select>
                    <span class="note">*)</span></td>                                
                </tr>
                <tr>
                    <td>Telepon</td><td>: <input type="text" name="shop_phone" value="<?php echo $shop->shop_phone ?>"/> <span class="note">*) Harus angka</span></td>                                
                </tr>
                <tr>
                    <td>Supervisor</td><td>: <input type="text" name="shop_supervisor" value="<?php echo $shop->shop_supervisor ?>"/> <span class="note">*) Harus angka</span></td>                                
                </tr> 
                <tr>
                    <td>Alamat </td><td>: <textarea name="shop_address" style="vertical-align:text-top"><?php echo $shop->shop_address ?></textarea> <span class="note">*)</span></td>                                
                </tr>
                <tr>
                    <td><span class="note">Upload foto / gambar toko</span></td>
                </tr>
                <tr>
                    <td>Foto / Gambar</td><td>: <input type="file" name="shop_img" /></td>
                </tr>
                <tr>
                    <td colspan="2">
                    <span class="button"><input class="button" type="submit" name="submit_ubah_toko" value="Simpan"/></span>
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