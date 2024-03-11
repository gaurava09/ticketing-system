<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$lastChatID = 0;
?>
<style type="text/css">
   .ref-div{
      text-align: center;
   }
   .refresh-chat{
      color: #d22636;
      cursor: pointer;
      display: none;
   }
</style>

<?php $this->load->view('common/flashmsg'); ?>
<?php $this->load->view('common/ajaxerror'); ?>



<div class="card chat-sidebar-container sidebar-container" data-sidebar-container="chat">



<div class="chat-sidebar-wrap sidebar" data-sidebar="chat" style="left: 0px;">
   <div class="border-right">
      <div class="pt-2 pb-2 pl-3 pr-3 d-flex align-items-center o-hidden box-shadow-1 chat-topbar">
         <a class="link-icon d-md-none" data-sidebar-toggle="chat"><i class="icon-regular ml-0 mr-3 i-Left"></i></a>
         <div class="form-group m-0 flex-grow-1">
            <input class="form-control form-control-rounded" id="search_contact" type="text" placeholder="Search contacts">
         </div>
      </div>

      <?php
      if($convolist){

         echo ' <div class="contacts-scrollable perfect-scrollbar">
            <div class="mt-4 pb-2 pl-3 pr-3 font-weight-bold text-muted border-bottom">Contacts</div>';

            foreach ($convolist as $key => $value) {

               /*$profile_picture = 'assets/dist-assets/images/faces/user.jpg';

               if(ps($value,'profile_picture')){
                   $profile_picture = $value['profile_picture'];
               }*/

               echo '<div class="p-3 d-flex align-items-center border-bottom userlist_row ">
                    <a href="'.site_url('chat/'.$value['id']).'"> ';
               //echo '<img class="avatar-sm rounded-circle mr-3 d-none" src="'.base_url($profile_picture).'" alt="alt"></a>';

                 echo '     <div>
                        <a href="'.site_url('chat/'.$value['id']).'"><h6 class="m-0 userlist_name">'.$value['name'].' ('.$value['ticket_no'].')</h6></a>
                        <span class="text-muted text-small d-none">3 Oct, 2018</span>
                     </div>
                  </div>';
            }
         echo '</div>';
      }
      ?>
   </div>
</div>

<?php
$current_user_profile = '';
$other_user_profile = '';
$other_user_name = '';

   if(ps($chatdata,'other_user')) {

      $current_user_profile = 'assets/dist-assets/images/faces/user.jpg';
      if(ps($chatdata['current_user'],'profile_picture')){
         $current_user_profile = ps($chatdata['current_user'],'profile_picture');
      }

      $other_user_profile = 'assets/dist-assets/images/faces/user.jpg';
      if(ps($chatdata['other_user'],'profile_picture')){
         $other_user_profile = ps($chatdata['other_user'],'profile_picture');
      }
      $other_user_name = $chatdata['other_user']['name'];
?>

<div class="chat-content-wrap sidebar-content" data-sidebar-content="chat" style="margin-left: 260px;">
   
   <div class="d-flex pl-3 pr-3 pt-2 pb-2 o-hidden box-shadow-1 chat-topbar">
      <div class="row" style="width: 100%">
      <a class="link-icon d-md-none d-none" data-sidebar-toggle="chat"><i class="icon-regular i-Right ml-0 mr-3"></i></a>
      <div class="col-md-6 d-flex align-items-center">
         <img class="avatar-sm rounded-circle mr-2" src="<?php echo base_url($other_user_profile); ?>">
         <p class="m-0 text-title text-16 flex-grow-1"><?php echo $other_user_name; echo ' ('.$conversation['ticket_no'].')'; ?></p>
      </div>

      <div class="col-md-6  text-right">
         <a class="btn btn-primary export" type="button" href="<?php echo site_url('chat/export/'.$conversationId); ?>">Export</a>
      </div>
   </div>
   </div>

   <div class="chat-content perfect-scrollbar" data-suppress-scroll-x="true">
      <?php
      

      $today = date('Y-m-d');

      foreach ($chatdata['chats'] as $key => $value) {
         
         $chatDate = date('Y-m-d',strtotime($value['created_at']));

         
         if($chatDate == $today){
            $date_time = custTime($value['created_at']);
         }else{
            $date_time = custDate($value['created_at']) .', '.custTime($value['created_at']);
         }
         

         $lastChatID = $value['id'];

         if($this->role == 'customer' ){

            if($value['sender'] == 'customer'){
               echo '<div class="d-flex mb-4 current_user">
                  <div class="message flex-grow-1">
                     <div class="d-flex">
                        <p class="mb-1 text-title text-16 flex-grow-1">You</p>
                        <span class="text-small text-muted time_row"> '.$date_time.' </span>
                     </div>
                     <p class="m-0 msg_row">'.$value['message'].'</p>
                  </div>
                  <img class="avatar-sm rounded-circle ml-3" src="'.base_url($current_user_profile).'" >
               </div>';
            }else{
                  echo '<div class="d-flex mb-4 user">
                     <img class="avatar-sm rounded-circle mr-3" src="'.base_url($other_user_profile).'" >
                     <div class="message flex-grow-1">
                        <div class="d-flex">
                           <p class="mb-1 text-title text-16 flex-grow-1">'.$other_user_name.'</p>
                           <span class="text-small text-muted">'.$date_time.'</span>
                        </div>
                        <p class="m-0">'.$value['message'].'</p>
                     </div>
                  </div>';
               }
             
          }else{
               if($value['sender'] == 'customer'){
                  echo '<div class="d-flex mb-4 user">
                     <img class="avatar-sm rounded-circle mr-3" src="'.base_url($other_user_profile).'" >
                     <div class="message flex-grow-1">
                        <div class="d-flex">
                           <p class="mb-1 text-title text-16 flex-grow-1">'.$other_user_name.'</p>
                           <span class="text-small text-muted">'.$date_time.'</span>
                        </div>
                        <p class="m-0">'.$value['message'].'</p>
                     </div>
                  </div>';                  
              }else{
                  echo '<div class="d-flex mb-4 current_user">
                     <div class="message flex-grow-1">
                        <div class="d-flex">
                           <p class="mb-1 text-title text-16 flex-grow-1">You</p>
                           <span class="text-small text-muted time_row"> '.$date_time.' </span>
                        </div>
                        <p class="m-0 msg_row">'.$value['message'].'</p>
                     </div>
                     <img class="avatar-sm rounded-circle ml-3" src="'.base_url($current_user_profile).'" >
                  </div>';
               }
          }//end if role
          
      }

      ?>


   </div>

   <?php if($complaint['status'] != 4 && $complaint['status'] != 0){ ?>
      <div class="pl-3 pr-3 pt-3 pb-3 box-shadow-1 chat-input-area">

         <?php echo form_open_multipart('chat/create',array('id' => 'chatForm','class' => 'inputForm','autocomplete' => 'off') ); ?>
            <div class="form-group">
               <textarea class="form-control form-control-rounded" id="message" placeholder="Type your message" name="message" cols="30" rows="2"></textarea>
            </div>
            <div class="d-flex">
               <div class="flex-grow-1 ref-div">
                  <span class="refresh-chat">Refresh Chat</span>
               </div>
               <button class="btn btn-icon btn-rounded btn-primary mr-2 sendMessage"  type="submit"><i class="i-Paper-Plane"></i></button>
            </div>
         </form>
      </div>
   <?php } ?>

</div>
<?php
   }else if(!$convolist){ ?>
         <div class="chat-content-wrap sidebar-content" data-sidebar-content="chat" style="margin-left: 260px;">
            <p class="text-center mt-3">Conversation not found</p>
         </div>
<?php }

?>

</div>


<div class="chat_html_cu d-none">
   <?php
   echo '<div class="d-flex mb-4 current_user rowch">
         <div class="message flex-grow-1">
            <div class="d-flex">
               <p class="mb-1 text-title text-16 flex-grow-1">You</p>
               <span class="text-small text-muted time_row"> - </span>
            </div>
            <p class="m-0 msg_row"> -</p>
         </div>
         <img class="avatar-sm rounded-circle ml-3" src="'.base_url($current_user_profile).'" >
      </div>';
   ?>
</div>

<div class="chat_html_ot d-none">
   <?php
   echo '<div class="d-flex mb-4 user rowch">
            <img class="avatar-sm rounded-circle mr-3" src="'.base_url($other_user_profile).'" >
            <div class="message flex-grow-1">
               <div class="d-flex">
                  <p class="mb-1 text-title text-16 flex-grow-1">'.$other_user_name.'</p>
                  <span class="text-small text-muted time_row"> - </span>
               </div>
               <p class="m-0 msg_row">- </p>
            </div>
         </div>';
   ?>
</div>

<?php $this->load->view('common/footer');  ?>

<script src="<?php echo base_url('assets/custom.js'); ?>"></script>


<?php if($conversationId){ ?>

<?php if($complaint['status'] != 4 && $complaint['status'] != 0){ ?>

   <script type="text/javascript">

   var conversationId = '<?php echo $conversationId; ?>';
   var role = '<?php echo $this->role; ?>';
   var lastChatID = '<?php echo $lastChatID; ?>';
   var canAjax = 1;
   var noResponse = 0;
   var delayTime = 5;
   var timeoutId;


$(document).ready(function(){

   $('.chat-content').scrollTop($('.chat-content')[0].scrollHeight);

   $("#chatForm").submit(function(e){
         e.preventDefault();
         $token  = $('#chatForm [name=token]').val();
         $message = $.trim($('#message').val());
  
         if($message == ""){
             return false;
         }
         $('.refresh-chat').hide();
         canAjax = 0;
         // $('.chat_html_cu .msg_row').html($message);
         // $html = $('.chat_html_cu .rowch').clone();
         // $('.chat-content').append($html);
         $('#message').val('');

         $button = $(".sendMessage");
         showLoading($button);

         var postdata = {"message":$message,"token":$token};

         $.ajax({
            type: 'post',
            url: '<?php echo site_url('chat/'.$conversationId)?>',
            data: postdata,
            success: function($res) {
               if($res.status == 1){
                    getChatText();
               }else{  
                     $('#message').val($message);
                     alert($res.message);
               }               
               stopLoading();

               canAjax = 1;
               // reChatConfig();
            },
            error: function(error, textStatus, errorMessage) {
                alert('Failed to send message');
            }             
        }); //end ajax
    });//end send chat

   function getChatText(){
         if(canAjax == 0){
            return false;
         }
         $.ajax({
            type: "GET",
            url: '<?php echo site_url('chat/'.$conversationId.'/refresh')?>?lastid='+lastChatID,
            success: function($res) {
               // console.log($res);
                 if($res.status == 1){
                     //limit ajax call
                     if($res.data.length == 0){
                        noResponse++;

                        if(noResponse > 120){
                           canAjax = 0;
                           $('.refresh-chat').show();
                        }
                        else if(noResponse > 90){
                           delayTime = 20;
                        }
                        else if(noResponse > 60){
                           delayTime = 10;
                        }
                     }else{
                        noResponse = 0;
                     }

                     $.each($res.data, function(i,v){
                        if(v.sender == 'you'){
                           $class = '.chat_html_cu';
                        }else{
                           $class = '.chat_html_ot';
                        }

                        $($class+' .msg_row').html(v.message);
                        $($class+' .time_row').html(v.time);
                        $html = $($class+' .rowch').clone();
                        $('.chat-content').append($html);

                        $(".chat-content").animate({ scrollTop: $('.chat-content').prop("scrollHeight")}, 1000);

                        lastChatID = v.id;
                     });
                 }
             },
            error: function(error, textStatus, errorMessage) {
                alert('Failed to send message');
            }   
         });
      } //end getChatText

      function reChatConfig(){
         noResponse =0;
         canAjax =1;
         delayTime= 0;
         $('.refresh-chat').hide();
         clearTimeout(timeoutId);
         startChat();
      }

      $('.refresh-chat').click(reChatConfig);


      function startChat(){
         if(canAjax == 1){
            getChatText();
            timeoutId = setTimeout(startChat, (delayTime*1000));
         }
      }

      startChat();
   
   // setInterval(function(){ getChatText(); }, (delayTime*1000));

}); //end doc ready
</script>

<?php } //if complaint closed or deleted ?>

<?php } //if convo id?>


<script type="text/javascript">
   $(document).ready(function(){
   // $(document).on('keyup','#search_contact', function(){

         $("#search_contact").keyup(function(){
            $val = $.trim($(this).val());
            if($val == ''){
               $('.userlist_row').addClass('d-flex');
               $('.userlist_row').show();
               return false;
            }

            $('.userlist_row').hide();
            $('.userlist_row').removeClass('d-flex');

            $('.userlist_name').each(function(i,v){
               // console.log($(v).text());
               $user = $(v).text();

               $regex = RegExp($val,"i");
               $found = $user.search($regex)
               if($found >= 0){
                  $(v).parents('.userlist_row').show();
                  $(v).parents('.userlist_row').addClass('d-flex');
               }
            });
         });

}); //end doc ready
</script>