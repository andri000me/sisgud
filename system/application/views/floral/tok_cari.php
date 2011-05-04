<?php include 'layouts/header.php'; ?>    
<?php include 'layouts/menu.php'; ?>
<?php include 'layouts/content_top.php' ?>    
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Toko >> Cari Toko</h1>
            <?php echo form_open('toko/cari') ?>
            <table class="table-form">
                <tr>
                    <td>Keywords</td><td>: <input type="text" name="keywords" /></td>
                    <td><span class="button"><input class="button" type="submit" name="submit_cari_toko" value="Search"/></span></td>
                </tr>                                
            </table>
            <?php if(isset($err_msg)) echo $err_msg ?>
            <?php echo form_close() ?>
            
            <?php if(isset($row_data)) { ?>
            <p>Total : <?php echo $total_item ?> toko</p>
            <p><?php if(!empty($page)) echo 'Page : '.$page ?></p>
            <table class="table-data">
                <tr>
                    <td class="header">No</td>
                    <td class="header">Kode Toko</td>
                    <td class="header">Nama Toko</td>
                    <td class="header">Mutasi Keluar</td>                    
                    <td class="header">Retur Barang</td>                    
                    <td class="header">Total Barang</td>
                    <td class="header">Action</td>                    
                </tr>
                <?php echo $row_data ?>
            </table> 
            <?php } ?>
            <div id="dialog-msg" title="Notifikasi" style="display:none">
            <p id="msg"></p>
            </div>
      </div>                
        <div class="tempatemo_right_bottom">
        </div>
    </div><!-- End of Right Section -->
    
    <div class="cleaner"></div>

</div><!-- End Of Content area bottom -->
<?php include 'layouts/footer.php' ?>        