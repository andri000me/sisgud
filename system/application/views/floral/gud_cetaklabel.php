<?php include 'layouts/header.php' ?>    
<?php include 'layouts/menu.php' ?>
<?php include 'layouts/content_top.php' ?>        
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Laporan >> Cetak Label</h1>
            <?php echo form_open('gudang/cetak/label') ?>
            <?php if(isset($list_supp) || $this->session->userdata('link_download')) { ?>
            <table class="table-form">                              
                <tr>
                    <td>Supplier</td>
                    <td>: <?php if(isset($list_supp)) { echo $list_supp; ?>
                        <span class="button"><input type="submit" name="submit_cetak_label" value="Display" class="button"/></span>
                        <?php } else { echo '<span style="color:red">Belum ada supplier</span>';} ?>
                        <?php if($this->session->userdata('link_download')) { ?>
                        <span class="button"><input type="button" value="Simpan" class="button" onclick="window.location.replace('<?php echo $this->session->userdata('link_download') ?>')"/></span>
                        <?php } ?>
                    </td></tr>                
            </table> 
            <?php } else { ?>
            <p style="color:red">Anda tidak memiliki label yang siap untuk dicetak. Klik disini untuk membuat <a href="<?php echo base_url()?>gudang/mutasi/keluar" title="Mutasi Keluar">mutasi keluar</a></p>
            <?php } ?>
            <?php echo form_close() ?>
            <p><?php if(isset($err_msg)) echo $err_msg ?></p>
            <?php if(isset($row_data)) { ?>
            <div class="table-container">
            <p style="text-align:center;font-weight:bold;color:#000;">DAFTAR CETAK LABEL</p>
            <table class="table-form">                               
                <tr><td>SUPPLIER</td><td> : <?php echo strtoupper($sup_name) ?></td></tr>                
            </table>
            <table class="table-data">
                <tr>
                    <td class="header">No</td>
                    <td class="header">Kode Label</td>                    
                    <td class="header">Nama Barang</td>                    
                    <td class="header">Harga Jual (Rp)</td>                                       
                    <td class="header">Jumlah</td>
                    <td class="header">Action</td>
                </tr>                
                <?php echo $row_data ?>
            </table>            
            </div>
            <?php } ?>
            <?php if($this->session->userdata('link_download')) { ?>
            <p><span style="color:green">Untuk menyimpan hasil cetak kode label <b><?php echo $this->session->userdata('item_code') ?></b>, silahkan tekan tombol simpan di atas</span></p>
            <?php $this->session->unset_userdata('link_download');} ?>
      </div>                
        <div class="tempatemo_right_bottom">
        </div>
    </div><!-- End of Right Section -->
    
    <div class="cleaner"></div>

</div><!-- End Of Content area bottom -->
<?php include 'layouts/footer.php' ?>        