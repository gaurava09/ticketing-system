<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link href="<?php echo base_url('assets/libs/flatpickr/flatpickr.min.css'); ?>" rel="stylesheet" type="text/css" />


<div class="">
<div class="float-left breadcrumb"><h1 class="mr-2">Customer Feedback Form [Ticket No. <?php echo $complaint['ticket_no']; ?>]</h1></div>
<div class="float-right"><a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo site_url('customer/feedback'); ?>"><i class="i-Left-3"></i> Back</a></div>
<div class="clearfix"></div>
</div>

<div class="separator-breadcrumb border-top"></div>

<?php $this->load->view('common/flashmsg'); ?>
<?php $this->load->view('common/ajaxerror'); ?>


<div class="row">
<div class="col-md-12">
<div class="card mb-4">
<div class="card-body">


<?php echo form_open_multipart('customer/feedback/create/'.$complaint['id'],array('id' => 'feedbackForm','autocomplete' => 'off') ); ?>
	
<div class="mt-4 mb-4">  

<div class="row">

<div class="col-md-3 form-group mb-3">
<label for=" ">GA No.</label>
<input class="form-control"  type="text" placeholder="GA No." value="<?php echo ps($project,'ga_no'); ?>" readonly="" />
</div>


<div class="col-md-3 form-group mb-3">
<label for=" ">Equipment</label>
<input class="form-control"  type="text" placeholder="Equipment" value="<?php echo ps($project,'equipment_name'); ?>" readonly="" />
</div>

<?php
$period_class = 'd-none';
if($complaint['complaint_type'] == 1) {
    $period_class = '';
}
?>
<div class="col-md-3 form-group mb-3 <?php echo $period_class; ?>" >
<label for=" ">Visit Period</label>
<input class="form-control" id="period" type="text" placeholder="From To" name="period" value="<?php echo $period; ?>" readonly />
</div>


<div class="col-md-3 form-group mb-3">
<label for=" ">MTC Representative Name</label>
<?php
if(!$representative){
$representative = 'Admin';
}
?>
<input class="form-control" type="text" placeholder="" name="name" value="<?php echo $representative; ?>" readonly />
</div>


<div class="col-md-12 mt-2 mb-2">  
<div class="row">    
<div class="col-md-4 form-group mb-3">
<label for=" "><b>1) Was the visit timing suitable to your requirement?</b></label>
</div>

<div class="col-md-8 form-group mb-3">
<div class="row">

<div class="col-2">
<div class="form-check"><input class="form-check-input" id="gridRadios1" type="radio" name="suitable_time" value="yes">
<label class="form-check-label ml-1" for="gridRadios1">Yes</label></div>
</div>    

<div class="col-2">
<div class="form-check"><input class="form-check-input" id="gridRadios2" type="radio" name="suitable_time" value="no">
<label class="form-check-label ml-1" for="gridRadios2">No</label></div>
</div>

<div class="col-2">
<div class="form-check"><input class="form-check-input" id="gridRadios1" type="radio" name="suitable_time" value="na">
<label class="form-check-label ml-1" for="gridRadios1">NA</label></div>
</div> 

</div></div></div></div>  
            


<div class="col-md-12 mt-2 mb-2">  
<div class="row">    
<div class="col-md-4 form-group mb-3">
<label for=" "><b>2) Feedback on Engineer Competencies</b></label>
</div>

<div class="col-md-8 form-group mb-3">
<div class="row">

<div class="col-12">
<div class="table-responsive">
<table class="table">
<thead>
<tr>
<th scope="col">#</th>
<th scope="col" class="text-center">POOR <br> 1</th>
<th scope="col" class="text-center">AVERAGE <br> 2</th>
<th scope="col" class="text-center">SATISFACTORY <br> 3</th>
<th scope="col" class="text-center">GOOD <br> 4</th>
<th scope="col" class="text-center">EXCELLENT <br> 5</th>
</tr>
</thead>
<tbody>

<tr class="form-group">
<th scope="row">i) Technical knowledge</th>
<?php
	for ($i=1; $i <=5; $i++) { 
		echo '<td class="text-center"><input type="radio" name="tech_knowledge" value="'.$i.'"></td>';
	}
?>
</tr>

<tr class="form-group">
<th scope="row">ii) Communication skills</th>
<?php
	for ($i=1; $i <=5; $i++) { 
		echo '<td class="text-center"><input type="radio" name="comm_skill" value="'.$i.'"></td>';
	}
?>
</tr>

<tr class="form-group">
<th scope="row">iii) Punctuality</th>
<?php
	for ($i=1; $i <=5; $i++) { 
		echo '<td class="text-center"><input type="radio" name="punctuality" value="'.$i.'"></td>';
	}
?>
</tr>

<tr class="form-group">
<th scope="row ">iv) Commitment to Safety</th>
<?php
	for ($i=1; $i <=5; $i++) { 
		echo '<td class="text-center"><input  type="radio" name="safety" value="'.$i.'"></td>';
	}
?>
</tr>
    
</tbody>
</table>
</div>
</div>    
</div></div>
</div>
</div>



<div class="col-md-12 mt-2 mb-2">  
<div class="row">    
<div class="col-md-4 form-group mb-3">
<label for=" "><b>3) Feedback on Equipment Performance</b></label>
</div>

<div class="col-md-8 form-group mb-3">
<div class="row">

<div class="col-12">
<div class="table-responsive">
<table class="table">
<thead>
<tr>
<th scope="col"> </th>
<th scope="col" class="text-center">POOR <br> 1</th>
<th scope="col" class="text-center">AVERAGE <br> 2</th>
<th scope="col" class="text-center">SATISFACTORY <br> 3</th>
<th scope="col" class="text-center">GOOD <br> 4</th>
<th scope="col" class="text-center">EXCELLENT <br> 5</th>
</tr>
</thead>
<tbody>
<tr>
<th scope="row"> </th>
<?php
	for ($i=1; $i <=5; $i++) { 
		echo '<td class="text-center"><input  type="radio" name="equipment_performance" value="'.$i.'"></td>';
	}
?>
</tr>
</tbody>
</table>
</div>
</div>    
</div>    
</div></div>
</div>




<div class="col-md-12 mt-2 mb-2">  
<div class="row">    
<div class="col-md-4 form-group mb-3"><label for=" "><b>4) Any Suggestion for Improvement in Service</b></label></div>
<div class="col-md-8 form-group mb-3">
<div class="row">
<div class="col-12 "><textarea class="form-control" name="suggestion"></textarea></div>
</div></div>
</div></div>


<div class="col-md-12 mt-2 mb-2">  
<div class="row">    
<div class="col-md-4 form-group mb-3"><label for=" "><b>5) Comments</b></label></div>
<div class="col-md-8 form-group mb-3">
<div class="row">
<div class="col-12"><textarea class="form-control" name="comment"></textarea></div>
</div></div>
</div></div>


<div class="col-md-12 mt-2 mb-2">  
<div class="row">    
<div class="col-md-4 form-group mb-3"><label for=" "><b>6) Please rate us on the scale of 1-10 for the service provided.</b></label></div>
<div class="col-md-8 form-group mb-3">
<div class="row">
<div class="col-12">
<div class="table-responsive">
<table class="table">
<thead>
<tr>
<th scope="col"> </th>
<?php
	for ($i=1; $i <=10; $i++) { 
		echo '<th scope="col" class="text-center"><p class="mb-1 form-group"> '.$i.'</p><input type="radio" name="service_rating" value="'.$i.'"></th>';
	}
?>
</tr>
</thead>
</table>
</div>
</div>
</div></div>
</div></div>

<div class="col-md-12 "><button class="btn btn-primary float-right" id="feedbackForm_submit" type="submit">Submit</button> </div>


</div>
</div> 


    </form>


</div>
</div>
</div>  
</div>



<?php $this->load->view('common/footer');  ?>

<script src="<?php echo base_url('assets/libs/jquery-validation/jquery.validate.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/libs/jquery-validation/additional-methods.min.js'); ?>"></script>

<script src="<?php echo base_url('assets/libs/flatpickr/flatpickr.min.js');?>"></script>

<script src="<?php echo base_url('assets/custom.js'); ?>"></script>


<script type="text/javascript">
$(document).ready(function(){
	
	$("#period").flatpickr(
		{ 	mode: "range",
			// minDate: 'today',
			minDate: new Date().fp_incr(1),
			maxDate: new Date().fp_incr(60) 
 		}
	);

	$('#feedbackForm').validate({
        ignore: [],
        // debug: true,
        rules: {
            period: {required: true},
            name: {required: true},
            suitable_time: {required: true},
            tech_knowledge: {required: true,digits: true},
            comm_skill: {required: true,digits: true},
            punctuality: {required: true,digits: true},
            safety: {required: true,digits: true},
            equipment_performance: {required: true,digits: true},
            

            suggestion: {required: true},
            comment: {required: true},

            service_rating: {required: true,digits: true},
        },
        messages: {

        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        },

        submitHandler: function(form) {
        	$('.errors').html('');

          	var formData = new FormData(form);

            $button = $('#feedbackForm_submit');
            showLoading($button);

            $.ajax({
              type: 'post',
                url: $(form).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                success: function($res) {
                    if($res.status == 1){
                      window.location.href = "<?php echo site_url('customer/feedback')?>";
                      return false;
                    }
                    else{
                        showError($res.message);
                    }
                    stopLoading();
                },
                error: function(error, textStatus, errorMessage) {
                    showError('Request could not be completed');
                    stopLoading();
                }             
            });
        }
    }); //end form validate


});
</script>
