<?php include 'layouts/header.php'; ?>    
<?php include 'layouts/menu.php'; ?>
<?php include 'layouts/content_top.php' ?>    
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Laporan >> Stok Gudang</h1>
            <?php echo form_open('gudang/stok') ?>
            <table class="table-form">
                <tr>
                    <td>Keywords</td><td>: <input type="text" name="keywords" /></td>
                    <td><span class="button"><input class="button" type="submit" name="submit_search_stock" value="Search"/></span></td>
                </tr>                                
            </table>
            <p><?php if(isset($err_msg)) echo $err_msg ?></p>
            <?php echo form_close() ?>
            <?php if(isset($row_data)) { ?>
            <p>Total : <?php echo $total_item ?> item</p>
            <p><?php if(!empty($page)) echo 'Page : '.$page ?></p>
            <p><?php if($this->session->userdata('msg')) { echo $this->session->userdata('msg');$this->session->unset_userdata('msg'); } ?></p>
            <table class="table-data">
                <tr>
                    <td class="header">No</td>
                    <td class="header">Kode Barang</td>
                    <td class="header">Nama Barang</td>
                    <td class="header">Kelompok <br /> Barang</td>
                    <td class="header">Supplier</td>
                    <?php if($this->session->userdata('p_role') == 'supervisor' || $this->session->userdata('p_role') == 'operator_retur') { ?>
                    <td class="header">Harga Modal (Rp)</td>
                    <?php } ?>
                    <?php if($this->session->userdata('p_role') != 'operator_retur') { ?>
                    <td class="header">Harga Jual (Rp)</td>
                    <?php } ?>
                    <td class="header">Stok (item)</td>
                    <?php if($this->session->userdata('p_role') == 'supervisor' || $this->session->userdata('p_role') == 'operator') { ?>
                    <td class="header">Action</td>
                    <?php } ?>
                </tr>
                <?php echo $row_data ?>
            </table>
            <p><?php if(!empty($page)) echo 'Page : '.$page ?></p>
            <?php } ?>
            <div id="dialog-confirm" title="Konfirmasi">
            </div>
      </div>                
        <div class="tempatemo_right_bottom">
        </div>
    </div><!-- End of Right Section -->
    
    <div class="cleaner"></div>

</div><!-- End Of Content area bottom -->
<?php include 'layouts/footer.php' ?>        