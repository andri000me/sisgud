<?php include 'layouts/header.php'; ?>    
<?php include 'layouts/menu.php'; ?>
<?php include 'layouts/content_top.php' ?>    
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Laporan >> Rekap Mutasi Masuk</h1>
            <?php echo form_open('gudang/rekapmasuk') ?>
            <table class="table-form">            	
                <tr>
                    <td>Tanggal Mutasi</td><td>: <input type="text" name="date_entry" id="date_bon" /></td>
                    <td><span class="button"><input class="button" type="submit" name="submit_cari_mutasi" value="Search"/></span></td>
                </tr>                                
            </table>
            <p><?php if(isset($err_msg)) echo $err_msg ?></p>
            <?php echo form_close() ?>
            
            <?php if(isset($row_data)) { ?>            
            <p style="text-align:center;font-weight:bold;color:#000;">DAFTAR CETAK ULANG LABEL</p>
            <p>Tentukan supplier yang akan dicetak ulang labelnya.</p>
            <p><?php if(!empty($page)) echo 'Page : '.$page ?></p>
            <table class="table-data">
                <tr>
                    <td class="header">No</td>
                    <td class="header">Kode Supplier</td>
                    <td class="header">Nama Supplier</td>
                    <td class="header">Jenis</td>
                    <td class="header">Jumlah</td>                    
                    <td class="header">Opsi</td>
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