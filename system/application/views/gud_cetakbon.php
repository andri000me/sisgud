<?php require 'layouts/header.php';?>
<?php require 'layouts/menu.php'; ?>
<!-- start latest post -->
<div id="latest-post" class="wide-post">
	<h1 class="title"><a href="#">Cetak BON</a></h1>
	<div class="entry">
	<!-- tambah departemen -->
	<div id="tampilan">	
	<!-- kolom supplier -->
	<form action="<?php echo base_url()."index.php/gudang/cetak/bon"?>" method="POST">
	<table cellspacing = "10">
	<tr>
		<td>Nama Toko</td> 
		<td>: <?php echo $list_toko_bon; ?></td>
        <td><input type="submit" name="submit_print_bon" value="Print BON"/>&nbsp; <?php if(isset($form_notify_bon)) echo $form_notify_bon;?> </td>
	</tr>
	</table>
    </form>
    <form action="<?php echo base_url()."index.php/gudang/cetak/bon"?>" method="POST">
	<table cellspacing = "10">
	<tr>
		<td>Nama Toko</td> 
		<td>: <?php echo $list_toko_pdf; ?></td>
        <td><input type="submit" name="submit_toko" value="GO"/></td></td>
	</tr>
	</table>
    </form>
	<!-- table  departemen -->
    <p style="text-align: right; font-weight: bold;padding-right: 25px;">CETAK BON TOKO : <?php if(isset($shop_name))echo $shop_name;?></p>
	<table id="search" width = "95%" cellspacing = "10">
	<tr id ="head">
		<td width ="10%"> Kode Bon</td>
		<td width ="20%"> Total Jenis Barang (Macam)</td>
		<td width ="10%"> Tanggal </td>
        <td width="10%"> Action </td>	
	</tr>
    <?php if(isset($tr))echo $tr ?>    
    </table>
	<?php if(isset($pagination)) echo $pagination ?>
 
    <?php if(isset($form_notify)) echo $form_notify;?>
	</div>		
<!-- end latest post -->
<?php require 'layouts/footer.php'; ?>
