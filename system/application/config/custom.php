<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config['log_enable'] = TRUE;
/**
*Template configuration
*/
$config['template'] = 'floral/';

/**
* Konfigurasi cetak label
* 1 => konfigurasi lama, masih belum nampilin nama modiest dan mode
* 2 => konfigurasi baru
*/
$config['label'] = 2;

/**
* Kode kelompok barang untuk hadiah
*/
$config['hadiah'] = array(
		'888','901','902','903','904','905','906','907','908','909','910',
		'911','912','913','914','915','916','917','918','919','920','921',
		'922','923','924','925'
	);

/**
*
*/
$config['kode_hm'] = array('E','F','A','S','H','I','O','N','M','O','D');
//end of file custom.php

/**
 * Default timezone
 */
$config['timezone'] = 'Asia/Jakarta';

/**
 * kode bulan
 */
$config['kode_bulan']=array('','O','P','Q','R','S','T','U','V','W','X','Y','Z');
