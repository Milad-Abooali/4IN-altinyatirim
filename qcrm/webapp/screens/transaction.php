<section id="transaction-deposit" class="d-hide">
    <?php
        $screen="transaction";
        $section="deposit";
        include 'inc/trade-selected-tp.php';
        include 'inc/transaction-active-req.php';
        if(!$is_active_transaction){
    ?>
    <div class="section mt-2 text-center">
        <h2>Deposit</h2>
        <form id="deposit" class="card p-2" action="#">
            <div class="card-body pb-1">
                <div class="form-group basic">
                    <div class="input-wrapper">
                        <label class="label" for="amount"><?= $_L->T('Amount','webapp') ?> ($)</label>
                        <input type="number" class="form-control" min="0.00" max="10000.00" step="0.01" id="amount" placeholder="0,00" autocomplete="off" required>
                    </div>
                </div>
                <div class="form-group basic">
                    <div class="custom-file-upload" id="docp">
                        <label><?= $_L->T('Required','webapp') ?></label>
                        <input type="file" id="doc" multiple="multiple">
                        <label for="doc">
                        <span>
                            <strong>
                                <ion-icon name="arrow-up-circle-outline" role="img" class="md hydrated" aria-label="arrow up circle outline"></ion-icon>
                                <i><?= $_L->T('Upload_Payment_Receipt','webapp') ?></i>
                            </strong>
                            <small>You can select multiple files</small><br>
                            <small>PNG, JPG, JPEG and PDF accepted</small>
                        </span>
                        </label>
                    </div>
                </div>
                <div class="form-group basic">
                    <div class="input-wrapper">
                        <label class="label" for="amount"><?= $_L->T('Comment','webapp') ?></label>
                        <textarea class="form-control" type="text" id="comment" name="comment" spellcheck="false"></textarea>
                    </div>
                </div>
            </div>
            <div class="form-button-group  transparent">
                <button type="submit" class="btn btn-primary btn-lg btn-block"><?= $_L->T('Submit','webapp') ?></button>
            </div>
        </form>
    </div>
    <?php } else { ?>
    <div id="active-transaction" class="section mt-2 text-center">
        <div class="card p-2">
            <div class="section mt-2 mb-2">
                <div class="listed-detail">
                    <div class="icon-wrapper">
                        <div class="iconbox">
                            <ion-icon name="add" role="img" class="md hydrated" aria-label="arrow forward outline"></ion-icon>
                        </div>
                    </div>
                    <h3 class="text-center mt-2"><?= $_L->T('Transaction_Detail','webapp') ?></h3>
                </div>
                <ul class="listview flush transparent simple-listview no-space mt-3">
                    <li>
                        <strong><?= $_L->T('Type','webapp') ?></strong>
                        <span><?= $pending_transaction['type'] ?></span>
                    </li>
                    <li>
                        <strong><?= $_L->T('Status','webapp') ?></strong>
                        <span class="text-success"><?= $pending_transaction['status'] ?></span>
                    </li>
                    <li>
                        <strong><?= $_L->T('To','webapp') ?></strong>
                        <span><?= $pending_transaction['destination'] ?></span>
                    </li>
                    <li>
                        <strong><?= $_L->T('From','webapp') ?></strong>
                        <span><?= $_L->T('Wire_bank','webapp') ?></span>
                    </li>
                    <li>
                        <strong><?= $_L->T('Receipt','webapp') ?></strong>
                        <span><?= $_L->T('Yes','webapp') ?></span>
                    </li>
                    <li>
                        <strong><?= $_L->T('Date','webapp') ?></strong>
                        <span><?= $pending_transaction['created_at'] ?></span>
                    </li>
                    <li>
                        <strong><?= $_L->T('Amount','webapp') ?></strong>
                        <h3 class="m-0">$ <?= $pending_transaction['amount'] ?></h3>
                    </li>
                </ul>
                <div class="pt-2">
                  <button data-tid="<?= $pending_transaction['id'] ?>" type="button" class="do-cancel btn btn-danger btn-lg btn-block"><?= $_L->T('Cancel','webapp') ?></button>
                </div>
            </div>
        </div>
    <?php } ?>
</section>
<section id="transaction-withdraw" class="d-hide">
    <?php
    $screen="transaction";
    $section="withdraw";
    include 'inc/trade-selected-tp.php';
    include 'inc/transaction-active-req.php';
    if(!$is_active_transaction){
        ?>
        <div class="section mt-2 text-center">
            <h2>Withdraw</h2>
            <form id="withdraw" class="card p-2" action="#">
                <div class="card-body pb-1">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="amount"><?= $_L->T('Amount','webapp') ?> ($)</label>
                            <input type="number" class="form-control" min="0.00" max="100000.00" step="0.01" id="amount" placeholder="0,00" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="amount"><?= $_L->T('Bank_Account','webapp') ?> </label>
                            <input type="text" class="form-control"  id="bankAccount" placeholder="Please choose your bank account" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-group basic">
                        <div class="custom-file-upload" id="docp">
                            <label><?= $_L->T('Optional','webapp') ?></label>
                            <input type="file" id="docw" multiple="multiple">
                            <label for="docw">
                        <span>
                            <strong>
                                <ion-icon name="arrow-up-circle-outline" role="img" class="md hydrated" aria-label="arrow up circle outline"></ion-icon>
                                <i><?= $_L->T('Upload_Files','webapp') ?></i>
                            </strong>
                            <small>You can select multiple files</small><br>
                            <small>PNG, JPG, JPEG and PDF accepted</small>
                        </span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="amount"><?= $_L->T('Comment','webapp') ?></label>
                            <textarea class="form-control" type="text" id="comment" name="comment" spellcheck="false"></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-button-group  transparent">
                    <button type="submit" class="btn btn-primary btn-lg btn-block"><?= $_L->T('Submit','webapp') ?></button>
                </div>
            </form>
        </div>
    <?php } else { ?>
        <div id="active-transaction" class="section mt-2 text-center">
        <div class="card p-2">
            <div class="section mt-2 mb-2">
                <div class="listed-detail">
                    <div class="icon-wrapper">
                        <div class="iconbox">
                            <ion-icon name="add" role="img" class="md hydrated" aria-label="arrow forward outline"></ion-icon>
                        </div>
                    </div>
                    <h3 class="text-center mt-2"><?= $_L->T('Transaction_Detail','webapp') ?></h3>
                </div>
                <ul class="listview flush transparent simple-listview no-space mt-3">
                    <li>
                        <strong><?= $_L->T('Type','webapp') ?></strong>
                        <span><?= $pending_transaction['type'] ?></span>
                    </li>
                    <li>
                        <strong><?= $_L->T('Status','webapp') ?></strong>
                        <span class="text-success"><?= $pending_transaction['status'] ?></span>
                    </li>
                    <li>
                        <strong><?= $_L->T('To','webapp') ?></strong>
                        <span><?= $pending_transaction['destination'] ?></span>
                    </li>
                    <li>
                        <strong><?= $_L->T('From','webapp') ?></strong>
                        <span><?= $_L->T('Wire_bank','webapp') ?></span>
                    </li>
                    <li>
                        <strong><?= $_L->T('Receipt','webapp') ?></strong>
                        <span><?= $_L->T('Yes','webapp') ?></span>
                    </li>
                    <li>
                        <strong><?= $_L->T('Date','webapp') ?></strong>
                        <span><?= $pending_transaction['created_at'] ?></span>
                    </li>
                    <li>
                        <strong><?= $_L->T('Amount','webapp') ?></strong>
                        <h3 class="m-0">$ <?= $pending_transaction['amount'] ?></h3>
                    </li>
                </ul>
                <div class="pt-2">
                    <button data-tid="<?= $pending_transaction['id'] ?>" type="button" class="do-cancel btn btn-danger btn-lg btn-block"><?= $_L->T('Cancel','webapp') ?></button>
                </div>
            </div>
        </div>
    <?php } ?></section>
<section id="transaction-history" class="d-hide">
    <div id="dev-view" class="section mt-2 text-center">
        <div class="transactions"></div>
    </div>
</section>

<section id="transaction-wallet" class="d-hide">
    <div id="dev-view" class="section mt-2 text-center">
        <img src="webapp/assets/img/dev.png" class="img-fluid">
        <br><br>
        <h2><?= $_L->T('Wallet','webapp') ?></h2>
        <p><?= $_L->T('Coming_Soon','webapp') ?></p>
    </div>
</section>