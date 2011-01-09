<?php include 'layouts/header.php';?>   
            <div class="cleaner"></div>
        </div>

        <div id="templatemo_content_area_top">
        	<div class="templatemo_left_top">            	              
            </div>            
        	
            <div class="cleaner"></div>
        </div><!-- End Of Content area top -->
        
        <div id="templatemo_content_area_bottom">
        	<div class="templatemo_left_section">
            	<div class="templatemo_title">
               	    Please Log In         </div>
                <div class="templatemo_section">
                	<div class="templatemo_section_top">
                    </div>
                    <div class="templatemo_section_mid">
                    	<div class="templatemo_img_frame">
                        	<img src="<?php echo base_url() ?>css/images/green-lock.png" alt="Squrrial" />
                        </div>
                        <form action="<?php echo base_url().'home/login' ?>" method="post">
                        <p>
                            Username <br />
                            <input type="text" name="username" /><br />
                            Password <br />
                            <input type="password" name="passwd" /><br />
                            <span class="button"><input type="submit" class="button" name="submit_login" value="Login" style="margin: 5px 0 0 100px"/>
                        </p>
                        </form>
                      <div class="cleaner"></div>
                    </div>                    
                    <div class="templatemo_section_bottom">
                    </div>
                    <p class="err_msg"><?php if(isset($err_login)) echo $err_login ?></p>
                </div>            

            </div><!-- End of Left Section -->   
            
            <div class="cleaner"></div>

        </div><!-- End Of Content area bottom -->
<?php include 'layouts/footer.php'; ?>	