<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
           
<!--========================= Left sidebar =========================-->
<div class="side-content-wrap">
<div class="sidebar-left open rtl-ps-none" data-perfect-scrollbar="" data-suppress-scroll-x="true">
<ul class="navigation-left">

<li class="nav-item" data1-item="dashboard"><a class="nav-item-hold" href="<?php echo site_url(); ?>"><i class="nav-icon i-Bar-Chart"></i><span class="nav-text">Dashboard</span></a>
<div class="triangle"></div>
</li>



<?php if($this->role == 'admin') { ?>
	<li class="nav-item" data-item="customer_management"><a class="nav-item-hold" href="#"><i class="nav-icon i-File-Horizontal-Text"></i><span class="nav-text">Customer Management</span></a>
		<div class="triangle"></div>
	</li>
<?php } ?>

<?php if($this->role == 'sales') { ?>

	<li class="nav-item" data1-item="dashboard"><a class="nav-item-hold" href="<?php echo site_url('enquiry'); ?>"><i class="nav-icon i-Bar-Chart"></i><span class="nav-text">Enquiry List</span></a>
	<div class="triangle"></div>
	</li>

<?php } else { ?>
	<li class="nav-item" data-item="ticket_management"><a class="nav-item-hold" href="#"><i class="nav-icon i-Receipt-3"></i><span class="nav-text">Complaint Management</span></a>
	<div class="triangle"></div>
	</li>
<?php } ?>



<?php if($this->role == 'admin') { ?>
	<!-- <li class="nav-item" data-item="customer_feedback"><a class="nav-item-hold" href="#"><i class="nav-icon i-Add-UserStar"></i><span class="nav-text">Customer Feedback</span></a>
		<div class="triangle"></div>
	</li> -->
<?php } ?>


<!-- <?php if($this->role == 'admin') { ?>
<li class="nav-item" data-item="enquiry_management"><a class="nav-item-hold" href="#"><i class="nav-icon i-File-Horizontal-Text"></i><span class="nav-text">Enquiry Management</span></a>
<div class="triangle"></div>
</li>
<?php } ?> -->

<?php if($this->role != 'sales') { ?>
<li class="nav-item"><a class="nav-item-hold" href="<?php echo site_url('chat'); ?>"><i class="nav-icon i-Speach-Bubble-3"></i><span class="nav-text">Message</span></a>
<div class="triangle"></div>
</li>
<?php } ?>

<?php if($this->role == 'admin') { ?>

<li class="nav-item" data-item="cms_pages"><a class="nav-item-hold" href="#"><i class="nav-icon i-File-Clipboard-Text--Image"></i><span class="nav-text">Pages</span></a>
<div class="triangle"></div>
</li>


<li class="nav-item" data-item="master_data_management"><a class="nav-item-hold" href="#"><i class="nav-icon i-Add-UserStar"></i><span class="nav-text">Master Data Management</span></a>
<div class="triangle"></div>
</li>
<?php } ?>

</ul>
</div>
<div class="sidebar-left-secondary rtl-ps-none" data-perfect-scrollbar="" data-suppress-scroll-x="true">
<!-- Submenu Dashboards-->
<!-- <ul class="childNav" data-parent="dashboard">
<li class="nav-item"><a href="<?php echo site_url(); ?>"><i class="nav-icon i-Clock-3"></i><span class="item-name">Dashboard</span></a></li>
</ul> -->

<?php if($this->role == 'admin') { ?>
<ul class="childNav" data-parent="customer_management">
<li class="nav-item"><a href="<?php echo site_url('customer'); ?>"><i class="nav-icon i-File-Clipboard-Text--Image"></i><span class="item-name">Customer List</span></a></li>
<li class="nav-item"><a href="<?php echo site_url('customer/create'); ?>"><i class="nav-icon i-Add-File"></i><span class="item-name">Add New Customer</span></a></li>
</ul>

<ul class="childNav" data-parent="customer_feedback">
<li class="nav-item"><a href="<?php echo site_url('feedback'); ?>"><i class="nav-icon i-File-Horizontal-Text"></i><span class="item-name">List of Feedback</span></a></li>
</ul>

<?php } ?>


<ul class="childNav" data-parent="ticket_management">
<li class="nav-item"><a href="<?php echo site_url('complaint'); ?>"><i class="nav-icon i-File-Clipboard-Text--Image"></i><span class="item-name">Complaint List</span></a></li>

<?php if($this->role == 'admin') { ?>
<li class="nav-item"><a href="<?php echo site_url('complaint/create'); ?>"><i class="nav-icon i-Add-File"></i><span class="item-name">Add Complaint</span></a></li>
<?php } ?>

</ul>


<?php if($this->role == 'admin') { ?>
<ul class="childNav" data-parent="enquiry_management">
<li class="nav-item"><a href="<?php echo site_url('enquiry'); ?>"><i class="nav-icon i-File-Clipboard-Text--Image"></i><span class="item-name">Enquiry List </span></a></li>

</ul>

<ul class="childNav" data-parent="master_data_management">

<div class="accordion accordion_3 openings_min">
<!--accordion-->
<!-- <li class="nav-item">
<a class="min_box-h nav_h1 font_s1"><i class="nav-icon i-File-Clipboard-Text--Image"></i><span class="item-name">Equipments</span></a>
<div class="min_box-tx">

<ul>
<li class="nav-item"><a href="<?php echo site_url('equipment'); ?>"><i class="nav-icon i-File-Clipboard-Text--Image"></i><span class="item-name">List </span></a></li>
<li class="nav-item"><a href="<?php echo site_url('equipment/create'); ?>"><i class="nav-icon i-Add-File"></i><span class="item-name">Add</span></a></li>
</ul></div>
</li> -->
<!--accordion-->

<!--accordion-->
<!-- <li class="nav-item">
<a class="min_box-h nav_h1 font_s1"><i class="nav-icon i-File-Clipboard-Text--Image"></i><span class="item-name">Spare parts</span></a>
<div class="min_box-tx">

<ul>
<li class="nav-item"><a href="<?php echo site_url('sparepart'); ?>"><i class="nav-icon i-File-Clipboard-Text--Image"></i><span class="item-name">List </span></a></li>
<li class="nav-item"><a href="<?php echo site_url('sparepart/create'); ?>"><i class="nav-icon i-Add-File"></i><span class="item-name">Add</span></a></li>
</ul></div>
</li> -->
<!--accordion-->


<!--accordion-->
<!-- <li class="nav-item">
<a class="min_box-h nav_h1 font_s1"><i class="nav-icon i-File-Clipboard-Text--Image"></i><span class="item-name">Project </span></a>
<div class="min_box-tx">

<ul>
<li class="nav-item"><a href="<?php echo site_url('project'); ?>"><i class="nav-icon i-File-Clipboard-Text--Image"></i><span class="item-name">List </span></a></li>
<li class="nav-item"><a href="<?php echo site_url('project/create'); ?>"><i class="nav-icon i-Add-File"></i><span class="item-name">Add</span></a></li>
</ul></div>
</li> -->
<!--accordion-->



<!--accordion-->
<li class="nav-item">
<a class="min_box-h nav_h1 font_s1"><i class="nav-icon i-File-Clipboard-Text--Image"></i><span class="item-name">Company </span></a>
<div class="min_box-tx">

<ul>
<li class="nav-item"><a href="<?php echo site_url('company'); ?>"><i class="nav-icon i-File-Clipboard-Text--Image"></i><span class="item-name">List </span></a></li>
<li class="nav-item"><a href="<?php echo site_url('company/create'); ?>"><i class="nav-icon i-Add-File"></i><span class="item-name">Add</span></a></li>
</ul></div>
</li>
<!--accordion-->


<!--accordion-->
<li class="nav-item">
<a class="min_box-h nav_h1 font_s1"><i class="nav-icon i-File-Clipboard-Text--Image"></i><span class="item-name">Department </span></a>
<div class="min_box-tx">

<ul>
<li class="nav-item"><a href="<?php echo site_url('department'); ?>"><i class="nav-icon i-File-Clipboard-Text--Image"></i><span class="item-name">List </span></a></li>
<li class="nav-item"><a href="<?php echo site_url('department/create'); ?>"><i class="nav-icon i-Add-File"></i><span class="item-name">Add</span></a></li>
</ul></div>
</li>
<!--accordion-->

<!--accordion-->
<li class="nav-item">
<a class="min_box-h nav_h1 font_s1"><i class="nav-icon i-File-Clipboard-Text--Image"></i><span class="item-name">Designation </span></a>
<div class="min_box-tx">

<ul>
<li class="nav-item"><a href="<?php echo site_url('designation'); ?>"><i class="nav-icon i-File-Clipboard-Text--Image"></i><span class="item-name">List </span></a></li>
<li class="nav-item"><a href="<?php echo site_url('designation/create'); ?>"><i class="nav-icon i-Add-File"></i><span class="item-name">Add</span></a></li>
</ul></div>
</li>
<!--accordion-->

<!--accordion-->
<li class="nav-item">
<a class="min_box-h nav_h1 font_s1"><i class="nav-icon i-File-Clipboard-Text--Image"></i><span class="item-name">Employees </span></a>
<div class="min_box-tx">

<ul>
<li class="nav-item"><a href="<?php echo site_url('employee'); ?>"><i class="nav-icon i-File-Clipboard-Text--Image"></i><span class="item-name">List </span></a></li>
<li class="nav-item"><a href="<?php echo site_url('employee/create'); ?>"><i class="nav-icon i-Add-File"></i><span class="item-name">Add</span></a></li>
</ul></div>
</li>
<!--accordion-->

</div>
</ul>


<ul class="childNav" data-parent="cms_pages">
<div class="accordion accordion_3 openings_min">
<!--accordion-->
<li class="nav-item">
<a class="min_box-h nav_h1 font_s1"><i class="nav-icon i-File-Clipboard-Text--Image"></i><span class="item-name">FAQs</span></a>
<div class="min_box-tx">

<ul>
<li class="nav-item"><a href="<?php echo site_url('faq/edit'); ?>"><i class="nav-icon i-File-Clipboard-Text--Image"></i><span class="item-name">Page</span></a></li>
<li class="nav-item"><a href="<?php echo site_url('faq/category'); ?>"><i class="nav-icon i-File-Clipboard-Text--Image"></i><span class="item-name">Category</span></a></li>
<li class="nav-item"><a href="<?php echo site_url('faq/subcategory'); ?>"><i class="nav-icon i-File-Clipboard-Text--Image"></i><span class="item-name">Sub Category</span></a></li>
</ul></div>
</li>
<!--accordion-->

<li class="nav-item"><a href="<?php echo site_url('about/edit'); ?>"><i class="nav-icon i-Add-File"></i><span class="item-name">About</span></a></li>

<li class="nav-item"><a href="<?php echo site_url('contact/edit'); ?>"><i class="nav-icon i-Add-File"></i><span class="item-name">Contact</span></a></li>

<li class="nav-item"><a href="<?php echo site_url('news'); ?>"><i class="nav-icon i-Add-File"></i><span class="item-name">News Update</span></a></li>

<li class="nav-item"><a href="<?php echo site_url('product/edit'); ?>"><i class="nav-icon i-Add-File"></i><span class="item-name">Product</span></a></li>


<li class="nav-item"><a href="<?php echo site_url('regpage/edit'); ?>"><i class="nav-icon i-Add-File"></i><span class="item-name">Registration</span></a></li>

<li class="nav-item"><a href="<?php echo site_url('loginpage/edit'); ?>"><i class="nav-icon i-Add-File"></i><span class="item-name">Login</span></a></li>

<li class="nav-item"><a href="<?php echo site_url('forgotpass/edit'); ?>"><i class="nav-icon i-Add-File"></i><span class="item-name">Forgot Password</span></a></li>

<li class="nav-item"><a href="<?php echo site_url('resetpass/edit'); ?>"><i class="nav-icon i-Add-File"></i><span class="item-name">Reset Password</span></a></li>


</div>
</ul>
<?php } ?>

</div>
<div class="sidebar-overlay"></div>
</div>
<!--========================= Left sidebar =========================-->