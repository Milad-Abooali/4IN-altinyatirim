<?php
######################################################################
#  M | 11:20 AM Tuesday, July 6, 2021
#          Add Comment Section
#          Fix Security Bugs
######################################################################

require_once "config.php";


// LangMan
global $_L;

if(($_SESSION["id"]) ?? false) {
    if($_SESSION["tye"] = "Admin"){
        header("location: welcome2.php");
        exit;
    } else {
        header("location: welcome.php");
        exit;
    }
}

// Processing form data when form is submitted
global $sess;
if($_SERVER["REQUEST_METHOD"] == "POST") {

    /**
     * Escape User Input Values POST & GET
     */
    GF::escapeReq();

    if(is_numeric($_POST['username'])) {
        $_POST['username'] = $sess->getUsernamebyPhone($_POST['username']);
    }
    $sess->login($_POST['timeoffset'], $_POST['username'], $_POST['password'], $_POST['remember'] ?? false, true, $_POST['target']);

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title><?php echo Broker['title'];?> - <?= $_L->T('Feel_The_Difference','head') ?></title>
    <meta content="Admin Dashboard" name="description" />
    <meta content="Themesbrand" name="author" />
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <?php include('includes/css.php'); ?>
    <?php if( !isset($_SESSION['id'])) { ?>
        <link rel="stylesheet" href="assets/css/login.css">
    <?php } ?>
<body>
<!-- Begin page -->

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

            <form  id="login" class="login__form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"  method="post">
                <div>
                    <a href="index.php" class="logo logo-admin"><img src="media/broker/<?= Broker['logo'];?>" height="50" alt="logo"></a>

                    <h1 class="login__title">
                        <span>Welcome</span> Back
                    </h1>

                    <p class="login__description">
                        <?= $_L->T('login_note','login') ?> <?= Broker['title'];?>.
                    </p>
                </div>
                <?= ($sess->ERROR) ? '<div class="alert alert-danger text-dark">'.$sess->ERROR.'</div>' : NULL ?>
                <?php if (Broker['maintenance']): ?>
                    <div class="alert alert-warning"><?= $_L->T('maintenance','login') ?></div>
                <?php else: ?>
                <div>
                    <div class="login__inputs">
                        <div>
                            <label for="username" class="login__label">Email</label>
                            <input class="login__input" type="text" id="username" name="username" placeholder="Enter your email address" required />
                        </div>

                        <div>
                            <label for="password" class="login__label">Password</label>
                            <div class="login__box">
                                <input class="login__input" type="password" id="password" name="password" placeholder="Enter your password" required />
                                <i class="ri-eye-off-line login__eye" id="input-icon"></i>
                            </div>
                        </div>
                    </div>

                    <div class="login__check">
                        <label class="login__check-label" for="remember">
                            <input class="login__check-input" type="checkbox" id="remember" name="remember" />
                            <i class="ri-check-line login__check-icon"></i>
                            Remember me
                        </label>
                    </div>
                </div>
                <div>
                    <div class="login__buttons">
                        <input type="hidden" id="timeoffset" name="timeoffset" class="btn btn-primary" value="0">
                        <input type="hidden" class="form-control" id="target" name="target" value="">

                        <button type="button" id="web-trader" class="text-nowrap login__button login__button-ghost"><i class="mdi mdi-chart-line"></i> <?= $_L->T('Web_Trader','sidebar') ?> <?= $_L->T('Login','login') ?></button>
                        <button type="submit" class="login__button"><?= $_L->T('Login','login') ?></button>
                    </div>

                    <a href="forget-password.php" class="login__forgot"><i class="mdi mdi-lock"></i> <?= $_L->T('Forgot_password','login') ?> </a>
                    <small class="float-right text-muted"><?= $_L->T('IP','general') ?>:
                        <?php if ($_waf->isBlacklistIP()) { ?>
                            <span class="text-danger"><?= GF::getIP() ?></span>
                        <?php } else if($_waf->isWhitelistIP()) { ?>
                            <span class="text-success"><?= GF::getIP() ?></span>
                        <?php } else { ?>
                            <span class="text-warning"><?= GF::getIP() ?></span>
                        <?php } ?>
                    </small>
                    <br>
                    <small>
                        <i class="mdi mdi-account-plus"></i> <?= $_L->T('register_note','login') ?> <a href="register.php" class="text-muted"><?= $_L->T('Register','login') ?></a>.
                    </small>


                </div>
                <?php endif; ?>
            </form>



        </div>
    </div>
</div>



<?php include('includes/script.php'); ?>

<script src="assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>

<?php include('includes/script-bottom.php'); ?>

<script>
    let dt = new Date();
    let ddd = dt.getTimezoneOffset();
    $("#timeoffset").val(ddd*(-1));

    $('body').on('click','#web-trader', function(){
        $('input#target').val('<?= REDIRECT_TO['web_trader'] ?>');
        $('form#login').trigger('submit');
    });

    /*=============== SHOW HIDDEN - PASSWORD ===============*/
    const showHiddenPassword = (inputPassword, inputIcon) => {
        const input = document.getElementById(inputPassword),
            iconEye = document.getElementById(inputIcon)

        iconEye.addEventListener('click', () => {
            // Change password to text
            if (input.type === 'password') {
                // Switch to text
                input.type = 'text'

                // Add icon
                iconEye.classList.add('ri-eye-line')

                // Remove icon
                iconEye.classList.remove('ri-eye-off-line')
            } else {
                // Change to password
                input.type = 'password'

                // Remove icon
                iconEye.classList.remove('ri-eye-line')

                // Add icon
                iconEye.classList.add('ri-eye-off-line')
            }
        })
    };
    showHiddenPassword('password', 'input-icon');
</script>

</body>
</html>