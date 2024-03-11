<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	$back_url = site_url('sparepart');
?>
<link href="<?php echo base_url('assets/libs/select2/css/select2.min.css'); ?>" rel="stylesheet" type="text/css">

<div class="">
	<div class="float-left breadcrumb"><h1 class="mr-2">Edit Sparepart</h1></div>
	<div class="float-right">
	<a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo $back_url; ?>"><i class="i-Left-3"></i> Back</a>
	</div>
	<div class="clearfix"></div>
</div>



<div class="separator-breadcrumb border-top"></div>


<?php $this->load->view('common/flashmsg'); ?>



<div class="errors"></div>

<?php echo form_open_multipart('sparepart/'.$data['id'].'/update',array('id' => 'cmsForm','autocomplete' => 'off') ); ?>


<div class="mt-4 mb-4">  

<div class="row">

<div class="col-md-4 form-group mb-3"><label for=" ">Name</label>
<input class="form-control" type="text" name="name" value="<?php echo $data['name']; ?>" />
</div>

<div class="col-md-4 form-group mb-3"><label for=" ">Unit Of Measurement</label>
<input class="form-control" type="text" name="unit" value="<?php echo $data['unit']; ?>" />
</div>

<div class="col-md-4 form-group mb-3"><label for=" ">Equipment Name</label>
    <select class="form-control " id="equipment" name="equipment">
        <option value="" data-model="">Select Equipment</option>
        <?php
            foreach($equipment as  $key => $value){
                if($data['equipment_id'] == $value['id']){
                     echo '<option value="'.$value['id'].'" data-id="'.$value['id'].'" selected>'.cap($value['name']).'</option>';
                }else{
                     echo '<option value="'.$value['id'].'" data-id="'.$value['id'].'">'.cap($value['name']).'</option>';
                }
               
            }
        ?>
    </select>
</div>

<?php
    $current_models = json_decode($data['model'],true);

    if(json_last_error() == JSON_ERROR_NONE && is_array($models)){

    }else{
        $current_models = [];
    }
    ?>

<div class="col-md-4 form-group mb-3"><label for=" ">Equipment Model</label>
    <select class="form-control " id="e_model" name="model[]" multiple>
        <option value="" >Select Model</option>
        <?php
            foreach($models as  $key => $value){ //loop all models
                if($key == $data['equipment_id']){ //show models of selected models only
                    foreach ($value as $k => $model) {
                        if( in_array($model, $current_models)){
                            echo '<option value="'.$model.'"  selected>'.$model.'</option>';
                        }else{
                            echo '<option value="'.$model.'" >'.$model.'</option>';
                        }
                    }
                }
            }
        ?>
    </select>
</div>


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


<?php $this->load->view('common/footer');  ?>
<script src="<?php echo base_url('assets/libs/jquery-validation/jquery.validate.min.js'); ?>"></script>

<script src="<?php echo base_url('assets/libs/select2/js/select2.min.js') ?>"></script>

<script src="<?php echo base_url('assets/custom.js'); ?>"></script>

<script type="text/javascript">

var models =<?php echo json_encode($models );?>;
    
 $(document).ready(function(){
    $("#e_model").select2({
          placeholder: "Select models"
    });

    $('#equipment').on('change', function() {
        $id = $(this).find(":selected").data('id');

        $list = models[$id];

        $html = '<option value="" >Select Model</option>';
        $list.forEach( function (item, index) {
          $html += '<option value="'+item+'" >'+item+'</option>';
        });

        $('#e_model').html($html);
    });

    $('#cmsForm').validate({
        ignore: [],
        // debug: true,
        rules: {
            name: {required: true},
            unit: {required: true},
            model: {required: true},
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
                    if($res.status == 1){
                        window.location.href = "<?php echo site_url('sparepart/'.$data['id'].'/edit')?>";
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

});//end doc
</script>