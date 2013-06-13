<?php include 'layouts/header.php'; ?>    
<?php include 'layouts/menu.php'; ?>
<?php include 'layouts/content_top.php' ?>    
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Laporan >> Distribusi Barang</h1>
            <?php echo form_open('laporan/distribusi') ?>
            <table class="table-form">
                <tr>
                    <td>Pilih Tipe</td>
                    <td>: <select name="type" style="width: 180px; height:23px;">
                            <option value="1">Supplier</option>
                            <option value="2">Kelompok Barang</option>
                    </select></td>
                </tr>
                <tr>
                    <td>Pilih Toko</td>
                    <td>: <?php echo $list_shop ?>
                    </td>
                </tr>
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
            <?php if(isset($table)){echo $table; }?>
           
      </div>                
        <div class="tempatemo_right_bottom">
        </div>
    </div><!-- End of Right Section -->
    
    <div class="cleaner"></div>

</div><!-- End Of Content area bottom -->

<?php include 'layouts/footer.php' ?>        