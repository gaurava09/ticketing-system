<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '') {
//   	$back_url = $_SERVER['HTTP_REFERER'];
// }else{
	$back_url = site_url('department');
// }
?>


<div class="">
	<div class="float-left breadcrumb"><h1 class="mr-2">Add Department </h1></div>
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

<?php echo form_open_multipart('department/create',array('id' => 'addDept','autocomplete' => 'off') ); ?>
<div class="mt-4 mb-4">  

<div class="row">

<div class="col-md-4 form-group mb-3"><label for=" ">Name</label>
<input class="form-control" id="dept_name" type="text" placeholder=" " name="name" /></div>

<div class="col-md-4 form-group mb-3"><label for=" ">Top Dept</label>
    <br>
<input type="radio" name="topdept" value="1">
<label for="male">Yes</label> &nbsp;&nbsp;

<input type="radio"  name="topdept" value="0">
<label for="female">No</label>

</div>



<div class="col-md-12 mt-4">
<button class="btn btn-primary float-right" id="submit_edit" type="submit">Submit</button>    
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
	$('#addDept').validate({
        ignore: [],
        // debug: true,
        rules: {
            name: {required: true},
            topdept: {required: true},
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
            $button = $("#submit_edit");
            showLoading($button);

            $.ajax({
              type: 'post',
                url: $(form).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                success: function($res) {
                    
                    if($res.status == 1){
                      window.location.href = "<?php echo site_url('department')?>";
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