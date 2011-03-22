<?php include 'layouts/header.php'; ?>    
<?php include 'layouts/menu.php'; ?>
<?php include 'layouts/content_top.php' ?>        
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>About Sistem Informasi Gudang (Sisgud)</h1>
            <table style="margin: 0 0 0 20px;font-style: italic;">
                <tr><td>Current Version</td><td>: 1.1 (Rev. 26)</td></tr>
                <tr><td>Author</td><td>: Purwa Ren</td></tr>
                <tr><td>Latest Build</td><td>: March, 22<sup>nd</sup> 2011</td></tr>
            </table>
            <p>Sistem Inventori Gudang (SISGUD) merupakan aplikasi untuk pencatatan mutasi gudang baik mutasi masuk maupun mutasi keluar. Aplikasi ini diharapkan mampu untuk meningkatkan kinerja karyawan dalam pencatatan data-data gudang<p>
            <p>
            <b>Updates : </b>
            <ul style="margin:0">
            <li>kode barang jadi 10 digit. <br />
                xxx-xx-xxxxx</li>
            <li>bug fixing:
            <ul>
            <li>tertukar alamat dan telepon, manajemen pengguna </li>
            <li>supplier, dibedakan antara medan dan luar medan. luar medan hm+15% </li>
            <li>harga jual > harga modal, buat peringatan -</li>
            <li>yang ditampiln di mutasi keluar hanya barang yang masih ada stok, -</li>
            <li>jika ada baris barang yang dobel kode, digabung saja -</li>
            <li>yang boleh diretur adalah barang yang sudah pernah di mutasi keluar (tandanya harga jual >0) -</li>
            <li> rekap retur per supplier -</li>
            <li> cetak mutasi -> ctak mutasikeluar, rekap mutasi->rekap mutasi keluar. di pdf judulnya diganti bon mutasi keluar -</li>
            <li>rekap retur dibuat antara tanggal brp smp brp -</li>
            <li>tulisan selamat datang diganti assalamu'alaikum -</li>
            <li>tulisan mode fashion dibuat yang standar(yang gambar) -</li>
            <li>search stok gudang kok gak jalan -</li>
            <li>export data </li>
            <li>validasi form retur barang -</li>
            </ul></li>
            <li>rekap mutasi bisa dicari berdasr: tgl bon,tgl mutasi masuk, supplier </li>
            <li>Fasilitas untuk outlet obral<br /> 
              + hanya untuk menampung data2 barang yang akan diobral, barang yang sudah 
                masuk disini, tinggal dibuat mutasi masuknya lagi terus dijadikan barang
                baru sebagai barang obral</li>
            <li>Fasilitas untuk outlet barang rusak / retur -ok
            <ul>
              <li>untuk mencatat barang2 yang diretur</li>
              <li>pada saat cetak pdf untuk barang yang diretur, ditambahkan 3 kolom yaitu barang, potong bon atau uang</li>
            </ul></li>
            <li>ditambahkan field kategori toko untuk memfasilitasi 2 hal di atas. Kategori toko virtual -ok,</li>
            <li>kode bon untuk bon toko dibuat formatnya INISIAL-TH-No-urut, cth: NB-11-555 -ok untuk kodebon toko, obral dan barang rusak belum diubah -ok</li>
            <li>buat satu role sendiri untuk retur, role name= operator_retur -ok</li>
            <li>cetak label dibedain per user yang login, file label nya -ok</li>
            <li>rekap ekspor data -ok</li>
            <li>rekap mutasi sisa - sedang dikerjakan</li>
            </ul>
            </p>
      </div>                
        <div class="tempatemo_right_bottom">
        </div>
    </div><!-- End of Right Section -->
    
    <div class="cleaner"></div>

</div><!-- End Of Content area bottom -->
<?php include 'layouts/footer.php' ?>        