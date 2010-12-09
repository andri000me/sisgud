<?php require 'layouts/header.php';?>
<?php require 'layouts/menu.php'; ?>
<!-- start latest post -->
<div id="latest-post" class="wide-post">
	<h1 class="title"><a href="#">Print Mutasi</a></h1>
	<div class="entry">
	<!-- tambah departemen -->
	<div id="tampilan">
	<form method = "post" action = "">
	<!-- kolom supplier -->
    <form action="<?php echo base_url().'index.php/gudang/mutasi/print'?>" method="POST">    
    
	<table cellspacing = "10" style="text-align:left">
	<tr>
		<td >Nama Supplier</td> 
		<td >: <?php if(isset($list_sup)) {echo $list_sup.'<input type="submit" name="submit_print_mutasi" value="GO"/>';} else {echo '<select><option>Tidak Ada</option></select>';} ?> </td>        
	</tr>
	</table>    
	<!-- table  departemen -->   
    </form>
    
	</div>		
<!-- end latest post -->
<?php require 'layouts/footer.php'; ?>
