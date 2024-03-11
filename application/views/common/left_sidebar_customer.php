<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
           
<!--========================= Left sidebar =========================-->
<div class="side-content-wrap">
<div class="sidebar-left open rtl-ps-none" data-perfect-scrollbar="" data-suppress-scroll-x="true">


<ul class="navigation-left">
<li class="nav-item" data1-item="dashboard"><a class="nav-item-hold" href="<?php echo site_url('customer/dashboard'); ?>"><i class="nav-icon i-Bar-Chart"></i><span class="nav-text">Dashboard</span></a>
<div class="triangle"></div>
</li>

<li class="nav-item" data-item="complaint_history"><a class="nav-item-hold" href="#"><i class="nav-icon i-File-Horizontal-Text"></i><span class="nav-text">Complaint History</span></a>
<div class="triangle"></div>
</li>
<!-- <li class="nav-item" data-item="product_spare_details"><a class="nav-item-hold" href="#"><i class="nav-icon i-Receipt-3"></i><span class="nav-text">Spare Enquiry</span></a>
<div class="triangle"></div>
</li> -->

<li class="nav-item" data-item="customer_feedback"><a class="nav-item-hold" href="#"><i class="nav-icon i-Add-UserStar"></i><span class="nav-text">Feedback</span></a>
<div class="triangle"></div>
</li>

<li class="nav-item"><a class="nav-item-hold" href="<?php echo site_url('chat'); ?>"><i class="nav-icon i-Speach-Bubble-3"></i><span class="nav-text">Message</span></a>
<div class="triangle"></div>
</li>

<li class="nav-item" data-item="account_setting"><a class="nav-item-hold" href="#"><i class="nav-icon i-Gears"></i><span class="nav-text">Account Setting</span></a>
<div class="triangle"></div>
</li>

<li class="nav-item" data-item="info"><a class="nav-item-hold" href="#"><i class="nav-icon i-Library"></i><span class="nav-text">Info</span></a>
<div class="triangle"></div>
</li>

</ul>
</div>
<div class="sidebar-left-secondary rtl-ps-none" data-perfect-scrollbar="" data-suppress-scroll-x="true">
<!-- Submenu Dashboards-->
<!-- <ul class="childNav" data-parent="dashboard">
<li class="nav-item"><a href="<?php echo site_url('customer/dashboard'); ?>"><i class="nav-icon i-Clock-3"></i><span class="item-name">Dashboard</span></a></li>
</ul> -->

<ul class="childNav" data-parent="complaint_history">
<li class="nav-item"><a href="<?php echo site_url('customer/complaint'); ?>"><i class="nav-icon i-File-Clipboard-Text--Image"></i><span class="item-name">List of Complaints</span></a></li>
<li class="nav-item"><a href="<?php echo site_url('customer/complaint/create'); ?>"><i class="nav-icon i-Add-File"></i><span class="item-name">Add Complaint</span></a></li>
</ul>
<ul class="childNav" data-parent="product_spare_details">
<li class="nav-item"><a href="<?php echo site_url('customer/enquiry'); ?>"><i class="nav-icon i-File-Clipboard-Text--Image"></i><span class="item-name">Enquiry History</span></a></li>
<li class="nav-item"><a href="<?php echo site_url('customer/enquiry/create'); ?>"><i class="nav-icon i-Add-File"></i><span class="item-name">Add Spare Enquiry</span></a></li>
</ul>
<ul class="childNav" data-parent="account_setting">
<li class="nav-item"><a href="<?php echo site_url('customer/account'); ?>"><i class="nav-icon i-Male-21"></i><span class="item-name">Account Setting</span></span></a></li>
<li class="nav-item"><a href="<?php echo site_url('customer/change-password'); ?>"><i class="nav-icon i-Eye"></i><span class="item-name">Change Password</span></a></li>
</ul>
<!-- chartjs-->
<ul class="childNav" data-parent="customer_feedback">
<li class="nav-item"><a href="<?php echo site_url('customer/feedback'); ?>"><i class="nav-icon i-File-Horizontal-Text"></i><span class="item-name">List of Feedback</span></a></li>

</ul>

<ul class="childNav" data-parent="info">
<li class="nav-item"><a href="<?php echo site_url('about'); ?>"><i class="nav-icon i-File-Horizontal-Text"></i><span class="item-name">About Us</span></a></li>

<li class="nav-item"><a href="<?php echo site_url('news'); ?>"><i class="nav-icon i-File-Horizontal-Text"></i><span class="item-name">News updates</span></a></li>

<li class="nav-item"><a href="<?php echo site_url('faq'); ?>"><i class="nav-icon i-Split-Horizontal-2-Window"></i><span class="item-name">FAQs</span></a></li>

<li class="nav-item"><a href="<?php echo site_url('contact'); ?>"><i class="nav-icon i-Split-Horizontal-2-Window"></i><span class="item-name">Contact Us</span></a></li>


</ul>


</div>
<div class="sidebar-overlay"></div>
</div>
<!--========================= Left sidebar =========================-->