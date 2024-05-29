<?php
defined('BASEPATH') OR exit('No direct script access allowed');


// if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '') {
//   	$back_url = $_SERVER['HTTP_REFERER'];
// }else{
	$back_url = site_url('company');
// }
?>


<div class="">
	<div class="float-left breadcrumb"><h1 class="mr-2">Company View</h1></div>
	<div class="float-right">
	<a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo $back_url; ?>"><i class="i-Left-3"></i> Back</a>
	<a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo site_url('company/'.$data['id'].'/edit'); ?>"><i class="i-File-Edit"></i> Edit</a>
	</div>
	<div class="clearfix"></div>
</div>



<div class="separator-breadcrumb border-top"></div>


<?php $this->load->view('common/flashmsg'); ?>


<div class="form-group">

<div class="row">

<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Company Name:</b></p> </div>  
<div class="col-md-8"><p><?php echo cap($data['name']); ?></p></div>
</div>
</div>

<!-- <div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Domain:</b></p> </div>  
<div class="col-md-8"><p><?php echo $data['domain'] ; ?></p></div>
</div>
</div> -->

<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Location:</b></p> </div>  
<div class="col-md-8"><p><?php echo $data['location'] ; ?></p></div></div>
</div>


<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Status:</b></p> </div>  
<div class="col-md-8"><p><?php echo ( $data['status'] == 1) ? 'Active' : 'De-active'; ?></p></div>
</div>
</div>

<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Created At:</b></p> </div>  
<div class="col-md-8"><p><?php echo custDate($data['created_at']); ?></p></div>
</div>
</div>

<?php if ($employees_array != '') { ?>
	<div class="col-md-12">  
	<div class="row">
	<div class="col-md-2"><p><b>A09 Employees: </b></p></div> 
	<div class="col-md-10"><p><?php echo $employees_array; ?></p></div>
	</div>
	</div>
<?php } ?>

</div>

</div>


<?php $this->load->view('common/footer');  ?>

