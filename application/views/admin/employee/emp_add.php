<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$back_url = site_url('employee');

?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style type="text/css">
    .toggle-password{cursor: pointer;}
</style>


<div class="">
	<div class="float-left breadcrumb"><h1 class="mr-2">Add Employee </h1></div>
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

<?php echo form_open_multipart('employee/create',array('id' => 'cmsForm','autocomplete' => 'off') ); ?>
<div class="mt-4 mb-4">  

<div class="row">

<div class="col-md-4 form-group mb-3"><label for=" ">First Name</label>
<input class="form-control" type="text" placeholder=" " name="first_name" /></div>

<div class="col-md-4 form-group mb-3"><label for=" ">Last Name</label>
<input class="form-control" type="text" placeholder=" " name="last_name" /></div>

<div class="col-md-4 form-group mb-3"><label for=" ">Employee Id</label>
<input class="form-control" type="text" placeholder=" " name="emp_id" /></div>

<div class="col-md-4 form-group mb-3"><label for=" ">Email Id </label>
<input class="form-control" type="text" placeholder=" " name="email" /></div>

<div class="col-md-4 form-group mb-3"><label for=" ">Password</label>

<!-- <input class="form-control" type="password" placeholder=" " name="password" /> -->
        <div class="input-group input-group-merge">
            <input class="form-control " type="password" name="password">
            <div class="input-group-append" data-password="false">
                <div class="input-group-text toggle-password">
                    <i class="fa fa-eye " aria-hidden="true"></i>
                </div>
            </div>
        </div>
</div>

<div class="col-md-4 form-group mb-3">
    <div class="row">

        <div class="col-md-5">
            <div class="form-group mb-3">
            <label for=" ">Country Code</label>
            <select class="form-control form-control-rounded" id="country_code" name="country_code">
                <?php
                    foreach ($phonecodes as $key => $value) {
                        if($value['phonecode']){
                             $attr = '';
                            if($value['phonecode'] == '91'){
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
            <div class="form-group mb-3">
                <label for=" ">Contact Number</label>
                <input class="form-control" type="text" placeholder=" " name="mobile" />
            </div>
        </div>
    </div>
</div>


<div class="col-md-4 form-group mb-3"><label for=" ">Designation</label>
    <select class="form-control " id="designation" name="designation">
            <option value="">Select Designation</option>
        <?php
            foreach($designation as  $key => $value){
                echo '<option value="'.$value['id'].'">'.cap($value['name']).'</option>';
            }
        ?>
    </select>
</div>

<div class="col-md-4 form-group mb-3"><label for=" ">Department</label>
    <select class="form-control" id="department" name="department">
            <option value="">Select Department</option>
        <?php
            foreach($department as  $key => $value){
                if( $value['top_dept'] == 1){
                      echo '<option value="'.$value['id'].'">'.$value['name'].' [TOP]</option>';
                  }
                  else{
                      echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
                  }
                // echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
            }
        ?>
    </select>
</div>
<div class="col-md-4 form-group mb-3"><label for=" ">Role</label>
    <select class="form-control " id="role" name="role">
        <option value="">Select Role</option>
        <option value="admin">Admin</option>
        <option value="employee">Employee</option>
    </select>
</div>

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
    $(document).on('click', '.toggle-password', function() {
        $(this).find('i').toggleClass("fa-eye fa-eye-slash");
        var input = $(this).parents('.form-group').find('input');
        input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
    });


	$('#cmsForm').validate({
        ignore: [],
        // debug: true,
        rules: {
            first_name: {required: true, lettersonly:true},
            last_name: {required: true, lettersonly:true},
            emp_id: {required: false,digits :true},
            email: {required: true, validateEmail: true},
            password: {required: true, strongPass:true},
            mobile: {required: true, mobile:true},
            country_code: {required: true},
            designation: {required: true},
            department: {required: true},
        },
        messages: {
            // emp_id:{digits:'Please enter only numbers.'}
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
            hideError();

            showLoading($button);

            $.ajax({
              type: 'post',
                url: $(form).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                success: function( $res) {
      
                    if($res.status == 1){
                      window.location.href = "<?php echo site_url('employee')?>";
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