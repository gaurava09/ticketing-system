<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<style type="text/css">
	.dropdown .dropdown-toggle::after {
    border:none;
}
</style>
<!--========================= HEADER =========================-->
<div class="main-header">
<div class="logo2">
<img style="width: 170px;" src="<?php echo base_url('assets/images/logo.png'); ?>" alt="">
</div>
<div class="menu-toggle">
<div></div>
<div></div>
<div></div>
</div>

<div class="d-flex align-items-center top_link_wrapper">

<?php if($this->role == 'admin') { ?>
<!-- <div class="dropdown">
    <a class="nav-link dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="javascript:void(0)" role="button" aria-haspopup="false" aria-expanded="true">
        Create New 
        <i class="i-Arrow-Down-in-Circle"></i>
    </a>
    <div class="dropdown-menu" x-placement="bottom-start">
        <a href="<?php echo site_url('complaint/create'); ?>" class="dropdown-item"> <span>New Complaint</span> </a>
        <a href="<?php echo site_url('customer/create'); ?>" class="dropdown-item"> <span>New Customer</span> </a>
        <a href="<?php echo site_url('company/create'); ?>" class="dropdown-item"> <span>New Company</span> </a>
        <a href="<?php echo site_url('department/create'); ?>" class="dropdown-item"> <span>New Department</span> </a>
        <a href="<?php echo site_url('designation/create'); ?>" class="dropdown-item"> <span>New Designation</span> </a>
        <a href="<?php echo site_url('employee/create'); ?>" class="dropdown-item"> <span>New Employee</span> </a>
    </div>
</div> -->
<?php } ?>

<?php if($this->role == 'customer') { ?>
    <!-- <div class="desktop_links">
         <a href="<?php echo site_url('customer/complaint/create'); ?>" class="btn cust_top_link"> <span>New Complaint</span> </a>
    </div>
    <div class="mobile_links">
        <a class="nav-link dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="javascript:void(0)" role="button" aria-haspopup="false" aria-expanded="true">
            Create New 
            <i class="i-Arrow-Down-in-Circle"></i>
        </a>
        <div class="dropdown-menu" x-placement="bottom-start">
            <a href="<?php echo site_url('customer/complaint/create'); ?>" class="dropdown-item"> <span>New Complaint</span> </a>
            <a href="<?php echo site_url('contact'); ?>" class="dropdown-item"> <span>Contact Us</span> </a>
        </div>
    </div> -->
   

<!-- <div class="dropdown">
    <a class="nav-link dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="javascript:void(0)" role="button" aria-haspopup="false" aria-expanded="true">
        Create New 
        <i class="i-Arrow-Down-in-Circle"></i>
    </a>
    <div class="dropdown-menu" x-placement="bottom-start">
        <a href="<?php //echo site_url('customer/complaint/create'); ?>" class="dropdown-item"> <span>New Complaint</span> </a>
        <a href="<?php //echo site_url('customer/enquiry/create'); ?>" class="dropdown-item"> <span>New Spare Part Enquiry</span> </a>
    </div>
</div> -->
<?php } ?>
<!-- <div class="search-bar">
<input type="text" placeholder="Search">
<i class="search-icon text-muted i-Magnifi-Glass1"></i>
</div> -->
</div>

<!-- <div style="margin: auto"></div> -->
<div class="header-part-right">

    <?php if($this->role == 'customer') { ?>
        <div class="desktop_links">
            <a href="<?php echo site_url('contact'); ?>" class="btn cust_top_link cust_top_link_contact"> <span>Contact Us</span> </a>
        </div>
    <?php } ?>

<!-- Full screen toggle -->
<!-- <i class="i-Full-Screen header-icon d-none d-sm-inline-block" data-fullscreen></i> -->

<!-- Notificaiton -->
<div class="dropdown">
<div class="badge-top-container" role="button" id="dropdownNotification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	<span class="badge badge-primary d-none">3</span>
	<i class="i-Bell text-muted header-icon"></i>
</div>
<!-- Notification dropdown -->
<div class="dropdown-menu dropdown-menu-right notification-dropdown rtl-ps-none" aria-labelledby="dropdownNotification" data-perfect-scrollbar data-suppress-scroll-x="true">

<div class="dropdown-item d-flex1 empty_notification d-none">
<div class="notification-details1 flex-grow-1">
<p class="m-0 text-center"> No new notifications</p>
</div> </div>

</div>
</div>
<!-- Notificaiton End -->

<!-- User avatar dropdown -->
<div class="dropdown">
<div class="user col align-self-end">
<img src="<?php echo base_url($this->picture); ?>" id="userDropdown" alt="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
<div class="dropdown-header">
<i class="i-Lock-User mr-1"></i> 

<?php
$userData = $this->session->userdata($this->user);
echo $userData['first_name'].' '.$userData['last_name'];

echo '</div>';

if($this->role == 'customer'){ 
	echo '<a class="dropdown-item" href="'.base_url('customer/change-password').'">Change Password</a>';
	
}
 else{
 	echo '<a class="dropdown-item" href="'.base_url('account').'">Account settings</a>';
 	echo '<a class="dropdown-item" href="'.base_url('change-password').'">Change Password</a>';
} 
?>
<a class="dropdown-item" href="<?php echo site_url('logout'); ?>">Sign out</a>
</div>
</div>
</div>
</div>
</div>
<!--========================= HEADER =========================-->

