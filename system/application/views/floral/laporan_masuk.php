<?php include 'layouts/header.php'; ?>    
<?php include 'layouts/menu.php'; ?>
<?php include 'layouts/content_top.php' ?>    
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Laporan >> Barang Masuk</h1>
            <?php echo form_open('laporan/masuk') ?>
            <table class="table-form">
                <tr>
                    <td>Pilih Tipe</td>
                    <td>: <select name="type" style="width: 180px; height:23px;" id="report_type">
                            <option value="1">Supplier</option>
                            <option value="2">Kelompok Barang</option>
                    </select></td>
                </tr>
                <tr id="supplier">
                    <td>Kode Supplier</td>
                    <td>: <input type="text" name="sup_id" style="width: 175px;" id="sup_id" class="sup_name" autocomplete="off"/></td>
                </tr>
                <!--<tr id="category" style="display: none">
                    <td>Kelompok Barang</td>
                    <td>: <input type="text" name="cat_id" style="width: 175px;"/></td>
                </tr>-->
                <tr>
                    <td>Periode</td>
                    <td>:
                        <input type="text" name="date_from" style="width:71px" class="date"/> s.d.
                        <input type="text" name="date_to" style="width:71px" class="date"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="button"><input class="button" type="submit" name="submit_report_display" value="Display"/></span>
                        <span class="button"><input class="button" type="submit" name="submit_report_print" value="Cetak"/></span>
                    </td>
                </tr>
            </table>
            <p><?php if(isset($err_msg)) echo $err_msg ?></p>
            <?php echo form_close() ?>
            <?php if(isset($table)) { echo $table; } ?>

      </div>                
        <div class="tempatemo_right_bottom">
        </div>
    </div><!-- End of Right Section -->
    
    <div class="cleaner"></div>

</div><!-- End Of Content area bottom -->

<?php include 'layouts/footer.php' ?>        