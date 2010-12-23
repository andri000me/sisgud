<?php include 'layouts/header.php'; ?>    
<?php include 'layouts/menu.php'; ?>
<?php include 'layouts/content_top.php' ?>    
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Laporan >> Stok Gudang</h1>
            <?php echo form_open('gudang/stok') ?>
            <table class="table-form">
                <tr>
                    <td>Keywords</td><td>: <input type="text" name="keywords" /></td>
                    <td><span class="button"><input class="button" type="submit" name="submit_search_stock" value="Search"/></span></td>
                </tr>                                
            </table>
            <p><?php if(isset($err_msg)) echo $err_msg ?></p>
            <?php if(isset($row_data)) { ?>
            <p>Total : <?php echo $total_item ?> item</p>
            <p><?php if(!empty($page)) echo 'Page : '.$page ?></p>
            <table class="table-data">
                <tr>
                    <td class="header">No</td>
                    <td class="header">Kode Barang</td>
                    <td class="header">Nama Barang</td>
                    <td class="header">Kelompok Barang</td>
                    <td class="header">Supplier</td>
                    <td class="header">Harga Jual (Rp)</td>
                    <td class="header">Stok (item)</td>
                </tr>
                <?php echo $row_data ?>
            </table> 
            <?php } ?>
            <?php echo form_close() ?>
      </div>                
        <div class="tempatemo_right_bottom">
        </div>
    </div><!-- End of Right Section -->
    
    <div class="cleaner"></div>

</div><!-- End Of Content area bottom -->
<?php include 'layouts/footer.php' ?>        