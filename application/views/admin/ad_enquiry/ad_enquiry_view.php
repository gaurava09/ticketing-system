<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$status_list = enquiry_status_list();
?>



<div class="">
<div class="float-left breadcrumb"><h1 class="mr-2">Enquiry View</h1></div>
<div class="float-right">
	<a class="btn btn-primary export" type="button" href="<?php echo site_url('enquiry/export'); ?>">Export</a>
	<a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo site_url('enquiry'); ?>"><i class="i-Left-3"></i> Back</a>
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
<div class="col-md-4"><p><b>Enquiry No.:</b></p> </div>
<div class="col-md-8"><p><?php echo cap(ps($enquiry,'enquiry_no')); ?></p></div>
</div>
</div>

<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Company:</b></p> </div>
<div class="col-md-8"><p><?php echo cap(ps($customer,'company_name')); ?></p></div>
</div>
</div>


<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Customer:</b></p> </div>  
<div class="col-md-8"><p><?php echo cap(ps($customer,'first_name')).' '.cap(ps($customer,'last_name')); ?></p></div>
</div>
</div>


<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>GA No.:</b></p> </div>
<div class="col-md-8"><p><?php echo ps($enquiry,'ga_no'); ?></p></div>
</div>
</div>


<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Equipment:</b></p> </div>  
<div class="col-md-8"><p><?php echo cap(ps($project,'equipment_name')); ?></p></div>
</div>
</div>

<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Equipment Model:</b></p> </div>  
<div class="col-md-8"><p><?php echo ps($enquiry,'model'); ?></p></div>
</div>
</div>


<?php  if(is_array($spareparts) && !empty($spareparts)){ ?>

<div class="col-md-12">   
<div class="row">
<div class="col-md-2"><p><b>Spareparts:</b></p> </div>  
<div class="col-md-10">
		    <table class="enq_sparepart_table">
        <thead>
            <tr>
                <th>Sr No.</th>
                <th>Name of Spare parts</th>
                <th>Measurement Unit</th>
                <th>Quantity</th>
            </tr>
        </thead>
        
        <tbody>

		<?php 
			if(is_array($spareparts)) {
				foreach ($spareparts as $key => $value) {
					$unit = isset($value['unit']) ? $value['unit'] : '';

					if(isset($value['name'])){
						echo '<tr>';
						echo '<td>'.($key+1).'</td>';
						echo '<td>'.cap($value['name']).'</td>';
						echo '<td>'.cap($unit).'</td>';
						echo '<td>'.$value['qty'].'</td>';
						echo '</tr>';

						// echo '<span>'.cap($value['name']).' ['.$value['qty'].']</span> <br>';
					}
				}
			}else{
				echo $spareparts;
			}
		?>
		</tbody>
    </table>
</div>
</div>
</div>

<?php  } ?>

<?php  if($enquiry['query'] != ''){ ?>
<div class="col-md-12">   
<div class="row">
<div class="col-md-2"><p><b>Additional spare:</b></p> </div>  
<div class="col-md-10"><p><?php echo ps($enquiry,'query'); ?></p></div>
</div>
</div>
<?php  } ?>

<?php /* if($enquiry['remark'] != ''){ ?>
<div class="col-md-12">   
<div class="row">
<div class="col-md-2"><p><b>Remark
<?php
if($user){
	echo 'By '.$user['first_name'];
	if($user['id'] == $this->userid){
		echo '(you)';
	}
}
?>
:</b></p> </div>  
<div class="col-md-10"><p><?php echo $enquiry['remark']; ?></p></div>
</div>
</div>
<?php  } ?>

<?php  if($enquiry['document'] != ''){ ?>
<div class="col-md-12">   
<div class="row">
<div class="col-md-2"><p><b>Attachment:</b></p> </div>  
<div class="col-md-10">
<?php
echo '<a href="'.base_url($enquiry['document']).'" class="single_download" download>Download Attachment <i class="i-Download" aria-hidden="true"> </i></a> ';
?></div>
</div>
</div>
<?php  } */ ?>

<div class="col-md-6">   
<div class="row">
<div class="col-md-4"><p><b>Status:</b></p> </div>  
<div class="col-md-8"><p><?php echo ps($status_list,$enquiry['status']); ?></p></div>
</div>
</div>

<div class="col-md-6">  
<div class="row">
<div class="col-md-4"><p><b>Enquiry Date: </b></p></div> 
<div class="col-md-8"><p><?php echo custDate($enquiry['created_at']); ?></p></div>
</div>
</div>

</div><!--  end row -->


<!-- START Enquiry History -->
<?php
  if($history){
      foreach ($history as $key => $value) {
          if($value['status']){
            continue;
          }
          echo '<div class="row">
            <div class="col-md-12 mt-3"><div class="separator-breadcrumb border-top"></div></div>
          </div>';

          echo '<div class="row">';
         if($value['remark']){
              echo '<div class="col-md-12">   
                    <div class="row">

                      <div class="col-md-8">
                      <p><b>Remark By '.cap($value['first_name'].' '.$value['last_name']);
                        if($value['user_id'] == $this->userid){
                          echo '(you)';
                        }
                                   
                  echo  ':</b></p> </div>  
                        <div class="col-md-4 text-right">
                          <p>('.custDate($value['created_at']).')</p>
                        </div>
                    </div>
                    </div>

                    <div class="col-md-12">   
                    <div class="row">
                      <div class="col-md-4"><p><b>Remark:</b></p> </div>  
                      <div class="col-md-8 text-capitalize"><p>'.$value['remark'].'</p></div>
                    </div>
                    </div>

                  ';
         }
         if($value['document']){
              echo '<div class="col-md-12">   
                    <div class="row">
                    <div class="col-md-4">
                    <p><b>Attachment:</b></p> </div>  
                    <div class="col-md-8"><a href="'.base_url($value['document']).'" class="single_download" download>Download Attachment <i class="i-Download" aria-hidden="true"> </i></a></p></div>
                    </div>
                </div>';
         }
         echo '</div>';


      } //end foreach
  }//end if history
?>

<!-- END Enquiry History -->

<?php 
$can_action = 0;
if($enquiry['status'] != 4){
	$can_action = 1;
}

/*if($enquiry['remark'] != '' && $this->role == 'admin') { 
  $can_action = 0;
}*/

if($can_action){ ?>

<div class="row">
  <div class="col-md-12 mt-3"><div class="separator-breadcrumb border-top"></div></div>
</div>

<div class="row mb-3">    
  <div class="col-md-4 col-sm-12 form-group mb-3">
  <label for=" ">Action</label>
    <select class="form-control selectType" >
      <option value="">Select</option>
      <?php
          if($this->role == 'sales') { 
             echo '<option value="1">Change Status</option>';
         }
       
         // if($enquiry['remark'] == '') { 
            echo '<option value="2">Reply To Customer</option>';
         // }
      ?>
    </select>
  </div>
</div>
<?php  }  ?> 



<?php echo form_open_multipart('enquiry/remark',array('id' => 'remarkForm','class' => 'd-none','autocomplete' => 'off') ); ?>

  <input type="hidden" name="id" value="<?php echo $enquiry['id']; ?>">
  <div class="row ">

      <div class="col-md-12 form-group mb-3">
          <label for=" ">Remark</label>
          <textarea class="form-control" name="remark"></textarea>
      </div>

        <div class="col-md-12 form-group mb-3 mom_div">
            <label for=" ">Upload document</label>

            <div class="col-12 input-group inp-group ">
                <div class="input-group-prepend delete_doc" style="cursor: pointer;">
                    <span class="input-group-text " title="Delete">X</span>
                </div>

                <div class="custom-file">
                    <input type="file" class="custom-file-input photo mom_doc" name='mom_doc'  accept="image/jpeg,image/gif,image/png,application/pdf">
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
      <button class="btn btn-primary" id="remarkForm_submit" type="submit">Submit</button> 
    </div>
  </div>
</form>





<?php echo form_open_multipart('enquiry/status',array('id' => 'statusForm','class' => 'd-none','autocomplete' => 'off') ); ?>

  <input type="hidden" name="id" value="<?php echo $enquiry['id']; ?>">
  <div class="row ">

      <div class="col-md-4 form-group mb-3">
          <label for=" ">Status</label>
            <select class="form-control"  name="status">
            <option value="">Select Status</option>
            <?php
                $status_list = enquiry_status_list();
                  foreach ($status_list as $key => $value) {
                      echo '<option value="'.$key.'">'.$value.'</option>';
                  }
                ?>
          </select>
      </div>


	    <div class="col-md-12 mt-4">
	      <button class="btn btn-primary" id="statusForm_submit" type="submit">Submit</button> 
	    </div>
  </div>
</form>



</div>
</div>
</div><!-- modal body -->
</div>
</div>
</div> <!-- main row -->



<?php $this->load->view('common/footer');  ?>

<script src="<?php echo base_url('assets/libs/jquery-validation/jquery.validate.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/libs/jquery-validation/additional-methods.min.js'); ?>"></script>

<script src="<?php echo base_url('assets/custom.js'); ?>"></script>


<script type="text/javascript">

	$('.selectType').change(function(){
        var val  = this.value;
        $('#remarkForm, #statusForm').addClass('d-none');
        if(val == 1){
          $('#statusForm').removeClass('d-none');
        }
        else if(val == 2){
          $('#remarkForm').removeClass('d-none');
        }
        
  });

	$('.export').click(function(e){
		e.preventDefault();
		$url = $(this).attr('href');
		var params = { 
			id : '<?php echo $enquiry['id']?>', 
		};

		$url = $url+'?'+$.param( params );
		window.location.href= $url;
	});

//forms
 $('#statusForm').validate({
        ignore: [],
        rules: {
            id: {required: true},
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
            
            $button = $('#statusForm_submit');
            showLoading($button);

            $.ajax({
                type: 'post',
                url: $(form).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                success: function($res) {
        
                    if($res.status == 1){
                      window.location.href = "<?php echo site_url('enquiry/'.$enquiry['id'])?>";
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

  $('#remarkForm').validate({
        ignore: [],
        // debug: true,
        rules: {
            id: {required: true},
            remark: {required: true},
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
                       window.location.href = "<?php echo site_url('enquiry/'.$enquiry['id'])?>";
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