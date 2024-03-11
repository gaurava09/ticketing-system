<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<div class="">
<div class="float-left breadcrumb"><h1 class="mr-2">User Profile</h1></div>
<div class="clearfix"></div>
</div>

<div class="separator-breadcrumb border-top"></div>

<?php $this->load->view('common/flashmsg'); ?>
<?php $this->load->view('common/ajaxerror'); ?>


<div class="row">
<div class="col-md-12">
<div class="card mb-4">
<div class="card-body">


<?php echo form_open_multipart('customer/account/',array('id' => 'cmsForm','autocomplete' => 'off') ); ?>
	
<div class="mt-4 mb-4"> 

<div class="row">

     <?php 
        $file_url= "../assets/dist-assets/images/faces/1.jpg";
        $file_name= "";
        if($customer['profile_picture']){
            $file_name = $customer['profile_picture'];
            $file_url = base_url().$customer['profile_picture'];
        }
    ?>
    <input type="hidden" name="prev_file" value="<?php echo $file_name; ?>" id='prev_file'>
    <div class="col-md-3 pr_upld form-group">
        <div class="small-12 medium-2 large-2 columns">
        <div class="crimg_box">  
        <div class="circle">
            <img class="profile-pic" src="<?php echo $file_url; ?>">
        </div>
        <div class="p-image">
        <span><i class="nav-icon i-Pen-2"></i></span>
            <input class="file-upload" type="file" accept="image/*" name="file" />
        </div>
        </div>
        </div>
    </div>


<div class="col-md-9">
<div class="row">
<div class="col-md-6">
<div class="mt-2 mb-2 form-group"><label for=" ">First Name </label><input class="form-control"  type="text" value="<?php echo $customer['first_name']; ?>" name="first_name"></div>
</div>

<div class="col-md-6">
<div class="mt-2 mb-2 form-group"><label for=" ">Last Name</label><input class="form-control" type="text" value="<?php echo $customer['last_name']; ?>"  name="last_name"></div>
</div>

<div class="col-md-6">
<div class="mt-2 mb-2 form-group"><label for=" ">Email </label><input class="form-control"  type="text" value="<?php echo $customer['email']; ?>" readonly=""></div>
</div>

<div class="col-md-6">
    <div class="row">

        <div class="col-md-4">
            <div class="mt-2 mb-2 form-group">
            <label for=" ">Country Code</label>
            <select class="form-control form-control-rounded" id="country_code" name="country_code">
                <option value="">Select</option>
                <?php
                    foreach ($phonecodes as $key => $value) {
                        if($value['phonecode']){
                             $attr = '';
                            if($value['phonecode'] == $customer['country_code']){
                                $attr = 'selected';
                            }
                            echo '<option value="'.$value['phonecode'].'" '.$attr.' >'.$value['phonecode'].' - '.$value['name'].'</option>';
                        }
                    }
                ?>
            </select>
            </div>
        </div>
         <div class="col-md-8">
            <div class="mt-2 mb-2 form-group">
                <label for=" ">Contact Number </label><input class="form-control" type="text" value="<?php echo $customer['mobile']; ?>" name="mobile">
            </div>
        </div>
    </div>
</div>

<div class="col-md-12"><div class="mb-2 form-group">
<label for=" ">Location </label><input class="form-control" type="text" value="<?php echo $customer['location']; ?>" name="location"> 
</div></div>



<div class="col-md-12 mt-4">
<button class="btn btn-primary float-right" id="cmsForm_submit" type="submit">Submit</button>
</div>

</div>

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
<script src="<?php echo base_url('assets/libs/jquery-validation/additional-methods.min.js'); ?>"></script>

<script src="<?php echo base_url('assets/custom.js'); ?>"></script>


<script type="text/javascript">

$(document).ready(function(){

	$('#cmsForm').validate({
        ignore: [],
        // debug: true,
        rules: {
            first_name: {required: true, lettersonly:true},
            last_name: {required: true, lettersonly:true},
            mobile: {required: true, mobile:true},
            country_code: {required: true},
            location: {required: true},
            file: {
                required: false,
                extension: "jpg,jpeg,png",
                filesize: 1, //1MB
              },

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

            $button = $('#cmsForm_submit');
            showLoading($button);

            $.ajax({
              type: 'post',
                url: $(form).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                success: function($res) {
                    if($res.status == 1){
                      window.location.href = "<?php echo site_url('customer/account')?>";
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
<script>
$(document).ready(function() {

    var readURL = function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.profile-pic').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(".file-upload").on('change', function(){
        readURL(this);
    });

    $(".upload-button").on('click', function() {
        $(".file-upload").click();
    });
});
</script>