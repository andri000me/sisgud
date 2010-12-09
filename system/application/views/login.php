<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : Radiance
Description: Fixed-width design for blogs and small websites.
Version    : 1.0
Released   : 20071007

-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Sistem Inventori Gudang :. Log In</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<?php echo $link_tag ?>
</head>
<body>
<!-- start header -->
<div id="logo">
</div>
<!-- end header -->
<hr />
<!-- start latest post -->
<div  class="wide-post">	    
	<h1 class="title">
    <div id="login_left"></div>
    <div id="login_form">
		<form action="<?php echo base_url().'index.php/home/login' ?>" method="post" class="login_form">
		<table id="table_form">
			<tr><td>Username </td><td> : <input type="text" name="username" /></td></tr>
			<tr><td>Password </td><td>: <input type="password" name="passwd" /></td></tr>
			<tr><td colspan="2" style="text-align: center;"><input type="submit" name="submit_login" value="L o g i n"/></td></tr>
		</table>        
		</form>
        <p id="err_login"><?php if(isset($err_login)) echo $err_login ?></p>        
	</div>
    <div id="login_right"></div>
    </h1>    
	<div class="bottom"></div>
</div>
<!-- end latest post -->
<!-- start recent posts & comments -->
<div id="recents" class="two-columns">
</div>
<!-- end recent posts & comments -->
<div id="footer">
</div>
</body>
</html>
