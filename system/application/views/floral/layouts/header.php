<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $page_title ?></title>
<link href="<?php echo base_url() ?>css/templatemo_style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() ?>css/menu_style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() ?>css/jquery.autocomplete.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() ?>css/le-frog/jquery-ui-1.8.7.custom.css" rel="stylesheet" type="text/css"/>
<?php if(isset($lib_js)) echo $lib_js ?>
<script language="javascript" type="text/javascript">
function clearText(field)
{
    if (field.defaultValue == field.value) field.value = '';
    else if (field.value == '') field.value = field.defaultValue;
}
</script>
</head>
<body>
	<div id="templatemo_container">  	  
    	<div id="templatemo_header">
        	<div id="templatemo_logo">
                <img src="<?php echo base_url() ?>css/images/logo_mode.png" id="templatemo_logo_img" />
            	<h1 class="mode-fashion">&nbsp;<br />&nbsp;</h1>
                <p id="logo_text">Jln. Nibung Baru 61-63<br /> Telp. (061) 227 654</p>
            </div>