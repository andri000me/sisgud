<?php require 'layouts/header.php';?>
<?php require 'layouts/menu.php'; ?>
<!-- start latest post -->
<div id="latest-post" class="wide-post">
	<h1 class="title"><a href="#">Print Mutasi</a></h1>
	<div class="entry">
	<!-- tambah departemen -->
	<div id="tampilan">
	<form method = "post" action = "">
	<!-- kolom supplier -->
    <form action="<?php echo base_url().'index.php/gudang/mutasi/rekap'?>" method="POST">     
    <table cellspacing = "10" style="text-align:left">	
    <tr>
        <td>Tanggal </td>
        <td>: 
            <select name="d">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
                <option value="13">13</option>
                <option value="14">14</option>
                <option value="15">15</option>
                <option value="16">17</option>
                <option value="19">18</option>
                <option value="20">20</option>
                <option value="21">21</option>
                <option value="22">22</option>
                <option value="23">23</option>
                <option value="24">24</option>
                <option value="25">25</option>
                <option value="26">26</option>
                <option value="27">27</option>
                <option value="28">28</option>
                <option value="29">29</option>
                <option value="30">30</option>
                <option value="31">31</option>
            </select> - 
            <select name="m">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
            </select> - 
            <select name="Y">
                <option value="2010">2010</option>
                <option value="2011">2011</option>
                <option value="2012">2012</option>
                <option value="2013">2013</option>
                <option value="2014">2014</option>
                <option value="2015">2015</option>
                <option value="2016">2016</option>
                <option value="2017">2017</option>
                <option value="2018">2018</option>
                <option value="2019">2019</option>
                <option value="2020">2020</option>
            </select>
            <input type="submit" name="submit_rekap_mutasi" value="GO"/>
        </td>
    </tr>
	</table>   
	<!-- table  departemen -->   
    </form>
    <!-- table  departemen -->
	<?php if(isset($list_mutasi)) echo $list_mutasi ?>    
	</div>		
<!-- end latest post -->
<?php require 'layouts/footer.php'; ?>