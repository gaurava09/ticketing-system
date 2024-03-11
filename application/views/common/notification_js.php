<script src="<?php echo base_url('assets/js.cookie.min.js'); ?>"></script>

<script type="text/javascript">
    $(document).ready(function(){

        function getUnreadNotifications(){
            $.ajax({
                url: "<?php echo site_url('notification') ?>",
                type: 'get',

                success: function(response){
                    if(response.status == 1){
                        if(response.data.length){
                            // $('#dropdownNotification span').html(response.data.length);
                            $('#dropdownNotification span').removeClass('d-none');
                        }else{
                            $('.empty_notification').removeClass('d-none');
                            return false;
                        }
                        
                        $unread_count = 0;
                        $html = '';
                        $.each(response.data, function(i,v){
                            $message = v.message;
                            if(v.link != ''){
                                $message = '<a href="'+v.link+'">'+v.message+'</a>';
                            }
                            $class = 'read';
                            if(v.is_read == 0){
                                $class = 'unread';
                                $unread_count++;
                            }

                            $html += '<div class="dropdown-item d-flex '+$class+'">';
                            $html += '<div class="notification-icon">  <i class="i-Receipt-3 text-success mr-1"></i> </div>';
                            $html += '<div class="notification-details flex-grow-1">';
                            $html += '<p class="m-0 d-flex align-items-center nmsg" data-id="'+v.id+'">'+$message+'</p>';
                            $html += '<p class="text-small text-muted m-0 text-right">'+v.time+'</p>';
                            $html += '</div> </div>';
                        });

                        if($unread_count > 0){
                            $('#dropdownNotification span').html($unread_count);
                        }else{
                            $('#dropdownNotification span').html('');
                        }
                        $('.notification-dropdown').append($html);
                    }                               
                }//end success fun
            });//end ajax
        }//end function
        

        $(document).on('click','.notification-details',function(e){
            e.preventDefault();
            $link = $(this).find('a').attr('href');
            $id = $.trim($(this).find('.nmsg').data('id'));

            if($id !=''){
                Cookies.set('ci_no_id', $id);
            }

            window.location.href = $link;
            return false;
        });//end function

        if(Cookies.get('ci_no_id')) {
            $ci_no_id = Cookies.get('ci_no_id');

            if($ci_no_id == ''){
                return false;
            }

            $url = 'notification/'+$ci_no_id+'/read';
            $.ajax({
                url: "<?php echo site_url() ?>"+$url,
                type: 'post',
                success: function(response){
                    getUnreadNotifications();

                    if(response.status == 1){
                        Cookies.remove('ci_no_id');
                    }
                }
            });
        }else{
            getUnreadNotifications();
        }
    });
</script>