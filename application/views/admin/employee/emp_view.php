<?php
defined('BASEPATH') OR exit('No direct script access allowed');


if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '') {
  	$back_url = $_SERVER['HTTP_REFERER'];
}else{
	$back_url = site_url('employee');
}
?>


<div class="">
	<div class="float-left breadcrumb"><h1 class="mr-2">A09 Employee View</h1></div>
	<div class="float-right">
	<a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo $back_url; ?>"><i class="i-Left-3"></i> Back</a>
	<a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo site_url('employee/'.$data['id'].'/edit'); ?>"><i class="i-File-Edit"></i> Edit</a>
	</div>
	<div class="clearfix"></div>
</div>



<div class="separator-breadcrumb border-top"></div>


<?php $this->load->view('common/flashmsg'); ?>


<div class="form-group">

<div class="row">

<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>First Name:</b></p> </div>  
<div class="col-md-8"><p><?php echo cap($data['first_name']); ?></p></div>
</div>
</div>


<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Last Name:</b></p> </div>  
<div class="col-md-8"><p><?php echo cap($data['last_name']); ?></p></div>
</div>
</div>

<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Email:</b></p> </div>  
<div class="col-md-8"><p><?php echo $data['email']; ?></p></div>
</div>
</div>

<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Contact Number:</b></p> </div>  
<div class="col-md-8"><p><?php echo '('.$data['country_code'].') '. $data['mobile']; ?></p></div>
</div>
</div>

<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Employee ID:</b></p> </div>  
<div class="col-md-8"><p><?php echo $data['emp_id']; ?></p></div>
</div>
</div>


<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Designation:</b></p> </div>  
<div class="col-md-8"><p>
	<?php 
		foreach($designation as  $key => $value){
             if($value['id'] == $data['designation_id']){
                echo cap($value['name']);
            }
        }
	?>
		
	</p></div>
</div>
</div>

<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Department:</b></p> </div>  
<div class="col-md-8"><p>
	<?php 
		foreach($department as  $key => $value){
             if($value['id'] == $data['department_id']){
                echo $value['name'];
            }
        }
	?>
		
	</p></div>
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
</div>

</div>


<?php $this->load->view('common/footer');  ?>
