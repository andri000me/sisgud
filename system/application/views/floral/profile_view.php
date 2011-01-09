<?php include 'layouts/header.php'; ?>    
<?php include 'layouts/menu.php'; ?>
<?php include 'layouts/content_top.php' ?>    
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Profile >> View </h1>
            <?php if(isset($pengguna)) { ?>
            <?php echo form_open('profile/ubah') ?>
            <table class="table-form" cellspacing="5">                
                <tr><td><span class="note">Informasi Account</span></td></tr>
                <tr>
                    <td>Username</td><td>: <?php echo $pengguna->p_username ?></td>                                
                </tr>
                <tr>
                    <td>Password</td><td>: ########</td>                                
                </tr>                
                <tr>
                    <td><span class="note">Informasi Karyawan</span></td>
                </tr>
                <tr>
                    <td>Nama </td><td>: <?php echo $pengguna->op_name ?></td>                                
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>: <?php echo ucwords($pengguna->p_role) ?></td>                                
                </tr> 
                <tr>
                    <td>Telepon </td><td>: <?php echo $pengguna->op_phone ?></td>                                
                </tr>               
                <tr>
                    <td>Alamat</td><td>: <?php echo $pengguna->op_address ?></td>                                
                </tr> 
                <tr>
                    <td colspan="2">
                        <span class="button"><input class="button" type="submit" name="submit_ubah" value="Ubah"/></span>                    
                    </td>
                </tr>                
            </table>
            <?php echo form_close() ?>
            <?php } ?>
      </div>                
        <div class="tempatemo_right_bottom">
        </div>
    </div><!-- End of Right Section -->
    
    <div class="cleaner"></div>

</div><!-- End Of Content area bottom -->
<?php include 'layouts/footer.php' ?>        