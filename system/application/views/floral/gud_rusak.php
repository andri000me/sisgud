<?php include 'layouts/header.php' ?>    
<?php include 'layouts/menu.php' ?>
<?php include 'layouts/content_top.php' ?>        
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Laporan >> Cetak Barang Rusak</h1>
            <?php echo form_open('gudang/rusak') ?>            
            <table class="table-form">                              
                <tr>
                    <td>Nama Toko</td>
                    <td>: <?php echo $list_toko_rusak ?>
                        <span class="button"><input type="submit" name="submit_preview_rusak" value="Preview" class="button"/></span>
                    </td></tr>                
            </table>            
            <?php echo form_close() ?>
            <p><?php if(isset($err_msg)) echo $err_msg ?></p>
            <?php if(isset($row_data)) { ?>
            <div class="table-container">
            <p style="text-align:center;font-weight:bold;color:#000;">BON BARANG RUSAK</p>
            <table class="table-form">                               
                <tr><td>Kode Bon</td><td> : <?php echo $dist_code ?></td></tr>                
                <tr><td>Toko Tujuan</td><td> : <?php echo $shop->shop_name ?></td></tr>
                <tr><td>Tanggal</td><td> : <?php echo date_to_string($tgl_bon) ?></td></tr>
            </table>
            <table class="table-data">
                <tr>
                    <td class="header" rowspan="2">No</td>
                    <td class="header" rowspan="2">Kode Supplier</td>                    
                    <td class="header" rowspan="2">Kode Barang</td>                    
                    <td class="header" rowspan="2">Nama Barang</td>                               
                    <td class="header" rowspan="2">Harga Jual (Rp)</td>                                       
                    <td class="header" rowspan="2">Qty Barang</td>                    
                    <td class="header" rowspan="2">Jumlah (Rp)</td>
                    <td class="header" colspan="3">Penggantian</td>
                </tr>
                <tr>
                    <td>Brg</td>
                    <td>Uang</td>
                    <td>Ptg Bon</td>
                </tr>
                <?php echo $row_data ?>                
            </table>
            <!--<?php echo form_open('gudang/rusak') ?>-->
            <p style="text-align:center"><input type="hidden" name="dist_code" value="<?php echo $dist_code ?>"/>
            <a href="<?php echo base_url().'gudang/rusak/'.$dist_code.'/'.$shop->shop_code ?>" target="new">
                <span class="button"><input class="button" type="submit" name="submit_cetak_rusak" value="Cetak"></span>
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