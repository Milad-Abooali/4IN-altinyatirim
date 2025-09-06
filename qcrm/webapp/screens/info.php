<?php
    global $db;
    $faq = $db->selectAll('webapp_faq');
?>
<section id="info-web" class="d-hide">
    <div id="dev-view" class="section mt-2">
        <a target="_blank" href="https://www.facebook.com/share.php?u=<?= Broker['web_url'] ?>" class="btn btn-text-primary shadowed btn-lg btn-block mb-2">
            <ion-icon name="logo-facebook" class="md hydrated"></ion-icon> <?= $_L->T('Facebook','webapp') ?>
        </a>
        <a target="_blank" href="https://twitter.com/intent/tweet?url=<?= Broker['web_url'] ?>" class="btn btn-text-primary shadowed btn-lg btn-block mb-2">
            <ion-icon name="logo-twitter" class="md hydrated"></ion-icon> <?= $_L->T('Twitter','webapp') ?>
        </a>
        <a target="_blank" href="https://www.linkedin.com/sharing/share-offsite/?url=<?= Broker['web_url'] ?>" class="btn btn-text-primary shadowed btn-lg btn-block mb-2">
            <ion-icon name="logo-Linkedin" class="md hydrated"></ion-icon> <?= $_L->T('Linkedin','webapp') ?>
        </a>
    </div>
</section>

<section id="info-faq" class="d-hide">
    <div class="section mt-2 text-center">
        <div class="card">
            <div class="card-body pt-3 pb-3">
                <img src="webapp/assets/img/faq.jpeg" alt="image" class="imaged w-50 ">
                <h2 class="mt-2"><?= $_L->T('Frequently_Asked','webapp') ?> <br> <?= $_L->T('Questions','webapp') ?></h2>
            </div>
        </div>
    </div>
    <div class="section inset mt-2">
        <div class="accordion" id="accordionFAQ">
            <?php if($faq) foreach ($faq as $item){ ?>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?= $item['id'] ?>">
                        <?= ucfirst($item['q']) ?>?
                    </button>
                </h2>
                <div id="faq<?= $item['id'] ?>" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                    <div class="accordion-body">
                        <?= ucfirst($item['a']) ?>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    <div class="section mt-3 mb-3">
        <div class="card bg-primary">
            <div class="card-body text-center">
                <h5 class="card-title"><?= $_L->T('Still_question','webapp') ?></h5>
                <p class="card-text">
                    <?= $_L->T('Feel_contact_us','webapp') ?>
                </p>
                <a href="mailto:<?=Broker['email'] ?>" class="btn btn-dark">
                    <ion-icon name="mail-open-outline" role="img" class="md hydrated" aria-label="mail open outline"></ion-icon> <?= $_L->T('Contact','webapp') ?>
                </a>
            </div>
        </div>
    </div>
</section>