<?php require 'layouts/header.php';?>
<?php require 'layouts/menu.php'; ?>
<!-- start latest post -->
<div id="latest-post" class="wide-post">
	<h1 class="title"><a href="#">Mutasi Masuk</a></h1>
	<div class="entry">
	<!-- tambah departemen -->
	<div id="tampilan">
	<form action="<?php echo base_url().'index.php/gudang/mutasi/masuk' ?>" method="post" name="mutasi_masuk">
	<!-- kolom supplier -->
	<table cellspacing = "10">
	<tr>
	    <td width = "100">Kode Supplier </td><td><input type="text" name="sup_code" id="sup_code" class="text_field" style="width:200px" readonly="yes"/> &nbsp; <?php if(isset($form_notify_supplier)) echo $form_notify_supplier ?></td>    
	</tr>
	<tr>
	    <td width = "100">Nama Supplier</td><td><input class="ac_input text_field" type="text" id="sup_name"  style="width:200px" value="" /></td>
	</tr>
    <tr>
	    <td width = "100">Tanggal BON</td> <td><input type="text" id="date_bon" name="date_bon" style="width:200px" value="" readonly="readonly"/></td>
	</tr>
	</table>
	<!-- table  mutasi masuk -->    
	<table id="search" width = "95%" cellspacing = "10">
        <tr id ="head">
            <td width = "5%"> No </td>
            <td width ="10%"> Kategori Barang </td>
            <td width ="40%"> Nama Barang </td>
            <td width ="15%"> Harga Modal </td>
            <td width ="5%"> QTY </td>
        </tr>
        <tr>
            <td><input type="text" name="item_num" size="3" value="1" readonly="yes"/></td>
            <td><input type="text" name="cat_code[]" size="12" maxlength="10"/></td>
            <td><input type="text" name="item_name[]" size="50"/></td>
            <td><input type="text" name="item_hp[]" size="20"/></td>
            <td><input type="text" name="item_qty[]" size="5"/></td>
        </tr>
        <tr>
            <td><input type="text" name="item_num" size="3" value="2" readonly="yes"/></td>
            <td><input type="text" name="cat_code[]" size="12" maxlength="10"/></td>
            <td><input type="text" name="item_name[]" size="50"/></td>
            <td><input type="text" name="item_hp[]" size="20"/></td>
            <td><input type="text" name="item_qty[]" size="5"/></td>
        </tr>        
        <tr>
            <td><input type="text" name="item_num" size="3" value="3" readonly="yes"/></td>
            <td><input type="text" name="cat_code[]" size="12" maxlength="10"/></td>
            <td><input type="text" name="item_name[]" size="50"/></td>
            <td><input type="text" name="item_hp[]" size="20"/></td>
            <td><input type="text" name="item_qty[]" size="5"/></td>
        </tr>
        <tr>
            <td><input type="text" name="item_num" size="3" value="4" readonly="yes"/></td>
            <td><input type="text" name="cat_code[]" size="12" maxlength="10"/></td>
            <td><input type="text" name="item_name[]" size="50"/></td>
            <td><input type="text" name="item_hp[]" size="20"/></td>
            <td><input type="text" name="item_qty[]" size="5"/></td>
        </tr>
        <tr>
            <td><input type="text" name="item_num" size="3" value="5" readonly="yes"/></td>
            <td><input type="text" name="cat_code[]" size="12" maxlength="10"/></td>
            <td><input type="text" name="item_name[]" size="50"/></td>
            <td><input type="text" name="item_hp[]" size="20"/></td>
            <td><input type="text" name="item_qty[]" size="5"/></td>
        </tr>
        <tr>
            <td><input type="text" name="item_num" size="3" value="6" readonly="yes"/></td>
            <td><input type="text" name="cat_code[]" size="12" maxlength="10"/></td>
            <td><input type="text" name="item_name[]" size="50"/></td>
            <td><input type="text" name="item_hp[]" size="20"/></td>
            <td><input type="text" name="item_qty[]" size="5"/></td>
        </tr>
        <tr>
            <td><input type="text" name="item_num" size="3" value="7" readonly="yes"/></td>
            <td><input type="text" name="cat_code[]" size="12" maxlength="10"/></td>
            <td><input type="text" name="item_name[]" size="50"/></td>
            <td><input type="text" name="item_hp[]" size="20"/></td>
            <td><input type="text" name="item_qty[]" size="5"/></td>
        </tr>
        <tr>
            <td><input type="text" name="item_num" size="3" value="8" readonly="yes"/></td>
            <td><input type="text" name="cat_code[]" size="12" maxlength="10"/></td>
            <td><input type="text" name="item_name[]" size="50"/></td>
            <td><input type="text" name="item_hp[]" size="20"/></td>
            <td><input type="text" name="item_qty[]" size="5"/></td>
        </tr>
        <tr>
            <td><input type="text" name="item_num" size="3" value="9" readonly="yes"/></td>
            <td><input type="text" name="cat_code[]" size="12" maxlength="10"/></td>
            <td><input type="text" name="item_name[]" size="50"/></td>
            <td><input type="text" name="item_hp[]" size="20"/></td>
            <td><input type="text" name="item_qty[]" size="5"/></td>
        </tr>
        <tr>
            <td><input type="text" name="item_num" size="3" value="10" readonly="yes"/></td>
            <td><input type="text" name="cat_code[]" size="12" maxlength="10"/></td>
            <td><input type="text" name="item_name[]" size="50"/></td>
            <td><input type="text" name="item_hp[]" size="20"/></td>
            <td><input type="text" name="item_qty[]" size="5"/></td>
        </tr>
        <tr>
            <td><input type="text" name="item_num" size="3" value="11" readonly="yes"/></td>
            <td><input type="text" name="cat_code[]" size="12" maxlength="10"/></td>
            <td><input type="text" name="item_name[]" size="50"/></td>
            <td><input type="text" name="item_hp[]" size="20"/></td>
            <td><input type="text" name="item_qty[]" size="5"/></td>
        </tr>
        <tr>
            <td><input type="text" name="item_num" size="3" value="12" readonly="yes"/></td>
            <td><input type="text" name="cat_code[]" size="12" maxlength="10"/></td>
            <td><input type="text" name="item_name[]" size="50"/></td>
            <td><input type="text" name="item_hp[]" size="20"/></td>
            <td><input type="text" name="item_qty[]" size="5"/></td>
        </tr>
        <tr>
            <td><input type="text" name="item_num" size="3" value="13" readonly="yes"/></td>
            <td><input type="text" name="cat_code[]" size="12" maxlength="10"/></td>
            <td><input type="text" name="item_name[]" size="50"/></td>
            <td><input type="text" name="item_hp[]" size="20"/></td>
            <td><input type="text" name="item_qty[]" size="5"/></td>
        </tr>
        <tr>
            <td><input type="text" name="item_num" size="3" value="14" readonly="yes"/></td>
            <td><input type="text" name="cat_code[]" size="12" maxlength="10"/></td>
            <td><input type="text" name="item_name[]" size="50"/></td>
            <td><input type="text" name="item_hp[]" size="20"/></td>
            <td><input type="text" name="item_qty[]" size="5"/></td>
        </tr>
        <tr>
            <td><input type="text" name="item_num" size="3" value="15" readonly="yes"/></td>
            <td><input type="text" name="cat_code[]" size="12" maxlength="10"/></td>
            <td><input type="text" name="item_name[]" size="50"/></td>
            <td><input type="text" name="item_hp[]" size="20"/></td>
            <td><input type="text" name="item_qty[]" size="5"/></td>
        </tr>	
    </table>
    <table id="button_holder">
        <tr>
        <td><input type = "submit" name="submit_mutasi_masuk" value = "Simpan"  />&nbsp;&nbsp; 
        <input type="button" onclick="appendRow()" value="Tambah Baris" />&nbsp;&nbsp;
        <input type = "submit" value = "Batal" /></td>
        </tr>
    </table>
    <p style="text-align:center">
        <?php
            if($this->session->userdata('form_notify'))
            {
                echo $this->session->userdata('form_notify');
                $this->session->unset_userdata('form_notify');
            }
        ?>
    </p>    
    </form>	
</div>
<!-- end latest post -->
<?php require 'layouts/footer.php'; ?>
