<?php
defined('BASEPATH') OR exit('No direct script access allowed');


// if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '') {
  	// $back_url = $_SERVER['HTTP_REFERER'];
// }else{
	$back_url = site_url('project');
// }
?>


<div class="">
	<div class="float-left breadcrumb"><h1 class="mr-2">Project View</h1></div>
	<div class="float-right">
	<a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo $back_url; ?>"><i class="i-Left-3"></i> Back</a>
	<a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo site_url('project/'.$data['id'].'/edit'); ?>"><i class="i-File-Edit"></i> Edit</a>
	</div>
	<div class="clearfix"></div>
</div>



<div class="separator-breadcrumb border-top"></div>


<?php $this->load->view('common/flashmsg'); ?>


<div class="form-group">

<div class="row">


<!-- <div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>ID:</b></p> </div>  
<div class="col-md-8"><p><?php echo $data['id']; ?></p></div>
</div>
</div> -->


<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>GA No.:</b></p> </div>  
<div class="col-md-8"><p><?php echo $data['ga_no']; ?></p></div>
</div>
</div>


<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Project No.:</b></p> </div>  
<div class="col-md-8"><p><?php echo $data['project_code']; ?></p></div>
</div>
</div>


<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Company 1:</b></p> </div>  
<div class="col-md-8"><p><?php echo cap($data['company']); ?></p></div>
</div>
</div>

<?php if($data['company_2']){ ?>
	<div class="col-md-6">   
	<div class="row">
	<div class="col-md-4"><p><b>Company 2:</b></p> </div>  
	<div class="col-md-8"><p><?php echo cap($data['company_2']); ?></p></div>
	</div>
	</div>
<?php } ?>

<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Project Name:</b></p> </div>  
<div class="col-md-8"><p><?php echo $data['project_name']; ?></p></div>
</div>
</div>


<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Date of Supply:</b></p> </div>  
<div class="col-md-8"><p><?php echo custDate($data['supply_date']); ?></p></div>
</div>
</div>

<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Warranty valid till:</b></p> </div>  
<div class="col-md-8"><p><?php echo custDate($data['warranty_till']); ?></p></div>
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
<div class="col-md-4"><p><b>Equipment Name:</b></p> </div>  
<div class="col-md-8"><p><?php echo $data['equipment_name']; ?></p></div>
</div>
</div>

<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Equipment Model:</b></p> </div>  
<div class="col-md-8"><p>
	<?php 
		if(is_array($models)){
			foreach ($models as $key => $value) {
				echo $value.'<br>'; 
			}
		}else{
			echo $data['equipment_model']; 
		}
	?>
		
	</p></div>
</div>
</div>


</div>

</div>


<?php $this->load->view('common/footer');  ?>
