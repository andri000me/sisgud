<?php include 'layouts/header.php'; ?>    
<?php include 'layouts/menu.php'; ?>
<?php include 'layouts/content_top.php' ?>
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Laporan >> Cetak Mutasi Sisa</h1>            
            <?php if(isset($row_data)) { ?>
            <div class="table-container">
            <p style="text-align:center;font-weight:bold;color:#000;">BON MUTASI KELUAR (SISA)</p>
            <table class="table-form">                              
                <tr><td>TANGGAL BON</td><td> : <?php echo $tgl_bon ?></td></tr>                
            </table>
            <table class="table-data">
                <tr>
                    <td rowspan="2" class="header">No</td>
                    <td rowspan="2"class="header">Kode Label</td>                    
                    <td rowspan="2"class="header">Nama Barang</td>                    
                    <td rowspan="2"class="header">Supplier</td>                    
                    <td rowspan="2"class="header">Qty</td>
                    <td colspan="<?php echo $shop_count ?>" class="header">Distribusi Toko</td>                    
                    <td rowspan="2"class="header">Harga Jual</td>
                </tr>
                <tr>
                    <?php echo $shop_name ?>
                </tr>
                <?php echo $row_data ?>
            </table>
            <table class="table-form" style="width:100%">
                <tr><td style="width:50%;text-align:center;">( . . . . . . . .)</td><td style="width:50%;text-align:center;">( . . . . . . . .)</td></tr>
            </table>
            <!--<?php echo form_open('gudang/sisa') ?>-->
            <p style="text-align:center">
            <a href="<?php echo base_url().'gudang/sisa/cetak' ?>" target="new"><span class="button"><input type="submit" name="submit_cetak_sisa" value="Cetak" class="button"/></span></a>
            </p>
            <!--<?php echo form_close() ?>-->
            </div>
            <?php } else { ?>
            <p><span style="color:red">Terjadi kesalahan. Silahkan pilih terlebih dahulu barang yang akan dibuat mutasinya</span></p>
            <?php } ?>
      </div>                
        <div class="tempatemo_right_bottom">
        </div>
    </div><!-- End of Right Section -->
    
    <div class="cleaner"></div>

</div><!-- End Of Content area bottom -->
<?php include 'layouts/footer.php' ?>        