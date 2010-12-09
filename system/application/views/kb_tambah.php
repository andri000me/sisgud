<?php require 'layouts/header.php';?>
<?php require 'layouts/menu.php'; ?>
<!-- start latest post -->
<div id="latest-post" class="wide-post">
	<h1 class="title"><a href="#">Tambah Kelompok Barang</a></h1>
	<div class="entry">
        <!-- tambah departemen -->
        <div id="kotak">
        <form method = "post" action = "<?php echo base_url().'index.php/kategori/tambah'?>">
        <table width ="500"  cellspacing = "10" >
            <tr>
                <td width ="30%">Kode Kelompok Barang</td> <td >:&nbsp;&nbsp;&nbsp;<input type="text" name="cat_code" class="text_field" maxlength="2"/></td>
            </tr>
            <tr>
                <td width ="30%">Nama Kelompok Barang</td> <td >:&nbsp;&nbsp;&nbsp;<input type="text" name="cat_name" class="text_field"/></td>
            </tr>
            <tr >
                <td colspan ="2"> <input type = "submit" value = "OK" name="submit_tambah_kategori" />&nbsp;&nbsp;&nbsp;<input type = "reset" value = "Cancel" /></td>
            </tr>	
        </table>
        <?php if(isset($err_vald))echo $err_vald;?>
        <?php if(isset($notify)) echo $notify; ?>
        </form>
        </div>	
		
<!-- end latest post -->
<?php require 'layouts/footer.php'; ?>