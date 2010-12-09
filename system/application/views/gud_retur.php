<?php require 'layouts/header.php';?>
<?php require 'layouts/menu.php'; ?>
<!-- start latest post -->
<div id="latest-post" class="wide-post">
	<h1 class="title"><a href="#">Stok Toko</a></h1>
	<div class="entry">
	<!-- tambah departemen -->
	<div id="box">
	<form method = "post" action = "<?php echo base_url().'index.php/gudang/retur';?>">
	<table>
	<tr>
		<td>Nama Toko</td>
		<td>: <?php if(isset($list_toko)) echo $list_toko; ?> </td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td>Keyword</td> <td>:<input type="text" name="keywords" size="20"/></td>
		<td>In </td> <td>: <select name = "key">                   
				<option value="item_code">Kode Label</option>
				<option value="item_name">Nama Barang</option>
	               </select></td>
		<td><input type = "submit" name = "submit_search_toko" value="Cari" style="width: 70px;" /></td>
	</tr>
	</form>
    </table>
	</div>
	<!-- table  departemen -->
	<form method="post" action = "<?php echo base_url().'index.php/gudang/retur';?>" >
		<?php if(isset($shop)) echo $shop ?>
		<table id="search" width = "95%" cellspacing = "7" cellpadding="4">
		<tr id ="head">
			<td width ="10%"> Kode Label</td>
			<td width ="30%"> Nama Barang </td>
			<td width ="5%"> Stok Gudang </td>		
			<td width ="5%"> Stok Toko </td>
			<td width ="15%"> Harga Modal </td>		
			<td width ="15%"> Harga Jual </td>		
			<td width ="10%"> Retur</td>		
		</tr>	
		<?php if(isset($tr)) echo $tr ?>
		<tr><td colspan = "9">&nbsp;&nbsp; <input type = "submit" name = "submit_simpan_retur" value = "Simpan" /></td></tr>
		<tr><td colspan = "9">&nbsp;&nbsp; <?php if(isset($notify)) { echo $notify; } else {echo '<b>Baris dengan jumlah retur dikosongkan akan diabaikan</b>';} ?></td></tr>
		</table>		
	</form>

		
<!-- end latest post -->
<?php require 'layouts/footer.php'; ?>
