<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$back_url = site_url('sparepart');

?>


<div class="">
	<div class="float-left breadcrumb"><h1 class="mr-2">Sparepart View</h1></div>
	<div class="float-right">
	<a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo $back_url; ?>"><i class="i-Left-3"></i> Back</a>
	<a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo site_url('sparepart/'.$data['id'].'/edit'); ?>"><i class="i-File-Edit"></i> Edit</a>
	</div>
	<div class="clearfix"></div>
</div>



<div class="separator-breadcrumb border-top"></div>


<?php $this->load->view('common/flashmsg'); ?>


<div class="form-group">

<div class="row">

<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Name:</b></p> </div>  
<div class="col-md-8"><p><?php echo cap($data['name']); ?></p></div>
</div>
</div>

<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Unit Of Measurement:</b></p> </div>  
<div class="col-md-8"><p><?php echo cap($data['unit']); ?></p></div>
</div>
</div>


<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Equipment Name:</b></p> </div>  
<div class="col-md-8"><p><?php echo cap($data['equipment_name']); ?></p></div>
</div>
</div>


<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Status:</b></p> </div>  
<div class="col-md-8"><p><?php echo ( $data['status'] == 1) ? 'Active' : 'Deactive'; ?></p></div>
</div>
</div>


<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Created At:</b></p> </div>  
<div class="col-md-8"><p><?php echo custDate($data['created_at']); ?></p></div>
</div>
</div>


<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Equipment Model:</b></p> </div>  
<div class="col-md-8">

<?php
	$models = json_decode($data['model'],true);

	if(json_last_error() == JSON_ERROR_NONE && is_array($models)){
		foreach ($models as $key => $value) {
			echo '<p>'.$value.'</p>';
		}
	}else{
		echo '<p>'.$data['model'].'</p>';
	}
	?>
		

	</div>
</div>
</div>


</div>

</div>


<?php $this->load->view('common/footer');  ?>
