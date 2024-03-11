<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">


<div class="">
<div class="float-left breadcrumb"><h1 class="mr-2">Edit Contact Us</h1></div>
<div class="float-right"><a class="btn btn-primary" type="button" data-dismiss="modal" href="<?php echo site_url('contact'); ?>"> VIEW PAGE</a></div>
<div class="clearfix"></div>
</div>

<div class="separator-breadcrumb border-top"></div>

<?php $this->load->view('common/flashmsg'); ?>
<?php $this->load->view('common/ajaxerror'); ?>


<div class="row">
<div class="col-md-12">
<div class="card mb-4">
<div class="card-body">


<?php echo form_open_multipart('contact/edit',array('id' => 'cmsForm','autocomplete' => 'off') ); ?>
<div class="mt-4 mb-4">  

<div class="row">

<div class="col-md-12 form-group mb-3"><label>Title</label>
<input class="form-control" type="text" value="<?php echo ps($data,'title');?>" name="title" /></div>

<div class="col-md-12 form-group mb-5">
    <label for=" ">Content</label>
     <textarea name="content" class="form-control"  rows='5' id='content'> <?php echo ps($data,'content');?></textarea>
</div>


<?php 
    $file_url= "";
    $file_name= "";
    if(ps($data,'image')){
        $file_name = $data['image'];
        $file_url = site_url().$data['image'];
    }
?>

<div class="col-md-12  mt-5"> 
  <input type="hidden" name="prev_file" value="<?php echo $file_name; ?>" id='prev_file'>
   <label>Attachment</label>
  <div class="form-group row mb-3">
     
       <div class="col-12 input-group inp-group">
            <div class="input-group-prepend"> <span class="input-group-text clear_img" title="Clear Image">X</span> </div>

            <div class="custom-file">
                <input type="file" class="custom-file-input" id="file" name='file'  accept="image/jpeg,image/png,image/jpg">
                <label class="custom-file-label" for="file">
                    <?php 
                        echo $file_name ? str_replace("documents/images/","",$file_name) : 'Select Image';
                    ?>
            </label>
            </div>
        </div>

        <div class="col-12 preview_div" ><img id="image_preview" src="<?php echo $file_url; ?>" class="thumbnail" /></div>
    </div>
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


<?php $this->load->view('common/footer');  ?>

<script src="<?php echo base_url('assets/libs/jquery-validation/jquery.validate.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/libs/jquery-validation/additional-methods.min.js'); ?>"></script>

<script src="<?php echo base_url('assets/custom.js'); ?>"></script>

<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>


<script type="text/javascript">
    // https://summernote.org/deep-dive/#initialization-options
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

// https://quilljs.com/docs/formats/
/* var quill = new Quill('#content', {
    // modules: {
    //     toolbar: [
    //       [{ header: [1, 2, 3,4,5, false] }],
    //       ['bold', 'italic', 'underline','link'],
    //       ['list'],
    //       ['list', 'code-block'],
    //     ]
    //   },
    theme: 'snow'
  });*/

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            console.log(e.target);
            $('#prev_file').val('');
            if(input.files[0].type == 'image/jpeg'){
              $('#image_preview').attr('src', e.target.result);
            }else{
              $('#image_preview').attr('src', '');
            }
            $('.custom-file-label').text(input.files[0].name);
            $('.preview_div').show();
        }
        console.log(input.files[0]);
        reader.readAsDataURL(input.files[0]);
      }
    }

    $("#file").change(function(){
        readURL(this);
    });

    $(document).on('click','.clear_img',function(){
        $('#file').val('');
        $('#prev_file').val('');
        $('.custom-file .custom-file-label').html('Choose File');
        $('.preview_div').hide();
    });

	$('#cmsForm').validate({
        ignore: [],
        // debug: true,
        rules: {
            title: {required: true},
            content: {required: true},
            file: {
                required: false,
                extension: "jpg,jpeg,png",
                filesize: 1, //1MB
              },
        },
        messages: {
            pass_2 :{
                'equalTo' : 'password does not match'
            }
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
                      window.location.href = "<?php echo site_url('contact/edit')?>";
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