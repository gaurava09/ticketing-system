<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$back_url = site_url('faq/category');

?>
<style type="text/css">
    .delete_faq{cursor: pointer;}
    .delete_faq:hover span{background: red;color: #fff;}
</style>

<div class="">
	<div class="float-left breadcrumb"><h1 class="mr-2">Add FAQ Sub-Category </h1></div>
	<div class="float-right">
	<a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo $back_url; ?>"><i class="i-Left-3"></i> Back</a>
	</div>
	<div class="clearfix"></div>
</div>

<div class="separator-breadcrumb border-top"></div>

<?php $this->load->view('common/flashmsg'); ?>
<?php $this->load->view('common/ajaxerror'); ?>


<div class="row">
<div class="col-md-12">
<div class="card mb-4">
<div class="card-body">

<?php echo form_open_multipart('faq/subcategory/create',array('id' => 'cmsForm','autocomplete' => 'off') ); ?>
<div class="mt-4 mb-4">  

<div class="row">
    <div class="col-md-12 form-group mb-3"><label for=" ">Name</label>
    <input class="form-control" id="dept_name" type="text" placeholder=" " name="name" /></div>

    <div class="col-md-6 form-group mb-3"><label for=" ">Category</label>
        <select class="form-control " id="category" name="category">
            <option value="" data-model="">Select Category</option>
            <?php
                foreach($category as  $key => $value){
                    echo '<option value="'.$value['id'].'" >'.$value['title'].'</option>';
                }
            ?>
        </select>
    </div>
</div>




<div class="row">
    <div class="col-md-12 form-group mt-3 ">
        <label for=" ">Add FAQs</label>
    </div>
</div>

<div class="faq_section">

    <div class="row faq_div mb-3">
        <div class="col-md-12 input-group inp-group">
            <div class="input-group-prepend">
                <span class="input-group-text " title="Title">Q</span>
            </div>

            <div class="custom-file">
                <input type="text" name="faqs[0][title]" class="form-control faq_title" placeholder="Question">
            </div>

            <div class="input-group-append sort_order">
                <input type="text" name="faqs[0][order]" class=" input-group-text faq_order" placeholder="Sort Order">
            </div>

            <div class="input-group-append delete_faq">
                <span class="input-group-text " title="Delete">X</span>
            </div>
        </div>

        <div class="col-md-12 input-group inp-group">
            <textarea name="faqs[0][desc]" class="form-control faq_desc" placeholder="Answer"></textarea>
        </div>
    </div>
</div> <!-- end faq wrapper -->


<div class="row">
    <div class="col-md-12 mt-4">
     <div class="text-center"> <span class="add_faq btn btn-primary"> ADD FAQ</span> </div>
    </div>
    <div class="col-md-12 mt-4">
     <button class="btn btn-primary float-right" id="cmsForm_submit" type="submit">Submit</button>
    </div>
</div>    
</div>
 
</form>
</div>
</div>
</div>
</div>



<div id="faq_html" class="d-none">
    <div class="row faq_div mb-3">
        <div class="col-md-12 input-group inp-group">
            <div class="input-group-prepend">
                <span class="input-group-text " title="Title">Q</span>
            </div>

            <div class="custom-file">
                <input type="text" name="faqs[0][title]" class="form-control faq_title" placeholder="Question">
            </div>

            <div class="input-group-append sort_order">
                <input type="text" name="faqs[0][order]" class=" input-group-text faq_order" placeholder="Sort Order">
            </div>

            <div class="input-group-append delete_faq">
                <span class="input-group-text " title="Delete">X</span>
            </div>
        </div>

        <div class="col-md-12 input-group inp-group">
            <textarea name="faqs[0][desc]" class="form-control faq_desc" placeholder="Answer"></textarea>
        </div>
    </div>
</div>


<?php $this->load->view('common/footer');  ?>

<script src="<?php echo base_url('assets/libs/jquery-validation/jquery.validate.min.js'); ?>"></script>

<script src="<?php echo base_url('assets/custom.js'); ?>"></script>

<script type="text/javascript">

     $('.add_faq').on('click',function(){
        $html = $('#faq_html').html();
        if($('.faq_section .faq_div').length > 20){
            alert('You can not add more than 20 faq');
            return false;
        }
        $('.faq_section').append($html);

        $.each($('.faq_section .faq_div'), function(i,ele){
            $(ele).find('.faq_title').attr('name', 'faqs['+i+'][title]');
            $(ele).find('.faq_desc').attr('name', 'faqs['+i+'][desc]');
            $(ele).find('.faq_order').attr('name', 'faqs['+i+'][order]');
        });
    });

    $(document).on('click','.delete_faq',function(){
        $(this).parents('.faq_div').remove();
    });


	$('#cmsForm').validate({
        ignore: [],
        // debug: true,
        rules: {
            name: {required: true},
            category: {required: true},
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
                      window.location.href = "<?php echo site_url('faq/subcategory')?>";
                      return false;
                    }
                    else{
                    	showError($res.message);
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