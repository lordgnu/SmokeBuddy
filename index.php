<?php

// Helper Constants
defined('DS')	||	define('DS', DIRECTORY_SEPARATOR);
defined('NL')	||	define('NL', PHP_EOL);

// System Constants
defined('BASE_DIR')		||	define('BASE_DIR', dirname(__FILE__));
defined('DATA_DIR')		||	define('DATA_DIR', BASE_DIR . DS . 'data');
defined('USER_DIR')		||	define('USER_DIR', DATA_DIR . DS . 'users');
defined('RESOURCE_DIR')	||	define('RESOURCE_DIR', BASE_DIR . DS . 'resources');
defined('LIB_DIR')		||	define('LIB_DIR', RESOURCE_DIR . DS . 'libs');
defined('TPL_DIR')		||	define('TPL_DIR', RESOURCE_DIR . DS . 'templates');
defined('CACHE_DIR')	||	define('CACHE_DIR', DATA_DIR . DS . 'cache');

// Include Libraries
require_once LIB_DIR . DS . 'smarty' . DS . 'Smarty.class.php';

// Include Subs
require_once LIB_DIR . DS . 'smokeBuddy' . DS . 'subs.php';

// Instance Smarty
$smarty = new Smarty();

// Set Smarty Directories
$smarty->setTemplateDir(TPL_DIR)
		->setCompileDir(CACHE_DIR)
		->setCacheDir(CACHE_DIR);

// Clear Smarty Cache
$smarty->clearAllCache();
$smarty->clearCompiledTemplate();

// Set dispatch variables
$action = array_key_exists('action', $_GET) ? $_GET['action'] : 'default';
$switch = array_key_exists('switch', $_GET) ? $_GET['switch'] : 'default';

// Load Data
$_DATA = loadSerialData();

/*
 * $templateFile = Template File to Include
 * $headerText = Header Text
 * $footerText = Footer Text
 * */
$templateFile = 'dashboard.tpl';
$headerText = 'Smoke Buddy';
$footerText = '';
$jump = false;
$loginError = false;

$smarty->assignByRef('templateFile', $templateFile);
$smarty->assignByRef('headerText', $headerText);
$smarty->assignByRef('footerText', $footerText);
$smarty->assign('error', false);

// Check for login cookie
$sbData = array();
if (array_key_exists('sb', $_COOKIE)) {
	// SmokeBuddy Cookie found!
	$sbData = unserialize($_COOKIE['sb']);
}

// Check user cookie data
if ($sbData['name'] == '' && $action != 'login') {
	// User needs to register or login
	$action = 'register';
}

// Switch on Action
switch ($action) {
	case 'settings':
		if ($switch == 'submit') {
			
		} else {
			$templateFile = 'settings.tpl';
			$headerText = 'Notifications';
		}
		break;
	case 'login':
		$name = $_POST['login-name'];
		$pin = $_POST['login-pin'];
		
		// Loop through the users and find name
		foreach ($_DATA['users'] as $user) {
			if ($user['name'] == $name) {
				// Found user!  Check Pin
				if ($user['pin'] == $pin) {
					$sbData = $user;
					$jump = true;
					break;
				}
			}
		}
		
		if ($jump !== true) {
			$loginError = 'Name and PIN do not match';
			$templateFile = 'register.tpl';
		}
		break;
	case 'register':
		$smarty->assign('loginError', false);
		
		if ($switch == 'submit') {
			// Get Post Data
			$user = array(
				'name'			=>	$_POST['name'],
				//'organization'	=>	$_POST['organization'],
				'pin'			=>	$_POST['pin'],
				'timer'			=>	(int) $_POST['timer']
			);
			
			// Add User
			$userAdded = addUser($user);
			if ($userAdded === true) {
				// User added successfully!
				$jump = true;
			} else {
				// There was an error
				$smarty->assign('error', $userAdded);
				$templateFile = 'register.tpl';
			}
		} else {
			// Show the form
			$templateFile = 'register.tpl';
		}
		break;
	case 'status':
		// Changing status
		switch ($switch) {
			case 'away':
			case 'smoking':
			case 'not-smoking':
				changeUserStatus($sbData['index'], $switch);
				
				if ($switch == 'away') {
					// Send to notification disabled time page
					
				} else {
					// Send back to dashboard
					$templateFile = 'dashboard.tpl';
				}
				break;
			default:
				$templateFile = 'status.tpl';
				break;
		}
		break;
	case 'debug':
		$smarty->assign('myData', $_DATA);
		$smarty->assign('sbData', $sbData);
		$templateFile = 'debug.tpl';
		$headerText = 'Debug Dump';
		break;
	default:
		$templateFile = 'dashboard.tpl';
		break;
}

// Save Serial Data
saveSerialData();

// Update Cookie
setcookie('sb', serialize($sbData), strtotime('+1 year'), '/');

if ($jump === false) {
	// Load the template
	if ($action != 'settings' && $action != 'register') {
		$smarty->assign('showSettings', true);
	} else {
		$smarty->assign('showSettings', false);
	}
	
	$smarty->display('main.tpl');
} elseif ($jump === true) {
	jump();
} else {
	jump($jump);
}