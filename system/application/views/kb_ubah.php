<?php require 'layouts/header.php';?>
<?php require 'layouts/menu.php'; ?>
<!-- start latest post -->
<div id="latest-post" class="wide-post">
	<h1 class="title"><a href="#">Ubah Kelompok Barang</a></h1>
	<div class="entry">
        <!-- tambah departemen -->
        <div id="kotak">
        <form method = "post" action = "<?php echo current_url() ?>">
        <table width ="500"  cellspacing = "10" >
            <tr>
                <td width ="30%">Kode Kelompok Barang</td> <td >:&nbsp;&nbsp;&nbsp;<input type="text" name="cat_code" class="text_field" maxlength="2" <?php if(isset($readonly)) echo ' readonly="readonly" '?> value="<?php if(isset($cat_code)) echo $cat_code?>"/></td>
            </tr>
            <tr>
                <td width ="30%">Nama Kelompok Barang</td> <td >:&nbsp;&nbsp;&nbsp;<input type="text" name="cat_name" class="text_field" <?php if(isset($readonly)) echo ' readonly="readonly" '?> value="<?php if(isset($cat_name)) echo $cat_name?>"/></td>
            </tr>
            <tr >
                <td colspan ="2"> <input type = "submit" value = "OK" name="submit_ubah_kategori" />&nbsp;&nbsp;&nbsp;<input type = "reset" value = "Cancel" /></td>
            </tr>	
        </table>
        <?php if(isset($err_vald))echo $err_vald;?>
        <?php if(isset($notify)) echo $notify; ?>
        </form>
        </div>	
		
<!-- end latest post -->
<?php require 'layouts/footer.php'; ?>