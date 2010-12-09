<?php require 'layouts/header.php';?>
<?php require 'layouts/menu.php'; ?>
<!-- start latest post -->
<div id="latest-post" class="wide-post">
	<h1 class="title"><a href="#">Ubah Informasi Toko</a></h1>
	<div class="entry">
        <!-- tambah departemen -->	
        <div id="kotak">
	<form method = "post" action = "<?php echo base_url().'index.php/toko/ubah';?>">
	<table>
	<tr>
		<td>Nama Toko</td>
		<td>: <?php if(isset($list_toko)) echo $list_toko; ?> </td>
		<td><input type = "submit" name = "submit_search_toko" value="GO" /></td>
	</tr>
	</form>
        <form method = "post" action = "<?php echo current_url()?>">
	<h3 style="text-align:right"><?php if(isset($shop_name)) echo strtoupper($shop_name)?></h3>
        <table width ="500"  cellspacing = "10" >
            <tr>
                <td width ="30%">Kode Toko</td> <td >:&nbsp;&nbsp;&nbsp;<input type="text" readonly="readonly" name="shop_code" class="text_field" maxlength="2" value="<?php if(isset($shop_code)) echo $shop_code?>" /></td>
            </tr>
            <tr>
                <td width ="30%">Nama Toko</td> <td >:&nbsp;&nbsp;&nbsp;<input type="text" readonly="readonly" name="shop_name" class="text_field" value="<?php if(isset($shop_name)) echo $shop_name?>" /></td>
            </tr>
            <tr>
                <td width ="30%">Inisial Toko</td> <td >:&nbsp;&nbsp;&nbsp;<input type="text" readonly="readonly" name="shop_initial" class="text_field" value="<?php if(isset($shop_initial)) echo $shop_initial?>" /></td>
            </tr>
            <tr>
                <td width ="30%">Alamat Toko</td> <td >:&nbsp;&nbsp;&nbsp;<input type="text" name="shop_address" class="text_field" value="<?php if(isset($shop_address)) echo $shop_address?>"/></td>
            </tr>
            <tr>
                <td width ="30%">No Telepon</td> <td >:&nbsp;&nbsp;&nbsp;<input type="text" name="shop_phone" class="text_field" value="<?php if(isset($shop_phone)) echo $shop_phone?>"/></td>
            </tr>
            <tr>
                <td width ="30%">Supervisor</td> <td >:&nbsp;&nbsp;&nbsp;<input type="text" name="shop_supervisor" class="text_field" value="<?php if(isset($shop_supervisor)) echo $shop_supervisor?>"/></td>
            </tr>
            <tr >
                <td colspan ="2"> <input type = "submit" value = "Simpan" name="submit_ubah_toko" />&nbsp;&nbsp;&nbsp;<input type = "reset" value = "Cancel" /></td>
            </tr>	
        </table>
        <?php if(isset($err_vald))echo $err_vald;?>
        <?php if(isset($notify)) echo $notify; ?>
        </form>
        </div>	
		
<!-- end latest post -->
<?php require 'layouts/footer.php'; ?>