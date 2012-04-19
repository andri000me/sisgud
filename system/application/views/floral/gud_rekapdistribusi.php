<?php include 'layouts/header.php' ?>    
<?php include 'layouts/menu.php' ?>
<?php include 'layouts/content_top.php' ?>        
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Laporan >> Rekap Distribusi Barang</h1>
            <?php echo form_open('gudang/distribusi') ?>            
            <table class="table-form">
                <tr>
                    <td>Cari Berdasarkan</td>
                    <td> : 
                        <select id="type-search" name="opsi" style="width:170px; margin:2px;" onchange="appendSearch()">
                            <option value="1">Kode Label</option>
                            <option value="2">Kelompok Barang</option>
                            <option value="3">Supplier</option>
                        </select>
                    </td>
                </tr>                           
                <tr>
                    <td>Periode</td>
                    <td>: <input type="text" class="date" name="tgl_awal" readonly="readonly" style="width:70px"/> s.d
                    <input type="text" class="date" name="tgl_akhir" readonly="readonly" style="width:70px"/>                        
                    </td>
                </tr>
                <tr class="tgl_mutasi">
                	<td>Kode Label</td>
                    <td>: <input type="text" name="item_code" style="width:168px"/>
                        <span class="button"><input type="submit" name="submit_rekap_distribusi" value="Display" class="button"/></span>
                    </td>
                </tr>
                <tr style="display:none" class="tgl_bon">
                    <td>Kelompok Barang</td>
                    <td>: <input type="text" name="cat_code" style="width:168px"/>
                        <span class="button"><input type="submit" name="submit_rekap_distribusi" value="Display" class="button"/></span>
                    </td>
                </tr>
                <tr style="display:none" class="supplier">
                    <td>Kode Supplier</td>
                    <td>: <input type="text" name="sup_code" id="sup_code" class="text_field" readonly="readonly" style="width:168px"/></td>
                </tr>
                <tr style="display:none" class="supplier">
                    <td>Nama Supplier</td>
                    <td>: <input class="ac_input text_field" type="text" id="sup_name"   value="" style="width:168px"/>
                        <span class="button"><input type="submit" name="submit_rekap_distribusi" value="Display" class="button"/></span>
                    </td>
                </tr>
            </table>           
            <?php echo form_close() ?>
            <p><?php if(isset($err_msg)) echo $err_msg ?></p>
            <?php if(isset($row_data)) { ?>
            <div class="table-container">
            <p style="text-align:center;font-weight:bold;color:#000;">REKAP DISTRIBUSI BARANG<br /> 
            <?php echo strtoupper($title) ?></p>            
            <table class="table-data">
                <tr>
                    <td class="header" rowspan="2">No</td>
                    <td class="header" rowspan="2">Kode Label</td>                    
                    <!-- <td class="header" rowspan="2">Nama</td> -->                    
                    <td class="header" rowspan="2">Qty</td>                                       
                    <td class="header" colspan="<?php echo $jumlah_toko?>">Distribusi Toko</td>                                       
                    <td class="header" rowspan="2">Stok</td>
                    <td class="header" rowspan="2">HM (Rp)</td>
                    <td class="header" rowspan="2">Harga Jual (Rp) </td>
                </tr>
                <?php echo $header?>                
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