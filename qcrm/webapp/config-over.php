<?php
/**
 * APP - Main Page
 * By Milad [m.abooali@hotmail.com]
 */

// DevMod
define('APP_Dev_Mod', isset($_REQUEST['dev']) );

// Open Account Options
define('OPEN_TP_Platform', false);
define('OPEN_TP_DEMO', true);
define('OPEN_TP_REAL', false);

// Origin Session
define('Origin_Session_Id', (string) session_id());

// APP ROOT Path
define('APP_ROOT', __DIR__.DIRECTORY_SEPARATOR);

// Webapp
require_once(__DIR__.'/lib/webapp.php');
webapp::checkSession();

// HTML
require_once(__DIR__.'/lib/html.php');

// Permits
require_once(__DIR__.'/lib/permits.php');

// Escape User Input Values POST & GET
GF::escapeReq();

// Header Icons |  Show / Hide
define('CUSTOM_HEADER',[
    'Icon_Sidebar'  => true,
    'Icon_Settings'  => true,
    'Icon_Notifications'  => true,
]);

/*
 * List Of Sections:
 * - TV
 * - Chart
 * - Market
 * - Accounts
 * - Positions
 * - Orders
 * - Deals
 * - Operations
 * - Wallet
 * - Deposit
 * - Withdraw
 * - History
 * - Profile
 * - Logout
 * - FAQ
 * - CRM
 * - AI
 * - Share
 */

// Footer Icons
define('CUSTOM_FOOTER',[
    'Left_1'   => 'Market',
    'Left_2'   => 'Accounts',
    'Right_1'  => 'Positions',
    'Right_2'  => 'Chart'
]);

// Screen Replacement With Redirection
define('CUSTOM_LINK',[
    'A_REGISTER' => false,
    'A_RECOVER'  => false,
    'CRM'        => '/',
    'Deposit'  => false,
    'Withdraw' => false,
    'Wallet'   => false,
    'History'  => false,
]);

// Profile Detail | Show / Hide
define('CUSTOM_PROFILE',[
    'First_Name'    => true,
    'Last_Name'     => true,
    'E_mail'        => true,
    'Phone'         => true,
    'Location'      => true,
    'Business_Unit' => false
]);
