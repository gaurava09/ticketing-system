<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	$back_url = site_url('equipment');
?>


<div class="">
	<div class="float-left breadcrumb"><h1 class="mr-2">Edit Equipment</h1></div>
	<div class="float-right">
	<a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo $back_url; ?>"><i class="i-Left-3"></i> Back</a>
	</div>
	<div class="clearfix"></div>
</div>



<div class="separator-breadcrumb border-top"></div>


<?php $this->load->view('common/flashmsg'); ?>



<div class="errors"></div>

<?php echo form_open_multipart('equipment/'.$data['id'].'/update',array('id' => 'cmsForm','autocomplete' => 'off') ); ?>


<div class="mt-4 mb-4">  

<div class="row">
<div class="col-md-12 form-group mb-3"><label for=" ">Name</label>
<input class="form-control" type="text" name="name" value="<?php echo $data['name']; ?>" /></div>
</div>

<div class="row model_wrapper">
    <?php
    $models = json_decode($data['model'],true);

    if(json_last_error() == JSON_ERROR_NONE && is_array($models)){
        foreach ($models as $key => $value) {
            if($key == count($models) -1){
                $class = 'i-Add add_model_div';
            }else{
                $class = 'i-Remove remove_model_div';
            }
            echo '<div class="col-md-4 model_div">
                    <div class="row">  
                        <div class="col-10 form-group mb-3">
                            <label for=" ">Model</label><input class="form-control" type="text"  name="model[]" value="'.$value.'" />
                        </div>
                        <div class="col-2 form-group mb-3 pt-4 mt-2"><a href="#"><i class="text-20 '.$class.'"></i></a></div>
                    </div>
                </div>';
        }
    }else{
        echo '<div class="col-md-4 model_div">
                    <div class="row">  
                        <div class="col-10 form-group mb-3">
                            <label for=" ">Model</label><input class="form-control" type="text"  name="model[]" value="'.$data['model'].'" />
                        </div>
                        <div class="col-2 form-group mb-3 pt-4 mt-2"><a href="#"><i class="text-20 i-Add add_model_div"></i></a></div>
                    </div>
                </div>';
    }
    

    ?>
    

</div>

<div class="row">
<div class="col-md-4 form-group mb-3"><label for=" ">Status</label>
	<select class="form-control" id="status" name="status">
		<option value="">Select Status</option>
		<option value="1" <?php echo ($data['status'] == 1) ? "selected" : ""; ?> >Active</option>
		<option value="0" <?php echo ($data['status'] == 0) ? "selected" : ""; ?> >Deactive</option>
	</select>
</div>


<div class="col-md-12 mt-4">
<button class="btn btn-primary float-right" id="submit_form" type="submit">Submit</button>    
</div>

</div>    
</div>
 
</form>

<div class="model_div_html d-none">
    <div class="col-md-4 model_div">
        <div class="row">  
            <div class="col-10 form-group mb-3">
                <label for=" ">Model</label><input class="form-control" type="text" placeholder=" " name="model[]" />
            </div>
            <div class="col-2 form-group mb-3 pt-4 mt-2"><a href="#"><i class="text-20 i-Add add_model_div"></i></a></div>
        </div>
    </div>
</div>

<?php $this->load->view('common/footer');  ?>

<script src="<?php echo base_url('assets/libs/jquery-validation/jquery.validate.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/custom.js'); ?>"></script>

<script type="text/javascript">

    $(document).on('click','.add_model_div', function(e) {
        e.preventDefault();
        $html = $('.model_div_html .model_div').clone();
        if($('.model_wrapper .model_div').length < 20){
           $('.model_wrapper i').removeClass('i-Add');
           $('.model_wrapper i').removeClass('add_model_div');
           $('.model_wrapper i').addClass('i-Remove remove_model_div');
           // $('.model_wrapper i').addClass('remove_model_div');
           $('.model_wrapper').append($html);
        }else{
           alert('you can not add more than 20 models');
        }
    });

    $(document).on('click','.model_wrapper .remove_model_div', function(e) {
        e.preventDefault();
        if($('.model_wrapper .model_div').length > 1){
           $(this).parents('.model_div').remove();
        }else{
           // alert('you can not assign more than 4 at a time');
        }
    });



    $('#cmsForm').validate({
        ignore: [],
        // debug: true,
        rules: {
            name: {required: true},
            'model[]': {required: true},
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
            $button = $("#submit_form");
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
                        window.location.href = "<?php echo site_url('equipment/'.$data['id'].'/edit')?>";
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