var nilesh = {

    validateEmail : function (data) {
            var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
            return pattern.test(data);
       },

     validateMobile : function (data) {
            var pattern = /^[0-9]{10}$/;
            return pattern.test(data);
        },

    isNumber : function (data) {
           var pattern = /^\d+$/;
           return pattern.test(data);
     },

    refreshCaptcha : function(){
        var min = 1;
        var max = 10;
        var value1 = Math.floor(Math.random() * (max - min + 1)) + min;
        var value2 = Math.floor(Math.random() * (max - min + 1)) + min;

        $('.value1').data('value', value1);
        $('.value2').data('value', value2);

        $('.value1').html(value1);
        $('.value2').html(value2);
    },

    validURL : function(myURL) {
            var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|'+ // domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))'+ // ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ //port
            '(\\?[;&amp;a-z\\d%_.~+=-]*)?'+ // query string
            '(\\#[-a-z\\d_]*)?$','i');
            return pattern.test(myURL);
         },
    strongPass : function(pass) {
            var pattern = /^(?=\S*[a-z])(?=\S*[A-Z])(?=\S*\d)(?=\S*[^\w\s])\S{8,}$/;
            return pattern.test(pass);
         }

};




if ( $.isFunction($.validator) ) {
    
    $.validator.addMethod('mobile', function (value, element, param) {
        if(value){
            var pattern = /^[0-9]{10}$/;
            return pattern.test(value);
        }else{
            return true;
        }
    }, 'Please enter a valid mobile number.');


    $.validator.addMethod('validateEmail', function (value, element, param) {
        var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);

        return this.optional(element) || pattern.test(value);       
    }, ' Please enter a valid email address.');

    $.validator.addMethod("alphanumeric", function(value, element) {
        // return this.optional(element) || /^[\w.]+$/i.test(value);
        return this.optional(element) || /^[a-z0-9]+$/i.test(value);
    }, "Code must contain only letters and numbers");


    $.validator.addMethod("alphachar", function(value, element) {
        $regex = /^[a-z0-9,.?&\-\s\']+$/i;
        return this.optional(element) || $regex.test(value);
    }, "Text must contain only alphanumberic and <i> . , ? & - '</i> chars.");

    $.validator.addMethod("lettersonly", function(value, element) {
      return this.optional(element) || /^[a-z]+$/i.test(value);
    }, "Letters only please"); 

     $.validator.addMethod("strongPass", function(value, element) {
      return this.optional(element) || nilesh.strongPass(value);
    }, "Password must be more than 8 digit and must contain atleast one lower & upper case letter, one digit and a special character"); 

     //custom validation
    // 25 MB = 25485760  1MB = 1024 bytes
    // Custom method for validate plugin
    $.validator.addMethod('filesize', function (value, element, param) {
        if (element.files[0] !== undefined){
            console.log(element.files[0].size);
            const fsize = element.files[0].size; 
            const fileSize = Math.round((fsize / 1024)); 
            const sizeMB = param*1024;
            return this.optional(element) || (fileSize <= sizeMB)
        }else{
            return true;
        }
        
    }, 'File size must be less than {0} MB');
}



function showError($message){
    $('.alert-dismissible').remove();
    $('.errors').html($message);
    $('.errors').addClass('active');
    $('html, body').animate({
        scrollTop: $(".errors").offset().top-100
    }, 700);
}
function hideError(){
    $('.errors').html('');
    $('.errors').removeClass('active');
}

function showLoading($ele){
    var l = Ladda.create($ele[0]);
    l.start();
}
function stopLoading(){
    Ladda.stopAll();
}


function successPopup($msg = ""){
    if($msg == ""){
        $msg = 'success.';
    }
    $('#success-alert-modal .msg').text($msg)
    $('#success-alert-modal').modal();

    stopLoading();
}

function errorPopup($msg = ""){
    if($msg == ""){
        $msg = 'Some error occured.';
    }
    $('#danger-alert-modal .msg').text($msg)
    $('#danger-alert-modal').modal(); 

    stopLoading();
}

$('#search_filter').after('<a href="#" class="float-right reset-filter">Reset</a>');

$(document).on('click','.reset-filter',function(e){
    e.preventDefault();
    $('.search_div input, .search_div select').val('');
    $('#search_filter').trigger('click');
});