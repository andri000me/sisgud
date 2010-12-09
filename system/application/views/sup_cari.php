<?php require 'layouts/header.php';?>
<?php require 'layouts/menu.php'; ?><!-- start latest post -->
<div id="latest-post" class="wide-post">
	<h1 class="title"><a href="#">Cari Supplier</a></h1>
	<div class="entry">
	<!-- search supplier -->
	<div id="bok">
	<form method = "post" action = "<?php echo base_url().'index.php/supplier/cari'?>">
	<table>
	<tr>
		<td>Keyword</td> <td>:<input type="text" name="keywords" size="20" class="text_field"/></td>
		<td>In </td> <td>: <select name = "key">
                        <option value="all">Semua</option>        
	                    <option value="sup_code">Kode</option>                     
	                    <option value="sup_name">Nama Supplier</option>                  
	               </select></td>
		<td><input type = "submit" name="sup_search" value = "Cari" style="width: 70px;" /></td>
	</tr>	
    </table>
    </form>
	</div>
	<!-- table  supplier -->
    <?php if(isset($list_sup)) echo $list_sup ?>
	
<!-- end latest post -->
<?php require 'layouts/footer.php'; ?>