<?php $role = $this->session->userdata('p_role') ?>
<div id="menu" class="menu">
	
	<ul>
		<?php if($role == 'supervisor') { ?>
		<li><a href="<?php echo base_url().'index.php/user' ?>" class="<?php if($pages=='user'): echo 'current_page_item'; endif; ?>" >MANAJEMEN<!--[if IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
		<ul>
			<li><a href="<?php echo base_url().'index.php/user/tambah' ?>" class="drop">Tambah User</a></li>
			<li><a href="<?php echo base_url().'index.php/user/lihat' ?>" class="drop" >Daftar User</a></li>			
		</ul>
		<?php } ?>
		
		<?php if($role == 'admin') { ?>
		<li><a href="<?php echo base_url().'index.php/user' ?>" class="<?php if($pages=='user'): echo 'current_page_item'; endif; ?>" >MANAJEMEN<!--[if IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
		<ul>
			<li><a href="<?php echo base_url().'index.php/user/tambah' ?>" class="drop">Tambah User</a></li>
			<li><a href="<?php echo base_url().'index.php/user/lihat' ?>" class="drop" >Daftar User</a></li>			
		</ul>
		<?php } ?>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
		</li>
		<li><a href="<?php echo base_url().'index.php/gudang' ?>" class="<?php if($pages=='gudang'): echo 'current_page_item'; endif; ?>">G U D A N G<!--[if IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
		<ul>
			<?php if($role=='supervisor') { ?>			
			<li><a href="<?php echo base_url().'index.php/gudang/stok'?>" class="drop">Stok Gudang</a></li>
			<?php } if($role=='operator') { ?>
			<li><a href="<?php echo base_url().'index.php/gudang/mutasi/masuk'?>" class="drop">Mutasi Masuk</a></li>
			<li><a href="<?php echo base_url().'index.php/gudang/mutasi/print'?>" class="drop">Print Mutasi</a></li>
            <li><a href="<?php echo base_url().'index.php/gudang/mutasi/rekap'?>" class="drop">Rekap Mutasi</a></li>
			<li><a href="<?php echo base_url().'index.php/gudang/retur'?>" class="drop">Retur Barang</a></li>
			<li><a href="<?php echo base_url().'index.php/gudang/stok'?>" class="drop">Stok Gudang</a></li>
			<?php } if($role == 'user') { ?>
			<li><a href="<?php echo base_url().'index.php/gudang/mutasi/keluar'?>" class="drop">Mutasi Keluar</a></li>
			<li><a href="<?php echo base_url().'index.php/gudang/cetak/label'?>" class="drop">Cetak Label</a></li>
			<li><a href="<?php echo base_url().'index.php/gudang/cetak/bon'?>" class="drop">Cetak Bon</a></li>
			<li><a href="<?php echo base_url().'index.php/gudang/export'?>" class="drop">Export Data</a></li>
			<li><a href="<?php echo base_url().'index.php/gudang/stok'?>" class="drop">Stok Gudang</a></li>
			<?php } if($role == 'admin') { ?>
			<li><a href="<?php echo base_url().'index.php/gudang/mutasi/keluar'?>" class="drop">Mutasi Keluar</a></li>
			<li><a href="<?php echo base_url().'index.php/gudang/mutasi/masuk'?>" class="drop">Mutasi Masuk</a></li>
			<li><a href="<?php echo base_url().'index.php/gudang/retur'?>" class="drop">Retur Barang</a></li>
			<li><a href="<?php echo base_url().'index.php/gudang/mutasi/print'?>" class="drop">Print Mutasi</a></li>			
			<li><a href="<?php echo base_url().'index.php/gudang/cetak/label'?>" class="drop">Cetak Label</a></li>
			<li><a href="<?php echo base_url().'index.php/gudang/cetak/bon'?>" class="drop">Cetak Bon</a></li>
			<li><a href="<?php echo base_url().'index.php/gudang/export'?>" class="drop">Export Data</a></li>
			<li><a href="<?php echo base_url().'index.php/gudang/stok'?>" class="drop">Stok Gudang</a></li>
			<?php } ?>
		</ul>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
		</li>
		<?php if($role == 'operator' || $role=='admin') { ?>
		<li><a href="<?php echo base_url().'index.php/kategori' ?>" class="<?php if($pages=='kategori'): echo 'current_page_item'; endif; ?>">KELOMPOK BARANG<!--[if IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
		<ul>
			<li><a href="<?php echo base_url().'index.php/kategori/tambah' ?>" class="drop" style="width:170px;margin:0px;text-align:center;">Tambah Kelompok</a></li>
			<li><a href="<?php echo base_url().'index.php/kategori/cari' ?>" class="drop" style="width:170px;margin:0px;text-align:center;">Cari Kelompok</a></li>			
		</ul>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->		
		</li>
		<li><a href="<?php echo base_url().'index.php/supplier' ?>" class="<?php if($pages=='supplier'): echo 'current_page_item'; endif; ?>" >SUPPLIER<!--[if IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
		<ul>
			<li><a href="<?php echo base_url().'index.php/supplier/tambah' ?>" class="drop" style="width:100px;margin:0px;text-align:center;"><span style="font-size:12px;">Tambah Supplier</span></a></li>
			<li><a href="<?php echo base_url().'index.php/supplier/cari' ?>" class="drop" style="width:100px;margin:0px;text-align:center;">Cari Supplier</a></li>			
		</ul>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
		</li>
		<?php }?>
		<li><a href="<?php echo base_url().'index.php/toko' ?>" class="<?php if($pages=='toko'): echo 'current_page_item'; endif; ?>" >TOKO<!--[if IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
		<ul>
			<?php if($role=='supervisor' || $role=='admin') {?>
			<li><a href="<?php echo base_url().'index.php/toko/tambah' ?>" class="drop">Tambah Toko</a></li>
			<li><a href="<?php echo base_url().'index.php/toko/ubah' ?>" class="drop">Ubah Toko</a></li>
			<?php }?>
			<li><a href="<?php echo base_url().'index.php/toko/stok' ?>" class="drop">Stok Toko</a></li>			
			<li><a href="<?php echo base_url().'index.php/toko/detail' ?>" class="drop">Detail Toko</a></li>			
		</ul>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
		</li>
		<?php if($role !='admin') { ?>
		<li><a href="<?php echo base_url().'index.php/help' ?>" class="<?php if($pages=='help'): echo 'current_page_item'; endif; ?>" >HELP<!--[if IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
		<ul>
			<li><a href="<?php echo base_url().'index.php/help/faq' ?>" class="drop">Petunjuk</a></li>
			<li><a href="<?php echo base_url().'index.php/help/about' ?>" class="drop" >About</a></li>			
		</ul>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
		</li>
		<?php }?>
	</ul>
</div>
<!-- end header -->
<hr />