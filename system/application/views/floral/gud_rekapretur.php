<?php include 'layouts/header.php' ?>    
<?php include 'layouts/menu.php' ?>
<?php include 'layouts/content_top.php' ?>        
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Laporan >> Rekap Retur Barang</h1>
            <?php echo form_open('gudang/retur/rekap') ?>
            <table class="table-form">
                <tr><td colspan="2"><span class="note">Yang bertanda *) wajib diisi</span></td></tr>
                <tr>
                    <td>Tanggal Awal</td><td>: <input type="text" class="date" name="tgl_awal" value="" readonly="readonly"/><span class="note"> *) </span>                  
                    <td>Kode Supplier</td><td>: <input type="text" name="sup_code" id="sup_code"/><span class="note"> *)</span>
                </tr>
                <tr>
                    <td>Tanggal Akhir</td><td>: <input type="text" class="date" name="tgl_akhir" value="" readonly="readonly"/><span class="note"> *) </span>
                    <td>Nama Supplier</td>
                    <td>: <input class="ac_input" type="text" id="sup_name"   value="" />
                        <span class="button"><input type="submit" name="submit_rekap_retur" value="Display" class="button"/></span>
                    </td>
                </tr>                
            </table>                    
            <?php echo form_close() ?>
            <p><?php if(isset($err_msg)) echo $err_msg ?></p>
            <!-- daftar retur -->
            <?php if(isset($row_data)) { ?>
            <div class="table-container">
            <p style="text-align:center;font-weight:bold;color:#000;">DAFTAR RETUR BARANG <br />SUPPLIER : <?php echo strtoupper($supplier->sup_name) ?></p>
            <table class="table-form">                               
                <tr><td>Total Barang</td><td> : <?php echo $total_item ?> macam</td></tr>                
                <tr><td>Total Retur</td><td> : <?php echo $total_retur ?> item</td></tr>                
            </table>
            <p><?php if(!empty($page)) echo 'Page : '.$page ?></p>
            <table class="table-data">
                <tr>
                    <td class="header">No</td>
                    <td class="header">Kode Barang</td>                    
                    <td class="header">Nama Barang</td>                    
                    <td class="header">Tanggal Retur</td>                                       
                    <td class="header">Asal Toko</td>                                       
                    <td class="header">Jumlah Retur</td>
                </tr>                
                <?php echo $row_data ?>
            </table>            
            </div>
            <?php } ?>
            <!-- view retur -->
            <?php if(isset($view_retur)) { ?>
            <div class="table-container">
            <p style="text-align:center;font-weight:bold;color:#000;">DETAIL RETUR BARANG</p>
            <table class="table-form">                               
                <tr><td>Kode Retur</td><td> : <?php echo $retur_code ?></td></tr>                
                <tr><td>Asal Toko</td><td> : <?php  echo $shop_name ?></td></tr>                
                <tr><td>Tanggal</td><td> : <?php echo date_to_string($tgl_retur) ?></td></tr>                
            </table>
            <table class="table-data">
                <tr>
                    <td class="header">No</td>
                    <td class="header">Kode Label</td>                   
                    <td class="header">Nama Barang</td>                            
                    <td class="header">Supplier</td>                    
                    <td class="header">Harga Jual (Rp)</td>                                                
                    <td class="header">Jumlah Retur</td>
                    <td class="header">Total (Rp)</td> 
                </tr>                
                <?php echo $view_retur ?>
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