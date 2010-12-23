<?php include 'layouts/header.php'; ?>    
<?php include 'layouts/menu.php'; ?>
<?php include 'layouts/content_top.php' ?>    
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Toko >> Stok Toko</h1>
            <?php echo form_open('toko/stok') ?>
            <table class="table-form">
                <tr>
                    <td>Toko </td><td>: <?php echo $list_toko ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Keywords</td><td>: <input type="text" name="keywords" style="width: 205px"/></td>
                    <td><span class="button"><input class="button" type="submit" name="submit_cari_stok" value="Search"/></span></td>
                </tr>                                
            </table>            
            <?php echo form_close() ?>
            <p><?php if(isset($err_msg)) echo $err_msg ?></p>
            <?php if(isset($row_data)) { ?>
            <p style="text-align:center;color:#000"><b>DATA STOK <?php echo strtoupper($shop->shop_name) ?></b></p>
            <table class="table-form">                
                <tr>
                    <td>Total Barang </td><td>: <?php echo $total_item ?> macam </td>
                </tr>
                <tr>
                    <td>Total Jumlah Barang </td><td>: <?php echo $total_jumlah ?> item</td>
                </tr>
                <tr>
                    <td>Total Retur Barang </td><td>: <?php echo $total_retur ?> item</td>
                </tr>
            </table>
            <p><?php if(!empty($page)) echo 'Page :'.$page ?></p>
            <table class="table-data">
                <tr>
                    <td class="header">No</td>
                    <td class="header">Kode Barang</td>
                    <td class="header">Nama Barang</td>
                    <td class="header">Supplier</td>                    
                    <td class="header">Harga Jual (Rp)</td>                    
                    <td class="header">Jumlah Barang</td>
                    <td class="header">Retur Barang</td>                                        
                </tr>
                <?php echo $row_data ?>
            </table> 
            <?php } ?>
           
      </div>                
        <div class="tempatemo_right_bottom">
        </div>
    </div><!-- End of Right Section -->
    
    <div class="cleaner"></div>

</div><!-- End Of Content area bottom -->
<?php include 'layouts/footer.php' ?>        