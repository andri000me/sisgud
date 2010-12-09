<?php require 'layouts/header.php';?>
<?php require 'layouts/menu.php'; ?>
<!-- start latest post -->
<div id="latest-post" class="wide-post">
	<h1 class="title"><a href="#">Stok Toko <?php if(isset($shop_name)) echo $shop_name?></a></h1>
	<div class="entry">
	<!-- tambah departemen -->
	<div id="box">
	<form method = "post" action = "<?php echo base_url().'index.php/toko/stok';?>">
	<table>
	<tr>
		<td>Nama Toko</td>
		<td>: <?php if(isset($list_toko)) echo $list_toko; ?> </td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td>Keyword</td> <td>:<input type="text" name="keywords" size="20"/></td>
		<td>In </td> <td>: <select name = "key">                   
				<option value="item_code">Kode Label</option>                     
				<option value="sup_code">Kode Supplier</option>
				<option value="item_name">Nama Barang</option>
	               </select></td>
		<td><input type = "submit" name = "submit_search_toko" value="Cari" style="width: 70px;" /></td>
	</tr>
	</form>
    </table>
	</div>
	<!-- table  departemen -->
	<table id="search" width = "95%" cellspacing = "7" cellpadding="4">
	<tr id ="head">
		<td width ="10%"> Kode Label</td>
		<td width ="30%"> Nama Barang </td>
		<td width ="5%"> Qty Awal </td>		
		<td width ="5%"> Qty Akhir </td>
		<td width ="10%"> Harga Jual </td>		
		<td width ="15%"> Supplier </td>		
	</tr>	
	<?php if(isset($tr)) echo $tr ?>
	<td colspan = "9"><br /><b>Keterangan</b> <br />1. Qty Awal : Jumlah total barang yang ada di toko sebelum ada penjualan<br /> 2. Qty Akhir : Jumlah total barang yang ada di toko setelah dikurangi jumlah penjualan</td>
	</table>

		
<!-- end latest post -->
<?php require 'layouts/footer.php'; ?>
