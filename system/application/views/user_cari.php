<?php require 'layouts/header.php';?>
<?php require 'layouts/menu.php'; ?><!-- start latest post -->
<div id="latest-post" class="wide-post">
	<h1 class="title"><a href="#">Daftar User</a></h1>
	<div class="entry">
	<!-- search supplier -->
	<div id="bok" >
	<form method = "post" action = "<?php echo base_url().'index.php/user/lihat'?>">
		<table>
		<tr>
			<td>Keyword</td> <td>:<input type="text" name="keywords" size="20" class="text_field"/></td>
			<td>In </td> <td>: <select name = "key">
				<option value="all">Semua</option>        
				    <option value="p_username">Username</option>                     
				    <option value="op_name">Nama </option>                  
			       </select></td>
			<td><input type = "submit" name="sup_search" value = "Cari" style="width: 70px;" /></td>
		</tr>	
		</table>
	</form>
	</div>
	<div id="table-container">
		<?php if(isset($list_user)) echo $list_user ?>
	</div>
   
	
<!-- end latest post -->
<?php require 'layouts/footer.php'; ?>