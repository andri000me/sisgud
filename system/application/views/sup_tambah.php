<?php require 'layouts/header.php';?>
<?php require 'layouts/menu.php'; ?>
<!-- start latest post -->
<div id="latest-post" class="wide-post">
	<h1 class="title"><a href="#">Tambah Supplier</a></h1>
	<div class="entry">
	    <!-- tambah departemen -->
	    <div id="kotak">
	    <form method = "post" action = "<?php echo base_url().'index.php/supplier/tambah'?>">
	    <table width ="500" cellspacing = "10">
            <tr>
		        <td width ="90">Kode Supplier</td> <td>: &nbsp;&nbsp;&nbsp;<input type="text" name="sup_code" class="text_field" maxlength="3"/></td>
	        </tr>
	        <tr>
		        <td width ="90">Nama Supplier</td> <td>: &nbsp;&nbsp;&nbsp;<input type="text" name="sup_name" class="text_field"/></td>
	        </tr>
            <tr>
		        <td width ="90">Alamat </td> <td>: &nbsp;&nbsp;&nbsp;<input type="text" name="sup_address" class="text_field"/></td>
	        </tr>
            <tr>
		        <td width ="90">Telepon </td> <td>: &nbsp;&nbsp;&nbsp;<input type="text" name="sup_phone" class="text_field"/></td>
	        </tr>
	        <tr >
		        <td colspan = "2"><input type = "submit" value = "OK" name="submit_tambah_supplier" />&nbsp;&nbsp; <input type = "reset" value = "Cancel" /></td>
	        </tr>
        </table>
        <?php if(isset($err_vald))echo $err_vald;?>
        <?php if(isset($notify)) echo $notify; ?>
	    </form>
    	</div>
		
<!-- end latest post -->
<?php require 'layouts/footer.php'; ?>
