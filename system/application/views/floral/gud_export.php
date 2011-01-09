<?php include 'layouts/header.php'; ?>    
<?php include 'layouts/menu.php'; ?>
<?php include 'layouts/content_top.php' ?>    
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Gudang >> Export Data</h1>
            <?php echo form_open('gudang/export') ?>
            <table class="table-form">
                <tr>
                    <td>Pilih Toko</td><td>: <?php echo $list_toko ?></td>
                    <td><span class="button"><input class="button" type="submit" name="submit_preview_export" value="Preview"/></span></td>
                </tr>                                
            </table>
            <p><?php if(isset($err_msg)) echo $err_msg ?></p>
            <?php echo form_close() ?>
            
            <?php if(isset($row_data)) { ?>
            <p style="text-align:center;color:#000;"><b>PREVIEW EXPORT DATA</b></p>
            <table class="table-form">
                <tr><td>Toko Tujuan</td><td>: <?php echo $shop->shop_name ?></td></tr>
                <tr><td>Total Barang</td><td>: <?php echo $total_item ?> macam</td></tr>
                <tr><td>Total </td><td>: <?php echo $total ?> item</td></tr>
            </table>
            <p><?php if(!empty($page)) echo 'Page : '.$page ?></p>
            <table class="table-data">
                <tr>
                    <td class="header">No</td>
                    <td class="header">Kode Label</td>
                    <td class="header">Nama Barang</td>
                    <td class="header">Harga Jual (Rp)</td>
                    <td class="header">Disc %</td>                    
                    <td class="header">Qty</td>                    
                </tr>
                <?php echo $row_data ?>
            </table>
            <?php echo form_open('gudang/export') ?>
            <input type="hidden" value="<?php echo $export ?>" name="export" />
            <input type="hidden" value="<?php echo $shop->shop_code ?>" name="shop_code" />
            <p style="text-align:center"><span class="button"><input class="button" type="submit" value="Export" name="submit_export"/></span></p>
            <?php echo form_close() ?>
            <?php } ?>
           
      </div>                
        <div class="tempatemo_right_bottom">
        </div>
    </div><!-- End of Right Section -->
    
    <div class="cleaner"></div>

</div><!-- End Of Content area bottom -->
<?php include 'layouts/footer.php' ?>        