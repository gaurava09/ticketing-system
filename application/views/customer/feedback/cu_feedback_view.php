<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$ratings = json_decode($feedback['rating'],true);

// dd($ratings);
?>

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

  
<div class="mt-4 mb-4">  

<div class="row">

<!-- <div class="col-md-3 form-group mb-3">
<label for=" ">GA No.</label>
<input class="form-control"  type="text" placeholder="GA No." value="<?php echo ps($project,'ga_no'); ?>" readonly="" />
</div> -->


<!-- <div class="col-md-3 form-group mb-3">
<label for=" ">Equipment</label>
<input class="form-control"  type="text" placeholder="Equipment" value="<?php echo ps($project,'equipment_name'); ?>" readonly="" />
</div> -->

<!-- <?php if($complaint['complaint_type'] == 1) { ?>
<div class="col-md-3 form-group mb-3">
<label for=" ">Visit Period</label>
<input class="form-control" id="period" type="text" placeholder="From To" value="<?php echo $feedback['period']; ?>" name="period"  readonly="" />
</div>
<?php } ?> -->

<!-- <div class="col-md-3 form-group mb-3">
<label for=" ">EquipMTC Representative Name</label>
<input class="form-control" type="text" value="<?php echo $feedback['representative_name']; ?>" name="name"  readonly=""/>
</div> -->


<!-- <div class="col-md-12 mt-2 mb-2">  
<div class="row">    
<div class="col-md-4 form-group mb-3">
<label for=" "><b>1) Was the visit timing suitable to your requirement?</b></label>
</div>

<div class="col-md-8 form-group mb-3">
<div class="row">

<div class="col-2">
<div class="form-check"><input class="form-check-input" id="gridRadios1" type="radio" name="suitable_time" value="yes" <?php if($feedback['suitable_time'] == 'yes'){ echo 'checked';} ?> disabled>
<label class="form-check-label ml-1" for="gridRadios1">Yes</label></div>
</div>    

<div class="col-2">
<div class="form-check"><input class="form-check-input" id="gridRadios2" type="radio" name="suitable_time" value="no" <?php if($feedback['suitable_time'] == 'yes'){ echo 'checked';} ?> disabled>
<label class="form-check-label ml-1" for="gridRadios2">No</label></div>
</div>

<div class="col-2">
<div class="form-check"><input class="form-check-input" id="gridRadios2" type="radio" name="suitable_time" value="no" <?php if($feedback['suitable_time'] == 'na'){ echo 'checked';} ?> disabled>
<label class="form-check-label ml-1" for="gridRadios2">NA</label></div>
</div>

</div></div></div></div>  --> 
            


<!-- <div class="col-md-12 mt-2 mb-2">  
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
    if($ratings['tech_knowledge'] == $i){ $checked = 'checked'; }else{  $checked = '';}

    echo '<td class="text-center"><input type="radio" name="tech_knowledge" value="'.$i.'" '.$checked.' disabled></td>';
  }
?>
</tr>

<tr class="form-group">
<th scope="row">ii) Communication skills</th>
<?php
  for ($i=1; $i <=5; $i++) { 
    if($ratings['comm_skill'] == $i){ $checked = 'checked'; }else{  $checked = '';}
    echo '<td class="text-center"><input type="radio" name="comm_skill" value="'.$i.'" '.$checked.' disabled></td>';
  }
?>
</tr>

<tr class="form-group">
<th scope="row">iii) Punctuality</th>
<?php
  for ($i=1; $i <=5; $i++) { 
    if($ratings['punctuality'] == $i){ $checked = 'checked'; }else{  $checked = '';}
    echo '<td class="text-center"><input type="radio" name="punctuality" value="'.$i.'" '.$checked.' disabled></td>';
  }
?>
</tr>

<tr class="form-group">
<th scope="row ">iv) Commitment to Safety</th>
<?php
  for ($i=1; $i <=5; $i++) { 
    if($ratings['safety'] == $i){ $checked = 'checked'; }else{  $checked = '';}
    echo '<td class="text-center"><input  type="radio" name="safety" value="'.$i.'" '.$checked.' disabled></td>';
  }
?>
</tr>
    
</tbody>
</table>
</div>
</div>    
</div></div>
</div>
</div> -->



<!-- <div class="col-md-12 mt-2 mb-2">  
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
    if($ratings['equipment_performance'] == $i){ $checked = 'checked'; }else{  $checked = '';}
    echo '<td class="text-center"><input  type="radio" name="equipment_performance" value="'.$i.'" '.$checked.' disabled></td>';
  }
?>
</tr>
</tbody>
</table>
</div>
</div>    
</div>    
</div></div>
</div> -->




<div class="col-md-12 mt-2 mb-2">  
<div class="row">    
<div class="col-md-4 form-group mb-3"><label for=" "><b>1) Any Suggestion for Improvement in Service</b></label></div>
<div class="col-md-8 form-group mb-3">
<div class="row">
<div class="col-12 "><textarea class="form-control" name="suggestion" readonly=""><?php echo $feedback['suggestion']; ?></textarea></div>
</div></div>
</div></div>


<div class="col-md-12 mt-2 mb-2">  
<div class="row">    
<div class="col-md-4 form-group mb-3"><label for=" "><b>2) Comments</b></label></div>
<div class="col-md-8 form-group mb-3">
<div class="row">
<div class="col-12"><textarea class="form-control" name="comment" readonly=""><?php echo $feedback['comment']; ?></textarea></div>
</div></div>
</div></div>


<div class="col-md-12 mt-2 mb-2">  
<div class="row">    
<div class="col-md-4 form-group mb-3"><label for=" "><b>3) Please rate us on the scale of 1-10 for the service provided.</b></label></div>
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
    if($ratings['service_rating'] == $i){ $checked = 'checked'; }else{  $checked = '';}
    echo '<th scope="col" class="text-center"><p class="mb-1 form-group"> '.$i.'</p><input type="radio" name="service_rating" value="'.$i.'" '.$checked.' disabled></th>';
  }
?>
</tr>
</thead>
</table>
</div>
</div>
</div></div>
</div></div>



</div>
</div> 




</div>
</div>
</div>  
</div>



<?php $this->load->view('common/footer');  ?>

