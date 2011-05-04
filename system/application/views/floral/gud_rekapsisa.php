<?php include 'layouts/header.php' ?>    
<?php include 'layouts/menu.php' ?>
<?php include 'layouts/content_top.php' ?>        
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Laporan >> Rekap Mutasi Sisa</h1>
            <?php echo form_open('gudang/sisa/rekap') ?>            
            <table class="table-form">
                <tr>
                    <td>Cari Berdasarkan</td>
                    <td> : 
                        <select id="type-search" name="opsi_cari" style="width:155px; margin:2px" onchange="appendSearch()">
                            <option value="1">Tanggal Mutasi</option>
                            <!--<option value="2">Tanggal Bon</option>-->
                            <!--<option value="3">Supplier</option>-->
                        </select>
                    </td>
                </tr>           
                <tr class="tgl_mutasi">
                    <td>Tanggal Mutasi</td>
                    <td>: <input type="text" class="date" name="tgl_mutasi" readonly="readonly"/>
                        <span class="button"><input type="submit" name="submit_rekap_mutasi_sisa" value="Display" class="button"/></span>
                    </td>
                </tr>                
            </table>                    
            <?php echo form_close() ?>
            <p><?php if(isset($err_msg)) echo $err_msg ?></p>
            <?php if(isset($row_data)) { ?>
            <div class="table-container">
            <p style="text-align:center;font-weight:bold;color:#000;">DAFTAR MUTASI SISA<br /> <?php echo strtoupper($title) ?></p>            
            <table class="table-data">
                <tr>
                    <td class="header">No</td>
                    <td class="header">Kode Mutasi</td>                    
                    <td class="header">Supplier</td>                    
                    <td class="header">Jenis Barang</td>                                       
                    <td class="header">Tanggal Mutasi</td>                                       
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