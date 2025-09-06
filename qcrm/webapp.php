<?php
/**
 * Router
 * App - Main wrapper
 * By Milad [m.abooali@hotmail.com]
 */

const Webapp_Version = '0.9.119';

require_once('config.php');
require_once('webapp/config-over.php');

$do = $_GET['do'] ?? false;

if($do){
    if ($do==='logout'){
        global $sess;
        webapp::notificaionAdd($_SESSION['id'],'secondary','log-out-outline','Logout','WebApp','');

        $sess->logout(0);
        header('Location: webapp.php');
    }
}

$countUnreadNotifications = webapp::countUnreadNotifications($_SESSION['id'] ?? 0);

$assets_version = Webapp_Version.( (APP_Dev_Mod)? '_'.strtotime('now'):null);

/**
 * Language
 */
global $_language;
global $_L;
$_L_webapp = $_L->get($_SESSION['language'])['webapp'];

/** Sections */
$sections = array(
    'Market'         => [
        'icon'    => 'analytics-outline',
        'text'    => 'Market',
        'f_text'  => 'Market View',
        'link'    => (CUSTOM_LINK['Market'] ?? false),
        'screen'  => 'trade',
        'section' => 'market'
    ],
    'Accounts'       => [
        'icon'    => 'logo-buffer',
        'text'    => 'Accounts',
        'f_text'  => 'Platform Accounts',
        'link'    => (CUSTOM_LINK['Accounts'] ?? false),
        'screen'  => 'trade',
        'section' => 'accounts'
    ],
    'Positions'      => [
        'icon'    => 'pulse-outline',
        'text'    => 'Positions',
        'f_text'  => 'Open Positions',
        'link'    => (CUSTOM_LINK['Positions'] ?? false),
        'screen'  => 'trade',
        'section' => 'positions'
    ],
    'Orders'         => [
        'icon'    => 'hourglass-outline',
        'text'    => 'Orders',
        'f_text'  => 'Pending Orders',
        'link'    => (CUSTOM_LINK['Orders'] ?? false),
        'screen'  => 'trade',
        'section' => 'pending'
    ],
    'Deals'          => [
        'icon'    => 'timer',
        'text'    => 'History',
        'f_text'  => 'History',
        'link'    => (CUSTOM_LINK['Deals'] ?? false),
        'screen'  => 'trade',
        'section' => 'history'
    ],
    'Operations'     => [
        'icon'    => 'logo-codepen',
        'text'    => 'Operations',
        'f_text'  => 'Operations',
        'link'    => (CUSTOM_LINK['Operations'] ?? false),
        'screen'  => 'trade',
        'section' => 'operation'
    ],
    'Wallet'         => [
        'icon'    => 'card-outline',
        'text'    => 'Wallet',
        'f_text'  => 'Wallet',
        'link'    => (CUSTOM_LINK['Wallet'] ?? false),
        'screen'  => 'transaction',
        'section' => 'wallet'
    ],
    'Deposit'        => [
        'icon'    => 'add',
        'text'    => 'Deposit',
        'f_text'  => 'Deposit',
        'link'    => (CUSTOM_LINK['Deposit'] ?? false),
        'screen'  => 'transaction',
        'section' => 'deposit'
    ],
    'Withdraw'       => [
        'icon'    => 'arrow-down-outline',
        'text'    => 'Withdraw',
        'f_text'  => 'Withdraw',
        'link'    => (CUSTOM_LINK['Withdraw'] ?? false),
        'screen'  => 'transaction',
        'section' => 'withdraw'
    ],
    'History'        => [
        'icon'    => 'timer',
        'text'    => 'History',
        'f_text'  => 'History',
        'link'    => (CUSTOM_LINK['History'] ?? false),
        'screen'  => 'transaction',
        'section' => 'withdraw'
    ],
    'Profile'        => [
        'icon'    => 'person-outline',
        'text'    => 'Profile',
        'f_text'  => 'Profile',
        'link'    => (CUSTOM_LINK['Profile'] ?? false),
        'screen'  => 'user',
        'section' => 'profile'
    ],
    'Logout'         => [
        'icon'    => 'log-out-outline',
        'text'    => 'Logout',
        'f_text'  => 'Logout',
        'link'    => (CUSTOM_LINK['Logout'] ?? false),
        'screen'  => 'user',
        'section' => 'logout'
    ],
    'FAQ'            => [
        'icon'    => 'help-outline',
        'text'    => 'FAQ',
        'f_text'  => 'FAQ',
        'link'    => (CUSTOM_LINK['FAQ'] ?? false),
        'screen'  => 'info',
        'section' => 'faq'
    ],
    'CRM'            => [
        'icon'    => 'help-buoy-outline',
        'text'    => 'CRM',
        'f_text'  => 'Access CRM',
        'link'    => (CUSTOM_LINK['CRM'] ?? '/'),
        'screen'  => 'info',
        'section' => 'faq'
    ],
    'AI'             => [
        'icon'    => 'chatbubble-outline',
        'text'    => 'AI',
        'f_text'  => 'AI',
        'link'    => (CUSTOM_LINK['AI'] ?? false),
        'screen'  => 'ai',
        'section' => 'ruby'
    ],
    'Share'          => [
        'icon'    => 'share-social-outline',
        'text'    => 'AI',
        'f_text'  => 'AI',
        'link'    => (CUSTOM_LINK['AI'] ?? false),
        'screen'  => 'info',
        'section' => 'web'
    ],
    'Chart'          => [
        'icon'    => 'stats-chart-outline',
        'text'    => 'Chart',
        'f_text'  => 'Advanced_Chart',
        'link'    => (CUSTOM_LINK['Chart'] ?? false),
        'screen'  => 'chart',
        'section' => 'achart'
    ],
    'TV'             => [
        'icon'    => 'tv-outline',
        'text'    => 'TV',
        'f_text'  => 'TV_View',
        'link'    => (CUSTOM_LINK['TV'] ?? false),
        'screen'  => 'trade',
        'section' => 'tv'
    ]
);

?>
<!doctype html><html lang="en">
<head>
    <title><?= $_L->T('WebApp','webapp') ?></title>
    <meta name="generator" content="Quant CRM">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#212529">
    <meta name="description" content=" ">
    <meta name="keywords" content="" />
    <link rel="icon" type="image/x-icon" href="webapp/assets/img/favicon.ico">
    <link rel="icon" type="image/png" sizes="16x16" href="webapp/assets/img/icon/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="webapp/assets/img/icon/favicon-32x32.png">
    <link rel="apple-touch-icon" type="image/png" sizes="180x180" href="webapp/assets/img/icon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="webapp/assets/img/icon/android-chrome-192x192.png">
    <link rel="icon" type="image/png" sizes="512x512" href="webapp/assets/img/icon/android-chrome-512x512.png">
    <link rel="manifest" href="webapp/__manifest.json?v=<?= $assets_version ?>"">
    <link rel="stylesheet" href="webapp/assets/css/style.css?v=<?= $assets_version ?>"">
    <link rel="stylesheet" href="webapp/assets/css/main.css?v=<?= $assets_version ?>">
    <?php if( !isset($_SESSION['id'])) { ?>
    <link rel="stylesheet" href="assets/css/login.css?v=<?= $assets_version ?>">
    <?php } ?>
    <link rel="stylesheet" href="assets/css/lang-icon.css?v=<?= $assets_version ?>">
    <link rel="stylesheet" href="webapp/assets/css/imagecrop.min.css">
    <link rel="stylesheet" href="webapp/assets/css/imagecrop_white.min.css">
    <link rel="stylesheet" href="webapp/assets/css/src/jquery.mCustomScrollbar/smooth-scrollbar.css">

    <script>
        const servicePort = {
            was :9119,
            feed:9101,
            udf :9105
        }
        let cLog = <?= (isset($_GET['dev'])) ? 'true' : 'false' ?>;
        const APP = {};
        APP.version = '<?= Webapp_Version ?>';
        APP.local   = <?= (Broker['title'] === "DevMod") ? 'true' : 'false' ?>;
        APP.serverTimeZoneOffset = <?= -(date('Z')/-60)*60*1000 ?>;
        APP.crm     = 'https://<?= Broker['crm_url'] ?>/';
        APP.socket  = `wss://altinyatirim.fx-technology.com:${servicePort.was}/`;
        APP.feed    = `wss://feed-1.fx-technology.com:${servicePort.feed}/`;
        APP.udf     = `https://udf.fx-technology.com:${servicePort.udf}/udf`;
        APP.screen  = `<?= ( isset($_SESSION['id']) ) ? 'home' : 'guest' ?>`;
        APP.section = `<?= ( isset($_SESSION['id']) ) ? 'start' : ($_GET['s'] ?? 'login') ?>`;
        APP.screenParams = {};
        APP.client  = {
            id       : "<?= $_SESSION['id'] ?? 0 ?>",
            session  : '<?= session_id() ?>',
            token    : '<?= TOKEN ?>',
            version  : '<?= Webapp_Version ?>',
            agreement  : <?= (!$_SESSION["date_approve"] || $_SESSION["date_approve"] == '0000-00-00 00:00:00') ? 'false' : 'true' ?>
        };
        APP.selectedAccount = '';
        APP.selectedSymbol = '';
        APP.callback = {};
        <?php if( isset($_SESSION['app']['avatar']) ) { ?>
            APP.client.avatar = "<?= $_SESSION['app']['avatar'] ?>";
        <?php } ?>
        APP.status = {};
        var notificationsCounter = <?= $countUnreadNotifications ?? 0; ?>;
        APP.settings = {};
        APP.settingsDef = {
            autoSizing : true,
            floatHeader : false,
            floatFooter : false,
            confirm4notify : true,
            confirm4orders : true,
            confirm4updateTrade : true,
            confirm4logout : false,
            confirm4closePosition : true,
            confirm4cancelOrder : true,
            confirm4cancelTransaction : true,
            cycleStatus : <?= (APP_Dev_Mod) ? '5' : '1' ?>,
            cycleAccountSummery : <?= (APP_Dev_Mod) ? '5' : '1' ?>,
            cycleMarketPrices : <?= (APP_Dev_Mod) ? '10' : '1' ?>,
            cycleSymbolPrices : <?= (APP_Dev_Mod) ? '10' : '1' ?>,
            cycleOpenPositions : <?= (APP_Dev_Mod) ? '5' : '1' ?>,
            cyclePendingOrders : <?= (APP_Dev_Mod) ? '5' : '1' ?>,
            cycleNotify : 30,
            cycleTvScreen : 1,
            networkServer : 's0',
            networkProxy : '0',
            cacheEnable : true
        };
        const LanguageT = <?= json_encode($_L_webapp) ?>;
        APP.TV = {};
        let uiParam = {};
        APP.maxHeapSize = 254000000;
        APP.watchlist = `top`;
        APP.filterStartOn = 2;
        APP.pingServersCycel = 0;
        APP.agreement = `<?php echo Broker['terms_file'] ?>`;
        const exceptionSymbols = new Set([`PCUSD`]);
        const reloadNeed = new Set([`achart`]);

        const DEBOUNES = {};
        const THROTTLES = {};
        const LOOPS = {};
        var last7day = Math.round((Date.now() / 1000 )-604800 );
    </script>

    <script src="assets/js/jquery.min.js"></script>
    <script src="webapp/assets/js/plugins/apexcharts/apexcharts.min.js"></script>
    <script src="webapp/assets/js/lib/dayjs.min.js"></script>
    <script src="webapp/assets/js/lib/socket.io.js"></script>
    <script type="text/javascript" src="achart/charting_library/charting_library.standalone.js"></script>
    <script type="text/javascript" src="achart/datafeeds/udf/dist/bundle.js"></script>
</head><body>

<!-- loader -->
<div id="loader">
    <img src="webapp/assets/img/loading-icon.png" alt="icon" class="loading-icon">
</div>
<!-- * loader -->

<!-- App Header -->
<div id="app-header" class="appHeader bg-dark text-light" style="display: none;">
    <div class="left">
        <?php if( isset($_SESSION['id']) ){ ?>
        <?php if(CUSTOM_HEADER['Icon_Sidebar']) { ?>
        <a href="#" onclick="syncCounters();" class="headerButton" data-bs-toggle="modal" data-bs-target="#sidebarPanel">
            <ion-icon name="menu-outline" role="img" class="md hydrated" aria-label="menu outline"></ion-icon>
        </a>
        <?php } ?>
        <?php } ?>
        <?php if(APP_Dev_Mod) { ?>
            <a href="#" class="show-PanelDev headerButton text-warning" data-bs-toggle="modal" data-bs-target="#PanelDev">
            <ion-icon name="build"></ion-icon>
        </a>
        <a href="#" class="show-PanelDev headerButton do-reScreen text-warning"><ion-icon name="refresh-outline"></ion-icon></a>
        <?php } ?>
    </div>
    <div class="pageTitle">
        <img src="webapp/assets/img/header-logo.png" alt="logo" class="logo">
    </div>
    <div class ="right">
        <?php if(CUSTOM_HEADER['Icon_Settings']) { ?>
        <a href="#" class="show-PanelAppSettings headerButton" data-bs-toggle="modal" data-bs-target="#PanelAppSettings">
            <i class="icon ion-ios-settings"></i>
        </a>
        <?php } ?>
        <?php if(isset($_SESSION['id'])){ ?>
            <?php if(CUSTOM_HEADER['Icon_Notifications']) { ?>
        <a href="#" data-last-id="0" class="show-PanelNotifications headerButton" data-bs-toggle="modal" data-bs-target="#PanelNotifications">
            <ion-icon class="icon md hydrated" name="notifications-outline" role="img" aria-label="notifications outline"></ion-icon>
            <span id="notifications-unread" class="badge badge-danger">0</span>
        </a>
            <?php } ?>
        <?php } ?>
    </div>
    <div id="app-header-alt" class="d-hide appHeaderAlt bg-primary text-light">
        <button type="button" onclick="toggleMenu()" class="btn btn-outline-light btn-block shadowed">
            <ion-icon name="arrow-up-outline"></ion-icon> <?= $_L->T('Hide','webapp') ?>
        </button>
        <img onclick="toggleMenu()" src="webapp/assets/img/header-logo.png" alt="logo" class="logo">
    </div>
</div>
<!-- * App Header -->

<!-- Focus Bar -->
<div id="focus-bar" class="d-hide section full sticky-top" style="z-index: 1150;">
    <div class="ask-bid wide-block pt-1 pb-1 d-flex justify-content-around">
        <div class="text-center">
            <small><?= $_L->T('Bid','webapp') ?></small>
            <div id="focus-bar-bid">. . .</div>
        </div>
        <div class="text-center">
            <small><?= $_L->T('Spread','webapp') ?></small>
            <div id="focus-bar-spread">. . .</div>
        </div>
        <div class="text-center">
            <small><?= $_L->T('Ask','webapp') ?></small>
            <div id="focus-bar-ask">. . .</div>
        </div>
    </div>
</div>
<!-- *  Focus Bar -->

<!-- App Capsule -->
<div id="appCapsule">
<?php if( isset($_SESSION['id']) && (!$_SESSION["date_approve"] || $_SESSION["date_approve"] == '0000-00-00 00:00:00') ) { ?>
    <!-- Agreement -->
    <div id="agreement-wrapper" class="section inset mt-5">
        <div class="wide-block pt-2 pb-2">
            <div class="content-header mb-05"><?= $_L->T('Agreement','general') ?></div>
            <div id="agreement" class="card-body"> </div>
            <div class="content-footer mt-05">
                <small class="float-left mr-auto text-danger"><?= $_L->T('Agreement_note','modal') ?></small>
                <a href="logout.php" class="btn btn-outline-danger"><?= $_L->T('Logout','login') ?></a>
                <button id="btn-agree" class="btn btn-success" onclick="acceptAgreement()"><?= $_L->T('I_Agree','general') ?></button>
            </div>
        </div>
    </div>
    <!-- * Agreement -->
<?php }
else {  ?>
    <!-- Screen -->
    <div id="screen-wrapper" class=" ">
        <?php
            if(! isset($_SESSION['id'])) {
                echo WEBAPP\html::screen('guest', null);
            }
        ?>
    </div>
    <!-- * Screen -->
<?php } ?>
</div>
<!-- * App Capsule -->

<!-- App Bottom Menu -->
<div id="app-footer" class="appBottomMenu" style="position: fixed; z-index: 0; padding-bottom: 0;display: none;">
    <!-- Left_1 -->
    <?php $item = CUSTOM_FOOTER['Left_1'];
    if($sections[$item]['link']) { ?>
        <a href="<?= $sections[$item]['link'] ?>" class="item" target="_blank">
            <div class="col">
                <ion-icon name="<?= $sections[$item]['icon'] ?>"></ion-icon>
                <strong><?= $_L->T($sections[$item]['text'],'webapp') ?></strong>
            </div>
        </a>
    <?php }
    else { ?>
        <a href="#" class="item show-section" screen="<?= $sections[$item]['screen'] ?>" section="<?= $sections[$item]['section'] ?>">
            <div class="col">
                <ion-icon name="<?= $sections[$item]['icon'] ?>"></ion-icon>
                <strong><?= $_L->T($sections[$item]['text'],'webapp') ?></strong>
            </div>
        </a>
    <?php } ?>
    <!-- Left_2 -->
    <?php $item = CUSTOM_FOOTER['Left_2'];
    if($sections[$item]['link']) { ?>
        <a href="<?= $sections[$item]['link'] ?>" class="item" target="_blank">
            <div class="col">
                <ion-icon name="<?= $sections[$item]['icon'] ?>"></ion-icon>
                <strong><?= $_L->T($sections[$item]['text'],'webapp') ?></strong>
            </div>
        </a>
    <?php }
    else { ?>
        <a href="#" class="item show-section" screen="<?= $sections[$item]['screen'] ?>" section="<?= $sections[$item]['section'] ?>">
            <div class="col">
                <ion-icon name="<?= $sections[$item]['icon'] ?>"></ion-icon>
                <strong><?= $_L->T($sections[$item]['text'],'webapp') ?></strong>
            </div>
        </a>
    <?php } ?>
    <a href="#" class="item show-section" screen="home" section="start">
        <div class="col">
            <div class="action-button large">
                <ion-icon name="grid-outline"></ion-icon>
            </div>
        </div>
    </a>
    <!-- Right_1 -->
    <?php $item = CUSTOM_FOOTER['Right_1'];
    if($sections[$item]['link']) { ?>
        <a href="<?= $sections[$item]['link'] ?>" class="item" target="_blank">
            <div class="col">
                <ion-icon name="<?= $sections[$item]['icon'] ?>"></ion-icon>
                <strong><?= $_L->T($sections[$item]['text'],'webapp') ?></strong>
            </div>
        </a>
    <?php }
    else { ?>
        <a href="#" class="item show-section" screen="<?= $sections[$item]['screen'] ?>" section="<?= $sections[$item]['section'] ?>">
            <div class="col">
                <ion-icon name="<?= $sections[$item]['icon'] ?>"></ion-icon>
                <strong><?= $_L->T($sections[$item]['text'],'webapp') ?></strong>
            </div>
        </a>
    <?php } ?>
    <!-- Right_2 -->
    <?php $item = CUSTOM_FOOTER['Right_2'];
    if($sections[$item]['link']) { ?>
        <a href="<?= $sections[$item]['link'] ?>" class="item" target="_blank">
            <div class="col">
                <ion-icon name="<?= $sections[$item]['icon'] ?>"></ion-icon>
                <strong><?= $_L->T($sections[$item]['text'],'webapp') ?></strong>
            </div>
        </a>
    <?php }
    else { ?>
        <a href="#" class="item show-section" screen="<?= $sections[$item]['screen'] ?>" section="<?= $sections[$item]['section'] ?>">
            <div class="col">
                <ion-icon name="<?= $sections[$item]['icon'] ?>"></ion-icon>
                <strong><?= $_L->T($sections[$item]['text'],'webapp') ?></strong>
            </div>
        </a>
    <?php } ?>
    <div id="app-footer-alt" class="d-hide appFooterAlt">
        <div class="bg-primary text-light d-hide ">
            <ion-icon  size="large" onclick="toggleMenu()" name="grid-outline"></ion-icon>
        </div>
        <button type="button" onclick="toggleMenu()" class="btn btn-outline-secondary btn-block shadowed">
            <ion-icon name="arrow-down-outline"></ion-icon> <?= $_L->T('Hide','webapp') ?>
        </button>
    </div>
</div>
<!-- * App Bottom Menu -->

<!-- App Sidebar -->
<?php if(isset($_SESSION['id'])){ ?>
<div class="modal fade panelbox panelbox-left" id="sidebarPanel" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <!-- profile box -->
                <div class="profileBox pt-2 pb-2">
                    <div class="image-wrapper">
                        <a href="#" class="item show-section" screen="user" section="avatar">
                            <img src=" " alt="image" class="avatar imaged w36">
                        </a>
                    </div>
                    <div class="in">
                        <strong class="fname">-</strong>
                        <div class="text-muted username">-</div>
                    </div>
                    <a href="#" class="btn btn-link btn-icon sidebar-close" data-bs-dismiss="modal">
                        <ion-icon name="close-outline"></ion-icon>
                    </a>
                </div>
                <!-- * profile box -->

                <!-- Transaction -->
                <div class="sidebar-balance">
                    <div class="listview-title"><?= $_L->T('Transaction','webapp') ?></div>
                </div>
                <div class="action-group">
                    <?php if(CUSTOM_LINK['Deposit']){?>
                        <a href="<?= CUSTOM_LINK['Deposit'] ?>" class="action-button" target="_blank">
                            <div class="in">
                                <div class="iconbox">
                                    <ion-icon name="add-outline"></ion-icon>
                                </div>
                                <?= $_L->T('Deposit','webapp') ?>
                            </div>
                        </a>
                    <?php } else { ?>
                        <a href="#" class="action-button show-section w-50" screen="transaction" section="deposit">
                            <div class="in">
                                <div class="iconbox">
                                    <ion-icon name="add-outline"></ion-icon>
                                </div>
                                <?= $_L->T('Deposit','webapp') ?>
                            </div>
                        </a>
                    <?php } ?>
                    <?php if(CUSTOM_LINK['Withdraw']){?>
                        <a href="<?= CUSTOM_LINK['Withdraw'] ?>" class="action-button" target="_blank">
                            <div class="in">
                                <div class="iconbox">
                                    <ion-icon name="arrow-down-outline"></ion-icon>
                                </div>
                                <?= $_L->T('Withdraw','webapp') ?>
                            </div>
                        </a>
                    <?php } else { ?>
                        <a href="#" class="action-button show-section w-50" screen="transaction" section="withdraw">
                            <div class="in">
                                <div class="iconbox">
                                    <ion-icon name="arrow-down-outline"></ion-icon>
                                </div>
                                <?= $_L->T('Withdraw','webapp') ?>
                            </div>
                        </a>
                    <?php } ?>
                    <!--
                    <?php if(CUSTOM_LINK['History']){?>
                        <a href="<?= CUSTOM_LINK['History'] ?>" class="action-button" target="_blank">
                            <div class="in">
                                <div class="iconbox">
                                    <ion-icon name="timer"></ion-icon>
                                </div>
                                <?= $_L->T('History','webapp') ?>
                            </div>
                        </a>
                    <?php } else { ?>
                        <a href="#" class="action-button show-section" screen="transaction" section="history">
                            <div class="in">
                                <div class="iconbox">
                                    <ion-icon name="timer"></ion-icon>
                                </div>
                                <?= $_L->T('History','webapp') ?>
                            </div>
                        </a>
                    <?php } ?>
                    <?php if(CUSTOM_LINK['Wallet']){?>
                        <a href="<?= CUSTOM_LINK['Wallet'] ?>" class="action-button" target="_blank">
                            <div class="in">
                                <div class="iconbox">
                                    <ion-icon name="card-outline"></ion-icon>
                                </div>
                                <?= $_L->T('Wallet','webapp') ?>
                            </div>
                        </a>
                    <?php } else { ?>
                        <a href="#" class="action-button show-section" screen="transaction" section="wallet">
                            <div class="in">
                                <div class="iconbox">
                                    <ion-icon name="card-outline"></ion-icon>
                                </div>
                                <?= $_L->T('Wallet','webapp') ?>
                            </div>
                        </a>
                    <?php } ?>
                    -->
                </div>
                <!-- * Transaction -->

                <!-- crm menu -->
                <div class="listview-title mt-1">User</div>
                <ul class="listview flush transparent no-line image-listview">
                    <li>
                        <a href="#" class="item show-section" screen="user" section="profile">
                            <div class="icon-box bg-secondary">
                                <ion-icon name="person-outline"></ion-icon>
                            </div>
                            <div class="in">
                                <?= $_L->T('Profile','webapp') ?>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a id="do-logout" href="#" class="item">
                            <div class="icon-box bg-danger">
                                <ion-icon name="log-out-outline"></ion-icon>
                            </div>
                            <div class="in">
                                <?= $_L->T('Logout','webapp') ?>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- * crm menu -->


                <!-- trade menu -->
                <div class="listview-title mt-1"><?= $_L->T('Trading_Room','webapp') ?></div>
                <ul  id="trade-menu" class="listview flush transparent no-line image-listview">

                    <li>
                        <a href="#" class="item show-section" screen="trade" section="accounts">
                            <div class="icon-box bg-primary">
                                <ion-icon name="logo-buffer"></ion-icon>
                            </div>
                            <div class="in">
                                <?= $_L->T('Platform_Accounts','webapp') ?>
                                <span id="account-counts" class="badge badge-primary"></span>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="item show-section" screen="trade" section="market">
                            <div class="icon-box bg-primary">
                                <ion-icon name="analytics-outline"></ion-icon>
                            </div>
                            <div class="in">
                                <?= $_L->T('Market_View','webapp') ?>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="item show-section" screen="trade" section="positions">
                            <div class="icon-box bg-primary">
                                <ion-icon name="pulse-outline"></ion-icon>
                            </div>
                            <div class="in">
                                <?= $_L->T('Open_Positions','webapp') ?>
                                <span id="position-counts" class="badge badge-primary"></span>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="item show-section" screen="trade" section="pending">
                            <div class="icon-box bg-primary">
                                <ion-icon name="hourglass-outline"></ion-icon>
                            </div>
                            <div class="in">
                                <?= $_L->T('Pending_Orders','webapp') ?>
                                <span id="order-counts" class="badge badge-primary"></span>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="item show-section" screen="trade" section="history">
                            <div class="icon-box bg-primary">
                                <ion-icon name="timer"></ion-icon>
                            </div>
                            <div class="in">
                                <?= $_L->T('History','webapp') ?>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="item show-section" screen="trade" section="operation">
                            <div class="icon-box bg-primary">
                                <ion-icon name="logo-codepen"></ion-icon>
                            </div>
                            <div class="in">
                                <?= $_L->T('Operations','webapp') ?>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- * trade menu -->

                <!-- app menu -->
                <div class="listview-title mt-1">App Options</div>
                <ul class="listview flush transparent no-line image-listview">
                    <li>
                        <a href="#" class="item show-section" screen="info" section="faq">
                            <div class="icon-box bg-info">
                                <ion-icon name="help-outline"></ion-icon>
                            </div>
                            <div class="in">
                                <?= $_L->T('FAQ','webapp') ?>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="<?= CUSTOM_LINK['CRM'] ?>" target="_blank" class="item">
                            <div class="icon-box bg-info">
                                <ion-icon name="help-buoy-outline"></ion-icon>
                            </div>
                            <div class="in">
                                <?= $_L->T('Access_CRM','webapp') ?>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="item show-PanelNotifications" data-bs-toggle="modal" data-bs-target="#PanelNotifications">
                            <div class="icon-box bg-info">
                                <ion-icon name="notifications-outline"></ion-icon>
                            </div>
                            <div class="in">
                                <?= $_L->T('Notifications','webapp') ?>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="item show-PanelAppSettings" data-bs-toggle="modal" data-bs-target="#PanelAppSettings">
                            <div class="icon-box bg-info">
                                <ion-icon name="settings-outline"></ion-icon>
                            </div>
                            <div class="in">
                                <?= $_L->T('Settings','webapp') ?>
                            </div>
                        </a>
                    </li>
               </ul>
                <!-- * app menu -->

            </div>
        </div>
    </div>
</div>
<?php } ?>
<!-- * App Sidebar -->

<!-- Panel Dev -->
<?php if(APP_Dev_Mod) { ?>
<div class="modal fade panelbox panelbox-left" id="PanelDev" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= $_L->T('Dev_Panel','webapp') ?></h5>
                <a href="#" data-bs-dismiss="modal">Close</a>
            </div>
            <div class="modal-body">
                <div class="wide-block pt-2 pb-2">
                    <div id="dev-app-info">
                        <h5 class="text-black-50">APP Detail</h5>
                        <table class="table table-striped table-responsive-sm table-borderless">
                            <tbody>
                                <tr>
                                    <td>Session</td>
                                    <td><small class="dev-session"> </small></td>
                                </tr>
                                <tr>
                                    <td>User ID</td>
                                    <td><small class="dev-uid"> </small></td>
                                </tr>
                                <tr>
                                    <td>Screen</td>
                                    <td>
                                        <small class="dev-screen"> </small>
                                        >
                                        <small class="dev-section"> </small>
                                        <button class="float-end btn btn-sm btn-outline-secondary do-reScreen"><ion-icon name="refresh-outline"></ion-icon></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div>
                        <h5 class="text-black-50">Connections Status</h5>
                        <div class="chip chip-media">
                            <i class="chip-icon bg-light">
                                <div id="status-socket" class="spinner-grow spinner-grow-sm"></div>
                            </i>
                            <span class="chip-label">Socket</span>
                        </div>
                        <br>
                        <div class="chip chip-media">
                            <i class="chip-icon bg-light">
                                <div id="status-feed" class="spinner-grow spinner-grow-sm"></div>
                            </i>
                            <span class="chip-label">Feed</span>
                        </div>
                        <br>
                        <div class="chip chip-media">
                            <i class="chip-icon bg-light">
                                <div id="status-crm" class="spinner-grow spinner-grow-sm"></div>
                            </i>
                            <span class="chip-label">CRM</span>
                        </div>
                        <br>
                        <div class="chip chip-media">
                            <i class="chip-icon bg-light">
                                <div id="status-ruby" class="spinner-grow spinner-grow-sm"></div>
                            </i>
                            <span class="chip-label">Ruby</span>
                        </div>
                        <br>
                        <div class="chip chip-media">
                            <i class="chip-icon bg-light">
                                <div id="status-meta" class="spinner-grow spinner-grow-sm"></div>
                            </i>
                            <span class="chip-label">Meta API</span>
                        </div>
                    </div>
                   <hr>
                    <div>
                        <h5 class="text-black-50">Mass Action</h5>
                        <div class="modal-header">
                            <button onclick="forceReloadPage()" class="btn btn-sm btn-block btn-outline-primary square me-1">Force Reload All Users</button>
                        </div>
                    </div>
                    <hr>
                   <div>
                        <h5 class="text-black-50">Online Clients</h5>
                        <div class="modal-header">
                            <h4><strong id="online-total" class="text-success"></strong> Online</h4>
                        </div>
                        <ul id="roles"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<!-- * Panel Dev -->

<!-- Panel App Settings -->
<div class="modal fade panelbox panelbox-right" id="PanelAppSettings" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= $_L->T('Settings','webapp') ?></h5>
                <a href="#" data-bs-dismiss="modal">Close</a>
            </div>
            <div class="modal-body">
                <div class="loading text-center"></div>
                    <div class="listview-title"><?= $_L->T('Theme','webapp') ?></div>
                    <ul class="listview image-listview inset">
                        <li>
                            <div class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="moon-outline" role="img" class="md hydrated" aria-label="moon outline"></ion-icon>
                                </div>
                                <div class="in">
                                    <div><?= $_L->T('Dark_Mode','webapp') ?></div>
                                    <div class="form-check form-switch ms-2">
                                        <input class="form-check-input dark-mode-switch" type="checkbox" id="darkmodeSwitch">
                                        <label class="form-check-label" for="darkmodeSwitch"></label>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                <div class="p-3">
                    <div class="form-check">
                        <input type="checkbox" class="app-setting-item form-check-input" id="floatHeader">
                        <label class="form-check-label" for="floatHeader"> <?= $_L->T('Float_Header','webapp') ?> </label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="app-setting-item form-check-input" id="floatFooter">
                        <label class="form-check-label" for="floatFooter"> <?= $_L->T('Float_Footer','webapp') ?> </label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="app-setting-item form-check-input" id="autoSizing">
                        <label class="form-check-label" for="autoSizing"> <?= $_L->T('Auto_Fit_Screen_Size','webapp') ?> </label>
                    </div>
                    <div class="mt-3">
                        <p>Switch Screen:</p>
                        <button type="button" onclick="screenMobile()" class="btn btn-outline-primary btn-block shadowed me-1 mb-1"><ion-icon name="phone-portrait-outline"></ion-icon> <?= $_L->T('Mobile','webapp') ?></button>
                        <button type="button" onclick="screenTv()" class="btn btn-outline-primary btn-block shadowed me-1 mb-1"><ion-icon name="tv-outline"></ion-icon> <?= $_L->T('Desktop','webapp') ?></button>
                    </div>
                </div>
                <hr>
                <div>
                    <div class="listview-title">Language</div>
                    <div class="dropdown">
                        <button class="btn btn-primary btn-block dropdown-toggle text-capitalize" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="flag-icon flag-icon-<?= $_language ?> "></span> <?= $_language ?> </span> <span class="mdi mdi-chevron-down "> </span>
                        </button>
                        <div class="dropdown-menu" style="">
                            <?php
                            $languages = scandir('./languages');
                            unset($languages[0]);
                            unset($languages[1]);
                            foreach ($languages as $lang) {
                                $lang = str_replace('.ini','',$lang);
                                ?>
                                <a href="?language=<?= $lang ?>" class="dropdown-item w-100">
                                    <span class="flag-icon flag-icon-<?= $lang ?>"></span> <span class="align-middle text-capitalize"> <?= $lang ?> </span>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <hr>
                <div>
                    <div class="listview-title"><?= $_L->T('Action_Confirmation','webapp') ?></div>
                    <div class="form-check">
                        <input type="checkbox" class="app-setting-item form-check-input" id="confirm4notify">
                        <label class="form-check-label" for="confirm4notify"> <?= $_L->T('Delete_Notifications','webapp') ?></label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="app-setting-item form-check-input" id="confirm4orders">
                        <label class="form-check-label" for="confirm4orders"> <?= $_L->T('Orders_Buy_Sell','webapp') ?></label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="app-setting-item form-check-input" id="confirm4closePosition">
                        <label class="form-check-label" for="confirm4closePosition"> <?= $_L->T('Close_Position','webapp') ?></label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="app-setting-item form-check-input" id="confirm4cancelTransaction">
                        <label class="form-check-label" for="confirm4cancelTransaction"> <?= $_L->T('Cancel_Transaction','webapp') ?></label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="app-setting-item form-check-input" id="confirm4cancelOrder">
                        <label class="form-check-label" for="confirm4cancelOrder"> <?= $_L->T('Cancel_Order','webapp') ?></label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="app-setting-item form-check-input" id="confirm4updateTrade">
                        <label class="form-check-label" for="confirm4updateTrade"> <?= $_L->T('Edit_Position_Order','webapp') ?></label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="app-setting-item form-check-input" id="confirm4logout">
                        <label class="form-check-label" for="confirm4logout"> <?= $_L->T('Logout','webapp') ?></label>
                    </div>
                </div>
                <hr>
                <div>
                    <div class="listview-title"><?= $_L->T('Update_Cycles','webapp') ?> <small>(<?= $_L->T('Second','webapp') ?>)</small></div>
                    <small class="text-danger">* <?= $_L->T('Set_to_stop_update','webapp') ?></small>
                    <div class="input-wrapper">
                        <label class="label" for="cycleStatus"><?= $_L->T('Services_Status','webapp') ?></label>
                        <input type="number" step="5" min="5" max="600" class="app-setting-item form-control" id="cycleStatus" placeholder="x Second">
                    </div>
                    <div class="input-wrapper">
                        <label class="label" for="cycleAccountSummery"><?= $_L->T('Account_Summery','webapp') ?></label>
                        <input type="number" step="1" min="1" max="600" class="app-setting-item form-control" id="cycleAccountSummery" placeholder="x Second">
                    </div>
                    <div class="input-wrapper">
                        <label class="label" for="cycleMarketPrices"><?= $_L->T('Market','webapp') ?></label>
                        <input type="number" step="1" min="1" max="300" class="app-setting-item form-control" id="cycleMarketPrices" placeholder="x Second">
                    </div>
                    <div class="input-wrapper">
                        <label class="label" for="cycleSymbolPrices"><?= $_L->T('Symbol_Price','webapp') ?></label>
                        <input type="number" step="1" min="1" max="300" class="app-setting-item form-control" id="cycleSymbolPrices" placeholder="x Second">
                    </div>
                    <div class="input-wrapper">
                        <label class="label" for="cycleOpenPositions"><?= $_L->T('Open_Positions','webapp') ?></label>
                        <input type="number" step="1" min="1" max="300" class="app-setting-item form-control" id="cycleOpenPositions" placeholder="x Second">
                    </div>
                    <div class="input-wrapper">
                        <label class="label" for="cyclePendingOrders"><?= $_L->T('Pending_Orders','webapp') ?></label>
                        <input type="number" step="1" min="1" max="300" class="app-setting-item form-control" id="cyclePendingOrders" placeholder="x Second">
                    </div>
                    <div class="input-wrapper">
                        <label class="label" for="cycleNotify"><?= $_L->T('Notifications','webapp') ?></label>
                        <input type="number" step="5" min="5" max="600" class="app-setting-item form-control" id="cycleNotify" placeholder="x Second">
                    </div>
                    <div class="input-wrapper">
                        <label class="label" for="cycleTvScreen"><?= $_L->T('Tv_Screen','webapp') ?></label>
                        <input type="number" step="1" min="1" max="300" class="app-setting-item form-control" id="cycleTvScreen" placeholder="x Second">
                    </div>
                </div>
                <hr>
                <div>
                    <div class="listview-title"><?= $_L->T('Network','webapp') ?></div>
                    <div class="input-wrapper">
                        <label class="label" for="networkServer"><?= $_L->T('Server_Connection_Method','webapp') ?></label>
                        <select class="app-setting-item form-control custom-select" id="networkServer">
                            <option value="s0">Main (NodeJs)</option>
                            <option value="s1">Mirror 1 (CRM Socket)</option>
                            <option value="s2" disabled>Mirror 2 (CRM Ajax)</option>
                        </select>
                    </div>
                    <div class="input-wrapper">
                        <label class="label" for="networkProxy"><?= $_L->T('DNS_Proxy','webapp') ?></label>
                        <select class="app-setting-item form-control custom-select" id="networkProxy">
                            <option value="0" selected>No Proxy</option>
                            <option value="1" disabled>Asia (Middle East)</option>
                            <option value="2" disabled>Asia (Pacific)</option>
                            <option value="3" disabled>Africa</option>
                            <option value="4" disabled>Europe</option>
                            <option value="5" disabled>USA</option>
                        </select>
                    </div>
                </div>
                <hr>
                <div>
                    <div class="listview-title"><?= $_L->T('Cache','webapp') ?></div>
                    <div class="form-check">
                        <input type="checkbox" class="app-setting-item form-check-input" id="cacheEnable">
                        <label class="form-check-label" for="cacheEnable"> <?= $_L->T('Enable','webapp') ?></label>
                    </div>
                    <button type="button" onclick="clearCache()" class="btn btn-warning btn-block mt-2">
                        <ion-icon name="trash"></ion-icon> <?= $_L->T('Clear','webapp') ?>
                    </button>
                </div>
                <hr>
                <div>
                    <div class="listview-title"><?= $_L->T('Version','webapp') ?></div>
                    <small>Q Webapp <?= Webapp_Version ?></small>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- * Panel App Settings -->

<!-- Panel Notifications -->
<div class="modal fade panelbox panelbox-right" id="PanelNotifications" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content pb-3">
            <div class="modal-header">
                <h5 class="modal-title"><?= $_L->T('Notifications','webapp') ?></h5>
                <a href="#" data-bs-dismiss="modal"><?= $_L->T('Close','webapp') ?></a>
            </div>
            <div class="d-flex justify-content-around py-3 border-top border-bottom">
                <button id="notification-seen-all" class="btn btn-sm btn-outline-info">
                    <ion-icon name="eye-outline"></ion-icon> <?= $_L->T('Mark_All_Seen','webapp') ?>
                </button>
                <button id="notification-delete-all" class="btn btn-sm btn-outline-danger">
                    <ion-icon name="trash-outline" ></ion-icon> <?= $_L->T('Delete_All','webapp') ?>
                </button>
            </div>
            <div class="modal-body">
                <div id="notification-container" class="section full overflow-auto">
                    <ul class="listview image-listview flush"></ul>
                </div>
                <div class="d-flex justify-content-center">
                    <button id="moreNotifications" type="button" class="loading btn btn-block btn-secondary">

                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- * Panel Notifications -->

<!-- Modal Notification -->
<div class="modal fade modalbox" id="ModalNotification" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="appHeader">
                    <div class="left">
                        <a href="#" class="go-back" data-bs-toggle="modal" data-bs-target="#ModalNotification">
                            <ion-icon name="chevron-back-outline" role="img" class="md hydrated" aria-label="chevron back outline"></ion-icon>
                        </a>
                    </div>
                    <div class="pageTitle">
                        <?= $_L->T('Notification_Detail','webapp') ?>
                    </div>
                    <div class="right">
                        <a href="#" data-id="0" class="del-notification headerButton" >
                            <ion-icon name="trash-outline" role="img" class="md hydrated" aria-label="trash outline"></ion-icon>
                        </a>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="loading"></div>
                <div class="section"></div>
            </div>
        </div>
    </div>
</div>
<!-- * Modal Notification -->

<!-- Dialog Confirmation -->
<div class="modal fade dialogbox" id="DialogConfirmation" data-bs-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-icon text-warning">
                <ion-icon name="warning"></ion-icon>
            </div>
            <div class="modal-header">
                <h5 class="modal-title"><?= $_L->T('Title','webapp') ?></h5>
            </div>
            <div class="modal-body">
                <?= $_L->T('Are_you_sure','webapp') ?>
            </div>
            <div class="modal-footer">
                <div class="btn-inline">
                    <a href="#" class="btn btn-text-secondary" data-bs-dismiss="modal"><?= $_L->T('CANCEL','webapp') ?></a>
                    <a href="#" onclick="" class="do-confirm btn btn-text-secondary" data-bs-dismiss="modal"><span class="text-danger"><?= $_L->T('DELETE','webapp') ?></span></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- * Dialog Confirm Delete -->

<!-- Panel Left -->
<div class="modal fade panelbox panelbox-left" id="PanelLeft" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= $_L->T('Left_Panel_Title','webapp') ?></h5>
                <a href="#" data-bs-dismiss="modal"><?= $_L->T('Close','webapp') ?></a>
            </div>
            <div class="modal-body">
                <?= $_L->T('Panel_Body','webapp') ?>
            </div>
        </div>
    </div>
</div>
<!-- * Panel Left -->

<!-- Panel Right -->
<div class="modal fade panelbox panelbox-right" id="PanelRight" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= $_L->T('Right_Panel_Title','webapp') ?></h5>
                <a href="#" data-bs-dismiss="modal"><?= $_L->T('Close','webapp') ?></a>
            </div>
            <div class="modal-body">
                <?= $_L->T('Panel_Body','webapp') ?>
            </div>
        </div>
    </div>
</div>
<!-- * Panel Right -->

<!-- Modal Basic -->
<div class="modal fade modalbox" id="ModalBasic" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= $_L->T('Modal_title','webapp') ?></h5>
                <a href="#" data-bs-dismiss="modal"><?= $_L->T('Close','webapp') ?></a>
            </div>
            <div class="modal-body">
                <p>
                    <?= $_L->T('Panel_Body','webapp') ?>
                </p>
            </div>
        </div>
    </div>
</div>
<!-- * Modal Basic -->

<!-- iOS Add to Home Action Sheet -->
<div class="modal inset fade action-sheet ios-add-to-home" id="ios-add-to-home-screen" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= $_L->T('Add_to_Home_Screen','webapp') ?></h5>
                <a href="#" class="close-button" data-bs-dismiss="modal">
                    <ion-icon name="close"></ion-icon>
                </a>
            </div>
            <div class="modal-body">
                <div class="action-sheet-content text-center">
                    <div class="mb-1"><img src="webapp/assets/img/icon/apple-touch-icon.png" alt="image" class="imaged w64 mb-2">
                    </div>
                    <div>
                        Install <strong><?= Broker['title'] ?> WebApp</strong> iPhone'unuzun ana ekranında.
                    </div>
                    <div>
                        Tap <ion-icon name="share-outline"></ion-icon> ve Ana ekrana ekle.
                    </div>
                    <div class="mt-2">
                        <button class="btn btn-primary btn-block" data-bs-dismiss="modal"><?= $_L->T('Close','webapp') ?></button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- * iOS Add to Home Action Sheet -->

<!-- Android Add to Home Action Sheet -->
<div class="modal inset fade action-sheet android-add-to-home" id="android-add-to-home-screen" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= $_L->T('Add_to_Home_Screen','webapp') ?></h5>
                <a href="#" class="close-button" data-bs-dismiss="modal">
                    <ion-icon name="close"></ion-icon>
                </a>
            </div>
            <div class="modal-body">
                <div class="action-sheet-content text-center">
                    <div class="mb-1">
                        <img src="webapp/assets/img/icon/android-chrome-192x192.png" alt="image" class="imaged w64 mb-2">
                    </div>
                    <div>
                        Install <strong><?= Broker['title'] ?> WebApp</strong> iPhone'unuzun ana ekranında.
                    </div>
                    <div>
                        Tap <ion-icon name="ellipsis-vertical"></ion-icon> ve Ana ekrana ekle.
                    </div>
                    <div class="mt-2">
                        <button class="btn btn-primary btn-block" data-bs-dismiss="modal"><?= $_L->T('Close','webapp') ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- * Android Add to Home Action Sheet -->

<!-- notification main -->
<div id="notification-main" class="notification-box">
    <div id="type" class="notification-dialog ios-style">
        <div class="notification-header">
            <div class="in">
                <ion-icon id="icon" name="information-circle-outline"></ion-icon>
                <strong id="title" class="ms-1"></strong>
            </div>
            <div class="right">
                <span id="time">Just now</span>
                <a href="#" class="close-button">
                    <ion-icon name="close-circle"></ion-icon>
                </a>
            </div>
        </div>
        <div class="notification-content">
            <div class="in" id="body">
            </div>
        </div>
    </div>
</div>
<!-- * notification main -->

<!-- Account Summary -->
<div class="modal fade action-sheet" id="accountSummary" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span class="loading float-end"></span> <?= $_L->T('Account_Summary','webapp') ?> (<small></small>)</h4>
                <span class="text-danger" id="tradeStatus"></span>
            </div>
            <div class="modal-body">
                <div class="action-sheet-content">
                    <div class="mb-2 d-flex justify-content-between" style="overflow-x:scroll">
                        <a href="#" class="btn btn-sm btn-info show-section mx-1" screen="trade" section="positions">
                            <ion-icon name="pulse-outline" role="img" class="md hydrated" aria-label="pulse outline"></ion-icon>
                            <?= $_L->T('Positions','webapp') ?>
                        </a>
                        <a href="#" class="btn btn-sm btn-info show-section mx-1" screen="trade" section="pending">
                            <ion-icon name="hourglass-outline"></ion-icon>
                            <?= $_L->T('Pending_Orders','webapp') ?>
                        </a>
                        <a href="#" class="btn btn-sm btn-info show-section mx-1" screen="trade" section="history">
                            <ion-icon name="timer" role="img" class="md hydrated" aria-label="timer"></ion-icon>
                            <?= $_L->T('History','webapp') ?>
                        </a>
                        <a href="#" class="btn btn-sm btn-info show-section mx-1" screen="trade" section="operation">
                            <ion-icon name="logo-codepen" role="img" class="md hydrated" aria-label="timer"></ion-icon>
                            <?= $_L->T('Operations','webapp') ?>
                        </a>
                    </div>
                    <ul class="listview">
                        <li><?= $_L->T('Balance','webapp') ?><span class="float-end" id="Balance"></span></li>
                        <li><?= $_L->T('Equity','webapp') ?><span class="float-end" id="Equity"></span></li>
                        <li><?= $_L->T('Margin','webapp') ?><span class="float-end" id="Margin"></span></li>
                        <li><?= $_L->T('MarginLevel','webapp') ?><span class="float-end" id="MarginLevel"></span></li>
                        <li><?= $_L->T('Margin','webapp') ?> Free<span class="float-end" id="MarginFree"></span></li>
                        <li><?= $_L->T('Margin','webapp') ?> Leverage<span class="float-end" id="MarginLeverage"></span></li>
                        <li><?= $_L->T('Profit','webapp') ?><span class="float-end" id="Profit"></span></li>
                        <li><?= $_L->T('Swap','webapp') ?><span class="float-end" id="Storage"></span></li>
                    </ul>
                    <a href="#" class="mt-1 btn btn-sm btn-outline-secondary btn-block " data-bs-dismiss="modal"><?= $_L->T('Close','webapp') ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- * Account Summary -->

<!-- Open Account -->
<div class="modal fade action-sheet" id="openAccount" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= $_L->T('New_Platform_Account','webapp') ?></h5>
            </div>
            <div class="modal-body">
                <div class="action-sheet-content">
                    <form name="open-account">
                        <div class="form-group basic <?= (OPEN_TP_Platform)?:'d-hide'?>">
                            <div class="input-wrapper">
                                <label class="label" for="platform"><?= $_L->T('Platform','webapp') ?></label>
                                <select class="form-control custom-select" name="platform" id="platform" required>
                                    <option value="" disabled><?= $_L->T('Please_Select_Platform','webapp') ?></option>
                                    <option value="2" selected>MT5</option>
                                    <option value="1" disabled>MT4</option>
                                </select>
                            </div>
                            <div class="input-info"><?= $_L->T('Select_Platform','webapp') ?></div>
                        </div>
                        <div class="form-group basic <?= (OPEN_TP_DEMO && OPEN_TP_REAL)?:'d-hide' ?>">
                            <label class="type">Type</label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="" disabled><?= $_L->T('Please_Select_Type','webapp') ?></option>
                                <option value="1" <?= (OPEN_TP_DEMO)?'selected':'disabled'?>><?= $_L->T('Demo','webapp') ?></option>
                                <option value="2" <?= (OPEN_TP_REAL)?:'disabled'?> <?= (OPEN_TP_REAL && !OPEN_TP_DEMO)?'selected':''?>><?= $_L->T('Real','webapp') ?></option>
                            </select>
                            <div class="input-info"><?= $_L->T('Select_Type','webapp') ?></div>
                        </div>
                        <div class="form-group basic">
                            <label class="group"><?= $_L->T('Group','webapp') ?></label>
                            <select class="form-control" name="group" id="group" required>
                                <option value=""> - </option>
                            </select>
                            <div class="input-info"></div>
                        </div>
                        <div class="form-group basic <?= (OPEN_TP_DEMO)?:'d-hide' ?>">
                            <label class="amount"><?= $_L->T('Amount','webapp') ?></label>
                            <input type="number" min="100" max="100000" step="1" value="<?= (OPEN_TP_DEMO)?1000:0 ?>" class="form-control" id="amount" placeholder="Deposit Amount" name="amount" required>
                            <div class="input-info"><?= $_L->T('Account_start_balance','webapp') ?></div>
                        </div>
                        <div class="form-group basic">
                            <button type="submit" class="btn btn-primary btn-block btn-lg" disabled><?= $_L->T('Open_The_Account','webapp') ?></button>
                            <a href="#" class="mt-1 btn btn-sm btn-outline-secondary btn-block " data-bs-dismiss="modal"><?= $_L->T('Close','webapp') ?></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- * Open Account -->

<!-- Position Edit -->
<div class="modal fade action-sheet" id="positionEdit" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= $_L->T('Edit_Position','webapp') ?></h5>
            </div>
            <div class="modal-body">
                <div class="action-sheet-content">

                    <form name="edit-position">

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group basic">
                                    <label class="label">S/L</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" placeholder="<?= $_L->T('Enter_Stop_Loss','webapp') ?>" value="" id="SL">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group basic">
                                    <label class="label">T/P</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" placeholder="<?= $_L->T('Enter_Take_Profit','webapp') ?>" value="" id="TP">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <small>* <?= $_L->T('sl_tp_note','webapp') ?></small>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <button type="button" class="btn btn-primary btn-block btn-lg" disabled><?= $_L->T('Update','webapp') ?></button>
                            <a href="#" class="mt-1 btn btn-sm btn-outline-secondary btn-block " data-bs-dismiss="modal"><?= $_L->T('Close','webapp') ?></a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- * Position Edit -->

<!-- Position Detail -->
<div class="modal fade action-sheet" id="positionDetail" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span class="loading float-end"></span><?= $_L->T('Position_Detail','webapp') ?>  (<small></small>)</h4>
            </div>
            <div class="modal-body">
                <div class="action-sheet-content">
                    <div class="mb-2 d-flex justify-content-between">
                        <button class="act-edit col me-2 position-edit btn btn-sm btn-warning" position="" data-bs-toggle="modal" data-bs-target="#positionEdit"> <?= $_L->T('Edit_Position','webapp') ?></button>
                        <button class="act-close col position-close btn btn-sm btn-danger" position=""> <?= $_L->T('Close','webapp') ?> </button>
                    </div>
                    <table class="table table table-sm table-striped">
                        <tr><td><?= $_L->T('Symbol','webapp') ?></td><td id="symbol"></td></tr>
                        <tr><td><?= $_L->T('Profit','webapp') ?></td><td id="profit"></td></tr>
                        <tr><td><?= $_L->T('Storage','webapp') ?></td><td id="storage"></td></tr>
                        <tr><td><?= $_L->T('Volume','webapp') ?></td><td id="volume"></td></tr>
                        <tr><td><?= $_L->T('Open_Price','webapp') ?></td><td id="open-price"></td></tr>
                        <tr><td><?= $_L->T('Current_Price','webapp') ?></td><td id="current-price"></td></tr>
                        <tr><td><?= $_L->T('Stop_Loss','webapp') ?></td><td id="sl"></td></tr>
                        <tr><td><?= $_L->T('Take_Profit','webapp') ?></td><td id="tp"></td></tr>
                        <tr><td><?= $_L->T('Margin_Rate','webapp') ?></td><td id="margin-rate"></td></tr>
                        <tr><td><?= $_L->T('Create_Time','webapp') ?></td><td id="time"></td></tr>
                    </table>
                    <a href="#" class="mt-1 btn btn-sm btn-outline-secondary btn-block " data-bs-dismiss="modal"><?= $_L->T('Close','webapp') ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- * Position Detail -->

<!-- Order Edit -->
<div class="modal fade action-sheet" id="orderEdit" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= $_L->T('Edit_Order','webapp') ?></h5>
            </div>
            <div class="modal-body">
                <div class="action-sheet-content">

                    <form name="edit-order">

                        <div class="row">
                            <div class="col-3 text-center">
                                <span value="-.01" class="update-volume btn btn-outline-primary text-danger py-3 my-2 width-100">-0.01</span>
                                <br>
                                <span value="-1" class="update-volume btn btn-outline-primary text-danger py-3 my-2 width-100">-1</span>
                            </div>
                            <div class="col-6">
                                <div class="form-group basic pt-2">
                                    <div class="input-group mt-2">
                                        <input type="number" class="form-control text-center" value="" step="0.01" placeholder="<?= $_L->T('Enter_Volume','webapp') ?>" id="volume" name="volume">
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 text-center">
                                <span value=".01" class="update-volume btn btn-outline-primary text-success py-3 my-2 width-100">+0.01</span>
                                <br>
                                <span value="1" class="update-volume btn btn-outline-primary text-success py-3 my-2 width-100">+1</span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group basic">
                                    <label class="label">S/L</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" placeholder="<?= $_L->T('Enter_Stop_Loss','webapp') ?>" value="" id="SL">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group basic">
                                    <label class="label">T/P</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" placeholder="<?= $_L->T('Enter_Take_Profit','webapp') ?>" value="" id="TP">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <small>* <?= $_L->T('sl_tp_note','webapp') ?></small>
                            </div>
                        </div>


                        <div id="advanced-order" class="row border-top">
                            <div class="col-12">
                                <div class="form-group basic">
                                    <label for="PriceOrder" class="label"><?= $_L->T('Price','webapp') ?></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" placeholder="<?= $_L->T('Enter_Price','webapp') ?>" value="" id="PriceOrder" name="PriceOrder">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group basic">
                                    <label for="p-order-type" class="label"><?= $_L->T('Order_Type','webapp') ?></label>
                                    <div class="input-group">
                                        <select id="p-order-type" name="p-order-type" class="w-100 custom-select" disabled>
                                            <option value="2" selected>BUY LIMIT</option>
                                            <option value="3">SELL LIMIT</option>
                                            <option value="4">BUY STOP</option>
                                            <option value="5">SELL STOP</option>
                                            <option value="6">BUY STOP LIMIT</option>
                                            <option value="7">SELL STOP LIMIT</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group basic">
                                    <label for="time-type" class="label"><?= $_L->T('Time_Type','webapp') ?></label>
                                    <div class="input-group">
                                        <select id="time-type" name="time-type" class="w-100 custom-select">
                                            <option value="0" selected="">Good till Canceled</option>
                                            <option value="1">Intra-day</option>
                                            <option value="2">Specified time</option>
                                            <option value="3">Specified day</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group basic">
                                    <label class="label"><?= $_L->T('Date_Time','webapp') ?></label>
                                    <div class="input-group">
                                        <input id="TimeExpiration"  name="TimeExpiration" type="date" class="spe-datetime form-control d-hide">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group basic">
                                    <label class="label"><?= $_L->T('Price_Trigger','webapp') ?></label>
                                    <div class="input-group">
                                        <input id="PriceTrigger" name="PriceTrigger" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <button type="button" class="btn btn-primary btn-block btn-lg"><?= $_L->T('Update','webapp') ?></button>
                            <a href="#" class="mt-1 btn btn-sm btn-outline-secondary btn-block " data-bs-dismiss="modal"><?= $_L->T('Close','webapp') ?></a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- * Order Edit -->

<!-- Order Detail -->
<div class="modal fade action-sheet" id="orderDetail" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span class="loading float-end"></span> <?= $_L->T('Order_Detail','webapp') ?> (<small></small>)</h4>
            </div>
            <div class="modal-body">
                <div class="action-sheet-content">
                    <div class="mb-2 d-flex justify-content-between">
                        <button class="act-edit col me-2 order-edit btn btn-sm btn-warning" Order="" data-bs-toggle="modal" data-bs-target="#orderEdit"> <?= $_L->T('Edit_Order','webapp') ?></button>
                        <button class="act-cancel col order-cancel btn btn-sm btn-danger" Order=""> <?= $_L->T('Cancel','webapp') ?> </button>
                    </div>
                    <table class="table table table-sm table-striped">
                        <tr><td><?= $_L->T('Symbol','webapp') ?></td><td id="symbol"></td></tr>
                        <tr><td><?= $_L->T('Type','webapp') ?></td><td id="type"></td></tr>
                        <tr><td><?= $_L->T('Volume','webapp') ?></td><td id="volume"></td></tr>
                        <tr><td><?= $_L->T('Order_Price','webapp') ?></td><td id="order-price"></td></tr>
                        <tr><td><?= $_L->T('Current_Price','webapp') ?></td><td id="current-price"></td></tr>
                        <tr><td><?= $_L->T('Trigger_Price','webapp') ?></td><td id="trigger-price"></td></tr>
                        <tr><td><?= $_L->T('Stop_Loss','webapp') ?></td><td id="sl"></td></tr>
                        <tr><td><?= $_L->T('Take_Profit','webapp') ?></td><td id="tp"></td></tr>
                        <tr><td><?= $_L->T('Create_Time','webapp') ?></td><td id="time"></td></tr>
                    </table>
                    <a href="#" class="mt-1 btn btn-sm btn-outline-secondary btn-block " data-bs-dismiss="modal"><?= $_L->T('Close','webapp') ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- * Order Detail -->

<!-- Symbol Detail -->
<div class="modal fade action-sheet" id="symbolDetail" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span class="loading float-end"></span> <?= $_L->T('Symbol_Detail','webapp') ?> (<small></small>)</h4>
            </div>
            <div class="modal-body">
                <div class="action-sheet-content">
                    <ul class="listview">
                        <li><?= $_L->T('Symbol','webapp') ?><span class="float-end" id="Symbol"></span></li>
                        <li><?= $_L->T('Description','webapp') ?><span class="float-end" id="Description"></span></li>
                        <li><?= $_L->T('ContractSize','webapp') ?><span class="float-end" id="ContractSize"></span></li>
                    </ul>
                    <a href="#" class="mt-1 btn btn-sm btn-outline-secondary btn-block " data-bs-dismiss="modal"><?= $_L->T('Close','webapp') ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- * Symbol Detail -->

<!-- Trade Form -->
<div class="modal fade action-sheet" id="tradeForm" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <h5 class="col-4 modal-title text-start"> - </h5>
                    <div class="col-8 text-end pt-1">
                        <span class="text-secondary"><?= $_L->T('Order_Type','webapp') ?></span>
                        <select id="order-type" class="ms-2 custom-select advance-mode">
                            <option value="Market" selected=""><?= $_L->T('Market','webapp') ?></option>
                            <option value="Pending"><?= $_L->T('Pending','webapp') ?></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="action-sheet-content">

                    <form id="trade" name="trade">
                        <div class="row">
                            <div class="col-3 text-center">
                                <span value="-.01" class="update-volume btn btn-outline-primary text-danger py-3 my-2 width-100">-0.01</span>
                                <br>
                                <span value="-1" class="update-volume btn btn-outline-primary text-danger py-3 my-2 width-100">-1</span>
                            </div>
                            <div class="col-6">
                                <div class="form-group basic pt-2">
                                    <div class="input-group mt-2">
                                        <input type="number" class="form-control text-center" value="" step="0.01" placeholder="<?= $_L->T('Enter_Volume','webapp') ?>" id="volume" name="volume">
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 text-center">
                                <span value=".01" class="update-volume btn btn-outline-primary text-success py-3 my-2 width-100">+0.01</span>
                                <br>
                                <span value="1" class="update-volume btn btn-outline-primary text-success py-3 my-2 width-100">+1</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group basic">
                                    <label for="SL" class="label">S/L</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="<?= $_L->T('Enter_Stop_Loss','webapp') ?>" value="" id="SL" name="SL">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group basic">
                                    <label for="TP" class="label">T/P</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="<?= $_L->T('Enter_Take_Profit','webapp') ?>" value="" id="TP" name="TP">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <small>* <?= $_L->T('sl_tp_note','webapp') ?></small>
                            </div>
                        </div>

                        <div id="advanced-order" class="row border-top d-hide">
                            <div class="col-12">
                                <div class="form-group basic">
                                    <label for="PriceOrder" class="label"><?= $_L->T('Price','webapp') ?></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" placeholder="<?= $_L->T('Enter_Price','webapp') ?>" value="" id="PriceOrder" name="PriceOrder">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group basic">
                                    <label for="p-order-type" class="label"><?= $_L->T('Order_Type','webapp') ?></label>
                                    <div class="input-group">
                                        <select id="p-order-type" name="p-order-type" class="w-100 custom-select">
                                            <option value="2" selected>BUY LIMIT</option>
                                            <option value="3">SELL LIMIT</option>
                                            <option value="4">BUY STOP</option>
                                            <option value="5">SELL STOP</option>
                                            <option value="6">BUY STOP LIMIT</option>
                                            <option value="7">SELL STOP LIMIT</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group basic">
                                    <label for="time-type" class="label"><?= $_L->T('Time_Type','webapp') ?></label>
                                    <div class="input-group">
                                        <select id="time-type" name="time-type" class="w-100 custom-select">
                                            <option value="0" selected="">Good till Canceled</option>
                                            <option value="1">Intra-day</option>
                                            <option value="2">Specified time</option>
                                            <option value="3">Specified day</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group basic">
                                    <label class="label"><?= $_L->T('Date_Time','webapp') ?></label>
                                    <div class="input-group">
                                        <input id="TimeExpiration"  name="TimeExpiration" type="date" class="spe-datetime form-control d-hide">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group basic">
                                    <label class="label"><?= $_L->T('Price_Trigger','webapp') ?></label>
                                    <div class="input-group">
                                        <input id="PriceTrigger" name="PriceTrigger" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="loading text-center"></div>
                            <button id="trade-action" type="submit" class=" "> </button>
                            <a href="#" class="mt-1 btn btn-sm btn-outline-secondary btn-block " data-bs-dismiss="modal"><?= $_L->T('Close','webapp') ?></a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- * Trade Form -->

<!-- Chart -->
<div class="modal fade action-sheet" id="tradeChart" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">  <?= $_L->T('Trade_Chart','webapp') ?>(<small></small>)</h4>
            </div>
            <div class="modal-body">
                <div class="action-sheet-content">
                     <ul class="nav nav-tabs lined">
                        <li class="nav-item"><a time="15" class="nav-link chart-time" href="#">15M</a></li>
                        <li class="nav-item"><a time="30" class="active nav-link chart-time" href="#">30M</a></li>
                        <li class="nav-item"><a time="60" class="nav-link chart-time" href="#">1H</a></li>
                        <li class="nav-item"><a time="720" class="nav-link chart-time" href="#">12H</a></li>
                        <li class="nav-item"><a time="1440" class="nav-link chart-time" href="#">24H</a></li>
                    </ul>
                    <div class="card-body">
                        <div class="loading text-center"></div>
                        <div id="simple-chart"></div>
                    </div>
                    <a href="#" class="open-achart show-section btn btn-primary btn-lg btn-block" screen="chart" section="achart" params="">
                        <ion-icon name="expand-outline"></ion-icon> <?= $_L->T('Advanced_Chart','webapp') ?></a>
                    <a href="#" class="mt-1 btn btn-sm btn-outline-secondary btn-block" data-bs-dismiss="modal"><?= $_L->T('Close','webapp') ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- * Chart -->

<!-- Content Action Sheet -->
<div class="modal fade action-sheet" id="actionSheetContent" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Action Sheet</h5>
            </div>
            <div class="modal-body">
                <div class="action-sheet-content">
                    <p>
                        Lorem ipsum
                    </p>
                    <a href="#" class="btn btn-primary btn-lg btn-block" data-bs-dismiss="modal"><?= $_L->T('Close','webapp') ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- * Content Action Sheet -->

<!-- Socket Reconnecting -->
<div id="socket-reconnecting" class="hide-onload">
    <div>
        <div class="bg-danger px-2 py-1">
            <span class="spinner-grow spinner-grow-sm"></span>
            <span class="ms-1"><?= $_L->T('Reconnecting','webapp') ?> ...</span>
        </div>
        <div id="error-text" class="text-secondary card-body <?= (APP_Dev_Mod) ?: 'd-hide' ?>"></div>
    </div>
</div>
<!-- * Socket Reconnecting -->

<app-temps class="d-hide">
    <!-- Temp - TP TV Symbol -->
    <table>
        <tbody>
            <tr id="tv-symbol-temp" class="symbol-row d-hide" symbol="">
                <td id="symbol-watchlist">
                    <button type="button" onclick="addPersonalWatchlist(event, this)" class="add-symbol btn btn-icon text-secondary text-muted"><ion-icon name="star-outline"></ion-icon></button>
                    <button type="button" onclick="removePersonalWatchlist(event, this)" class="remove-symbol btn btn-icon text-warning text-muted"><ion-icon name="star"></ion-icon></button>
                </td>
                <th id="symbol-pair"></th>
                <td id="bid-price"></td>
                <td id="ask-price"></td>
                <td id="spread" class="text-center"></td>
            </tr>
        </tbody>
    </table>

    <!-- * Temp - TP TV Symbol -->

    <!-- Temp - TP Market Symbol -->
    <div id="symbol-temp" class="symbol-row d-hide" symbol="">
        <div class="col mt-2">
            <div class="row border-bottom mb-2 pb-1">
                <div class="col text-start">
                    <button type="button" onclick="addPersonalWatchlist(event, this)" class="add-symbol btn btn-icon text-secondary text-muted"><ion-icon name="star-outline"></ion-icon></button>
                    <button type="button" onclick="removePersonalWatchlist(event, this)" class="remove-symbol btn btn-icon text-warning text-muted"><ion-icon name="star"></ion-icon></button>
                    <button type="button" class="show-chart btn btn-icon text-secondary text-muted" symbol="" data-bs-toggle="modal" data-bs-target="#tradeChart"><ion-icon name="stats-chart-outline"></ion-icon></button>
                </div>
                <div class="col text-end market-text-datetime">
                    <div id="datetime" class="text-secondary">0000/00/00 00:00:00</div>
                </div>
            </div>
            <div class="row">
                <div class="col text-start">
                    <ion-icon id="price-icon" name="trending-up-outline" class="md hydrated"></ion-icon>
                </div>
                <div class="col-8 text-end">
                    <div class="inline-flex">
                        <button type="button" class="btn btn-success btn-sm do-trade buyleft" symbol="" trade="Buy" data-bs-toggle="modal" data-bs-target="#tradeForm">
                            <ion-icon name="arrow-up-outline" class="md hydrated"></ion-icon> <?= $_L->T('Buy','webapp') ?>
                        </button>
                        <input type="number" class="form-control lotsize" value="0.01" step="0.01" symbol="xxxx">
                        <button type="button" class="btn btn-danger btn-sm do-trade sellright" symbol=""  trade="Sell" data-bs-toggle="modal" data-bs-target="#tradeForm">
                            <ion-icon name="arrow-down-outline" class="md hydrated"></ion-icon> <?= $_L->T('Sell','webapp') ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col mt-2">
            <div class="in row listview2">
                <div class="col">
                    <strong id="symbol-pair">XXXZZZ</strong>
                    <div class="text-xsmall text-secondary"> <?= $_L->T('Open','webapp') ?>: <span id="open-price">0.01234</span></div>
                    <div class="text-xsmall text-secondary"><?= $_L->T('Close','webapp') ?>: <span id="close-price">0.01234</span></div>
                </div>
                <div class="col">
                    <div id="bid" class="card bg-success p-1">
                        <span class="text-small text-white text-center"><?= $_L->T('Bid_Price','webapp') ?></span>
                        <div>
                            <h3 class="text-white text-center">
                                <ion-icon id="bid-icon" name="arrow-up-outline" class="md hydrated"></ion-icon>
                                <span id="bid-price">0.01234</span>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div id="ask" class="card bg-primary p-1">
                        <span class="text-small text-white text-center"><?= $_L->T('Ask_Price','webapp') ?></span>
                        <div>
                            <h3 class="text-white text-center">
                                <ion-icon id="ask-icon" name="remove" class="md hydrated"></ion-icon>
                                <span id="ask-price">0.01234</span>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * Temp - TP Market Symbol -->

    <!-- Temp - TP Position -->
    <div id="position-temp" class="d-hide">
        <div class="in">
            <div>
                <h4 id="symbol"> </h4>
                <p id="price-current"> </p>
            </div>
            <div>
                <h4 id="volume" class="text-primary"> </h4>
                <p id="action"> </p>
            </div>
            <div>
                <h4>$ <span id="profit"> </span></h4>
                <p><?= $_L->T('Profit','webapp') ?></p>
            </div>
        </div>
        <div class="in">
            <div>
                <p>S/L: <span id="sl" class="text-danger"> </span></p>
            </div>
            <div>
                <p>T/P: <span id="tp" class="text-success"> </span></p>
            </div>
            <div>
                <p><?= $_L->T('Storage','webapp') ?>: $ <span id="Storage" class="text-danger"> </span></p>
            </div>
        </div>
        <hr>
        <div class="action-button d-flex justify-content-between">
            <button class="show-chart btn btn-icon text-secondary text-muted"  type="button"  symbol="" data-bs-toggle="modal" data-bs-target="#tradeChart"><ion-icon name="stats-chart-outline"></ion-icon></button>
            <button class="position-detail btn btn-secondary" position="" data-bs-toggle="modal" data-bs-target="#positionDetail"><?= $_L->T('Detail','webapp') ?>  </button>
            <button class="position-edit btn btn-warning" position="" data-bs-toggle="modal" data-bs-target="#positionEdit"> <?= $_L->T('Edit','webapp') ?> </button>
            <button class="position-close btn btn-danger" position="">  <?= $_L->T('Close','webapp') ?></button>
        </div>

    </div>
    <!-- * Temp - TP Position -->

    <!-- Temp - TP Pending -->
    <div id="order-temp" class="d-hide">
        <div class="in">
            <div>
                <h4 id="symbol"> </h4>
                <p id="price-current"> </p>
            </div>
            <div>
                <h4 id="volume" class="text-primary"> </h4>
                <p id="action"> </p>
            </div>
            <div>
                <h4><span id="price-order"> </span></h4>
                <p><?= $_L->T('Order_Price','webapp') ?></p>
            </div>
        </div>
        <div class="in">
            <div>
                <p>S/L: <span id="sl" class="text-danger"> </span></p>
            </div>
            <div>
                <p>T/P: <span id="tp" class="text-success"> </span></p>
            </div>
            <div>
                <p><?= $_L->T('Trigger','webapp') ?>: $ <span id="price-trigger" class="text-danger"> </span></p>
            </div>
        </div>
        <hr>
        <div class="action-button d-flex justify-content-between">
            <button class="show-chart btn btn-icon text-secondary text-muted"  type="button"  symbol="" data-bs-toggle="modal" data-bs-target="#tradeChart"><ion-icon name="stats-chart-outline"></ion-icon></button>
            <button class="order-detail btn btn-secondary" order="" data-bs-toggle="modal" data-bs-target="#orderDetail"> <?= $_L->T('Detail','webapp') ?> </button>
            <button class="order-edit btn btn-warning" order="" data-bs-toggle="modal" data-bs-target="#orderEdit"> <?= $_L->T('Edit','webapp') ?>  </button>
            <button class="order-cancel btn btn-danger" order=""> <?= $_L->T('Cancel','webapp') ?> </button>
        </div>

    </div>
    <!-- * Temp - TP Pending -->

    <!-- Temp - TP History -->
    <div id="deal-temp" class="d-hide">
        <div class="in">
            <div>
                <h4 id="symbol"> </h4>
                <p id="price"> </p>
            </div>
            <div>
                <h4 id="volume" class="text-primary"> </h4>
                <p id="action"> </p>
            </div>
            <div>
                <h4>$ <span id="profit"> </span></h4>
                <p><?= $_L->T('Profit','webapp') ?></p>
            </div>
        </div>
        <div class="in">
            <div>
                <p>S/L: <span id="sl" class="text-danger"> </span></p>
            </div>
            <div>
                <p>T/P: <span id="tp" class="text-success"> </span></p>
            </div>
            <div>
                <p><?= $_L->T('Storage','webapp') ?>: $ <span id="storage" class="text-danger"> </span></p>
            </div>
        </div>
        <hr>
        <div class="in">
            <p class="text-start"><?= $_L->T('Open','webapp') ?>: <span class="text-primary" id="price-open"></span><br><span id="time-open" class="text-dark"></span> </p>
            <p class="text-start"><?= $_L->T('Close','webapp') ?>: <span class="text-primary" id="price-close"></span><br><span id="time-close" class="text-dark"></span> </p>
        </div>

    </div>
    <!-- * Temp - TP History -->

    <!-- Temp - TP Operation -->
    <div id="operation-temp" class="d-hide">
        <div class="detail">
            <div>
                <strong id="type"> </strong>
                <p id="time"> </p>
            </div>
        </div>
        <div class="right">
            <div id="comment"></div>
            <div id="amount" class="price">  </div>
        </div>
    </div>
    <!-- * Temp - TP Operation -->
</app-temps>

<app-externalScripts>
    <!-- ========= JS Files =========  -->
    <!-- Bootstrap -->
    <script src="webapp/assets/js/lib/bootstrap.bundle.min.js"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Splide -->
    <script src="webapp/assets/js/plugins/splide/splide.min.js"></script>
    <!-- Base Js File -->
    <script src="webapp/assets/js/base.js"></script>

    <script src="webapp/assets/js/app/functions.js?v=<?= $assets_version ?>"></script>
    <script src="webapp/assets/js/app/socket.js?v=<?= $assets_version ?>" defer></script>
    <script src="webapp/assets/js/app/main.js?v=<?= $assets_version ?>" defer></script>

    <script src="webapp/assets/js/lib/jquery.dataTables.min.js" defer></script>
    <script src="webapp/assets/js/lib/dataTables.bootstrap5.min.js" defer></script>

    <script src="webapp/assets/js/lib/imagecrop.min.js" defer></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment-with-locales.min.js"></script>
    <script src="webapp/assets/js/plugins/jquery.mCustomScrollbar/smooth-scrollbar.js"></script>

    <script src="webapp/assets/js/app/loopman.js"></script>
</app-externalScripts>

<script>
    // Add to Home with 1.5 seconds delay.
    AddtoHome("1500", "once");
    instance = $('#trade-positions #position-temp').html();
    htmlStage = $('#trade-positions #positions-wrapper');

    const appUI = {
        $:{
            /* Temps */
            iTradeMarket_temp:$('app-temps #symbol-temp'),
            iTradeTv_tempSymbol:$('app-temps tr#tv-symbol-temp'),

            /* Panel - Account Notifications */
            iPanelNotifications: $('#PanelNotifications'),


            /* Dialogs - Account Summery */
            iAccountSummary: $('#accountSummary'),
            iAccountSummary_title: $(`#accountSummary .modal-title small`),
            iAccountSummary_tradeStatus: $(`#accountSummary #tradeStatus`),
            iAccountSummary_span: $('#accountSummary ul.listview span'),
            iAccountSummary_listview: $('#accountSummary ul.listview'),

            /* Dialogs - Focus Bar */
            iFocusBar: $('#focus-bar'),
            iFocusBar_ask: $('#focus-bar #focus-bar-ask'),
            iFocusBar_bid: $('#focus-bar #focus-bar-bid'),
            iFocusBar_spread: $('#focus-bar #focus-bar-spread'),


            /* Globals */
            iAppHeader: $('#app-header'),
            iAppFooter: $('#app-footer'),
            iScreenWrapper: $('#screen-wrapper'),
            iDoLogout: $('#do-logout'),
            cModal: $('.modal'),
            cNotification: $("#notification-main"),
            iSidebarPanel: $("#sidebarPanel"),
            hSymbolRows : {},
            hDealRows : {},
            hOperationRows : {},
            hPositionRows : {},
            hOrderRows : {},
        },
        E:{
            screenWrapper: document.getElementById('screen-wrapper'),
        }
    };



  if(cLog) {
        (function () { var script = document.createElement('script'); script.src="https://cdn.jsdelivr.net/npm/eruda";
            document.body.append(script);
            script.onload = function () { eruda.init(); } })();
    }

    // Force Full Screen


</script>

</body></html>