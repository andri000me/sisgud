<?php require 'layouts/header.php';?>
<?php require 'layouts/menu.php'; ?>
<!-- start latest post -->
<div id="latest-post" class="wide-post">
	<h1 class="title"><a href="#">Tambah User</a></h1>
	<div class="entry">
        <!-- tambah departemen -->
        <div id="kotak">
        <form method = "post" action = "<?php echo base_url().'index.php/user/tambah'?>">
        <table width ="500"  cellspacing = "10" >
            <tr>
                <td width ="30%">Nama </td> <td >:&nbsp;&nbsp;&nbsp;<input type="text" name="nama" class="text_field" /></td>
            </tr>
            <tr>
                <td width ="30%">Username</td> <td >:&nbsp;&nbsp;&nbsp;<input type="text" name="username" class="text_field" /></td>
            </tr>
            <tr>
                <td width ="30%">Password</td> <td >:&nbsp;&nbsp;&nbsp;<input type="password" name="password" class="text_field" /></td>
            </tr>	   
            <tr>
                <td width ="30%">Konfirmasi Password</td> <td >:&nbsp;&nbsp;&nbsp;<input type="password" name="konfirmasi_password" class="text_field"/></td>
            </tr>
	     <?php if($this->session->userdata('p_role')=='admin') { ?>
	    <tr>
                <td width ="30%">Role</td> <td >:&nbsp;&nbsp;
		<select name="role" style="width:150px">
			<option value="admin">Administrator</option>
			<option value="supervisor">Supervisor</option>
			<option value="operator" selected="selected">Operator</option>
			<option value="user">User</option></select>
		</select>
		</td>
            </tr>
	    <?php } ?>
            <tr>
                <td width ="30%">Alamat</td> <td >:&nbsp;&nbsp;&nbsp;<input type="text" name="alamat" class="text_field"/></td>
            </tr>            
	    <tr>
                <td width ="30%">Telepon</td> <td >:&nbsp;&nbsp;&nbsp;<input type="text" name="telepon" class="text_field"/></td>
            </tr>
            <tr >
                <td colspan ="2"> <input type = "submit" value = "Simpan" name="submit_tambah_user" />&nbsp;&nbsp;&nbsp;<input type = "reset" value = "Cancel" /></td>
            </tr>	
        </table>
        <?php if(isset($err_vald))echo $err_vald;?>
        <?php if(isset($notify)) echo $notify; ?>
        </form>
        </div>	
		
<!-- end latest post -->
<?php require 'layouts/footer.php'; ?>