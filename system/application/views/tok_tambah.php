<?php require 'layouts/header.php';?>
<?php require 'layouts/menu.php'; ?>
<!-- start latest post -->
<div id="latest-post" class="wide-post">
	<h1 class="title"><a href="#">Tambah Toko</a></h1>
	<div class="entry">
        <!-- tambah departemen -->
        <div id="kotak">
        <form method = "post" action = "<?php echo base_url().'index.php/toko/tambah'?>">
        <table width ="500"  cellspacing = "10" >
            <tr>
                <td width ="30%">Kode Toko</td> <td >:&nbsp;&nbsp;&nbsp;<input type="text" name="shop_code" class="text_field" maxlength="2"/></td>
            </tr>
            <tr>
                <td width ="30%">Nama Toko</td> <td >:&nbsp;&nbsp;&nbsp;<input type="text" name="shop_name" class="text_field" /></td>
            </tr>
            <tr>
                <td width ="30%">Inisial Toko</td> <td >:&nbsp;&nbsp;&nbsp;<input type="text" name="shop_initial" class="text_field" /></td>
            </tr>
            <tr>
                <td width ="30%">Alamat Toko</td> <td >:&nbsp;&nbsp;&nbsp;<input type="text" name="shop_address" class="text_field"/></td>
            </tr>
            <tr>
                <td width ="30%">No Telepon</td> <td >:&nbsp;&nbsp;&nbsp;<input type="text" name="shop_phone" class="text_field"/></td>
            </tr>
            <tr>
                <td width ="30%">Supervisor</td> <td >:&nbsp;&nbsp;&nbsp;<input type="text" name="shop_supervisor" class="text_field"/></td>
            </tr>
            <tr >
                <td colspan ="2"> <input type = "submit" value = "OK" name="submit_tambah_toko" />&nbsp;&nbsp;&nbsp;<input type = "reset" value = "Cancel" /></td>
            </tr>	
        </table>
        <?php if(isset($err_vald))echo $err_vald;?>
        <?php if(isset($notify)) echo $notify; ?>
        </form>
        </div>	
		
<!-- end latest post -->
<?php require 'layouts/footer.php'; ?>