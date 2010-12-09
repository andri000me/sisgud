<?php require 'layouts/header.php';?>
<?php require 'layouts/menu.php'; ?>
<!-- start latest post -->
<div id="latest-post" class="wide-post">
	<h1 class="title"><a href="#">Cetak Label</a></h1>
	<div class="entry">
	<!-- tambah departemen -->
	<div id="tampilan">
	<form method = "post" action = "">
	<!-- kolom supplier -->
    <form action="<?php echo base_url().'index.php/gudang/cetak/label'?>" method="POST">
	<table cellspacing = "10" style="text-align:left">
	<tr>
		<td >Nama Supplier</td> 
		<td >: <?php if(isset($list_supp)) {echo $list_supp;} else {echo '<select><option>Tidak Ada</option></select>';} ?> <input type="submit" name="submit_label_supplier" value="GO"/></td>        
	</tr>
	</table>
	<!-- table  departemen -->
    <p style="text-align: right; font-weight: bold;padding-right: 25px;">CETAK LABEL SUPPLIER : <?php if(isset($sup_name))echo strtoupper($sup_name);?></p>
	<table id="search" width = "95%" cellspacing = "10">
	<tr id ="head">
		<td width ="10%"> Kode Label</td>
		<td width ="40%"> Nama Barang </td>
		<td width ="5%"> Kode Supplier </td> 
		<td width ="10%"> Harga Jual </td>
		<td width ="5%"> Qty </td>	
        <td width ="5%"> Print Label </td>        
	</tr>
    <?php if(isset($tr))echo $tr;?>
	<tr >
		<td colspan = "6">&nbsp;&nbsp;<?php if(isset($print_button)) echo $print_button ?></td>
        
	</tr>
    <tr>
        <td colspan="6"><?php if(isset($file_name))echo '<a href="'.base_url().$file_name.'">Simpan File</a>';?></td>
    </tr>
	</table>
    
    </form>
    
	</div>		
<!-- end latest post -->
<?php require 'layouts/footer.php'; ?>
