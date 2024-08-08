<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link href="<?php echo base_url('assets/libs/flatpickr/flatpickr.min.css'); ?>" rel="stylesheet" type="text/css" />

<link href="<?php echo base_url('assets/libs/jquery-ui/jquery-ui.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('assets/libs/file-upload/file-upload-with-preview.min.css'); ?>" rel="stylesheet" type="text/css">


<div class="">
<div class="float-left breadcrumb"><h1 class="mr-2">Add Ticket</h1></div>
<div class="float-right"><a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo site_url('complaint'); ?>"><i class="i-Left-3"></i> Back</a></div>
<div class="clearfix"></div>
</div>

<div class="separator-breadcrumb border-top"></div>


<div class="row">
<div class="col-md-12">
<div class="card mb-4">
<div class="card-body">


<?php echo form_open_multipart('complaint/create',array('id' => 'addComplaint','autocomplete' => 'off') ); ?>

<div class="errors"></div>

<div class="mt-4 mb-4">  


<div class="row">

<div class="col-md-4 form-group mb-3"><label for=" ">Company Name </label>
	<select class="form-control" id="comapny" name="company">
		<option value="">Select Company</option>
	    <?php
	    	foreach ($company as $key => $value) {
	    		echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
	    	}
	    ?>
	</select>
</div>

<div class="col-md-4 form-group mb-3"><label for=" "> Customer Name </label>
	<select class="form-control" id="customer" name="customer">
		<!-- <option value="">Select Customer</option> -->
	</select>
</div>

<!-- <div class="col-md-4 form-group mb-3"><label for=" ">GA Number </label>
<input class="form-control" id="ga_no" type="text" placeholder="GA No." name="ga_no" />
 <a href="<?php echo base_url('assets/guide to check GA number_MTCSPL.pdf') ?>" target="blank">Guide to check Equipment GA No.</a>
</div>

<div class="col-md-12 mt-3"><div class="separator-breadcrumb border-top"></div></div>

<div class="col-md-4 form-group mb-3"><label for=" ">Equipment Name </label>
<input class="form-control" id="equipment_name" type="text" placeholder="Name of Equipment" readonly="" /></div>

<div class="col-md-4 form-group mb-3"><label for=" "> Model Number </label>
<input class="form-control" id="equipment_model" type="text" placeholder="Equipment Model" readonly="" /></div> -->

<!-- <div class="col-md-4 form-group mb-3"><label for=" ">Gurantee Validity</label>
<input class="form-control" id="guarantee_valid" type="text" placeholder=" " name="guarantee_valid" /></div> -->


<!-- <div class="col-md-4 form-group mb-3"><label for=" ">Customer Equipment Number</label>
<input class="form-control" id="cust_equipment_no" type="text" placeholder=" " name="cust_equipment_no" /></div> -->

</div>


<div class="col-md-12 form-group  mt-3">
<div class="row">
<div class="col-md-3 form-group mb-3 ">
<label for=" ">Ticket Type</label>
</div>

<div class="col-md-9 form-group mb-3">
<div class="row">
    <div class="col-md-12 form-group mb-3">
        <select class="form-control" id="complaint_type" name="complaint_type">
        	<option value="">Select Ticket Type</option>
            <?php
            $complaint_types = complaint_types();
            	foreach ($complaint_types as $key => $value) {
            		echo '<option value="'.$key.'">'.$value.'</option>';
            	}
            ?>
        </select>
    </div>

    <div class="col-md-6 form-group mb-3 order_div d-none">
       <label for=" ">Purchase Order No. (if applicable)</label>
		<input class="form-control" id="order_no" type="text" placeholder="Purchase order no." name="order_no" />
	</div>
	
<div class="col-md-6 form-group mb-3 order_div d-none">
  </div>

<div class="col-md-12 form-group date_div d-none">
    <label for=" "><b>Required Visit Date</b></label>
  </div>

  <div class="col-md-6 form-group mb-3 date_div d-none">
    <label for=" ">From</label>
    <input class="form-control" id="from" type="text" placeholder="From Date*" name="from_date"/>
  </div>

  <div class="col-md-6 form-group mb-3 date_div d-none">
     <label for=" ">To</label>
    <input class="form-control" id="to" type="text" placeholder="To Date*" name="to_date"/>
  </div>

    <div class="col-md-12 form-group mb-3 desc_div d-none">
      <label for=" ">Description</label>
        <textarea class="form-control" placeholder="Description*" id="description" name="description"></textarea>
    </div>
    

    <div class="col-md-12 form-group mb-3 file_div d-none">
    	<div class="col-md-12 mb-5 mt-5">
  		  <div class="custom-file-container" data-upload-id="compImages">
  		        <label for="images">Upload Images  <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
  		        <p> <i>[Note - You can upload max 3 images. Per image size must be less than 3 MB]</i></p>

  		        <label class="custom-file-container__custom-file" >
  		            <input type="file" class="custom-file-container__custom-file__custom-file-input" multiple accept="image/jpeg,image/png"  aria-label="Choose Files"   id="allimages" >

  		            <input type="hidden" name="MAX_FILE_SIZE" value="" />
  		            <span class="custom-file-container__custom-file__custom-file-control"></span>
  		        </label>
  		        <div class="custom-file-container__image-preview"></div>

  		    </div>
  		</div> 
    </div>
   	
   	<div class="col-md-12 form-group mb-3 cc_div">
      <label>Please enter e-mail ID’s to whom you want to include in this communication from your organisation</label>
        <div class="row cc_row">  
            <div class="col-6 form-group">
          <input class="form-control" type="text" placeholder="Add cc" name="email_cc[]"/>
            </div>
          <div class="col-2 form-group  pt-1 "><a href="#"><i class="text-20 i-Add add_cc"></i></a></div>
        </div>
    </div>

     <div class="col-md-12 step2_div"><button class="btn btn-primary float-right" type="submit" id="create_buton">Submit</button></div>
 </div>
    


</div>

</div>
</div>  
<!-- end step 2 -->

</form>
</div>
</div>
</div>
   
  
</div>


<div class="cc_html d-none">
  <div class="row cc_row">  
        <div class="col-6 form-group">
      <input class="form-control" type="text" placeholder="Add cc" name="email_cc[]"/>
        </div>
      <div class="col-2 form-group  pt-1 "><a href="#"><i class="text-20 i-Remove remove_cc"></i></a></div>
    </div>
</div>

<?php $this->load->view('common/footer');  ?>

<script src="<?php echo base_url('assets/libs/jquery-validation/jquery.validate.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/libs/jquery-validation/additional-methods.min.js'); ?>"></script>

<script src="<?php echo base_url('assets/libs/flatpickr/flatpickr.min.js');?>"></script>

<script src="<?php echo base_url('assets/libs/jquery-ui/jquery-ui.min.js') ?>"></script>>

<script src="<?php echo base_url('assets/custom.js'); ?>"></script>

<script src="<?php echo base_url('assets/libs/file-upload/file-upload-with-preview.min.js'); ?>"></script>


<script type="text/javascript">

var myFiles = [];

$(document).ready(function(){
	$('.add_cc').click(function(e){
        e.preventDefault();
        $html = $('.cc_html .cc_row').clone();
        if($('.cc_div .cc_row').length < 9){
          $('.cc_div').append($html);
        }else{
           alert('you can not add more than 10 cc');
        }
    });

    $(document).on('click','.cc_div .remove_cc', function(e) {
        e.preventDefault();
        
        if($('.cc_div .cc_row').length > 1){
           $(this).parents('.cc_row').remove();
        }else{
           // alert('you can not assign more than 4 at a time');
        }
    });

	var todate = $("#to").flatpickr(
    {   
      minDate: new Date().fp_incr(1),
      maxDate: new Date().fp_incr(60) 
    }
  );

  $("#from").flatpickr(
    {   //mode: "range",
      // minDate: 'today',
      minDate: new Date().fp_incr(1),
      maxDate: new Date().fp_incr(60) ,
      onChange: function (dateStr, dateObj) {
            todate.set("minDate", dateObj);
            todate.setDate(dateObj);
          }
    }
  );

	$('#complaint_type').on('change',function(){
    var val = this.value;

    $('.order_div, .date_div, .desc_div, .file_div').addClass('d-none');

    if(val == 1){
      $('.desc_div, .file_div').removeClass('d-none');
    }
    else if(val == 2){
      $('.desc_div, .file_div').removeClass('d-none');
    }
    else if(val == 3 ){
      $('.desc_div, .file_div').removeClass('d-none');
    }
    else if(val == 4){
      $('.desc_div, .file_div').removeClass('d-none');
    }
    else if(val == 5){
      $('.desc_div, .file_div').removeClass('d-none');
    }
  });

	
	//start file upload
	var imageUpload = new FileUploadWithPreview('compImages',
        {
          showDeleteButtonOnImages: true,
	          text: {
	              chooseFile: "Select one or more images to upload",
	              // browse: "Custom Button Copy",
	              selectedCount: "images selected",
	          },
	        }
	      );
	  imageUpload.clearPreviewPanel();

	  window.addEventListener('fileUploadWithPreview:imagesAdded', function(e) {
	    console.log(e.detail.uploadId);
	    if (e.detail.uploadId === 'compImages') {
	        if(e.detail.cachedFileArray.length > 5){
	          alert('Max 5 images are allowed to upload.');
	          // imageUpload.clearPreviewPanel();
	        }
	        myFiles = e.detail.cachedFileArray;
	        console.log(e.detail.cachedFileArray); //current all images
	        console.log(e.detail.addedFilesCount); // current selected images
	    }
	});


	$('#comapny').on('change',function(){
		var val = $.trim(this.value);

		if(val ==''){
			$('#customer').empty();
			//$('#ga_no, #equipment_name, #equipment_model').val('');
			//autoGA({});
			return false;
		}

		var data = {'id' : val};

    hideError();
		$.ajax({
          	type: 'get',
            url: "<?php echo site_url('complaint/company')?>",
            data: data,
            success: function($res) {
                if($res.status == 1){
                	console.log($res);
                	$html = '';
                	$html += '<option value="">Select Customer</option>';
                	$.each($res.data.customers, function(i,v){
                		$html += '<option value="'+v.id+'">'+v.first_name+' '+v.last_name+'</option>';
                	});

                	$('#customer').html($html);
                	//autoGA($res.data.projects);
                }
                else{
                  $('#customer').empty();
                  //$('#ga_no, #equipment_name, #equipment_model').val('');
                  //autoGA({});
                	showError($res.message);
                }
                // stopLoading();
            },
            error: function(error, textStatus, errorMessage) {
                showError('Request could not be completed');
                stopLoading();
            }             
        });
		
	});


	function autoGA(ga_no_list){
    	$( "#ga_no" ).autocomplete({
      		source: ga_no_list,
      		 focus: function( event, ui ) {
                  $( "#ga_no" ).val( ui.item.value );
                    return false;
               },
      		select: function( event, ui ) {
      			console.log(ui.item);
      			$( "#ga_no" ).val( ui.item.value );
                $( "#equipment_name" ).val( ui.item.equipment_name );
                $( "#equipment_model" ).val( ui.item.equipment_model );
                  return false;
            }
   		});
    }

	 //start submission
	$('#addComplaint').validate({
        ignore: [],
        // debug: true,
        rules: {
            company: {required: true},
            customer: {required: true},
            //ga_no: {required: true},
            //cust_equipment_no: {required: false},
            complaint_type: {required: true},
            description: {required: true},

            // order_no: {required: false},
            // email_cc: {required: false, validateEmail:true},
     		   /*order_no: {
                  required: function(){
                      $val = $('#complaint_type').find(':selected').val();
                      if($val == 1){
                          return true
                      }else{
                          return false;
                      }
                  }, 
              },
*/
          //   from_date: {
          //         required: function(){
          //             $val = $('#complaint_type').find(':selected').val();
          //             if($val == 1 || $val == 3){
          //                 return true
          //             }else{
          //                 return false;
          //             }
          //         }, 
          //     },

     		   // to_date: {
          //       required: function(){
          //            $val = $('#complaint_type').find(':selected').val();
          //             if($val == 1 || $val == 3){
          //                 return true
          //             }else{
          //                 return false;
          //             }
          //       }, 
          //   },
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
        	var imageLimit = 3;
          var maxfilesize = 3;

          var formData = new FormData(form);

          $error_html = '';

            //validate emails
            $('.cc_row').each(function(i,v){ 
              $ccemail = $.trim($(v).find('input').val());
              if($ccemail != ''){
                if(!nilesh.validateEmail($ccemail)){
                  $error_html += '<div class="error"> Invalid Email ['+$ccemail+' ]</div>';
              }
              }
            });

          	for (var i = 0; i < myFiles.length; i++) {
              	formData.append('file[]', myFiles[i]); 
          	}

          	//validate file count
	        if(myFiles.length > imageLimit){
	            $error_html += '<div class="error"> You can not upload more than '+imageLimit+' images</div>';
	        }

	        
          for (var i = 0; i < myFiles.length; i++) {
              var FileSize = myFiles[i].size / 1024 / 1024; // in MB
              if (FileSize > maxfilesize) {
                  $error_html += '<div class="error"> File size exceeds '+maxfilesize+' MB [ '+myFiles[i].name+' ]</div>';
              } else{
                  // formData.append('file[]', myFiles[i]); // append all our files to it
              }
            }

	        if($error_html){
	            $('.errors').html($error_html);
	            return false;
	        }

            $button = $('#create_buton');
            showLoading($button);

            $.ajax({
              type: 'post',
                url: $(form).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                success: function($res) {
         
                    if($res.status == 1){
                      window.location.href = "<?php echo site_url('complaint')?>";
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
