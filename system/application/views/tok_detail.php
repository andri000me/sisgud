<?php require 'layouts/header.php';?>
<?php require 'layouts/menu.php'; ?>
<!-- start latest post -->
<div id="latest-post" class="wide-post">
	<h1 class="title"><a href="#">Detail Toko</a></h1>
	<div class="entry">
	<!-- tambah departemen -->	
	<div class="table-container">
		<?php echo form_open('toko/detail')?>
			<?php echo $list_toko ?> <input type="submit" value="GO" name="submit_detail_toko" />
		</form>
		<?php if(isset($_POST['submit_detail_toko'])) : ?>
			<p id="img"><img src="<?php echo base_url()?>css/images/toko/nn.jpg" alt="foto toko" width="146" height="196"/></p>
			<h2><?php echo $shop_name ?></h2>
			<p id="alamat"><?php echo $alamat1 ?>&nbsp;<br /> <?php echo $alamat2 ?>&nbsp;</p>
			<p id="info">
			<?php echo $shop_code?> (Kode Toko) <br />
			<?php echo $shop_initial?> (Inisial Toko) <br />
			<?php echo $shop_supervisor?> (Supervisor) <br />
			Telp. <?php echo $shop_phone?> <br />
			</p>
		<?php endif; ?>
	</div>
	
		
<!-- end latest post -->
<?php require 'layouts/footer.php'; ?>
