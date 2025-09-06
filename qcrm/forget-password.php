<?php
######################################################################
#  M | 11:20 AM Tuesday, July 6, 2021
#          Add Comment Section
#          Fix Security Bugs
######################################################################

require_once "config.php";

// LangMan
global $_L;

/*
  Accept email of user whose password is to be reset
  Send email to user to reset their password
*/
$error = false;
if (isset($_POST['reset-password'])) {

    /**
     * Escape User Input Values POST & GET
     */
    GF::escapeReq();

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $_SESSION['captcha_length']++;
        $error = "Not Valid Email Address!";
    } else {
        // Check Captcha
        if (Broker['captcha'] && (strtoupper($_POST['captcha']) != $_SESSION['captcha']['code'])) {
            $_SESSION['captcha_length']++;
            $error = "You have entered the wrong captcha!";
        } else {

            global $db;
            $email = $db->escape($_POST['email']);
            // Check if exist
            $where = "email='$email' AND unit IN (".Broker['units'].")";
            $user = $db->selectRow('users',$where);

            if ($user) {
                $up_token['token'] = bin2hex(random_bytes(50));
                $db->updateId('users', $user['id'], $up_token);
                // Send Email
                global $_Email_M;
                $receivers[] = array (
                    'id'    =>  $user['id'],
                    'email' =>  $user['email'],
                    'data'  =>  array(
                        'token' =>  $up_token['token']
                    )
                );
                $subject = $theme = 'CRM_Reset_Password';
                $_Email_M->send($receivers, $theme, $subject);
            } else {
                $error = "Sorry, no user exists on our system with that email";
            }
            // Add actLog
            global $actLog; $actLog->add('Recover Pass', ($user['id'] ?? false), (!$error), json_encode(array($email)));
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $_L->T('Reset_Password','login') ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
    <?php include('includes/css.php'); ?>
    <link rel="stylesheet" href="assets/css/login.css">
<body>

<div class="container-login">

    <div class="container text-right">
        <a class="nav-link dropdown-toggle arrow-none waves-effect text-capitalize" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
            <span class="flag-icon flag-icon-<?= $_language ?> "></span> <?= $_language ?> </span> <span class="mdi mdi-chevron-down "> </span>
        </a>
        <div class="dropdown-menu dropdown-menu-left dropdown-menu-sm">
            <?php
            $languages = scandir('./languages');
            unset($languages[0]);
            unset($languages[1]);
            foreach ($languages as $lang) {
                $lang = str_replace('.ini','',$lang);
                ?>
                <a href="?language=<?= $lang ?>" class="dropdown-item-text btn-outline-light my-1 py-1">
                    <span class="flag-icon flag-icon-<?= $lang ?>"></span> <span class="align-middle text-capitalize"> <?= $lang ?> </span>
                </a>
            <?php } ?>
        </div>
    </div>

    <div class="login">
        <div class="login__content">
            <img class="login__img" src="assets/images/bg-login.png" alt="Login image" />

            <form class="login__form" action=""  method="post">

                <?php if (isset($_POST['reset-password']) && !$error) { ?>
                    <div>
                        <a href="index.php" class="logo logo-admin"><img src="media/broker/<?= Broker['logo'];?>" height="50" alt="logo"></a>

                        <h1 class="login__title">
                            <span><?= $_L->T('Pending_Confirmation','login') ?></span>
                        </h1>

                        <p class="login__description">
                            <?= $_L->T('Reset_Password_text','login', $email) ?>
                        </p>
                        <a type="button" class="login__button" href="login.php"><?= $_L->T('Back_to_Login','login') ?></a>
                    </div>
                <?php }
                else { ?>
                    <div>
                        <a href="index.php" class="logo logo-admin"><img src="media/broker/<?= Broker['logo'];?>" height="50" alt="logo"></a>
                        <h1 class="login__title">
                            <span><?= $_L->T('Reset_Password','login') ?></span>
                        </h1>
                        <p class="login__description">
                            <?= $_L->T('Reset_Password_Req_text','login') ?>
                        </p>
                    </div>
                    <div>
                        <div class="login__inputs">
                            <div>
                                <label for="email" class="login__label">Email</label>
                                <input class="login__input" type="text" id="email" name="email" placeholder="Enter your email address" required />
                            </div>

                            <?php if(Broker['captcha']) {
                                unset($_SESSION['captcha']);
                                include_once 'lib/captcha/captcha.php';
                                $_SESSION['captcha'] = simple_php_captcha();
                                if(DevMod) GF::cLog('Captcha: '.$_SESSION['captcha']['code']);
                                ?>
                                <div class="pt-3 px-2 row">
                                    <div class="col-md-6 pt-3">
                                        <label for="captcha" class="login__label"><?= $_L->T('Captcha','login') ?>:</label>
                                        <span class="btn btn-light float-right p-1 mr-1" style="margin-bottom: -65px;" id="doA-reCaptcha"><i class="fa fa-sync"></i></span>
                                        <input type="login__input" class="form-control" name="captcha" id="captcha" required>
                                    </div>
                                    <div class="col-md-6 p-5">
                                        <img class="captcha-img" src="<?= $_SESSION['captcha']['image_src'] ?>" alt="CAPTCHA code">
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div>
                        <div class="login__buttons">
                            <button type="submit" class="login__button" name="reset-password"><?= $_L->T('Submit','general') ?></button>
                        </div>
                        <a href="login.php" class="login__forgot"><i class="mdi mdi-lock"></i> <?= $_L->T('Back_to_Login','login') ?> </a>
                    </div>
                    <?php if($error) { ?>
                        <div class="text-left w-100 alert alert-danger text-dark my-5">
                            <?= $error ?>
                        </div>
                    <?php } ?>
                <?php  } ?>
            </form>
        </div>
    </div>
</div>

<?php include('includes/script.php'); ?>
<script src="assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
<?php include('includes/script-bottom.php'); ?>
</body>
</html>