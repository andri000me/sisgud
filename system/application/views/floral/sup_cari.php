<?php include 'layouts/header.php'; ?>    
<?php include 'layouts/menu.php'; ?>
<?php include 'layouts/content_top.php' ?>    
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Supplier >> Cari Supplier</h1>
            <?php echo form_open('suppliers/cari') ?>
            <table class="table-form">
                <tr>
                    <td>Keywords</td><td>: <input type="text" name="keywords" /></td>
                    <td><span class="button"><input class="button" type="submit" name="submit_cari_supplier" value="Search"/></span></td>
                </tr>                                
            </table>
            <p><?php if(isset($err_msg)) echo $err_msg ?></p>
            <?php echo form_close() ?>
            
            <?php if(isset($row_data)) { ?>
            <p>Total : <?php echo $total_item ?> item</p>
            <p><?php if(!empty($page)) echo 'Page : '.$page ?></p>
            <table class="table-data">
                <tr>
                    <td class="header">No</td>
                    <td class="header">Kode Supplier</td>
                    <td class="header">Nama Supplier</td>
                    <td class="header">Alamat</td>
                    <td class="header">Telepon</td>
                    <td class="header">Asal</td>
                    <td class="header">Action</td>                    
                </tr>
                <?php echo $row_data ?>
            </table>
            <p><?php if(!empty($page)) echo 'Page : '.$page ?></p>
            <?php } ?>
           
      </div>                
        <div class="tempatemo_right_bottom">
        </div>
    </div><!-- End of Right Section -->
    
    <div class="cleaner"></div>

</div><!-- End Of Content area bottom -->
<?php include 'layouts/footer.php' ?>        