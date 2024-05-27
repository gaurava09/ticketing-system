<?php
defined('BASEPATH') OR exit('No direct script access allowed');

  $complaint_types = complaint_types();
  $complaint_status_list = complaint_status_list();
  
  // echo $diff_date = $diff->d;
  $diff_date = totalDaysBetTwo(date('Y-m-d'), date('Y-m-d', strtotime($complaint['created_at'])));

?>

<div class="">
<div class="float-left breadcrumb"><h1 class="mr-2">Ticket View</h1></div>
<div class="float-right"><a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo site_url('customer/complaint'); ?>"><i class="i-Left-3"></i> Back</a></div>
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

<?php
if($complaint['status'] == 4){
  echo '<div class="row">
        <div class="col-md-12 ">  ';

    if($feedback){
        echo '<a class="btn btn-primary float-right" href="'.site_url('customer/feedback/'.$complaint['id']).'">View feedback</a>';
    }else{
        echo '<a class="btn btn-primary float-right" href="'.site_url('customer/feedback/create/'.$complaint['id']).'">Please take a moment and give us a feedback</a>';
    }
  
    echo '</div>
    </div>';
} 
?>

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
<div class="col-md-8"><p><?php //echo ps($customer,'first_name').'  '.ps($customer,'last_name'); ?></p></div>
</div>
</div> -->

<!-- <div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>GA Number:</b></p> </div>  
<div class="col-md-8"><p><?php echo ps($complaint,'ga_no'); ?></p></div>
</div>
</div> -->


<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Company Name:</b></p> </div>  
<div class="col-md-8"><p><?php echo cap(ps($company,'name')); ?></p></div>
</div>
</div>


<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Ticket Type:</b></p> </div>  
<div class="col-md-8"><p><?php echo $complaint_types[$complaint['complaint_type']]; ?></p></div>
</div>
</div>



<!-- <div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Equipment Name:</b></p> </div>  
<div class="col-md-8"><p><?php echo ps($project,'equipment_name'); ?></p></div>
</div>
</div> -->


<!-- <div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Gurantee Validity:</b></p> </div>  
<div class="col-md-8"><p><?php echo ps($project,'warranty_till'); ?></p></div>
</div>
</div>
 -->

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

    <!-- <div class="col-md-6">   
      <div class="row">
      <div class="col-md-4"><p><b>Purchase order no:</b></p> </div>  
      <div class="col-md-8"><p><?php echo $complaint['order_no']; ?></p></div>
      </div>
    </div> -->
  <?php  } ?>

<?php if($complaint['files']){ ?>
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
<div class="col-md-4"><p><b>Ticket Date: </b></p></div> 
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

<div class="row">
  <div class="col-md-12">

  <?php if($adminConvoId && $complaint['status'] != 2){ 
    $chat_text =($complaint['status'] == 4 || $complaint['status'] == 0) ? 'VIEW CHAT' : 'Helpdesk';
    ?>
      <a class="btn btn-primary float-right" type="button"  href="<?php echo site_url('chat/'.$adminConvoId); ?>"> <?php  echo $chat_text; ?></a> 
      
  <?php  }  ?>

  <?php 
      if($complaint['status'] != 0 && $complaint['status'] != 4 && $diff_date > 1){ 
    ?>
    <button class="btn btn-primary float-right" style="margin-right: 15px;"  type="button" data-toggle="modal" data-target="#escalationModal" id="esc_button">ESCALATE</button>

    <?php  }  ?>

  </div>
</div>



<!-- start complaint history -->

<?php

if($history){
  foreach ($history as $key => $value) {

      if( $value['employee'] != 'employee' && $value['reply_to'] != 'employee') {
     
      if($value['type'] == 'customer'){ 
         echo '<div class="row">
        <div class="col-md-12 mt-3"><div class="separator-breadcrumb border-top"></div></div>
      </div>';

        ?>
        <div class="row">
          <div class="col-md-8 mt-4 mb-4">
            <p><b>Status changed by you : <?php echo ps($complaint_status_list,$value['new_status']); ?></b></p>    
          </div>
          <div class="col-md-4 text-right"><p>(<?php echo custDate($value['created_at']); ?>)</p></div>
        </div>

    <?php  }
      else if($value['type'] == 'remark'){ 
           echo '<div class="row">
        <div class="col-md-12 mt-3"><div class="separator-breadcrumb border-top"></div></div>
      </div>';
      ?>
          <div class="row">

            <div class="col-md-12">   
            <div class="row">
            <div class="col-md-8"><p><b>Remark By <?php echo $value['emp_name']; ?>.</b></p> </div>  
            <div class="col-md-4 text-right"><p>(<?php echo custDate($value['created_at']); ?>)</p></div>
            </div>
            </div>


            <div class="col-md-4">   
            <div class="row">
            <div class="col-md-4"><p><b>Status:</b></p> </div>  
            <div class="col-md-8 text-capitalize"><p><?php echo ps($complaint_status_list,$value['new_status']); ?></p></div>
            </div>
            </div>

            <?php if($value['visit_date']){ ?>
            <div class="col-md-4">   
            <div class="row">
            <div class="col-md-4"><p><b>Visit Date:</b></p> </div>  
            <div class="col-md-8 text-capitalize"><p><?php echo custDate($value['visit_date']); ?></p></div>
            </div>
            </div>
          <?php } ?>


         <!--    <div class="col-md-4">   
            <div class="row">
            <div class="col-md-4"><p><b>Date:</b></p> </div>  
            <div class="col-md-8 text-capitalize"><p><?php echo custDate($value['created_at']); ?></p></div>
            </div>
            </div> -->


            <div class="col-md-12">   
            <div class="row">
            <div class="col-md-4"><p><b>Comment: </b></p> </div>  
            <div class="col-md-8"><p><?php echo htmlentities($value['remark']); ?></p></div>
            </div>
            </div>

          </div>
      <?php } ?>
      <?php }//end if remark
      if( $value['employee'] != 'employee') { 
      // if($value['assigned_by'] == 'admin' && $value['type'] == 'assign'){
      if($value['top_dept'] == 1 && $value['type'] == 'assign'){

         echo '<div class="row">
        <div class="col-md-12 mt-3"><div class="separator-breadcrumb border-top"></div></div>
      </div>';

       ?>

      <div class="row">

        <div class="col-md-12">   
          <div class="row">
          <div class="col-md-8"><p><b>Complaint assigned to <?php echo ps($value,'emp_name'); ?>.</b> </p> </div>  
          <div class="col-md-4 text-right"><p>(<?php echo custDate($value['created_at']); ?>)</p></div>
          </div>
        </div>


        <div class="col-md-6">   
        <div class="row">
        <div class="col-md-4"><p><b>Email:</b></p> </div>  
        <div class="col-md-8 "><p><?php echo $value['emp_email']; ?></p></div>
        </div>
        </div>

        <div class="col-md-4">   
        <div class="row">
        <div class="col-md-4"><p><b>Mobile:</b></p> </div>  
        <div class="col-md-8 "><p><?php echo $value['emp_mobile']; ?></p></div>
        </div>
        </div>

       <!--  <div class="col-md-3 d-none1">   
        <div class="row">
        <div class="col-md-4"><p><b>Date:</b></p> </div>  
        <div class="col-md-8 text-capitalize"><p><?php echo custDate($value['created_at']); ?></p></div>
        </div>
        </div> -->

        <?php if($value['conversation_id']){ 
          $chat_text =($complaint['status'] == 4 || $complaint['status'] == 0) ? 'VIEW CHAT' : 'CHAT';

          ?>
            <div class="col-md-2">   
            <div class="row">
            <div class="col-md-8 "><p><a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo site_url('chat/'.$value['conversation_id']); ?>"> <?php  echo $chat_text; ?></a> </p></div>
            </div>
            </div>
        <?php  }  ?>
        

        

      </div>
    <?php } ?>
    <?php
      }//end if assign

     if($value['type'] == 'customer_comment'){
        ?>
      <div class="row">
            <div class="col-md-12 mt-3"><div class="separator-breadcrumb border-top"></div></div>
          </div>

      <div class="row">
        <div class="col-md-12">   
              <div class="row">
              <div class="col-md-8"><p><b>Your Comment</b> </p> </div>  
              <div class="col-md-4 text-right"><p>(<?php echo custDate($value['created_at']); ?>)</p></div>
              </div>
            </div>

            <?php if($value['remark']){ ?>
        <div class="col-md-12">   
          <div class="row">
          <div class="col-md-4"><p><b>Comment:</b></p> </div>  
          <div class="col-md-8"><p><?php echo htmlentities($value['remark']); ?></p></div>
          </div>
        </div>
        <?php } ?>



        <?php if($value['mom_doc']){ ?>
          <div class="col-md-12">   
            <div class="row">
            <div class="col-md-4"><p><b>Attachment:</b></p> </div>  
            <div class="col-md-8"><p>
              <?php

                echo '<a href="'.base_url($value['mom_doc']).'" data-fancybox="gallery" rel="gallery'.$value['id'].'"> View Attachment</a>';

                echo '<a href="'.base_url($value['mom_doc']).'" class="single_download" style="margin-left:10px;" download><i class="i-Download" aria-hidden="true"> </i></a> ';
              
              ?></p></div>
            </div>
          </div>
        <?php } ?>

      </div>
    <?php
    }//end if cust comment

    }//end history foreach

  }//end if history

?>


<?php
$last_cust_status = 0;
$can_action = 0;  

if($complaint['status'] == 2 || $complaint['status'] == 3) {
  $can_action = 1;  
}

if($history){
    $history = array_reverse($history);
    
    if($history[0]['type'] == 'customer'){
      $last_cust_status = 1;
    }
} 
if( $complaint['status'] == 1 && $last_cust_status != 1) {
    $can_action = 1;
}

?>


<?php if($can_action){ ?>

<div class="row">
  <div class="col-md-12 mt-3"><div class="separator-breadcrumb border-top"></div></div>
</div>

<div class="row mb-3">    
  <div class="col-md-4 col-sm-12 form-group mb-3">
  <label for=" ">Action</label>
    <select class="form-control selectType" >
      <option value="">Select</option>
      <?php
        if( $complaint['status'] == 1 && $last_cust_status != 1) {
            echo '<option value="1">Complaint Status</option>';
        }

         if($complaint['status'] == 2 || $complaint['status'] == 3) { 
            echo '<option value="2">Reply To AGENCY09</option>';
         }
      ?>
    </select>
  </div>
</div>
<?php  }  ?> 

<?php

  // if( ! in_array($complaint['status'], [0,4])) { 
  if( $complaint['status'] == 1 && $can_action == 1) { //if status is completed 
?>

<?php echo form_open_multipart('customer/complaint/remark',array('id' => 'remarkForm','class' => 'd-none','autocomplete' => 'off') ); ?>

    <input type="hidden" name="complaint_id" value="<?php echo $complaint['id']; ?>">
    <div class="row ">
        <div class="col-md-4 form-group mb-3">
            <label for=" ">Status</label>
              <select class="form-control"  name="status">
              <option value="">Select Status</option>
              <?php
               /* if($complaint['status'] != 1){
                  echo '<option value="1">Open</option>';
                }*/
              ?>
              
              <option value="1">Completed</option>
              <option value="3">Not Completed</option>
            </select>
        </div>

      <div class="col-md-12 mt-4">
        <button class="btn btn-primary" id="remarkForm_submit" type="submit">Submit</button> 
      </div>
    </div>
  </form>
<?php  }
?>

<!-- start customer comment if  open / ongoing -->
<?php if($complaint['status'] == 2 || $complaint['status'] == 3) { ?>

  <?php echo form_open_multipart('customer/complaint/comment',array('id' => 'commentForm','class' => 'd-none','autocomplete' => 'off') ); ?>

    <input type="hidden" name="complaint_id" value="<?php echo $complaint['id']; ?>">
    <div class="row ">
        <div class="col-md-12 form-group mb-3">
            <label for=" ">Remark / Comment </label>
            <textarea class="form-control" name="comment"></textarea>
        </div>
    </div>

    <div class="row ">
        <div class="col-md-12 form-group mb-3 mom_div">
            <label for=" ">Upload supporting file</label>

            <div class="col-12 input-group inp-group ">
                <div class="input-group-prepend delete_doc" style="cursor: pointer;">
                    <span class="input-group-text " title="Delete">X</span>
                </div>

                <div class="custom-file">
                    <input type="file" class="custom-file-input photo mom_doc" name='mom_doc'   >
                    <!-- accept="image/jpeg,image/gif,image/png,application/pdf"> -->
                    <label class="custom-file-label" for="photo">Choose File (jpg,jpeg,png,pdf,xlsx,doc,docx format)</label>
                </div>
            </div>

            <div class="col-12 preview_div img_thumb " style="display: none;">
                <img src="" class="thumbnail image_preview" />
            </div>
        </div>
    </div>

    <div class="row">
      <div class="col-md-12 mt-4">
        <button class="btn btn-primary" id="commentForm_submit" type="submit">Submit</button> 
      </div>
    </div>
  </form>
<?php } ?>
<!-- end customer comment -->

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

<!-- start esc modal -->
<div class="modal fade" id="escalationModal" tabindex="-1" role="dialog" aria-labelledby="escalationModalTitle-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <?php echo form_open_multipart('customer/complaint/escalation',array('id' => 'escalationForm','autocomplete' => 'off') ); ?>

            <div class="modal-header">
                <h5 class="modal-title" id="escalationModalTitle-2">Send Escalation Mail</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
              <textarea name="remark" id="esc_remark" style="width: 100%;" placeholder="Any Comment(optional)"></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>

                <input type="hidden" name="complaint_id" value="<?php echo $complaint['id']; ?>">
                <button class="btn btn-primary ml-2" type="submit" id="sendEscMail">Send</button>
            </div>
          </form>
        </div>
    </div>
</div>
<!-- end esc modal -->


<?php $this->load->view('common/footer');  ?>

<script src="<?php echo base_url('assets/libs/jquery-validation/jquery.validate.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/libs/jquery-validation/additional-methods.min.js'); ?>"></script>


<?php //if($complaint['files']){ ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<?php //} ?>

<script src="<?php echo base_url('assets/custom.js'); ?>"></script>

<script type="text/javascript">

    $('.selectType').change(function(){
        var val  = this.value;
        $('#remarkForm, #commentForm').addClass('d-none');
        if(val == 1){
          $('#remarkForm').removeClass('d-none');
        }
        else if(val == 2){
          $('#commentForm').removeClass('d-none');
        }
        
  });

  $("#escalationForm").submit(function(e) {
      e.preventDefault(); // avoid to execute the actual submit of the form.
      $('.errors').html('');

      $content = $.trim($('#esc_remark').val());

      var form = $(this);
      var url = form.attr('action');

      showLoading($('#sendEscMail'));
      showLoading($('#esc_button'));

      $.ajax({
          type: 'post',
          url: url,
          data: form.serialize(),
          // processData: false,
          // contentType: false,
          success: function($res) {
              $('#escalationModal').modal('hide')
              if($res.status == 1){
                $('#esc_remark').val('');
                 successPopup($res.message);
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
  });


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

  $('#commentForm').validate({
        ignore: [],
        // debug: true,
        rules: {
            complaint_id: {required: true},
            comment: {required: true},
            // mom_text: {required: false},
            mom_doc: {
                required: false,
                extension: "jpg,jpeg,png,pdf,xlsx,doc,docx",
                filesize: 1, //1MB
            },
        },
        messages: {
           mom_doc: {
                extension: "Please upload only jpg,jpeg,png,pdf,xlsx,doc,docx files",
            },
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
            
            $button = $('#commentForm_submit');
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

  $(document).on('click','.delete_doc',function(){
        $('.custom-file .custom-file-label').html('Choose File (jpg,jpeg,png,pdf,xlsx,doc,docx format)');
        $(".mom_doc").val(null);
        $('.image_preview').attr('src', '');
        $('.img_thumb').hide();
    });

    $(document).on('change','.mom_doc',function(){
        // console.log(this.files);
        $('.image_preview').attr('src', '');
        $('.img_thumb').hide();

        if (this.files && this.files[0]) {
            $maxsize = 1; //mb
            $files = this.files[0];

            // console.log(this.files[0]);  console.log($files.type);

         /* if($files.type == 'image/jpeg' || $files.type == 'image/png'){
          }else{
                alert('Please upload jpeg or png file');
                return false;
          }

          var FileSize = $files.size / 1024 / 1024; // in MB
          if (FileSize > $maxsize) {
              alert('File size exceeds 1 MB');
                return false;
          } */

          var reader = new FileReader();
          reader.onload = function (e) {
                // if($files.type != 'application/pdf'){
                //     $('.image_preview').attr('src', e.target.result);
                //     $('.img_thumb').show();
                // }
              
                $('.custom-file .custom-file-label').html($files.name);
          }
          reader.readAsDataURL($files);
        }
    });
</script>