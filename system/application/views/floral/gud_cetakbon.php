<?php include 'layouts/header.php' ?>    
<?php include 'layouts/menu.php' ?>
<?php include 'layouts/content_top.php' ?>        
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Laporan >> Cetak Bon</h1>
            <?php echo form_open('gudang/cetak/bon') ?>            
            <table class="table-form">                              
                <tr>
                    <td>Nama Toko</td>
                    <td>: <?php echo $list_toko_bon ?>
                        <span class="button"><input type="submit" name="submit_preview_bon" value="Preview" class="button"/></span>
                    </td></tr>                
            </table>            
            <?php echo form_close() ?>
            <p><?php if(isset($err_msg)) echo $err_msg ?></p>
            <?php if(isset($row_data)) { ?>
            <div class="table-container">
            <p style="text-align:center;font-weight:bold;color:#000;">BON MUTASI KELUAR</p>
            <table class="table-form">                               
                <tr><td>Kode Bon</td><td> : <?php echo $dist_code ?></td></tr>                
                <tr><td>Toko Tujuan</td><td> : <?php echo $shop->shop_name ?></td></tr>
                <tr><td>Tanggal</td><td> : <?php echo date_to_string($tgl_bon) ?></td></tr>
            </table>
            <table class="table-data">
                <tr>
                    <td class="header">No</td>
                    <td class="header">Kode Supplier</td>                    
                    <td class="header">Kode Barang</td>                    
                    <td class="header">Nama Barang</td>                    
                    <td class="header">Disc %</td>                    
                    <td class="header">Harga Jual (Rp)</td>                                       
                    <td class="header">Qty Barang</td>                    
                    <td class="header">Jumlah (Rp)</td>                    
                </tr>                
                <?php echo $row_data ?>                
            </table>
            <!--<?php echo form_open('gudang/cetak/bon') ?>-->
            <p style="text-align:center"><input type="hidden" name="dist_code" value="<?php echo $dist_code ?>"/>
            <a href="<?php echo base_url().'gudang/cetak/bon/'.$dist_code ?>" target="new">
                <span class="button"><input class="button" type="submit" name="submit_cetak_bon" value="Cetak"></span>
            </a>
            </p>
            <!--<?php echo form_close() ?>-->
            </div>            
            <?php } ?>
      </div>                
        <div class="tempatemo_right_bottom">
        </div>
    </div><!-- End of Right Section -->
    
    <div class="cleaner"></div>

</div><!-- End Of Content area bottom -->
<?php include 'layouts/footer.php' ?>        