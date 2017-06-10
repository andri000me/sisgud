<?php include 'layouts/header.php'; ?>    
<?php include 'layouts/menu.php'; ?>
<?php include 'layouts/content_top.php' ?>
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Laporan >> Cetak Mutasi</h1>
            <?php echo form_open('gudang/mutasi/print') ?>
            <table class="table-form">                              
                <tr>
                    <td>Nama Supplier</td>
                    <td>: <?php if(isset($list_sup)) {echo $list_sup.' <span class="button"><input class="button" type="submit" name="submit_preview_mutasi" value="Preview"/></span><span class="button"><input class="button" type="submit" name="submit_print_mutasi" value="Cetak"/> <input class="button" type="button" onclick="print_mutasi_khusus()" value="Cetak II"/> </span>';} else {echo '<select><option>Tidak Ada</option></select>';} ?> </td></tr>
            </table>                    
            <?php echo form_close() ?>
            <?php if(isset($row_data)) { ?>
            <div class="table-container">
            <p style="text-align:center;font-weight:bold;color:#000;">BON MUTASI KELUAR</p>
            <table class="table-form">
                <tr><td>SUPPLIER</td><td> : <?php echo strtoupper($sup->sup_name) ?></td></tr>                
                <tr><td>TANGGAL BON</td><td> : <?php echo date_to_string($tgl_bon) ?></td></tr>                
            </table>
            <table class="table-data">
                <tr>
                    <td rowspan="2" class="header">No</td>
                    <td rowspan="2"class="header">Kode Label</td>                    
                    <td rowspan="2"class="header">Nama Barang</td>                    
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
            </div>
            <?php } else { ?>
            <p></p>
            <?php } ?>
      </div>                
        <div class="tempatemo_right_bottom">
        </div>
    </div><!-- End of Right Section -->
    
    <div class="cleaner"></div>

</div><!-- End Of Content area bottom -->
<?php include 'layouts/footer.php' ?>        