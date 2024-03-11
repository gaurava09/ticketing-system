<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$back_url = site_url('news/category');

?>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">


<style type="text/css">
    .banner_heading{display: none;}
    .delete_banner{cursor: pointer;}
    .delete_banner:hover span{background: red;color: #fff;}
</style>

<div class="">
	<div class="float-left breadcrumb"><h1 class="mr-2">Add Latest News Updates</h1></div>
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

<?php echo form_open_multipart('news/create',array('id' => 'cmsForm','autocomplete' => 'off') ); ?>
<div class="mt-4 mb-4">  

<div class="row">
    <div class="col-md-12 form-group mb-3"><label>Title</label>
        <input class="form-control" type="text"  name="title" /></div>

    <div class="col-md-12 form-group mb-5">
        <label for=" ">Content</label>
        <textarea name="content" class="form-control"  rows='5' id='content'> </textarea>
    </div>
</div>




<div class="row banner_heading ">
    <div class="col-md-12 form-group mt-3 ">
        <label for=" ">Banners</label>
    </div>
</div>

<div class="form-row banner_section">

<!--     <div class="col-md-12 mb-5 banner_div">
         <div class="col-12 input-group inp-group">
            <div class="input-group-prepend">
                <span class="input-group-text ">Banner 1</span>
            </div>

            <div class="custom-file">
                <input type="file" class="custom-file-input photo banner_image" name='banner[0][image]'  accept="image/jpg, image/jpeg, image/png">
                <label class="custom-file-label" for="photo">Choose Image</label>
            </div>

            <div class="input-group-append sort_order">
                <input type="text" name="banner[0][order]" class=" input-group-text banner_order" placeholder="Sort Order">
            </div>

            <div class="input-group-append delete_banner">
                <span class="input-group-text " title="Delete">X</span>
            </div>
        </div>

        <div class="col-12 preview_div img_thumb " >
            <img src="" class="thumbnail image_preview" />
        </div>
    </div> -->

</div>
 <!-- end banner section -->


<div class="row">
    <div class="col-md-12 mt-4">
     <div class="text-center"> <span class="add_banner btn btn-primary"> ADD Banner</span> </div>
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



<div id="banner_html" class="d-none">
    <div class="col-md-12 mb-5 banner_div">
         <div class="col-12 input-group inp-group">
            <div class="input-group-prepend">
                <span class="input-group-text ">Banner 1</span>
            </div>

            <div class="custom-file">
                <input type="file" class="custom-file-input photo banner_image" name='banner[0][image]'  accept="image/jpg, image/jpeg, image/png">
                <label class="custom-file-label" for="photo">Choose Image</label>
            </div>

            <div class="input-group-append sort_order">
                <input type="text" name="banner[0][order]" class=" input-group-text banner_order" placeholder="Sort Order">
            </div>

            <div class="input-group-append delete_banner">
                <span class="input-group-text " title="Delete">X</span>
            </div>
        </div>

        <div class="col-12 preview_div img_thumb " >
            <img src="" class="thumbnail image_preview" />
        </div>
    </div>
</div>


<?php $this->load->view('common/footer');  ?>

<script src="<?php echo base_url('assets/libs/jquery-validation/jquery.validate.min.js'); ?>"></script>

<script src="<?php echo base_url('assets/custom.js'); ?>"></script>

<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script type="text/javascript">
    $('#content').summernote(
    {
        placeholder: 'Content',
        tabsize: 2,
        height: 200,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'table']],
            // ['height', ['height']]
          ]
    });


     $('.add_banner').on('click',function(){
        $html = $('#banner_html').html();
        if($('.banner_section .banner_div').length >= 4){
            alert('You can not add more than 4 banner');
            return false;
        }
        $('.banner_heading').show();
        $('.banner_section').append($html);

        $.each($('.banner_section .banner_div'), function(i,ele){
            $(ele).find('.input-group-prepend .input-group-text').html('Banner '+(i+1));
            $(ele).find('.banner_image').attr('name', 'banner['+i+'][image]');
            $(ele).find('.banner_order').attr('name', 'banner['+i+'][order]');
        });
    });

    $(document).on('click','.delete_banner',function(){
        $(this).parents('.banner_div').remove();

        if($('.banner_section .banner_div').length == 0 ){
            $('.banner_heading').hide();
        }
    });

    $(document).on('change','.photo',function(){
        // console.log(this.files);
          $parent = $(this).parents('.banner_div');
          if (this.files && this.files[0]) {
                $maxsize = 1; //mb
                $files = this.files[0];

              console.log(this.files[0]);
              console.log($files.type);

              if($files.type == 'image/jpeg' || $files.type == 'image/png'){
              }else{
                    alert('Please upload jpeg or png file');
                    return false;
              }

              var FileSize = $files.size / 1024 / 1024; // in MB
              if (FileSize > $maxsize) {
                  alert('File size exceeds 1 MB');
                    return false;
              } 

              var reader = new FileReader();
              reader.onload = function (e) {
                  $parent.find('.prev_file').val('');
                  $parent.find('.image_preview').attr('src', e.target.result);
                  $parent.find('.preview_div').show();
                  $parent.find('.custom-file .custom-file-label').html($files.name);
              }
              reader.readAsDataURL($files);
            }
          // readURL(this);
      });

	$('#cmsForm').validate({
        ignore: [],
        // debug: true,
        rules: {
            title: {required: true},
            content: {required: false},
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
                      window.location.href = "<?php echo site_url('news')?>";
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