<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	$back_url = site_url('employee');
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<div class="">
	<div class="float-left breadcrumb"><h1 class="mr-2">Edit A09 Employee</h1></div>
	<div class="float-right">
	<a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo $back_url; ?>"><i class="i-Left-3"></i> Back</a>
	</div>
	<div class="clearfix"></div>
</div>



<div class="separator-breadcrumb border-top"></div>


<?php $this->load->view('common/flashmsg'); ?>



<div class="errors"></div>

<?php echo form_open_multipart('employee/'.$data['id'].'/update',array('id' => 'cmsForm','autocomplete' => 'off') ); ?>


<div class="mt-4 mb-4">  

<div class="row">

<div class="col-md-4 form-group mb-3"><label for=" ">First Name</label>
<input class="form-control" type="text" name="first_name" value="<?php echo $data['first_name']; ?>" /></div>

<div class="col-md-4 form-group mb-3"><label for=" ">Last Name</label>
<input class="form-control" type="text"  name="last_name" value="<?php echo $data['last_name']; ?>" /></div>

<div class="col-md-4 form-group mb-3"><label for=" ">Employee Id</label>
<input class="form-control" type="text"  name="emp_id" value="<?php echo $data['emp_id']; ?>" /></div>

<div class="col-md-4 form-group mb-3"><label for=" ">Email Id </label>
<input class="form-control" type="text"  name="email" value="<?php echo $data['email']; ?>" /></div>



<div class="col-md-4 form-group mb-3">
    <div class="row">

        <div class="col-md-5">
            <div class="form-group mb-3">
            <label for=" ">Country Code</label>
            <select class="form-control form-control-rounded" id="country_code" name="country_code">    
                <option value="" >Select</option>
                <?php
                    foreach ($phonecodes as $key => $value) {
                        if($value['phonecode']){
                             $attr = '';
                            if($value['phonecode'] == $data['country_code']){
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
                <input class="form-control" type="text" placeholder=" " name="mobile" value="<?php echo $data['mobile']; ?>" />
            </div>
        </div>
    </div>
</div>



<div class="col-md-4 form-group mb-3"><label for=" ">Designation</label>
    <select class="form-control " id="designation" name="designation">
        <option value="">Select Designation</option>
        <?php
            foreach($designation as  $key => $value){
                if($value['id'] == $data['designation_id']){
                    echo '<option value="'.$value['id'].'" selected>'.cap($value['name']).'</option>';
                }else{
                    echo '<option value="'.$value['id'].'">'.cap($value['name']).'</option>';
                }
            }
        ?>
    </select>
</div>

<div class="col-md-4 form-group mb-3"><label for=" ">Department</label>
    <select class="form-control" id="department" name="department">
        <option value="">Select Department</option>
        <?php
            foreach($department as  $key => $value){
                 $postfix = ($value['top_dept'] == 1) ? '[TOP]' : '';
                 if($value['id'] == $data['department_id']){
                    echo '<option value="'.$value['id'].'" selected>'.$value['name'].' '.$postfix.'</option>';
                }else{
                    echo '<option value="'.$value['id'].'">'.$value['name'].' '.$postfix.'</option>';
                }
            }
        ?>
    </select>
</div>
<div class="col-md-4 form-group mb-3"><label for=" ">Role</label>
    <select class="form-control " id="role" name="role">
        <option value="">Select Role</option>
        <option value="admin" <?php echo ($data['role'] == 'admin') ? "selected" : ""; ?> >Admin</option>
        <option value="employee" <?php echo ($data['role'] == 'employee') ? "selected" : ""; ?> >Employee</option>
    </select>
</div>

<div class="col-md-4 form-group mb-3"><label for=" ">Status</label>
	<select class="form-control" id="status" name="status">
		<option value="">Select Status</option>
		<option value="1" <?php echo ($data['status'] == 1) ? "selected" : ""; ?> >Active</option>
		<option value="0" <?php echo ($data['status'] == 0) ? "selected" : ""; ?> >Deactive</option>
	</select>
</div>

</div>

<div class="row">
    <div class="col-md-4 form-group mt-4">
        <input type="checkbox" id="change_pass" name="change_pass" value="1">
        <label for="change_pass">Change Password</label><br>
    </div>


    <div class="col-md-4 form-group pass_div op1">
        <label for=" ">Password</label>
        <!-- <input class="form-control" type="password"  name="password" value="" /> -->

        <div class="input-group input-group-merge">
            <input class="form-control " type="password" name="password">
            <div class="input-group-append" data-password="false">
                <div class="input-group-text toggle-password">
                    <i class="fa fa-eye " aria-hidden="true"></i>
                </div>
            </div>
        </div>

    </div>



</div>

<div class="row">
<div class="col-md-12 mt-4">
<button class="btn btn-primary float-right" id="submit_form" type="submit">Submit</button>    
</div>

</div>    
</div>
 
</form>
<style type="text/css">
    .op1{
        opacity: 0;
    }
    .toggle-password{cursor: pointer;}
</style>

<?php $this->load->view('common/footer');  ?>
<script src="<?php echo base_url('assets/libs/jquery-validation/jquery.validate.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/custom.js'); ?>"></script>

<script type="text/javascript">
    $(document).on('click', '.toggle-password', function() {
        $(this).find('i').toggleClass("fa-eye fa-eye-slash");
        var input = $(this).parents('.form-group').find('input');
        input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
    });

    $('#change_pass').change(function() {
        if(this.checked) {
            $('.pass_div').removeClass('op1');
        }else{
            $('.pass_div').addClass('op1');
        }
    });

    $('#cmsForm').validate({
        ignore: [],
        // debug: true,
        rules: {
            first_name: {required: true, lettersonly:true},
            last_name: {required: true, lettersonly:true},
            emp_id: {required: false,digits :true},
            email: {required: true, validateEmail: true},
            password: {
                required: function(){
                    if($('input[name="change_pass"]:checked').length){
                        return true
                    }else{
                        return false;
                    }
                }, 
                strongPass:true
            },
            mobile: {required: false, mobile:true},
            country_code: {required: true},
            country_code: {required: true},
            designation: {required: true},
            department: {required: true},
            status: {required: true},
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
                    // $res = JSON.parse(response);
                    if($res.status == 1){
                        window.location.href = "<?php echo site_url('employee/'.$data['id'].'/edit')?>";
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