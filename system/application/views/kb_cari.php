<?php require 'layouts/header.php';?>
<?php require 'layouts/menu.php'; ?>
<!-- start latest post -->
<div id="latest-post" class="wide-post">
	<h1 class="title"><a href="#">Cari Departemen</a></h1>
	<div class="entry">
	<!-- search departemen -->
	<div id="bok">
	<form method = "post" action = "<?php echo base_url().'index.php/kategori/cari'?>">
	<table>
	    <tr>
		    <td>Keyword</td> <td>:<input type="text" name="keywords" size="20"/></td>
		    <td>In </td> <td>: <select name = "key"> 
                        <option value="all">Semua</option>
	                    <option value="cat_code">Kode</option>                     
	                    <option value="cat_name">Nama Barang</option>                
	               </select></td>
		    <td><input type = "submit" value = "Cari" name="submit_cari_kategori" style="width: 70px;" /></td>
	    </tr>
    </table>
    <p><?php if(isset($err_vald))echo $err_vald;?></p>
	</form>
    
	</div>
	<?php if(isset($list_cat)) echo $list_cat; ?>
		
<!-- end latest post -->
<?php require 'layouts/footer.php'; ?>