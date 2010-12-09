<?php require 'layouts/header.php';?>
<?php require 'layouts/menu.php'; ?>
<!-- start latest post -->
<div id="latest-post" class="wide-post">
	<h1 class="title"><a href="#">Stock Gudang</a></h1>
	<div class="entry">
	<!-- tambah departemen -->
	<div id="box">
	<form method = "post" action = "<?php echo base_url().'index.php/gudang/stok'; ?>">
	<table>
	<tr>
		<td>Keyword</td> <td>:<input type="text" name="keywords" size="20"/></td>
		<td>In </td> <td>: <select name = "key">                   
				<option value="item_code">Kode Label</option>                     
				<option value="item_name">Nama Barang</option>
				<option value="sup_name">Supplier</option>				
	               </select></td>
		<td><input type = "submit" name="submit_stock_search" value = "Cari" style="width: 70px;" /></td>
	</tr>
	</form>
</table>
	</div>
	<!-- table  departemen -->
	<table id="search" width = "95%" cellspacing = "7">
	<tr id ="head">
		<td width ="10%"> Kode Label</td>
		<td width ="30%"> Nama Barang </td>
		<td width ="5%"> Qty Awal </td>		
		<td width ="5%"> Qty Akhir </td>
		<td width ="20%"> Harga Modal (Rp.) </td>
		<td width ="5%"> Kelompok Barang </td>
		<td width ="5%"> Supplier </td>
		<td width ="10%"> Operator </td>
	</tr>
	<?php if(isset($list_item)) echo $list_item; ?>
	<tr><td colspan = "9">&nbsp;&nbsp; <input type = "submit" value = "Close" /></td></tr>
	</table>		
<!-- end latest post -->
<?php require 'layouts/footer.php'; ?>
