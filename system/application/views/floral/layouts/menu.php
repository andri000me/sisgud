<?php $role = $this->session->userdata('p_role'); ?>
<div class="nav">
        <div class="table">                
        <?php if($role == 'admin') { ?>
        <ul class="select"><li><a href="#"><b>Pengguna</b></a>
        <div class="select_sub">
            <ul class="sub">
                <li><a href="<?php echo base_url() ?>user/tambah">Tambah Pengguna</a></li>                        
                <li><a href="<?php echo base_url() ?>user/manage">Manajemen Pengguna</a></li>                                         
            </ul>
        </div>
        </li>
        </ul>
        <?php } if($role == 'operator' || $role == 'user') { ?>
        <ul class="select"><li><a href="#"><b>Gudang</b></a>
        <div class="select_sub">
            <?php if($role == 'operator') { ?>
            <ul class="sub">
                <li><a href="<?php echo base_url() ?>gudang/mutasi/masuk">Mutasi Masuk</a></li>                             
                <li><a href="<?php echo base_url() ?>gudang/retur/tambah">Retur Barang</a></li>                              
            </ul>
            <?php } if($role == 'user') { ?>
            <ul class="sub">
                <li><a href="<?php echo base_url() ?>gudang/mutasi/keluar">Mutasi Keluar</a></li>                             
                <li><a href="<?php echo base_url() ?>gudang/export">Ekspor Data</a></li>                              
            </ul>
            <?php } ?>
        </div>
        </li>
        </ul>
        <?php } if($role == 'operator') { ?>
        <ul class="select"><li><a href="#"><b>Kelompok Barang</b></a>
        <div class="select_sub">
            <ul class="sub">
                <li><a href="<?php echo base_url() ?>kategori/tambah">Tambah Kelompok Barang</a></li>
                <li><a href="<?php echo base_url() ?>kategori/cari">Cari Kelompok Barang</a></li>                        
            </ul>
        </div>
        </li>
        </ul>
        <?php } if($role == 'operator') { ?>
        <ul class="select"><li><a href="#"><b>Supplier</b></a>
        <div class="select_sub">
            <ul class="sub">
                <li><a href="<?php echo base_url() ?>suppliers/tambah">Tambah Supplier</a></li>
                <li><a href="<?php echo base_url() ?>suppliers/cari">Cari Supplier</a></li>                        
            </ul>
        </div>
        </li>
        </ul>
        <?php } if($role != 'admin') { ?>
        <ul class="select"><li><a href="#"><b>Laporan</b></a>
        <div class="select_sub">
            <?php if($role=='operator') { ?>
            <ul class="sub">
                <li><a href="<?php echo base_url() ?>gudang/mutasi/print">Cetak Mutasi Keluar</a></li>
                <li><a href="<?php echo base_url() ?>gudang/mutasi/rekap">Rekap Mutasi Keluar</a></li>                
                <li><a href="<?php echo base_url() ?>gudang/retur/rekap">Rekap Retur</a></li>                
                <li><a href="<?php echo base_url() ?>gudang/stok">Stok Gudang</a></li>
            </ul>
            <?php } if($role=='user') { ?>
            <ul class="sub">
                <li><a href="<?php echo base_url() ?>gudang/cetak/label">Cetak Label</a></li>
                <li><a href="<?php echo base_url() ?>gudang/cetak/bon">Cetak Bon</a></li>                
                <li><a href="<?php echo base_url() ?>gudang/cetak/bon/rekap">Rekap Bon</a></li>                
                <li><a href="<?php echo base_url() ?>gudang/stok">Stok Gudang</a></li>
            </ul>
            <?php } if($role=='supervisor') { ?>
            <ul class="sub">                              
                <li><a href="<?php echo base_url() ?>gudang/stok">Stok Gudang</a></li>
                <li><a href="<?php echo base_url() ?>gudang/sisa">Cetak Mutasi Sisa</a></li>
            </ul>
            <?php } ?>
        </div>
        </li>
        </ul>
        <?php } if($role != 'admin') { ?>
        <ul class="select"><li><a href="#"><b>Toko</b></a>
        <div class="select_sub">
            <?php if($role == 'operator' || $role == 'user') { ?>
            <ul class="sub">                
                <li><a href="<?php echo base_url() ?>toko/cari">Cari Toko</a></li>
                <li><a href="<?php echo base_url() ?>toko/stok">Stok Toko</a></li>                        
            </ul>
            <?php } if($role == 'supervisor') { ?>
            <ul class="sub">
                <li><a href="<?php echo base_url() ?>toko/cari">Cari Toko</a></li>
                <li><a href="<?php echo base_url() ?>toko/stok">Stok Toko</a></li> 
                <li><a href="<?php echo base_url() ?>toko/tambah">Tambah Toko</a></li>                                      
            </ul>
            <?php } ?>
        </div>
        </li>
        </ul>
        <?php } ?>
        <ul class="select"><li><a href="#"><b>Help</b></a></li></ul>
        
        </div>
        </div>			
    <div class="cleaner"></div>
</div>