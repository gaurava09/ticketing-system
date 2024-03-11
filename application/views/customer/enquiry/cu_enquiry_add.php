<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link href="<?php echo base_url('assets/libs/jquery-ui/jquery-ui.min.css'); ?>" rel="stylesheet" type="text/css">
<!-- <link href="<?php echo base_url('assets/libs/select2/css/select2.min.css'); ?>" rel="stylesheet" type="text/css"> -->

<style type="text/css">
    /*.sparepart_div table{display: none;}*/
    .sparepart_div {display: none;}
    .qty{width: 75px;}

    .sparepart_table {
      border-collapse: collapse;
      width: 100%;
    }

    .sparepart_table td, .sparepart_table th {
      border: 1px solid #ddd;
      padding: 8px;
    }

    .sparepart_table tr:nth-child(even){background-color: #f2f2f2;}

    .sparepart_table tr:hover {background-color: #ddd;}

    .sparepart_table th {
      padding-top: 12px;
      padding-bottom: 12px;
      text-align: left;
      background-color: #535B53;
      color: white;
    }

    .sparepart_table input[type=checkbox]{
        float: right;
    }

</style>



<div class="">
<div class="float-left breadcrumb"><h1 class="mr-2">Add Sparepart Enquiry</h1></div>
<div class="float-right"><a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo site_url('customer/enquiry'); ?>"><i class="i-Left-3"></i> Back</a></div>
<div class="clearfix"></div>
</div>

<div class="separator-breadcrumb border-top"></div>

<?php $this->load->view('common/flashmsg'); ?>
<?php $this->load->view('common/ajaxerror'); ?>


<div class="row">
<div class="col-md-12">
<div class="card mb-4">
<div class="card-body">


<?php echo form_open_multipart('customer/enquiry/create/',array('id' => 'cmsForm','autocomplete' => 'off') ); ?>
	
<div class="mt-4 mb-4">  

<div class="row">

<div class="col-md-4 form-group mb-3">
    <label for=" ">Equipment GA No.</label>
    <input class="form-control" id="ga_no" type="text" placeholder="GA No." name="ga_no" />
    <a href="<?php echo base_url('assets/guide to check GA number_MTCSPL.pdf') ?>" target="blank">Guide to check Equipment GA No.</a>
</div>

<div class="col-md-4 form-group mb-3">
    <label for=" ">Equipment</label>
    <input class="form-control" id="equipment_id" type="hidden" name="equipment" readonly="" />
    <input class="form-control" id="equipment_name" type="text" placeholder="Equipment Name" readonly="" />
</div>

<div class="col-md-4 form-group mb-3">
    <label for=" ">Equipment Model</label>
    <input class="form-control" id="e_model" name="model" type="text" placeholder="Equipment Model" readonly="" />
</div>
<!-- 
<div class="col-md-4 form-group mb-3"><label for=" ">Equipment Model</label>
    <select class="form-control " id="e_model" name="model" disabled="">
        <option value="" >Select Model</option>
    </select>
</div> -->
</div>

<!-- <div class="row">
    <div class="col-md-4 form-group mb-3">
        <label for=" ">Spareparts</label>
        <select class="form-control " id="spareparts"  >
            <option value="">Select Spareparts</option>
        </select>
    </div>

    <div class="col-md-4 form-group mb-3">
        <div class="row">
            <div class="col-md-10 form-group ">
                <label for=" ">Quantity</label>
                <input class="form-control" id="quanity" type="text" placeholder="Quantity"  />
            </div>
            <div class="col-md-2 form-group">
                <label for=" "> </label>
                <button class="btn btn-primary float-right" id="addEnquiry" type="button">Add</button>
            </div>
        </div>
    </div>

</div> -->

<div class="row sparepart_div mt-4 mb-4">
    <div class="col-md-12">
        <table class="sparepart_table">
            <thead>
                <tr>
                    <th style="width: 40px;"></th>
                    <th>Name of Spare parts</th>
                    <th>Measurement Unit</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            
            <tbody>

            </tbody>
            
        </table>
        <span class="mt-2"><i>Note - Select checkbox for spare parts you wish to order</i></span>
    </div>
    
</div>

<div class="row">
<div class="col-md-12 form-group mb-3">
    <label for=" ">Additional spare if any please mention below</label>
    <textarea class="form-control" placeholder="" name="query"></textarea>
</div>

<div class="col-md-12 "><button class="btn btn-primary float-right" id="cmsForm_submit" type="submit">Submit</button> </div>


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

<script src="<?php echo base_url('assets/libs/jquery-ui/jquery-ui.min.js') ?>"></script>

<!-- <script src="<?php echo base_url('assets/libs/select2/js/select2.min.js') ?>"></script> -->

<script src="<?php echo base_url('assets/custom.js'); ?>"></script>



<script type="text/javascript">
var ga_no_list = <?php echo json_encode($projects );?>;

var models = <?php echo json_encode($models );?>;

$(document).ready(function(){
    /*$("#spareparts").select2({
      placeholder: "Select spare parts"
    });*/


    $(document).on('click','.plus_button',function(){
      $ele = $(this).parents('tr').find('.qty');
      $val = $ele.val();
      $ele.val(++$val);
    });

    $(document).on('click','.minus_button',function(){
        $ele = $(this).parents('tr').find('.qty');
        $val = $ele.val();
    
        if($val >= 1){
            $ele.val(--$val);
        }
    });

    function listSpareParts(){
        $model = $.trim($('#e_model').val());
        if($model != ''){
            $ga_no = $.trim($('#ga_no').val());
            $equipment_id = $.trim($('#equipment_id').val());

            if($ga_no == ''){
                alert('GA No is empty'); return false;
            }
            else if($equipment_id == ''){
                alert('Equipment id is empty'); return false;
            }
            getSpareparts($ga_no, $equipment_id, $model);
        }else{
            $('.sparepart_table tbody').html('');
            $('.sparepart_div').hide();
        }
    }

    $('#e_model').on('change', listSpareParts);
    

	function getSpareparts($ga_no, $equi_id ,$model){
         var postdata = {};
         postdata.ga_no = $ga_no;
         postdata.equi_id = $equi_id;
         postdata.model = $model;

        $.ajax({
            type: 'GET',
            url: '<?php echo site_url('customer/enquiry/spareparts'); ?>',
            data: postdata,

            success: function($res) {

                if($res.status == 1){
                    $html = '';
                    // $html = '<option value="">Select Spareparts</option>';
                    $.each($res.data, function(i,v){
                        // $html += '<option value="'+v.id+'">'+v.name+'</option>';

                        $html += '<tr> <td><input type="checkbox" name="check_'+v.id+'" value="1"></td>';
                        $html += '<td> <input type="hidden" name="sparepart[]" value="'+v.id+'" />'+v.name+'</td> ';
                        $html += '<td>'+v.unit+'</td> ';
                        $html += '<td><input type="button" value="-" class="minus_button" />';
                        $html += '  <input type="text" name="qty[]" class="qty" maxlength="12"/>';
                        $html += '  <input type="button" value="+" class="plus_button" /> </td> </tr>';
                    });

                    $('.sparepart_table tbody').html($html);
                    $('.sparepart_div').show();
                    hideError();
                }
                else{
                    $('.sparepart_table tbody').html('');
                    showError($res.message);
                }
                stopLoading()
            },
            error: function(error, textStatus, errorMessage) {
                showError('Request could not be completed');
                stopLoading();
            }  
         }); //end ajax
    }

    $( "#ga_no" ).autocomplete({
        source: ga_no_list,
         focus: function( event, ui ) {
              $( "#ga_no" ).val( ui.item.value );
                return false;
           },
        select: function( event, ui ) {
            
            $( "#ga_no" ).val( ui.item.value );
            $( "#equipment_name" ).val( ui.item.equipment_name );
            $( "#equipment_id" ).val( ui.item.equipment_id );
            $( "#e_model" ).val( ui.item.model );

            // $html = '<option value="'+ui.item.model+'" >'+ui.item.model+'</option>';

           /* $list = models[ui.item.equipment_id];
            $html = '<option value="" >Select Model</option>';
            $list.forEach( function (item, index) {
              $html += '<option value="'+item+'" >'+item+'</option>';
            });

            $('#e_model').html($html);*/
            
            listSpareParts();
            return false;
        }
    });

	$('#cmsForm').validate({
        ignore: [],
        // debug: true,
        rules: {
            ga_no: {required: true},
            equipment: {required: true},
            model: {required: true},
            // 'spareparts[]': {required: true},
            // 'qty[]': {required: true},
            // query: {required: true},
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
                      window.location.href = "<?php echo site_url('customer/enquiry')?>";
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
