<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style type="text/css">
    .toggle-password{cursor: pointer;}
</style>

<div class="">
	<div class="float-left breadcrumb"><h1 class="mr-2">Change Password </h1></div>
	<div class="clearfix"></div>
</div>

<div class="separator-breadcrumb border-top"></div>

<?php $this->load->view('common/flashmsg'); ?>
<?php $this->load->view('common/ajaxerror'); ?>


<div class="row">
<div class="col-md-12">
<div class="card mb-4">
<div class="card-body">

<?php echo form_open_multipart('customer/change-password',array('id' => 'cmsForm','autocomplete' => 'off') ); ?>

<div class=" mb-4">  

<div class="row">

<div class="col-md-6 col-sm-12">
    <div class="mt-2 mb-2 form-group"><label for=" ">Old Password </label>
        <!-- <input class="form-control"  type="password" value="" name="old_pass"> -->

        <div class="input-group input-group-merge">
                <input class="form-control " type="password" name="old_pass">
                <div class="input-group-append" data-password="false">
                    <div class="input-group-text toggle-password">
                        <i class="fa fa-eye " aria-hidden="true"></i>
                    </div>
                </div>
            </div>

    </div>
</div>
</div>

<div class="row">
<div class="col-md-6 col-sm-12">
<div class="mt-2 mb-2 form-group"><label for=" ">New Password</label>
    <!-- <input class="form-control" type="password" name="pass_1" id="pass_1"> -->

    <div class="input-group input-group-merge">
            <input class="form-control " type="password" name="pass_1" id="pass_1">
            <div class="input-group-append" data-password="false">
                <div class="input-group-text toggle-password">
                    <i class="fa fa-eye " aria-hidden="true"></i>
                </div>
            </div>
        </div>

</div>
</div>
</div>

<div class="row">
<div class="col-md-6 col-sm-12">
<div class="mt-2 mb-2 form-group"><label for=" ">Confirm  Password </label>
    <!-- <input class="form-control"  type="password" name="pass_2"> -->

    <div class="input-group input-group-merge">
            <input class="form-control " type="password" name="pass_2">
            <div class="input-group-append" data-password="false">
                <div class="input-group-text toggle-password">
                    <i class="fa fa-eye " aria-hidden="true"></i>
                </div>
            </div>
        </div>

</div>
</div>
</div>

<div class="row">
<div class="col-md-6 col-sm-12 mt-4">
<button class="btn btn-primary float-right" id="cmsForm_submit" type="submit">Submit</button>    
</div>
</div>

</div>    
 
</form>

</div>
</div>
</div>
</div>


<?php $this->load->view('common/footer');  ?>

<script src="<?php echo base_url('assets/libs/jquery-validation/jquery.validate.min.js'); ?>"></script>

<script src="<?php echo base_url('assets/custom.js'); ?>"></script>

<script type="text/javascript">
     $(document).on('click', '.toggle-password', function() {
        $(this).find('i').toggleClass("fa-eye fa-eye-slash");
        var input = $(this).parents('.form-group').find('input');
        input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
    });

	$('#cmsForm').validate({
        ignore: [],
        // debug: true,
        rules: {
            old_pass: {required: true},
            pass_1: {required: true, strongPass:true},
            pass_2: {required: true, equalTo : "#pass_1"},
        },
        messages: {
            pass_2 :{
                'equalTo' : 'password does not match'
            }
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
            $button = $("#cmsForm_submit");
            showLoading($button);

            $.ajax({
              type: 'post',
                url: $(form).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                success: function($res) {
                    if($res.status == 1){
                      window.location.href = "<?php echo site_url('customer/change-password')?>";
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

    });
</script>