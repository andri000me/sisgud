<?php include 'layouts/header.php' ?>    
<?php include 'layouts/menu.php' ?>
<?php include 'layouts/content_top.php' ?>        
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Laporan >> Rekap Mutasi</h1>
            <?php echo form_open('gudang/mutasi/rekap') ?>
            <?php if($this->session->userdata('p_role') == 'supervisor') { ?>            
            <table class="table-form">
                <tr>
                    <td>Cari Berdasarkan</td>
                    <td> : 
                        <select id="type-search" name="opsi" style="width:155px; margin:2px" onchange="appendSearch()">
                            <!--<option value="1">Tanggal Mutasi</option>-->
                            <!--<option value="2">Tanggal Bon</option>-->
                            <option value="3">Supplier</option>
                        </select>
                    </td>
                </tr>
                <tr class="supplier">
                    <td>Kode Supplier</td>
                    <td>: <input type="text" name="sup_code" id="sup_code" class="text_field" readonly="readonly"/></td>
                </tr>
                <tr class="supplier">
                    <td>Nama Supplier</td>
                    <td>: <input class="ac_input text_field" type="text" id="sup_name"   value="" />
                        <span class="button"><input type="submit" name="submit_rekap_mutasi" value="Display" class="button"/></span>
                    </td>
                </tr>
            </table>
            <?php } else { ?>
            <table class="table-form">
                <tr>
                    <td>Cari Berdasarkan</td>
                    <td> : 
                        <select id="type-search" name="opsi" style="width:155px; margin:2px" onchange="appendSearch()">
                            <option value="1">Tanggal Mutasi</option>
                            <option value="2">Tanggal Bon</option>
                            <option value="3">Supplier</option>
                        </select>
                    </td>
                </tr>           
                <tr class="tgl_mutasi">
                    <td>Tanggal Mutasi</td>
                    <td>: <input type="text" class="date" name="tgl_mutasi" readonly="readonly"/>
                        <span class="button"><input type="submit" name="submit_rekap_mutasi" value="Display" class="button"/></span>
                    </td>
                </tr>
                <tr style="display:none" class="tgl_bon">
                    <td>Tanggal Bon</td>
                    <td>: <input type="text" class="date" name="tgl_bon" readonly="readonly"/>
                        <span class="button"><input type="submit" name="submit_rekap_mutasi" value="Display" class="button"/></span>
                    </td>
                </tr>
                <tr style="display:none" class="supplier">
                    <td>Kode Supplier</td>
                    <td>: <input type="text" name="sup_code" id="sup_code" class="text_field" readonly="readonly"/></td>
                </tr>
                <tr style="display:none" class="supplier">
                    <td>Nama Supplier</td>
                    <td>: <input class="ac_input text_field" type="text" id="sup_name"   value="" />
                        <span class="button"><input type="submit" name="submit_rekap_mutasi" value="Display" class="button"/></span>
                    </td>
                </tr>
            </table>
            <?php } ?>
            <?php echo form_close() ?>
            <p><?php if(isset($err_msg)) echo $err_msg ?></p>
            <?php if(isset($row_data)) { ?>
            <div class="table-container">
            <p style="text-align:center;font-weight:bold;color:#000;">DAFTAR MUTASI MASUK<br /> <?php echo strtoupper($title) ?></p>            
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

    <div id="dialog-login-confirm" style="display: none" title="Otorisasi Supervisor">
        <p>PERHATIAN!!! Aksi ini akan menghapus data mutasi, data distribusi, data retur dan data barang</p>
        <table border="0">
            <tr>
                <td>Username</td>
                <td><input type="text" id="spv_user" /></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="password" id="spv_passwd" /></td>
            </tr>
        </table>
        <p style="color: red"><span id="login-error"></span></p>
    </div>

    <div class="cleaner"></div>

</div><!-- End Of Content area bottom -->
<?php include 'layouts/footer.php' ?>        