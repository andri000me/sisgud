<?php include 'layouts/header.php'; ?>    
<?php include 'layouts/menu.php'; ?>
<?php include 'layouts/content_top.php' ?>    
<div id="templatemo_content_area_bottom">   
    <div class="templatemo_right_section">
        <div class="tempatemo_section_box_1">
            <h1>Toko >> Detail </h1>           
            <?php if(isset($toko)) { ?>            
            <img class="shop_pict" src="<?php echo $shop_pict ?>" alt="Belum upload foto"/>
            <p class="shop_detail">                
               <b><?php echo $toko->shop_name.'<br />'.$toko->shop_address ?></b>
            </p>
            <table class="table_detail" cellpadding="5">        
                <tr><td>Kode Toko</td><td>: <?php echo $toko->shop_code ?></td></tr>
                <tr><td>Inisial Toko</td><td>: <?php echo $toko->shop_initial ?></td></tr>                
                <tr><td>Telepon</td><td>: <?php echo $toko->shop_phone ?></td></tr>
                <tr><td>Supervisor</td><td>: <?php echo $toko->shop_supervisor ?></td></tr>
                <tr><td>Total Mutasi</td><td>: <?php echo $toko->total ?> item</td></tr>
            </table> 
            <br />
            <br />
            <br />
            <br />
            <?php } ?>
           
      </div>                
        <div class="tempatemo_right_bottom">
        </div>
    </div><!-- End of Right Section -->
    
    <div class="cleaner"></div>

</div><!-- End Of Content area bottom -->
<?php include 'layouts/footer.php' ?>        