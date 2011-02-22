<?php include 'layouts/header.php' ?>    
<?php include 'layouts/menu.php' ?>
<?php include 'layouts/content_top.php' ?>        
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Laporan >> Rekap Bon Rusak</h1>
            <?php echo form_open('gudang/rusak/rekap') ?>            
            <table class="table-form">                              
                <tr>
                    <td>Nama Toko</td>
                    <td>: <?php echo $list_toko_rusak_pdf ?>                        
                    </td></tr>
                <tr><td>Tanggal</td><td> : <input type="text" id="date_bon" name="date_bon" value="" readonly="readonly" style="width:120px"/>
                <span class="button"><input type="submit" name="submit_rekap_rusak" value="Display" class="button"/></span>
                </td></tr>      
            </table>            
            <?php echo form_close() ?>
            <p><?php if(isset($err_msg)) echo $err_msg ?></p>
            <?php if(isset($row_data)) { ?>
            <div class="table-container">
            <p style="text-align:center;font-weight:bold;color:#000;">DAFTAR BON BARANG RUSAK</p>
            <table class="table-form">                       
                <tr><td>Toko Tujuan</td><td> : <?php echo $shop->shop_name ?></td></tr>                
            </table>
            <table class="table-data">
                <tr>
                    <td class="header">No</td>
                    <td class="header">Kode Bon</td>                    
                    <td class="header">Tanggal</td>                    
                    <td class="header">Jenis Barang</td>                    
                    <td class="header">Jumlah Barang</td>                                   
                    <td class="header">Action</td>                    
                </tr>                
                <?php echo $row_data ?>                
            </table>            
            </div>
            <?php } ?>
      </div>                
        <div class="tempatemo_right_bottom">
        </div>
    </div><!-- End of Right Section -->
    
    <div class="cleaner"></div>

</div><!-- End Of Content area bottom -->
<?php include 'layouts/footer.php' ?>        