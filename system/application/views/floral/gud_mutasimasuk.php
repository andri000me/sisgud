<?php include 'layouts/header.php'; ?>    
<?php include 'layouts/menu.php'; ?>
<?php include 'layouts/content_top.php' ?>    
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Gudang >> Mutasi Masuk</h1>
            <?php echo form_open('gudang/mutasi/masuk') ?>
            <table class="table-form">
                <tr><td>Kode Supplier</td><td>: <input type="text" name="sup_code" id="sup_code" class="text_field" readonly="readonly"/> &nbsp; <?php if(isset($form_notify_supplier)) echo $form_notify_supplier ?></td></tr>                
                <tr><td>Nama Supplier</td><td>: <input class="ac_input text_field" type="text" id="sup_name"   value="" /></td></tr>                
                <tr><td>Tanggal Bon</td><td>: <input type="text" id="date_bon" name="date_bon" value="" readonly="readonly"/></td><span id="err_tgl" style="color:red"></span></tr>                
            </table>
            <p><?php
            if($this->session->userdata('form_notify'))
            {
                echo $this->session->userdata('form_notify');
                $this->session->unset_userdata('form_notify');
            }
            ?></p>
            <table class="table-data">
                <tr>
                    <td class="header">No</td>
                    <td class="header">Kelompok Barang</td>
                    <td class="header">Nama Barang</td>
                    <td class="header">Harga Modal</td>
                    <td class="header">Qty (Jumlah)</td>
                </tr>
                <?php 
                    for($i=0;$i<15;) 
                    { 
                        echo '<tr>
                                <td>'.++$i.'</td>
                                <td><input type="text" name="cat_code[]" size="12" maxlength="3"/></td>
                                <td><input type="text" name="item_name[]" size="50"/></td>
                                <td><input type="text" name="item_hp[]" size="20"/></td>
                                <td><input type="text" name="item_qty[]" size="5"/></td>
                            </tr>';
                    } 
                ?>                
            </table>
            <p>
                <span class="button"><input type="submit" name="submit_mutasi_masuk" value="Simpan" class="button" id="submit_mutasi_masuk"/></span>
                <span class="button"><input type="button" value="Lagi" class="button"/></span>
                <span class="button"><input type="reset" value="Batal" class="button"/></span>
            </p>
            <?php echo form_close() ?>
      </div>                
        <div class="tempatemo_right_bottom">
        </div>
    </div><!-- End of Right Section -->
    
    <div class="cleaner"></div>

</div><!-- End Of Content area bottom -->
<?php include 'layouts/footer.php' ?>        