<div id="templatemo_content_area_top">
    <div class="templatemo_left_top">
        <div class="templatemo_news">
            <p><?php echo $now_date ?> .:. <?php echo $userinfo.'('.ucwords($this->session->userdata('p_role')).')' ?> .:. <a href="<?php echo base_url().'index.php/home/logout'?>" class="comments">Log Out</a></p>
        </div>               
    </div>            
    <div class="templatemo_right_top">
        <!--<img src="images/templatemo_img_1.jpg" alt="Flower" />-->
        <div id="templatemo_search">
            <?php echo form_open('gudang/stok') ?>
                <label>SEARCH:</label>
                <input type="text" value="Cari stok gudang" name="keywords" id="searchfield" title="searchfield" onfocus="clearText(this)" onblur="clearText(this)" />
                <input type="submit" name="submit_search_stock" value="Search" alt="Search" id="searchbutton" title="Search" />
            <?php echo form_close() ?>
        </div>
    </div>
    <div class="cleaner"></div>
</div><!-- End Of Content area top -->