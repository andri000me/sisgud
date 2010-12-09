<?php require 'layouts/header.php';?>
<?php require 'layouts/menu.php'; ?>
<!-- start latest post -->
<div id="latest-post" class="wide-post">
	<h1 class="title"><a href="#">Ubah Supplier</a></h1>
	<div class="entry">
	    <!-- tambah departemen -->
	    <div id="kotak">
	    <form method = "post" action = "<?php echo current_url() ?>">
	    <table width ="500" cellspacing = "10">
            <tr>
		        <td width ="90">Kode Supplier</td> <td>: &nbsp;&nbsp;&nbsp;<input type="text" <?php if(isset($readonly)) echo ' readonly="readonly" ' ?> value="<?php if(isset($sup_code)) echo $sup_code?>" name="sup_code" class="text_field" maxlength="3"/></td>
	        </tr>
	        <tr>
		        <td width ="90">Nama Supplier</td> <td>: &nbsp;&nbsp;&nbsp;<input type="text" <?php if(isset($readonly)) echo ' readonly="readonly" ' ?>  value="<?php if(isset($sup_name)) echo $sup_name?>" name="sup_name" class="text_field"/></td>
	        </tr>
            <tr>
		        <td width ="90">Alamat </td> <td>: &nbsp;&nbsp;&nbsp;<input type="text" <?php if(isset($readonly)) echo ' readonly="readonly" ' ?>  value="<?php if(isset($sup_address)) echo $sup_address?>" name="sup_address" class="text_field"/></td>
	        </tr>
            <tr>
		        <td width ="90">Telepon </td> <td>: &nbsp;&nbsp;&nbsp;<input type="text" <?php if(isset($readonly)) echo ' readonly="readonly" ' ?>  value="<?php if(isset($sup_phone)) echo $sup_phone?>" name="sup_phone" class="text_field"/></td>
	        </tr>
	        <tr >
		        <td colspan = "2"><input type = "submit" value = "OK" name="submit_ubah_supplier" />&nbsp;&nbsp; <input type = "reset" value = "Cancel" /></td>
	        </tr>
        </table>
        <?php if(isset($err_vald))echo $err_vald;?>
        <?php if(isset($notify)) echo $notify; ?>
	    </form>
    	</div>
		
<!-- end latest post -->
<?php require 'layouts/footer.php'; ?>
