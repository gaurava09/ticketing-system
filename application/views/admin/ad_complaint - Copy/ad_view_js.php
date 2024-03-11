<script type="text/javascript">

$(document).ready(function(){
    var userrole = '<?php echo $this->role; ?>';
    
    if($("#visit_date").length){
        $("#visit_date").flatpickr(
        {   
          minDate: new Date().fp_incr(1),
          maxDate: new Date().fp_incr(60) 
        }
      );
    }
    

  $('.selectType').change(function(){
        var val  = this.value;
        $('#assignForm, #remarkForm, #classForm').addClass('d-none');
        if(val == 1){
          $('#assignForm').removeClass('d-none');
        }
        else if(val == 2){
          $('#remarkForm').removeClass('d-none');
        }
        else if(val == 3){
          $('#classForm').removeClass('d-none');
        }
  });

  $('.add_assign_div').click(function(e){
        e.preventDefault();
        $html = $('.assign_div_html .assign_div').clone();
        if($('.assign_wrapper .assign_div').length < 4){
          $('.assign_wrapper').append($html);
        }else{
           alert('you can not assign more than 4 at a time');
        }
  });

  $(document).on('click','.assign_wrapper .remove_assign_div', function(e) {
        e.preventDefault();
        
        if($('.assign_wrapper .assign_div').length > 1){
           $(this).parents('.assign_div').remove();
        }else{
           // alert('you can not assign more than 4 at a time');
        }
  });


	$(document).on('change','.assign_wrapper .department', function(e) {
		var val = $.trim(this.value);

    $parent = $(this).parents('.assign_div');

		if(val ==''){
			$parent.find('.employee').empty();
			return false;
		}

		var data = {'id' : val};

		$.ajax({
          	type: 'get',
            url: "<?php echo site_url('employee/listbydept')?>",
            data: data,
            success: function($res) {
                if($res.status == 1){
                	$html = '<option value="">Select Employee</option>';
                	$.each($res.data, function(i,v){
                		$html += '<option value="'+v.id+'">'+v.name+'</option>';
                	});

                	$parent.find('.employee').html($html);
                }
                else{
                	showError($res.message);
                }
            },
            error: function(error, textStatus, errorMessage) {
                showError('Request could not be completed');
            }             
        });
		
	});


	 //start submission
	$('#assignForm').validate({
        ignore: [],
        // debug: true,
        rules: {
            complaint_id: {required: true},
            // 'department[]': {required: true},
            // 'employee[]': {required: true},
            // 'remark[]': {required: true},
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

            $('#assignForm .error').remove();

            $msg = '<span class="error invalid-feedback" style="display: inline;">This field is required.</span>';

            $error = 0;
            $.each($('.assign_wrapper .assign_div'),function(i,ele){
                $dept     = $.trim($(ele).find('.department').val());
                $employee = $.trim($(ele).find('.employee').val());
                $remark    = $.trim($(ele).find('.remark').val());

                if($dept == ''){
                  $(ele).find('.department').after($msg);
                  $error = 1;
                }
                if($employee == ''){
                  $(ele).find('.employee').after($msg);
                  $error = 1;
                }
                if($remark == '' && userrole != 'admin'){
                  $(ele).find('.remark').after($msg);
                  $error = 1;
                }
            });

            if($error){
                return false;
            }

            $button = $('#assignForm_submit');
            showLoading($button);

            $.ajax({
                type: 'post',
                url: $(form).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                success: function($res) {
         
                    if($res.status == 1){
                      window.location.href = "<?php echo site_url('complaint/'.$complaint['id'])?>";
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
      

    $('#remarkForm').validate({
        ignore: [],
        // debug: true,
        rules: {
            complaint_id: {required: true},
            remark: {required: true},
            status: {required: true},
            visit_date: {required: false},
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
            
            $button = $('#remarkForm_submit');
            showLoading($button);

            $.ajax({
                type: 'post',
                url: $(form).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                success: function($res) {
         
                    if($res.status == 1){
                      window.location.href = "<?php echo site_url('complaint/'.$complaint['id'])?>";
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

    //
    $('#solutionForm').validate({
        ignore: [],
        // debug: true,
        rules: {
            complaint_id: {required: true},
            remark: {required: true},
            mom_text: {required: false},
            mom_doc: {
                required: false,
                extension: "jpg,jpeg,png,pdf",
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
            
            $button = $('#solutionForm_submit');
            showLoading($button);

            $.ajax({
                type: 'post',
                url: $(form).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                success: function($res) {
                    if($res.status == 1){
                      window.location.href = "<?php echo site_url('complaint/'.$complaint['id'])?>";
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

    //classification
     //
    $('#classForm').validate({
        ignore: [],
        // debug: true,
        rules: {
            complaint_id: {required: true},
            class: {required: true},
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
            
            $button = $('#classForm_submit');
            showLoading($button);

            $.ajax({
                type: 'post',
                url: $(form).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                success: function($res) {
                    if($res.status == 1){
                      window.location.href = "<?php echo site_url('complaint/'.$complaint['id'])?>";
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

    //mom

    $(document).on('click','.delete_doc',function(){
        $('.custom-file .custom-file-label').html('Choose File (jpg,jpeg,png,pdf format)');
        $(".mom_doc").val(null);
        $('.image_preview').attr('src', '');
        $('.img_thumb').hide();
    });

    $(document).on('change','.mom_doc',function(){
        // console.log(this.files);
        $('.image_preview').attr('src', '');
        $('.img_thumb').hide();

        if (this.files && this.files[0]) {
            $maxsize = 1; //mb
            $files = this.files[0];

            // console.log(this.files[0]);  console.log($files.type);

         /* if($files.type == 'image/jpeg' || $files.type == 'image/png'){
          }else{
                alert('Please upload jpeg or png file');
                return false;
          }

          var FileSize = $files.size / 1024 / 1024; // in MB
          if (FileSize > $maxsize) {
              alert('File size exceeds 1 MB');
                return false;
          } */

          var reader = new FileReader();
          reader.onload = function (e) {
                if($files.type != 'application/pdf'){
                    $('.image_preview').attr('src', e.target.result);
                    $('.img_thumb').show();
                }
              
                $('.custom-file .custom-file-label').html($files.name);
          }
          reader.readAsDataURL($files);
        }
    });


});
</script>
