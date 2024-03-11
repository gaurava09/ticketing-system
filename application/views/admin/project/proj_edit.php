<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	$back_url = site_url('project');
?>
<link href="<?php echo base_url('assets/libs/flatpickr/flatpickr.min.css'); ?>" rel="stylesheet" type="text/css" />


<div class="">
	<div class="float-left breadcrumb"><h1 class="mr-2">Edit Project</h1></div>
	<div class="float-right">
	<a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo $back_url; ?>"><i class="i-Left-3"></i> Back</a>
	</div>
	<div class="clearfix"></div>
</div>



<div class="separator-breadcrumb border-top"></div>


<?php $this->load->view('common/flashmsg'); ?>



<div class="errors"></div>

<?php echo form_open_multipart('project/'.$data['id'].'/update',array('id' => 'cmsForm','autocomplete' => 'off') ); ?>


<div class="mt-4 mb-4">  

<div class="row">


<div class="col-md-4 form-group mb-3"><label for=" ">GA No.</label>
<input class="form-control" type="text" placeholder=" " name="ga_no" value="<?php echo $data['ga_no']; ?>" /></div>

<div class="col-md-4 form-group mb-3"><label for=" ">Equipment Name</label>
    <select class="form-control " id="equipment" name="equipment">
        <option value="" data-model="">Select Equipment</option>
        <?php
            $model = '';
            foreach($equipment as  $key => $value){
                if($value['id'] == $data['equipment_id']){
                    echo '<option value="'.$value['id'].'" data-id="'.$value['id'].'" selected>'.$value['name'].'</option>';
                    $model = $value['model'];
                }else{
                    echo '<option value="'.$value['id'].'" data-id="'.$value['id'].'">'.$value['name'].'</option>';
                }
            }
        ?>
    </select>
</div>

<div class="col-md-4 form-group mb-3"><label for=" ">Equipment Model</label>
    <select class="form-control " id="e_model" name="model">
        <option value="" >Select Model</option>
        <?php
            foreach($models as  $key => $value){
                if($key == $data['equipment_id']){
                    foreach ($value as $k => $v) {
                        if($data['model'] == $v){
                            echo '<option value="'.$v.'"  selected>'.$v.'</option>';
                        }else{
                            echo '<option value="'.$v.'" >'.$v.'</option>';
                        }
                    }
                }
            }
        ?>
    </select>
</div>

<div class="col-md-4 form-group mb-3"><label for=" ">Project No.</label>
<input class="form-control" type="text" placeholder=" " name="project_code" value="<?php echo $data['project_code']; ?>"/></div>


<div class="col-md-4 form-group mb-3"><label for=" ">Company 1</label>
    <select class="form-control " id="company" name="company">
        <option value="">Select Company 1</option>
        <?php
            foreach($company as  $key => $value){
                if($value['id'] == $data['company_id']){
                    echo '<option value="'.$value['id'].'" selected>'.cap($value['name']).'</option>';
                }else{
                    echo '<option value="'.$value['id'].'">'.cap($value['name']).'</option>';
                }
            }
        ?>
    </select>
</div>

<div class="col-md-4 form-group mb-3"><label for=" ">Company 2 (optional)</label>
    <select class="form-control " id="company_2" name="company_2">
        <option value="">Select Company 2</option>
        <?php
            foreach($company as  $key => $value){
                if($value['id'] == $data['company_id_2']){
                    echo '<option value="'.$value['id'].'" selected>'.cap($value['name']).'</option>';
                }else{
                    echo '<option value="'.$value['id'].'">'.cap($value['name']).'</option>';
                }
            }
        ?>
    </select>
</div>

<div class="col-md-4 form-group mb-3"><label for=" ">Project Name</label>
<input class="form-control" type="text" placeholder=" " name="project_name" value="<?php echo $data['project_name']; ?>" /></div>

<div class="col-md-4 form-group mb-3"><label for=" ">Date of Supply</label>
<input class="form-control" type="text" placeholder=" " id="supply_date" name="supply_date" value="<?php echo $data['supply_date']; ?>"  /></div>


<div class="col-md-4 form-group mb-3"><label for=" ">Warranty valid till</label>
<input class="form-control" type="text" placeholder=" " id="warranty_till"  name="warranty_till" value="<?php echo $data['warranty_till']; ?>"  /></div>




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
<style type="text/css">
    .op1{
        opacity: 0;
    }
</style>

<?php $this->load->view('common/footer');  ?>
<script src="<?php echo base_url('assets/libs/jquery-validation/jquery.validate.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/libs/flatpickr/flatpickr.min.js');?>"></script>
<script src="<?php echo base_url('assets/custom.js'); ?>"></script>

<script type="text/javascript">

$(document).ready(function(){
    var models =<?php echo json_encode($models );?>;
    
    $('#equipment').on('change', function() {
        $id = $(this).find(":selected").data('id');

        $list = models[$id];

        $html = '<option value="" >Select Model</option>';
        $list.forEach( function (item, index) {
          $html += '<option value="'+item+'" >'+item+'</option>';
        });

        $('#e_model').html($html);
    });


    var warranty_till = $("#warranty_till").flatpickr({
         minDate: new Date($("#supply_date").val()).fp_incr(1)
    });

    $("#supply_date").flatpickr(
        {   
            onChange: function (dateStr, dateObj) {
                warranty_till.set("minDate", new Date(dateObj).fp_incr(1));
            }
        }
    );


});

    $('#cmsForm').validate({
        ignore: [],
        // debug: true,
        rules: {
            ga_no: {required: true},
            equipment: {required: true},
            project_code: {required: true},
            model: {required: true},
            company: {required: true},
            company_2: {required: false},
            project_name: {required: true},
            supply_date: {required: true},
            warranty_till: {required: true},
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
                        window.location.href = "<?php echo site_url('project/'.$data['id'].'/edit')?>";
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