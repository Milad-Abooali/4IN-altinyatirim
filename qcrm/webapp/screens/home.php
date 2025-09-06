<section id="home-start" class="d-hide">
    <div id="dev-view" class="section mt-2 text-center">
        <div class="row">
            <div class="col-12 py-4 text-start">
                <h3 class="d-inline-block mt-1"><?= $_L->T('HELLO','webapp') ?>
                    <span class="mx-2"><?= ucfirst($_SESSION['webapp']['user']['user_extra']['fname']) ?></span>
                </h3>
                <button type="button" class="do-logout float-end btn btn-danger">
                    <ion-icon name="log-out-outline"></ion-icon> <?= $_L->T('Logout','webapp') ?>
                </button>
            </div>
            <hr>
            <div class="col-12 mb-3">
                <div class="d-flex justify-content-around">
                    <a href="#" class="btn btn-lg btn-info show-section" screen="transaction" section="deposit">
                        <ion-icon name="add-outline" class="d-block"></ion-icon>
                        <?= $_L->T('Deposit','webapp') ?>
                    </a>
                    <a href="#" class="btn btn-lg btn-info show-section" screen="transaction" section="withdraw">
                        <ion-icon name="arrow-down-outline" class="d-block"></ion-icon>
                        <?= $_L->T('Withdraw','webapp') ?>
                    </a>
                </div>
            </div>
        </div>
        <hr>
        <div class="col-12 mb-3">
            <button class="btn btn-lg btn-warning show-section" screen="trade" section="accounts">
                <ion-icon name="albums" class="md hydrated"></ion-icon>
                <?= $_L->T('MANAGE_TP_ACCOUNTS','webapp') ?>
            </button>
        </div>
        <hr>
        <div class="col-12 mb-3">
            <!-- EBook -->
   
        </div>
        <div class="col-12 mb-3">
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

        </div>
        <hr>
        <div class="col-12 mb-3">

        </div>
    </div>
</section>