<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$complaint_status_list = complaint_status_list();
?>

<div class="">
<div class="float-left breadcrumb"><h1 class="mr-2">Complaint View</h1></div>
<div class="float-right"><a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo site_url('customer/complaint'); ?>"><i class="i-Add-File"></i> Back</a></div>
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
</div>
<!-- <div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Employee Name:</b></p> </div> 
<div class="col-md-8"><p><?php echo ps($customer,'first_name').'  '.ps($customer,'last_name'); ?></p></div>
</div>
</div> -->

<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>GA Number:</b></p> </div>  
<div class="col-md-8"><p><?php echo ps($complaint,'ga_no'); ?></p></div>
</div>
</div>

<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Company Name:</b></p> </div>  
<div class="col-md-8"><p><?php echo ps($project,'company'); ?></p></div>
</div>
</div>


<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Complaint Type:</b></p> </div>  
<div class="col-md-8"><p><?php echo ps($complaint,'complaint_type'); ?></p></div>
</div>
</div>



<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Equipment Name:</b></p> </div>  
<div class="col-md-8"><p><?php echo ps($project,'equipment_name'); ?></p></div>
</div>
</div>


<!-- <div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Gurantee Validity:</b></p> </div>  
<div class="col-md-8"><p><?php echo ps($project,'warranty_till'); ?></p></div>
</div>
</div>
 -->

<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Model Number:</b></p> </div>  
<div class="col-md-8"><p><?php echo ps($project,'model'); ?></p></div>
</div>
</div>


<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Customer Equipment No.:</b></p> </div>  
<div class="col-md-8"><p><?php echo ps($complaint,'cust_equipment_no'); ?></p></div>
</div>
</div>

<?php
  $complaint_types = complaint_types();
 $compaint_type_key = array_search($complaint['complaint_type'],$complaint_types);

  if( $compaint_type_key == 1){ ?>

    <div class="col-md-6">   
      <div class="row">
      <div class="col-md-4"><p><b>Purchase order no:</b></p> </div>  
      <div class="col-md-8"><p><?php echo $complaint['order_no']; ?></p></div>
      </div>
    </div>
  <?php  } ?>

<?php  if( $compaint_type_key == 1 || $compaint_type_key == 3){ ?>
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
  else if($compaint_type_key == 2 && $complaint['files']){ ?>
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

<div class="col-md-12">  
<div class="row">
<div class="col-md-2"><p><b>Email CC: </b></p></div> 
<div class="col-md-10"><p><?php echo $complaint['email_cc']; ?></p></div>
</div>
</div>

</div><!--  end row -->


<!-- start complaint history -->
<?php

if($history){
  foreach ($history as $key => $value) {

    if($value['assigned_by'] == 'admin'){ 
      
      echo '<div class="row">
        <div class="col-md-12 mt-3"><div class="separator-breadcrumb border-top"></div></div>
      </div>';

      if($value['type'] == 'remark'){ ?>
          <div class="row">

            <div class="col-md-4">   
            <div class="row">
            <div class="col-md-4"><p><b>Remark By:</b></p> </div>  
            <div class="col-md-8 text-capitalize"><p><?php echo $value['emp_name']; ?></p></div>
            </div>
            </div>


            <div class="col-md-4">   
            <div class="row">
            <div class="col-md-4"><p><b>Status:</b></p> </div>  
            <div class="col-md-8 text-capitalize"><p><?php echo ps($complaint_status_list,$value['new_status']); ?></p></div>
            </div>
            </div>

            <div class="col-md-4">   
            <div class="row">
            <div class="col-md-4"><p><b>Date:</b></p> </div>  
            <div class="col-md-8 text-capitalize"><p><?php echo custDate($value['created_at']); ?></p></div>
            </div>
            </div>

            <div class="col-md-12">   
            <div class="row">
            <div class="col-md-4"><p><b>Remark: </b></p> </div>  
            <div class="col-md-8"><p><?php echo $value['remark']; ?></p></div>
            </div>
            </div>

          </div>
      <?php } else { ?>

      <div class="row">

        <div class="col-md-4">   
          <div class="row">
          <div class="col-md-5"><p><b>Assigned To:</b></p> </div>  
          <div class="col-md-7 text-capitalize"><p><?php echo ps($value,'dept_name'); ?></p></div>
          </div>
        </div>


        <div class="col-md-4">   
        <div class="row">
        <div class="col-md-4"><p><b>Employee:</b></p> </div>  
        <div class="col-md-8 text-capitalize"><p><?php echo $value['emp_name']; ?></p></div>
        </div>
        </div>

        <div class="col-md-4">   
        <div class="row">
        <div class="col-md-4"><p><b>Date:</b></p> </div>  
        <div class="col-md-8 text-capitalize"><p><?php echo custDate($value['created_at']); ?></p></div>
        </div>
        </div>

      </div>

    <?php
      }
    }
  }
}

?>


<?php
  if( ! in_array($complaint['status'], [0,4])) { ?>
      

<?php echo form_open_multipart('customer/complaint/remark',array('id' => 'remarkForm','autocomplete' => 'off') ); ?>
  
    <div class="row mt-3">
        <div class="col-md-12 mt-3"><div class="separator-breadcrumb border-top"></div></div>
    </div>

    <input type="hidden" name="complaint_id" value="<?php echo $complaint['id']; ?>">
    <div class="row ">
        <div class="col-md-4 form-group mb-3">
            <label for=" ">Status</label>
              <select class="form-control"  name="status">
              <option value="">Select Status</option>
              <?php
                if($complaint['status'] != 2){
                  echo '<option value="2">Open</option>';
                }
              ?>
              
              <option value="4">Close</option>
            </select>
        </div>

      <div class="col-md-12 mt-4">
        <button class="btn btn-primary" id="remarkForm_submit" type="submit">Submit</button> 
      </div>
    </div>
  </form>
<?php  }
?>

<?php
if($complaint['status'] == 4){
  echo '<div class="row">
        <div class="col-md-12 mt-4">  ';

    if($feedback){
        echo '<a class="btn btn-primary" href="'.site_url('customer/feedback/'.$complaint['id']).'">View feedback</a>';
    }else{
        echo '<a class="btn btn-primary" href="'.site_url('customer/feedback/create/'.$complaint['id']).'">Please take a moment and give us a feedback</a>';
    }
  
    echo '</div>
    </div>';
} 
?>

<!-- end complaint history -->


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


<?php if($complaint['files']){ ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<?php } ?>

<script src="<?php echo base_url('assets/custom.js'); ?>"></script>

<?php //$this->load->view('admin/ad_complaint/ad_view_js');  ?>

<script type="text/javascript">
  
  $('#remarkForm').validate({
        ignore: [],
        rules: {
            complaint_id: {required: true},
            status: {required: true},
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
            
            $button = $('#remarkForm_submit');
            showLoading($button);

            $.ajax({
                type: 'post',
                url: $(form).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                success: function($res) {
        
                    if($res.status == 1){
                      window.location.href = "<?php echo site_url('customer/complaint/'.$complaint['id'])?>";
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

</script>