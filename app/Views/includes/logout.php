<div class="col-md-6 col-sm-4 clearfix">
   <a href="<?php echo base_url('api/auth/sign_out') ?>" class="pull-right text-danger" style="font-size: 20px;">Logout</a>
   <ul class="notification-area pull-right mr-3">
      <!--  <li class="settings-btn">
         <i class="ti-bell"></i>
         </li> -->
     
   
     
      <li class="dropdown">
         <i class="ti-bell dropdown-toggle" data-toggle="dropdown">
         <span id="count_notif">
         </i>
         <div class="dropdown-menu bell-notify-box notify-box">
            <span class="notify-title">
               Notification
               <a href="#" style="font-size: 20px">view all</a> 
            </span>
            <div class="nofity-list" style="overflow: scroll;">
            </div>
         </div>
      </li>
     
     
      <?php if (session()->get('user_type') == 'user') {
         // code...
      ?>
      <li class="dropdown" >
         <i class="ti-calendar " id="view_my_calendar" style="color: orange;" ></i>
      </li>
      <?php } ?>
   </ul>
</div>