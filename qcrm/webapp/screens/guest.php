<?php
    global $_L;
?>

<!--
<section class="d-hide" id="guest-login">
    <div class="section text-center">
        <img src="webapp/assets/img/header-logo.png" alt="logo" class="mb-3">
        <hr>
        <h1><?= $_L->T('Login','webapp') ?></h1>
        <h4><?= $_L->T('Fill_form_login','webapp') ?></h4>
    </div>
    <div class="section mb-5 p-2">
        <form id="crm-login" action="#">
            <div class="card">
                <div class="card-body pb-1">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="login-email"><?= $_L->T('E_mail','webapp') ?></label>
                            <input type="text" class="form-control" id="login-email" placeholder="Your e-mail">
                            <i class="clear-input">
                                <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="password1"><?= $_L->T('Password','webapp') ?></label>
                            <input type="password" class="form-control" id="password" autocomplete="off" placeholder="Your password">
                            <i class="clear-input">
                                <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-button-group  transparent my-3">
                <button type="submit" class="btn btn-primary btn-block btn-lg"><?= $_L->T('Login','webapp') ?></button>
            </div>
            <hr>
            <div class="form-links">
                <div>
                    <?php if(CUSTOM_LINK['A_REGISTER']){ ?>
                    <a href="<?= CUSTOM_LINK['A_REGISTER'] ?>" class="" target="_blank"><?= $_L->T('Register_Now','webapp') ?></a>
                    <?php } else { ?>
                        <a href="#" class="show-section" screen="guest" section="register"><?= $_L->T('Register_Now','webapp') ?></a>
                    <?php } ?>
                </div>
                <div>
                    <?php if(CUSTOM_LINK['A_RECOVER']){ ?>
                        <a href="<?= CUSTOM_LINK['A_RECOVER'] ?>" class="text-muted" target="_blank"><?= $_L->T('Forgot_Password','webapp') ?></a>
                    <?php } else { ?>
                        <a href="#" class="show-section text-muted" screen="guest" section="recovery"><?= $_L->T('Forgot_Password','webapp') ?></a>
                    <?php } ?>
                </div>
            </div>
        </form>
    </div>
</section>
-->
<!--
<section class="d-hide" id="guest-recovery">
    <div class="section text-center">
        <img src="webapp/assets/img/header-logo.png" alt="logo" class="mb-3">
        <hr>
        <h1><?= $_L->T('Password_Recovery','webapp') ?></h1>
        <h4><?= $_L->T('Type_email_reset_pass','webapp') ?></h4>
    </div>
    <div class="section mb-5 p-2">
        <form id="crm-recovery" action="#">
            <div class="card">
                <div class="card-body pb-1">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="recovery-email"><?= $_L->T('E_mail','webapp') ?></label>
                            <input type="text" class="form-control" id="recovery-email" placeholder="Your e-mail">
                            <i class="clear-input">
                                <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-button-group  transparent my-3">
                <button type="submit" class="btn btn-primary btn-block btn-lg"><?= $_L->T('Submit','webapp') ?></button>
            </div>
            <hr>
            <div class="form-links">
                <div>
                    <?php if(CUSTOM_LINK['A_REGISTER']){ ?>
                        <a href="<?= CUSTOM_LINK['A_REGISTER'] ?>" class="" target="_blank"><?= $_L->T('Register_Now','webapp') ?></a>
                    <?php } else { ?>
                        <a href="#" class="show-section" screen="guest" section="register"><?= $_L->T('Register_Now','webapp') ?></a>
                    <?php } ?>
                </div>
                <div>
                    <a href="#" class="show-section text-muted" screen="guest" section="login"><?= $_L->T('Login','webapp') ?></a>
                </div>
            </div>
        </form>
    </div>
</section>
-->
<!--
<section class="d-hide" id="guest-register">
    <div class="section text-center">
        <img src="webapp/assets/img/header-logo.png" alt="logo" class="mb-3">
        <hr>
        <h2><?= $_L->T('Register','webapp') ?></h2>
        <h4><?= $_L->T('Register_start_trading','webapp') ?></h4>
    </div>
    <div class="section mb-5 p-2">
        <form id="crm-register" action="#">
            <div class="card">
                <div class="card-body pb-1">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="fname"><?= $_L->T('First_Name','webapp') ?></label>
                            <input type="text" class="form-control" id="fname" placeholder="Your First Name">
                            <i class="clear-input">
                                <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="lname"><?= $_L->T('Last_Name','webapp') ?></label>
                            <input type="text" class="form-control" id="lname" placeholder="Your Last Name">
                            <i class="clear-input">
                                <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="register-email"><?= $_L->T('E_mail','webapp') ?></label>
                            <input type="text" class="form-control" id="register-email" placeholder="Your e-mail">
                            <i class="clear-input">
                                <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label for="country" class="label"><?= $_L->T('Country','webapp') ?></label>
                            <input type="hidden" class="form-control" id="country" name="country" required>

                            <div class="dropdown">
                                <button class="country-selector btn btn-outline-secondary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?= $_L->T('Select_Country_Residence','webapp') ?>
                                </button>
                                <ul class="countries-list dropdown-menu" id="countries"></ul>
                            </div>

                        </div>
                    </div>
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label for="phone" class="label"><?= $_L->T('Phone_Number','webapp') ?></label>
                            <div class="input-group mb-3"><strong class="input-group-text" id="phone-plus">+</strong><input type="number" min="1" max="99999" pattern="[0-9]*" class="form-control text-primary" placeholder="1" id="phone-p" name="phone-p" required=""><input type="number" maxlength="11" pattern="[0-9]*" class="form-control w-50" placeholder="123xxxxxxx" id="phone" name="phone" required=""></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-button-group  transparent my-3">
                <button type="submit" class="btn btn-primary btn-block btn-lg"><?= $_L->T('Submit','webapp') ?></button>
            </div>
            <hr>
            <div class="form-links">
                <div>
                    <a href="#" class="show-section text-muted" screen="guest" section="login"><?= $_L->T('Login','webapp') ?></a>
                </div>
                <div>
                    <div>
                        <?php if(CUSTOM_LINK['A_RECOVER']){ ?>
                            <a href="<?= CUSTOM_LINK['A_RECOVER'] ?>" class="text-muted" target="_blank"><?= $_L->T('Forgot_Password','webapp') ?></a>
                        <?php } else { ?>
                            <a href="#" class="show-section text-muted" screen="guest" section="recovery"><?= $_L->T('Forgot_Password','webapp') ?></a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
-->

<section id="guest-login">
    <div class="container-login">
        <div class="login">
            <div class="login__content">
                <img class="login__img" src="assets/images/bg-tr.png" alt="Login image" />
                <form class="login__form" id="crm-login" action="#">
                    <div class="login__inputs">
                        <div class="pb-1">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="login-email"><?= $_L->T('E_mail','webapp') ?></label>
                                    <input type="text" class="form-control" id="login-email" placeholder="Your e-mail">
                                    <i class="clear-input">
                                        <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                    </i>
                                </div>
                            </div>
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="password1"><?= $_L->T('Password','webapp') ?></label>
                                    <input type="password" class="form-control" id="password" autocomplete="off" placeholder="Your password">
                                    <i class="clear-input">
                                        <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                    </i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="login__buttons">
                        <button type="submit" class="login__button"><?= $_L->T('Login','webapp') ?></button>
                        <?php if(CUSTOM_LINK['A_REGISTER']){ ?>
                            <a href="<?= CUSTOM_LINK['A_REGISTER'] ?>" class="login__button text-nowrap login__button-ghost" target="_blank"><?= $_L->T('Register_Now','webapp') ?></a>
                        <?php } else { ?>
                            <a href="#" class="show-section login__button text-nowrap login__button-ghost" screen="guest" section="register"><?= $_L->T('Register_Now','webapp') ?></a>
                        <?php } ?>
                    </div>
                    <hr>
                    <div class="form-links">
                        <div>
                            <?php if(CUSTOM_LINK['A_RECOVER']){ ?>
                                <a href="<?= CUSTOM_LINK['A_RECOVER'] ?>" class="text-muted" target="_blank"><?= $_L->T('Forgot_Password','webapp') ?></a>
                            <?php } else { ?>
                                <a href="#" class="show-section text-muted" screen="guest" section="recovery"><?= $_L->T('Forgot_Password','webapp') ?></a>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="d-hide" id="guest-recovery">
    <div class="container-login">
        <div class="login">
            <div class="login__content">
                <img class="login__img" src="assets/images/bg-tr.png" alt="Login image" />

                <form class="login__form" id="crm-recovery" action="#">
                    <div class="section text-center">
                        <h1><?= $_L->T('Password_Recovery','webapp') ?></h1>
                        <h4><?= $_L->T('Type_email_reset_pass','webapp') ?></h4>
                    </div>
                    <div class="login__inputs">
                        <div class="pb-1">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="login-email"><?= $_L->T('E_mail','webapp') ?></label>
                                    <input type="text" class="form-control" id="recovery-email" placeholder="Your e-mail">
                                    <i class="clear-input">
                                        <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                    </i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="login__buttons">
                        <button type="submit" class="login__button"><?= $_L->T('Submit','webapp') ?></button>
                        <button type="button" class="show-section login__button login__button-ghost" screen="guest" section="login"><?= $_L->T('Login','webapp') ?></button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>

<section class="d-hide" id="guest-register">
    <div class="container-login">
        <div class="login">
            <div class="login__content">
                <img class="login__img mt-5" src="assets/images/bg-tr.png" alt="Login image" />
                <form class="login__form" id="crm-register" action="#">
                    <div class="section text-center">
                        <h2><?= $_L->T('Register','webapp') ?></h2>
                        <h4><?= $_L->T('Register_start_trading','webapp') ?></h4>
                    </div>
                    <div class="login__inputs">
                        <div class="pb-1">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="fname"><?= $_L->T('First_Name','webapp') ?></label>
                                    <input type="text" class="form-control" id="fname" placeholder="Your First Name">
                                    <i class="clear-input">
                                        <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                    </i>
                                </div>
                            </div>
                        </div>
                        <div class="pb-1">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="lname"><?= $_L->T('Last_Name','webapp') ?></label>
                                    <input type="text" class="form-control" id="lname" placeholder="Your Last Name">
                                    <i class="clear-input">
                                        <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                    </i>
                                </div>
                            </div>
                        </div>
                        <div class="pb-1">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="register-email"><?= $_L->T('E_mail','webapp') ?></label>
                                    <input type="text" class="form-control" id="register-email" placeholder="Your e-mail">
                                    <i class="clear-input">
                                        <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                    </i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label for="country" class="label"><?= $_L->T('Country','webapp') ?></label>
                                <input type="hidden" class="form-control" id="country" name="country" required>

                                <div class="dropdown">
                                    <button class="country-selector btn btn-outline-secondary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <?= $_L->T('Select_Country_Residence','webapp') ?>
                                    </button>
                                    <ul class="countries-list dropdown-menu" id="countries"></ul>
                                </div>

                            </div>
                        </div>
                        <div class="pb-1">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="phone-plus"><?= $_L->T('Phone_Number','webapp') ?></label>
                                    <div class="input-group mb-3"><strong class="input-group-text" id="phone-plus">+</strong><input type="number" min="1" max="99999" pattern="[0-9]*" class="form-control text-primary" placeholder="1" id="phone-p" name="phone-p" required=""><input type="number" maxlength="11" pattern="[0-9]*" class="form-control w-50" placeholder="123xxxxxxx" id="phone" name="phone" required=""></div>
                                    <i class="clear-input">
                                        <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                    </i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="login__buttons">
                        <button type="submit" class="login__button"><?= $_L->T('Submit','webapp') ?></button>
                    </div>
                    <hr>
                    <div class="form-links">
                        <div>
                            <button type="button" class="show-section btn btn-link text-muted" screen="guest" section="login"><?= $_L->T('Login','webapp') ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
