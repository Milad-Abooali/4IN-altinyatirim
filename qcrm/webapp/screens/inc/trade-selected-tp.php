<div class="selected-tp d-hide section full mb-2 sticky-top">
    <div class="wide-block pt-1 pb-1">
        <?= $_L->T('Account','webapp') ?>:
        <strong class="mx-2 selected-tp-account"> </strong>
        <button type="button" onclick="updateAccountSummary(APP.selectedAccount)" class="btn btn-icon btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#accountSummary">
            <ion-icon name="information-outline"></ion-icon>
        </button>
        <button type="button" class="go-top btn btn-icon btn-outline-secondary text-muted me-1 d-hide">
            <ion-icon name="caret-up-outline"></ion-icon>
        </button>
        <button class="float-end btn btn-sm btn-link show-section" screen="trade" section="accounts" callback='{"screen":"<?= $screen ?>","section":"<?= $section ?>"}'>
            <ion-icon name="albums"></ion-icon> <?= $_L->T('Change','webapp') ?>
        </button>
    </div>
</div>

<div class="select-tp d-hide section full my-2">
    <div class="wide-block pt-1 pb-1">
        <div class="alert alert-outline-danger mb-1" role="alert">
            <?= $_L->T('Please_select_account','webapp') ?>
            <button class="float-end btn btn-sm btn-warning show-section" screen="trade" section="accounts" callback='{"screen":"<?= $screen ?>","section":"<?= $section ?>"}'><ion-icon name="albums"></ion-icon> <?= $_L->T('Select','webapp') ?></button>
        </div>
    </div>
</div>
