<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$complaint_types = complaint_types();
$complaint_status_list = complaint_status_list();
?>

<div class="">
<div class="float-left breadcrumb"><h1 class="mr-2">Complaint Summary</h1></div>
<div class="float-right">
  <?php
  if($this->role =='admin' && $complaint['feedback'] == 1){
        echo '<a class="btn btn-primary" href="'.site_url('feedback/'.$complaint['id']).'">View feedback</a>';
    }
  ?>
  <a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo site_url('complaint'); ?>"><i class="i-Left-3"></i> Back</a>

</div>
<div class="clearfix"></div>
</div>

<div class="separator-breadcrumb border-top"></div>

<?php $this->load->view('common/flashmsg'); ?>
<?php $this->load->view('common/ajaxerror'); ?>



<div class="row">
<div class="col-md-12">
<div class="card mb-4">

<div class="modal-body">
<div class="card-body">



<div class="form-group">

<div class="row">

<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Ticket ID:</b></p> </div>
<div class="col-md-8"><p><?php echo ps($complaint,'ticket_no'); ?></p></div>
</div>
</div>

<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Customer Name:</b></p> </div> 
<div class="col-md-8">
  <p>
  <?php 
    echo cap(ps($customer,'first_name')).'  '.cap(ps($customer,'last_name')); 
    echo ' (<a href="mailto:'.$customer['email'].'" target="_blank">'.$customer['email'].'</a>)';
  ?>
  </p>
</div>
</div>
</div>

<!-- <div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>GA Number:</b></p> </div>  
<div class="col-md-8"><p><?php echo ps($complaint,'ga_no'); ?></p></div>
</div>
</div> -->

<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Company Name:</b></p> </div>  
<div class="col-md-8"><p><?php echo cap(ps($customer,'company_name')); ?></p></div>
</div>
</div>


<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Complaint Type:</b></p> </div>  
<div class="col-md-8"><p><?php echo $complaint_types[$complaint['complaint_type']]; ?></p></div>
</div>
</div>



<!-- <div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Equipment Name:</b></p> </div>  
<div class="col-md-8"><p><?php echo cap(ps($project,'equipment_name')); ?></p></div>
</div>
</div> -->


<!-- <div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Gurantee Validity:</b></p> </div>  
<div class="col-md-8"><p><?php 
if(isset($project['warranty_till']) && $project['warranty_till'] != '' ){
  echo custDate($project['warranty_till']);
}else{
  echo ps($project,'warranty_till'); 
}

?></p></div>
</div>
</div> -->


<!-- <div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Model Number:</b></p> </div>  
<div class="col-md-8"><p><?php echo ps($project,'model'); ?></p></div>
</div>
</div> -->

<!-- <div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Customer Equipment No.:</b></p> </div>  
<div class="col-md-8"><p><?php echo ps($complaint,'cust_equipment_no'); ?></p></div>
</div>
</div> -->

<?php
 // $complaint_type_key = array_search($complaint['complaint_type'],$complaint_types);
  $complaint_type_key = $complaint['complaint_type'];

   if( $complaint_type_key == 1){ ?>

    <div class="col-md-6">   
      <div class="row">
      <div class="col-md-4"><p><b>Purchase order no:</b></p> </div>  
      <div class="col-md-8"><p><?php echo $complaint['order_no']; ?></p></div>
      </div>
    </div>
  <?php  } ?>

<?php  if( $complaint_type_key == 1 || $complaint_type_key == 3){ ?>
    <div class="col-md-6">   
      <div class="row">
      <div class="col-md-4"><p><b>Visit From Date:</b></p> </div>  
      <div class="col-md-8"><p><?php echo custDate($complaint['from_date']); ?></p></div>
      </div>
    </div>

    <div class="col-md-6">   
      <div class="row">
      <div class="col-md-4"><p><b>Visit To Date:</b></p> </div>  
      <div class="col-md-8"><p><?php echo custDate($complaint['to_date']); ?></p></div>
      </div>
    </div>

<?php  }
  else if($complaint_type_key == 2 && $complaint['files']){ ?>
    <div class="col-md-6">  
      <div class="row">
        <div class="col-md-4"><p><b>Attachment:</b></p> </div>   
        <div class="col-md-8"><a data-toggle="modal" data-target="#exampleModalCenter" href="#">View Document </a></div>
      </div>
    </div>
<?php  }
?>

<div class="col-md-6">  
<div class="row">
<div class="col-md-4"><p><b>Complaint Date: </b></p></div> 
<div class="col-md-8"><p><?php echo custDate($complaint['created_at']); ?></p></div>
</div>
</div>

<div class="col-md-6">  
<div class="row">
<div class="col-md-4"><p><b>Status: </b></p></div> 
<div class="col-md-8"><p><?php echo ps($complaint_status_list,$complaint['status']); ?></p></div>
</div>
</div>

<div class="col-md-12">  
<div class="row">
<div class="col-md-2"><p><b>Message: </b></p></div> 
<div class="col-md-10"><p><?php echo $complaint['description']; ?></p></div>
</div>
</div>

<?php if($complaint['email_cc']) { 
  $email_arr = explode(",", $complaint['email_cc']);

  $email_str = '';
  foreach ($email_arr as $key => $value) {
    $email_str .= '<a href="mailto:'.$value.'" target="_blank">'.$value.'</a>, ';
  }

  ?>
  <div class="col-md-12">  
  <div class="row">
  <div class="col-md-2"><p><b>Email CC: </b></p></div> 
  <div class="col-md-10"><p><?php echo rtrim($email_str,", "); ?></p></div>
  </div>
  </div>
<?php } ?>


<?php if($complaint['classification']) {  ?>
  <div class="col-md-12">  
  <div class="row">
  <div class="col-md-2"><p><b>Complaint Classification: </b></p></div> 
  <div class="col-md-10"><p><?php 
  $classifications = classifications();

  echo $complaint['classification'] .' - '.$classifications[$complaint['classification']]; 
  ?></p></div>
  </div>
  </div>
<?php } ?>

</div><!--  end row -->


<?php if($custConvoId){ 
  $chat_text =($complaint['status'] == 4 || $complaint['status'] == 0) ? 'VIEW CHAT' : 'CHAT WITH CUSTOMER';
  ?>
    <div class="row">
      <div class="col-md-12"><p><a class="btn btn-primary float-right" type="button"  href="<?php echo site_url('chat/'.$custConvoId); ?>"> <?php  echo $chat_text; ?></a> </p></div>
    </div>
<?php  }  ?>

<!-- start complaint history -->
<?php $this->load->view('admin/ad_complaint/ad_complaint_view_history');  ?>
<!-- end complaint history -->


<!-- start forms -->
<?php $this->load->view('admin/ad_complaint/ad_complaint_view_form');  ?>
<!-- end forms -->

</div>
</div>
</div><!-- modal body -->
</div>
</div>
</div> <!-- main row -->


<?php
if($complaint['files']){ ?>
<!--  Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalCenterTitle-2">Documents </h5>
      <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    </div>
    <div class="modal-body">
      <div class="row">
    <?php
      foreach ($complaint['files'] as $key => $value) {
         echo '<div class="col-md-4 mt-3">  <a href="'.base_url($value['path']).'" data-fancybox="gallery"> <img src="'.base_url($value['path']).'" class="img-fluid"> </a>  </div>';
      }
    ?>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-primary" type="button" data-dismiss="modal">Close</button>
    </div>
    </div>
  </div>
</div>
<?php } ?>

<?php $this->load->view('common/footer');  ?>

<script src="<?php echo base_url('assets/libs/jquery-validation/jquery.validate.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/libs/jquery-validation/additional-methods.min.js'); ?>"></script>



<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>


<?php  //if(strtolower($complaint['complaint_type']) == 'request for engineer visit'){ ?>
<link href="<?php echo base_url('assets/libs/flatpickr/flatpickr.min.css'); ?>" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url('assets/libs/flatpickr/flatpickr.min.js');?>"></script>
<?php //} ?>


<script src="<?php echo base_url('assets/custom.js'); ?>"></script>


<?php $this->load->view('admin/ad_complaint/ad_view_js');  ?>
