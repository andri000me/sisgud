<?php require 'layouts/header.php';?>
<?php require 'layouts/menu.php'; ?>
<!-- start latest post -->
<div id="latest-post" class="wide-post">
	<h1 class="title"><a href="#">Mutasi Keluar</a></h1>
	<div class="entry">
	<!-- tambah departemen -->
	<div id="tampilan" style="width: 900px; overflow: auto;">
	<form method = "post" action = "">
	<!-- table  departemen -->
	<br />
	<table id="search" width = "900" cellspacing = "5" >
        <tr id ="head">
            <td rowspan = "2" width ="10%"> Kode Label</td>
            <td rowspan = "2" width ="10%">   Nama Barang </td>
            <td rowspan = "2" width ="10%"> Qty Awal</td>
            <td colspan = "<?php echo $jmlh_toko; ?>" >  Distribusi Toko</td>
            <td rowspan = "2" width ="10%"> Qty Akhir </td>
            <!--<td rowspan = "2" width ="150"> Harga Beli Satuan </td>-->
            <td rowspan = "2" width ="10%"> Harga Jual </td>
        </tr>
        <?php echo $shop_initial; ?>
        <?php echo $tr ?>
    </table>
    <table id="button_holder">
	<tr >
		<td >
        <input type = "submit" name="submit_mutasi_keluar" value = "OK"  />&nbsp;&nbsp; 
        <!--<input type="button" value="Tambah Baris" onClick="<?php //echo $append; ?>" />-->
        <input type = "submit" value = "Cancel" /></td>
	</tr>
	</table>
    <?php if(isset($form_notify)) echo $form_notify; ?>
	</div>		
<!-- end latest post -->
<?php require 'layouts/footer.php'; ?>