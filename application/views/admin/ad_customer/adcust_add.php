<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$back_url = site_url('customer');

?>


<div class="">
	<div class="float-left breadcrumb"><h1 class="mr-2">Add Customer </h1></div>
	<div class="float-right">
	<a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo $back_url; ?>"><i class="i-Left-3"></i> Back</a>
	</div>
	<div class="clearfix"></div>
</div>

<div class="separator-breadcrumb border-top"></div>


<div class="row">
<div class="col-md-12">
<div class="card mb-4">
<div class="card-body">


<div class="errors"></div>

<?php echo form_open_multipart('customer/create',array('id' => 'cmsForm','autocomplete' => 'off') ); ?>
<div class="mt-4 mb-4">  

<div class="row">

<div class="col-md-4 form-group mb-3"><label for=" ">First Name</label>
<input class="form-control" type="text" placeholder=" " name="first_name" /></div>

<div class="col-md-4 form-group mb-3"><label for=" ">Last Name</label>
<input class="form-control" type="text" placeholder=" " name="last_name" /></div>

<div class="col-md-4 form-group mb-3"><label for=" ">Email Id </label>
<input class="form-control" type="text" placeholder=" " name="email" /></div>

<div class="col-md-4 form-group mb-3">

    <div class="row">

    <div class="col-md-5">
        <div class="form-group">
        <label for=" ">Country Code</label>
        <select class="form-control form-control-rounded" id="country_code" name="country_code">
            <?php
                foreach ($phonecodes as $key => $value) {
                    if($value['phonecode']){
                         $attr = '';
                        if($value['phonecode'] == 91){
                            $attr = 'selected';
                        }
                        echo '<option value="'.$value['phonecode'].'" '.$attr.' >'.$value['phonecode'].' - '.$value['name'].'</option>';
                    }
                }
            ?>
        </select>
        </div>
    </div>

    <div class="col-md-7">
    <div class="form-group">
        <label for="mobile">Contact Number</label>
            <input class="form-control " id="mobile"  name="mobile">
        </div>
    </div>

    </div>

</div>


<div class="col-md-4 form-group mb-3"><label for=" ">Location </label>
<input class="form-control" type="text" placeholder=" " name="location" /></div>

<div class="col-md-4 form-group mb-3"><label for=" ">Company</label>
    <select class="form-control " id="company" name="company">
            <option value="">Select Company</option>
        <?php
            foreach($company as  $key => $value){
                echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
            }
        ?>
    </select>
</div>


<div class="col-md-4 form-group mb-3"><label for=" ">Password</label>
<input class="form-control" type="password" placeholder=" " name="password" /></div>


<div class="col-md-12 mt-4">
<button class="btn btn-primary float-right" id="submit_form" type="submit">Submit</button>    
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
	$('#cmsForm').validate({
        ignore: [],
        // debug: true,
        rules: {
            first_name: {required: true, lettersonly:true},
            last_name: {required: true, lettersonly:true},
            email: {required: true, validateEmail: true},
            password: {required: true, strongPass:true},
            mobile: {required: true, mobile:true},
            country_code: {required: true},
            location: {required: true},
            company: {required: true},
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
            $button = $("#submit_form");
            showLoading($button);

            $.ajax({
              type: 'post',
                url: $(form).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                success: function( $res) {
      
                    if($res.status == 1){
                      window.location.href = "<?php echo site_url('customer')?>";
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