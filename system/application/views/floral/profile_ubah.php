<?php include 'layouts/header.php'; ?>    
<?php include 'layouts/menu.php'; ?>
<?php include 'layouts/content_top.php' ?>    
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Profile >> Ubah</h1>
            <?php if(isset($pengguna)) { ?>
            <?php echo form_open('profile/ubah') ?>
            <table class="table-form">
                <tr><td colspan="2"><span class="note">Yang bertanda *) tidak boleh dikosongkan</span></td></tr>
                <tr><td><span class="note">Informasi Account</span></td></tr>
                <tr>
                    <td>Username</td><td>: <input type="text" name="p_username" readonly="readonly" value="<?php echo $pengguna->p_username ?>"/> <span class="note">*) </span></td>                                
                </tr>
                <tr>
                    <td>Password Lama</td><td>: <input type="password" name="p_passwd" /> <span class="note"> *) Biarkan kosong jika tidak ingin mengganti password</span></td>                                
                </tr> 
                <tr>
                    <td>Password Baru</td><td>: <input type="password" name="new_passwd"  /> <span class="note"></span></td>                                
                </tr>
                <tr>
                    <td>Konfirmasi Password</td><td>: <input type="password" name="new_passwd_confirm"  /> <span class="note"></span></td>                                
                </tr>
                <tr>
                    <td><span class="note">Informasi Karyawan</span></td>
                </tr>
                <tr>
                    <td>Nama </td><td>: <input type="text" name="op_name" value="<?php echo $pengguna->op_name ?>"/> <span class="note">*) </span></td>                                
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>: 
                        <select name="p_role" style="width:155px;margin-left: 2px;">
                            <option value="1" <?php if($pengguna->p_role == 'admin') echo 'selected="selected"' ?> >Administrator</option>
                            <option value="2" <?php if($pengguna->p_role == 'supervisor') echo 'selected="selected"' ?> >Supervisor</option>
                            <option value="3" <?php if($pengguna->p_role == 'operator') echo 'selected="selected"' ?> >Operator</option>
                            <option value="4" <?php if($pengguna->p_role == 'user') echo 'selected="selected"' ?> >User</option>
                        </p>
                        <span class="note">*) </span>
                    </td>                                
                </tr> 
                <tr>
                    <td>Telepon </td><td>: <input type="text" name="op_phone" value="<?php echo $pengguna->op_phone ?>"/> <span class="note"> *)</span></td>                                
                </tr>               
                <tr>
                    <td>Alamat</td><td>: <textarea name="op_address" style="vertical-align:text-top"><?php echo $pengguna->op_address ?></textarea> <span class="note">*)</span></td>                                
                </tr> 
                <tr>
                    <td colspan="2">
                    <span class="button"><input class="button" type="submit" name="submit_ubah_profile" value="Simpan"/></span>
                    <span class="button"><input class="button" type="reset" value="Batal"/></span>
                    </td>
                </tr>                
            </table>            
            <?php echo form_close() ?>
            <p><?php if(isset($err_msg)) echo $err_msg ?></p>            
            <?php } ?>
      </div>                
        <div class="tempatemo_right_bottom">
        </div>
    </div><!-- End of Right Section -->
    
    <div class="cleaner"></div>

</div><!-- End Of Content area bottom -->
<?php include 'layouts/footer.php' ?>        