<?php
defined('BASEPATH') OR exit('No direct script access allowed');


// if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '') {
//   	$back_url = $_SERVER['HTTP_REFERER'];
// }else{
	$back_url = site_url('company');
// }
?>


<div class="">
	<div class="float-left breadcrumb"><h1 class="mr-2">Edit Company</h1></div>
	<div class="float-right">
	<a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo $back_url; ?>"><i class="i-Left-3"></i> Back</a>
	</div>
	<div class="clearfix"></div>
</div>



<div class="separator-breadcrumb border-top"></div>


<?php $this->load->view('common/flashmsg'); ?>



<div class="errors"></div>

<?php echo form_open_multipart('company/'.$data['id'].'/update',array('id' => 'CompanyForm','autocomplete' => 'off') ); ?>
<div class="mt-4 mb-4">  

<div class="row">

    <div class="col-md-4 form-group mb-3"><label for=" ">Name</label>
    <input class="form-control"  type="text" placeholder=" " name="name" value="<?php echo $data['name']; ?>" /></div>

    <div class="col-md-4 form-group mb-3"><label for=" ">Domain</label>
        <input class="form-control"  type="text" placeholder=" " name="domain" value="<?php echo $data['domain']; ?>" />
        <p>Add domain name without protocol i.e http:// or https:// and www</p>
    </div>


    <div class="col-md-4 form-group mb-3"><label for=" ">Location</label>
    <input class="form-control"  type="text" placeholder=" " name="location" value="<?php echo $data['location']; ?>" /></div>



    <div class="col-md-4 form-group mb-3"><label for=" ">Status</label>
    	<select class="form-control" id="status" name="status">
    		<option value="">Select Status</option>
    		<option value="1" <?php echo ($data['status'] == 1) ? "selected" : ""; ?> >Active</option>
    		<option value="0" <?php echo ($data['status'] == 0) ? "selected" : ""; ?> >Deactive</option>
    	</select>
    </div>
</div>

<div class="col-md-12 mt-4">
<button class="btn btn-primary float-right" id="submit_dept" type="submit">Submit</button>    
</div>

</div>    
 
</form>


<?php $this->load->view('common/footer');  ?>
<script src="<?php echo base_url('assets/libs/jquery-validation/jquery.validate.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/custom.js'); ?>"></script>


<script type="text/javascript">
	$('#CompanyForm').validate({
        ignore: [],
        // debug: true,
        rules: {
            name: {required: true},
            domain: {required: true},
            location: {required: true},
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
            var formData = new FormData(form);
            $button = $("#submit_dept");
            showLoading($button);

            $.ajax({
              type: 'post',
                url: $(form).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                success: function($res) {
      
                    if($res.status == 1){
                      window.location.href = "<?php echo site_url('company/'.$data['id'].'/edit')?>";
                      return false;
                    }
                    else{
                    	showError($res.message);
                        // errorPopup($res.message);
                    }
                    stopLoading();
                },
                error: function(error, textStatus, errorMessage) {
                    console.log(error);
                    showError('Request could not be completed');
                    stopLoading();
                }             
            });
        }

    });
</script>