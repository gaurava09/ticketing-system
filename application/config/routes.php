<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Admin/Dashboard';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;



// ```````````Admin Login``````````````
$route['admin/login'] 			= 'Admin/AdAuth/login';
$route['logout'] 				= 'Admin/AdAuth/logout';


// ```````````Admin Account``````````````
$route['account']['get'] 					= 'Admin/AdAccount/edit';
$route['account']['post'] 					= 'Admin/AdAccount/update';
$route['change-password']['get'] 			= 'Admin/AdAccount/changePassword';
$route['change-password']['post'] 			= 'Admin/AdAccount/changePasswordDb';


// ```````````Company``````````````
$route['company'] 						= 'Admin/Company/index';
$route['company/list'] 					= 'Admin/Company/list';
$route['company/create']['get'] 		= 'Admin/Company/create';
$route['company/create']['post'] 		= 'Admin/Company/store';
$route['company/(:num)']['get'] 		= 'Admin/Company/view/$1';
$route['company/(:num)/edit']['get'] 	= 'Admin/Company/edit/$1';
$route['company/(:num)/update']['post'] = 'Admin/Company/update/$1';


// ```````````department``````````````
$route['department'] 						= 'Admin/Department/index';
$route['department/list'] 					= 'Admin/Department/list';
$route['department/create']['get'] 			= 'Admin/Department/create';
$route['department/create']['post'] 		= 'Admin/Department/store';
$route['department/(:num)']['get'] 			= 'Admin/Department/view/$1';
$route['department/(:num)/edit']['get'] 	= 'Admin/Department/edit/$1';
$route['department/(:num)/update']['post'] 	= 'Admin/Department/update/$1';


// ```````````designation``````````````
$route['designation'] 						= 'Admin/Designation/index';
$route['designation/list'] 					= 'Admin/Designation/list';
$route['designation/create']['get'] 		= 'Admin/Designation/create';
$route['designation/create']['post'] 		= 'Admin/Designation/store';
$route['designation/(:num)']['get'] 		= 'Admin/Designation/view/$1';
$route['designation/(:num)/edit']['get'] 	= 'Admin/Designation/edit/$1';
$route['designation/(:num)/update']['post'] = 'Admin/Designation/update/$1';


// ```````````employee``````````````
$route['employee'] 							= 'Admin/Employee/index';
$route['employee/list'] 					= 'Admin/Employee/list';
$route['employee/create']['get'] 			= 'Admin/Employee/create';
$route['employee/create']['post'] 			= 'Admin/Employee/store';
$route['employee/(:num)']['get'] 			= 'Admin/Employee/view/$1';
$route['employee/(:num)/edit']['get'] 		= 'Admin/Employee/edit/$1';
$route['employee/(:num)/update']['post'] 	= 'Admin/Employee/update/$1';
$route['employee/listbydept'] 				= 'Admin/Employee/empByDepartment';


// ```````````equipment``````````````
$route['equipment'] 						= 'Admin/Equipment/index';
$route['equipment/list'] 					= 'Admin/Equipment/list';
$route['equipment/create']['get'] 			= 'Admin/Equipment/create';
$route['equipment/create']['post'] 			= 'Admin/Equipment/store';
$route['equipment/(:num)']['get'] 			= 'Admin/Equipment/view/$1';
$route['equipment/(:num)/edit']['get'] 		= 'Admin/Equipment/edit/$1';
$route['equipment/(:num)/update']['post'] 	= 'Admin/Equipment/update/$1';


// ```````````sparepart``````````````
$route['sparepart'] 						= 'Admin/Sparepart/index';
$route['sparepart/list'] 					= 'Admin/Sparepart/list';
$route['sparepart/create']['get'] 			= 'Admin/Sparepart/create';
$route['sparepart/create']['post'] 			= 'Admin/Sparepart/store';
$route['sparepart/(:num)']['get'] 			= 'Admin/Sparepart/view/$1';
$route['sparepart/(:num)/edit']['get'] 		= 'Admin/Sparepart/edit/$1';
$route['sparepart/(:num)/update']['post'] 	= 'Admin/Sparepart/update/$1';


// ```````````project``````````````
$route['project'] 							= 'Admin/Project/index';
$route['project/list'] 						= 'Admin/Project/list';
$route['project/create']['get'] 			= 'Admin/Project/create';
$route['project/create']['post'] 			= 'Admin/Project/store';
$route['project/(:num)']['get'] 			= 'Admin/Project/view/$1';
$route['project/(:num)/edit']['get'] 		= 'Admin/Project/edit/$1';
$route['project/(:num)/update']['post'] 	= 'Admin/Project/update/$1';

$route['project/upload']['get']				= 'Admin/Project/upload';
$route['project/upload']['post']			= 'Admin/Project/uploadProject';

// ```````````customer``````````````
$route['customer'] 							= 'Admin/Customer/index';
$route['customer/list'] 					= 'Admin/Customer/list';
$route['customer/create']['get'] 			= 'Admin/Customer/create';
$route['customer/create']['post'] 			= 'Admin/Customer/store';
$route['customer/(:num)']['get'] 			= 'Admin/Customer/view/$1';
$route['customer/(:num)/edit']['get'] 		= 'Admin/Customer/edit/$1';
$route['customer/(:num)/update']['post'] 	= 'Admin/Customer/update/$1';
$route['customer/export']['get'] 			= 'Admin/Customer/export';

// ```````````complaint``````````````
$route['complaint'] 						= 'Admin/AdComplaint/index';
$route['complaint/company'] 				= 'Admin/AdComplaint/getCompanyData';
$route['complaint/list'] 					= 'Admin/AdComplaint/list';
$route['complaint/create']['get'] 			= 'Admin/AdComplaint/create';
$route['complaint/create']['post'] 			= 'Admin/AdComplaint/store';
$route['complaint/(:num)']['get'] 			= 'Admin/AdComplaint/view/$1';
$route['complaint/(:num)/edit']['get'] 		= 'Admin/AdComplaint/edit/$1';
$route['complaint/(:num)/update']['post'] 	= 'Admin/AdComplaint/update/$1';
$route['complaint/assign']['post'] 			= 'Admin/AdComplaint/assign';
$route['complaint/remark']['post'] 			= 'Admin/AdComplaint/remark';
$route['complaint/remarkEmp']['post'] 		= 'Admin/AdComplaint/remarkEmp';
$route['complaint/solution']['post'] 		= 'Admin/AdComplaint/solution';
$route['complaint/classification']['post'] 	= 'Admin/AdComplaint/classification';

$route['complaint/export']['get'] 			= 'Admin/AdComplaint/export';

// ```````````Feedback``````````````
$route['feedback'] 							= 'Admin/AdFeedback/index';
$route['feedback/list'] 					= 'Admin/AdFeedback/list';
$route['feedback/(:num)']['get'] 			= 'Admin/AdFeedback/view/$1';
$route['feedback/export']['get'] 			= 'Admin/AdFeedback/export';
$route['feedback/pdf/(:num)']['get'] 		= 'Admin/AdFeedbackPdf/dompdf/$1';

// ```````````Ad Enquiry``````````````
$route['enquiry'] 							= 'Admin/AdEnquiry/index';
$route['enquiry/list'] 						= 'Admin/AdEnquiry/list';
$route['enquiry/create']['get'] 			= 'Admin/AdEnquiry/create/$1';
$route['enquiry/create']['post'] 			= 'Admin/AdEnquiry/store/$1';
$route['enquiry/(:num)']['get'] 			= 'Admin/AdEnquiry/view/$1';
$route['enquiry/export']['get'] 			= 'Admin/AdEnquiry/export';
$route['enquiry/status']['post'] 			= 'Admin/AdEnquiry/status/$1';
$route['enquiry/remark']['post'] 			= 'Admin/AdEnquiry/remark/$1';



//START CUSTOMER ROUTES
// ```````````Auth``````````````
$route['customer/dashboard'] = 'Customer/CuDashboard';


$route['customer/login'] 				= 'Customer/CuAuth/login';
$route['customer/register'] 			= 'Customer/CuAuth/register';
$route['customer/verifyDetails'] 		= 'Customer/CuAuth/verifyDetails';
$route['customer/verifyCode'] 			= 'Customer/CuAuth/verifyCode';
$route['customer/getCompany']['post'] 	= 'Customer/CuAuth/getCompany';
$route['customer/logout'] 				= 'Customer/CuAuth/logout';

$route['customer/forgotpassword'] 	= 'Customer/CuAuth/forgotPassword';

$route['customer/resetpassword/(:any)']['get']	= 'Customer/CuAuth/resetPassword/$1';
$route['customer/resetpassword']['post']		= 'Customer/CuAuth/resetPasswordVerify/';

// ```````````Cu complaint``````````````
$route['customer/complaint'] 						= 'Customer/CuComplaint/index';
$route['customer/complaint/search']['get']			= 'Customer/CuComplaint/searchComplaint';
$route['customer/complaint/create']['get'] 			= 'Customer/CuComplaint/create';
$route['customer/complaint/create']['post'] 		= 'Customer/CuComplaint/store';
$route['customer/complaint/(:num)']['get'] 			= 'Customer/CuComplaint/view/$1';
$route['customer/complaint/remark']['post'] 		= 'Customer/CuComplaint/remark';
$route['customer/complaint/comment']['post'] 		= 'Customer/CuComplaint/comment';
$route['customer/complaint/escalation']['post'] 	= 'Customer/CuComplaint/escalation';

// ```````````Cu Feedback``````````````
$route['customer/feedback'] 						= 'Customer/CuFeedback/index';
$route['customer/feedback/list'] 					= 'Customer/CuFeedback/list';
$route['customer/feedback/create/(:num)']['get'] 	= 'Customer/CuFeedback/create/$1';
$route['customer/feedback/create/(:num)']['post'] 	= 'Customer/CuFeedback/store/$1';
$route['customer/feedback/(:num)']['get'] 			= 'Customer/CuFeedback/view/$1';


// ```````````Cu Enquiry``````````````
$route['customer/enquiry'] 							= 'Customer/CuEnquiry/index';
$route['customer/enquiry/list'] 					= 'Customer/CuEnquiry/list';
$route['customer/enquiry/spareparts'] 				= 'Customer/CuEnquiry/getSpareparts';
$route['customer/enquiry/create']['get'] 			= 'Customer/CuEnquiry/create/$1';
$route['customer/enquiry/create']['post'] 			= 'Customer/CuEnquiry/store/$1';
$route['customer/enquiry/(:num)']['get'] 			= 'Customer/CuEnquiry/view/$1';


// ```````````Cu Account``````````````
$route['customer/account']['get'] 					= 'Customer/CuAccount/edit';
$route['customer/account']['post'] 					= 'Customer/CuAccount/update';
$route['customer/change-password']['get'] 			= 'Customer/CuAccount/changePassword';
$route['customer/change-password']['post'] 			= 'Customer/CuAccount/changePasswordDb';



// ```````````Pages``````````````
//about
$route['about'] 								= 'Pages/About/index';
$route['about/edit']['get'] 					= 'Pages/About/edit';
$route['about/edit']['post'] 					= 'Pages/About/update';

//contact
$route['contact'] 								= 'Pages/Contact/index';
$route['contact/edit']['get'] 					= 'Pages/Contact/edit';
$route['contact/edit']['post'] 					= 'Pages/Contact/update';

//faq
$route['faq'] 									= 'Pages/Faq/index';
$route['faq/edit']['get'] 						= 'Pages/Faq/edit';
$route['faq/edit']['post'] 						= 'Pages/Faq/update';

$route['faq/category'] 							= 'Pages/Faq/category';
$route['faq/category/create']['get'] 			= 'Pages/Faq/categoryCreate';
$route['faq/category/create']['post'] 			= 'Pages/Faq/categoryStore';
$route['faq/category/(:num)/edit']['get'] 		= 'Pages/Faq/categoryEdit/$1';
$route['faq/category/(:num)/update']['post'] 	= 'Pages/Faq/categoryUpdate/$1';

$route['faq/subcategory'] 						= 'Pages/Faq/subCategory';
$route['faq/subcategory/create']['get'] 		= 'Pages/Faq/subCategoryCreate';
$route['faq/subcategory/create']['post'] 		= 'Pages/Faq/subCategoryStore';
$route['faq/subcategory/(:num)/edit']['get'] 	= 'Pages/Faq/subCategoryEdit/$1';
$route['faq/subcategory/(:num)/update']['post'] = 'Pages/Faq/subCategoryUpdate/$1';

//news
$route['news'] 									= 'Pages/News/index';
$route['news/list'] 							= 'Pages/News/list';
$route['news/newslist'] 						= 'Pages/News/newsList';
$route['news/create']['get'] 					= 'Pages/News/create';
$route['news/create']['post'] 					= 'Pages/News/store';
$route['news/(:num)/edit']['get'] 				= 'Pages/News/edit/$1';
$route['news/(:num)/edit']['post'] 				= 'Pages/News/update/$1';
$route['news/(:num)']['get'] 					= 'Pages/News/view/$1';

//registration page cms/ slider
$route['regpage/edit']['get'] 					= 'Pages/Registration/edit';
$route['regpage/edit']['post'] 					= 'Pages/Registration/update';

//login page cms/ slider
$route['loginpage/edit']['get'] 				= 'Pages/Login/edit';
$route['loginpage/edit']['post'] 				= 'Pages/Login/update';

//forgot password page cms/ slider
$route['forgotpass/edit']['get'] 				= 'Pages/Forgotpass/edit';
$route['forgotpass/edit']['post'] 				= 'Pages/Forgotpass/update';

//Reset password page cms/ slider
$route['resetpass/edit']['get'] 				= 'Pages/Resetpass/edit';
$route['resetpass/edit']['post'] 				= 'Pages/Resetpass/update';

//product page cms / slider
$route['product/edit']['get'] 				= 'Pages/Product/edit';
$route['product/edit']['post'] 				= 'Pages/Product/update';

// ```````````CHAT``````````````
$route['chat']['get'] 							= 'Chat/index';
$route['chat/(:num)']['get'] 					= 'Chat/view/$1';
$route['chat/(:num)']['post'] 					= 'Chat/store/$1';
$route['chat/(:num)/refresh']['get']			= 'Chat/getChat/$1';
$route['chat/export/(:num)']['get'] 			= 'Chat/export/$1';

// ```````````Notifications``````````````
$route['notification'] 						= 'Notification/getNotifications';
$route['notification/(:num)/read']['post'] 	= 'Notification/markRead/$1';


// ```````````Cron``````````````
// $route['cron/mail'] 						= 'Cron/MailCron/Generic';
$route['cron/newsmail'] 					= 'Cron/MailCron/News';
$route['cron/opencomplaint'] 				= 'Cron/OpenComplaintCron';
$route['cron/delete_notifications'] 		= 'Cron/NotificationCron/deleteOld';
$route['cron/chat_notifications'] 			= 'Cron/ChatCron';


$route['test1'] 						= 'Test/index';
