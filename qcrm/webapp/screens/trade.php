<?php

global $db;
global $_L;

$where = 'user_id='.$_SESSION['id'];
$tp_accounts = $db->select('tp',$where);

?>
<section id="trade-accounts" class="d-hide">
    <?php $screen="trade";$section="accounts";  ?>
    <div class="section my-3">
        <button class="btn shadowed btn-lg btn-block mb-2 btn-success" data-bs-toggle="modal" data-bs-target="#openAccount">
            <ion-icon name="add-circle"></ion-icon> <?= $_L->T('New_Platform_Account','webapp') ?>
        </button>
    </div>
    <div class="section full my-3">
        <table class="table table-sm">
            <thead>
            <tr>
                <th class="text-center"><?= $_L->T('Accounts','webapp') ?></th>
                <th class="text-center"><?= $_L->T('Options','webapp') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if($tp_accounts) { ?>

                    <tr id="clearSelectedAccount" class="d-hide">
                        <td colspan="2" class="text-center">
                            <button type="button"  onclick="clearSelectedAccount(1);reloadScreen();" class="btn btn-danger m-3 col">
                                <?= $_L->T('Clear','webapp') ?> <?= $_L->T('Selected','webapp') ?>
                            </button>
                        </td>
                    </tr>

                <?php foreach($tp_accounts as $tp_account) { ?>
                    <tr style="line-height: 65px;">
                        <td class="text-center align-middle">
                            <?php if( !$tp_account['expired'] ){ ?>
                                <button type="button" onclick="updateAccountSummary(<?= $tp_account['login'] ?>)" class="btn btn-icon btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#accountSummary">
                                    <ion-icon name="information-outline"></ion-icon>
                                </button>
                            <?php } ?>

                            <span class=""><?= $tp_account['login'] ?></span>
                            <sup class="ps-1 text-warning"><?= ($tp_account['group_id']==2) ? $_L->T('Real','webapp') : $_L->T('Demo','webapp')  ?></sup>
                        </td>
                        <td class="text-center">
                        <?php if( $tp_account['expired'] ){ ?>
                            <a href="#" class="disabled btn btn-warning m-3 col"><?= $_L->T('DisabledExpired','webapp') ?></a>
                        <?php } else { ?>
                            <a href="#" account="<?= $tp_account['login'] ?>" onclick="updateAccountSummary(<?= $tp_account['login'] ?>)"class="do-selectTPA btn btn-warning m-3 col"><?= $_L->T('Select','webapp') ?></a>
                        <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
</section>
<section id="trade-market" class="d-hide">
    <?php $screen="trade";$section="market"; include 'inc/trade-selected-tp.php'; ?>
    <ul class="listview image-listview text inset no-line">
        <li>
            <div class="item">
                <div class="in d-flex justify-content-between">
                    <div class="me-2">
                        <input list="symbols-list" type="search" class="form-control text-center" id="filter-symbol" placeholder="<?= $_L->T('Filter_Symbol','webapp') ?>"  autocomplete="off">
                        <datalist id="symbols-list"></datalist>
                    </div>
                    <div class="btn-group">
                        <span class="btn btn-lg btn-icon text-secondary text-muted border">
                            <span class="loading float-sm-end"></span>
                        </span>
                        <span onclick="startMarketLoop()" class="d-hide market-loop-start btn btn-lg btn-icon text-secondary text-muted border fix-btn-group-end"><ion-icon name="caret-forward-circle-outline"></ion-icon></span>
                        <span onclick="stopMarketLoop()"  class="market-loop-stop btn btn-lg btn-icon text-secondary text-muted border fix-btn-group-end"><ion-icon name="pause-circle-outline"></ion-icon></span>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="item d-flex justify-content-center">
                <div id="watchlist-wrapper">
                    <button type="button" onclick="watchlistSelect(`all`)" class="watchlist-all btn btn-sm text-secondary px-3">
                        <ion-icon class="small text-primary" size="small" name="menu-outline"></ion-icon> <?= $_L->T('All','webapp') ?>
                    </button>
                    <button type="button" onclick="watchlistSelect(`top`)" class="watchlist-top btn btn-sm text-secondary px-3">
                        <ion-icon class="small text-danger" size="small" name="flame-outline"></ion-icon> <?= $_L->T('Hot','webapp') ?>
                    </button>
                    <button type="button" onclick="watchlistSelect(`personal`)" class="watchlist-personal btn btn-sm text-secondary px-3">
                        <ion-icon class="small text-warning" size="small" name="star"></ion-icon> <?= $_L->T('Personal','webapp') ?>
                    </button>
                </div>
            </div>
        </li>
    </ul>
    <div class="section mt-2">
        <div id="symbols-wrapper" class="card row"> </div>
    </div>
</section>
<section id="trade-positions" class="d-hide">
    <?php $screen="trade";$section="positions"; include 'inc/trade-selected-tp.php'; ?>
    <div class="section mt-2 mb-2">
        <div class="section-title"><ion-icon name="pulse-outline"></ion-icon> <?= $_L->T('Open_Positions','webapp') ?>
            <span class="loading float-end"></span>
        </div>
        <!-- Position -->
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
        <!-- * Position -->

        <div id="positions-wrapper" class="goals">  </div>

    </div>
</section>
<section id="trade-pending" class="d-hide">
    <?php $screen="trade";$section="pending"; include 'inc/trade-selected-tp.php'; ?>
    <div class="section mt-2 mb-2">
        <div class="section-title"><ion-icon name="hourglass-outline"></ion-icon> <?= $_L->T('Pending_Orders','webapp') ?>
            <span class="loading float-end"></span>
        </div>
        <!-- Order -->
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
        <!-- * Order -->

        <div id="orders-wrapper" class="goals">  </div>

    </div>
</section>
<section id="trade-history" class="d-hide">
    <?php $screen="trade";$section="history"; include 'inc/trade-selected-tp.php'; ?>
    <ul class="listview image-listview text inset no-line">
        <li>
            <div class="item">
                <div class="in">
                    <div class="row">
                        <input id="start-date" class="col form-control" type="date" value="<?php echo date('Y-m-d',strtotime("-1 days"));?>">
                        <input id="end-date" class="col form-control" type="date" value="<?php echo date('Y-m-d',strtotime("+1 days"));?>">
                        <button id="update" class="ms-2 btn btn-icon btn-secondary" type="button">
                            <ion-icon name="search-outline"></ion-icon>
                        </button>
                    </div>
                </div>
            </div>
        </li>
    </ul>
    <div class="section mt-2 mb-2">
        <div class="section-title"><ion-icon name="timer"></ion-icon> <?= $_L->T('Account_History','webapp') ?>
            <span class="loading float-end"></span>
        </div>
        <!-- Deal -->
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
        <!-- * Deal -->

        <div id="deals-wrapper" class="goals">  </div>

    </div>
</section>
<section id="trade-operation" class="d-hide">
    <?php $screen="trade";$section="operation"; include 'inc/trade-selected-tp.php'; ?>
    <ul class="listview image-listview text inset no-line">
        <li>
            <div class="item">
                <div class="in">
                    <div class="row">
                        <input id="start-date" class="col form-control" type="date" value="<?php echo date('Y-m-d',strtotime("-1 days"));?>">
                        <input id="end-date" class="col form-control" type="date" value="<?php echo date('Y-m-d',strtotime("+1 days"));?>">
                        <button id="update" class="ms-2 btn btn-icon btn-secondary" type="button">
                            <ion-icon name="search-outline"></ion-icon>
                        </button>
                    </div>
                </div>
            </div>
        </li>
    </ul>
    <div class="section mt-2 mb-2">
        <div class="section-title"><ion-icon name="timer"></ion-icon> <?= $_L->T('Account_Operations','webapp') ?>
            <span class="loading float-end"></span>
        </div>
        <!-- Operation Temp -->
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
        <!-- * Operation Temp -->

        <div id="operations-wrapper" class="transactions">  </div>

    </div>
</section>

<section id="trade-tv" class="d-hide">
    <div class="row mx-2">
        <div class="col-9">
            <!-- TV Charts -->
            <div id="tv-chart">
                <div id="left-chart" class="achart"></div>
            </div>
            <!-- * TV Charts -->
            <!-- Account Tabs -->
            <div id="account-tabs" class="card">
                <div class="card-body float-end">
                    <ul class="nav nav-tabs capsuled" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#open-positions" role="tab">
                                <?= $_L->T('Open_Positions','webapp') ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#pending-orders" role="tab">
                                <?= $_L->T('Pending_Orders','webapp') ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#account-history" role="tab">
                                <?= $_L->T('Account_History','webapp') ?> (24H)
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#balance-operations" role="tab">
                                <?= $_L->T('Balance_Operations','webapp') ?> (24H)
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content mt-1 pb-3">
                        <!-- TV Positions -->
                        <div data-scrollbar class="tab-pane fade show active scrollbar" id="open-positions" role="tabpanel">
                            <table class="table">
                                <thead id="fixed">
                                <tr>
                                    <th><?= $_L->T('Open_Time','webapp') ?></th>
                                    <th><?= $_L->T('Symbol','webapp') ?></th>
                                    <th><?= $_L->T('Type','webapp') ?></th>
                                    <th><?= $_L->T('Volume','webapp') ?></th>
                                    <th><?= $_L->T('Price','webapp') ?></th>
                                    <th>SL</th>
                                    <th>TP</th>
                                    <th><?= $_L->T('Current_Price','webapp') ?></th>
                                    <th><?= $_L->T('Profit','webapp') ?></th>
                                    <th><?= $_L->T('Swap','webapp') ?></th>
                                    <th><?= $_L->T('Action','webapp') ?></th>
                                </tr>
                                <!-- Position Temp -->
                                <tr class="position-temp d-hide">
                                    <td id="time"></td>
                                    <td id="symbol"></td>
                                    <td id="action"></td>
                                    <td id="volume"></td>
                                    <td id="price-open"></td>
                                    <td id="sl"></td>
                                    <td id="tp"></td>
                                    <td>
                                        <ion-icon id="price-icon" name="" class=""></ion-icon>
                                        <span id="price-current"></span>
                                    </td>
                                    <td id="profit"></td>
                                    <td id="storage"></td>
                                    <td>
                                        <div class="btn-group action-button" role="group">
                                            <button type="button" class="position-edit btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#positionEdit">
                                                <ion-icon name="create-outline"></ion-icon> <?= $_L->T('Edit','webapp') ?>
                                            </button>
                                            <button type="button" class="position-close btn btn-sm btn-danger">
                                                <ion-icon name="close-outline"></ion-icon> <?= $_L->T('Close','webapp') ?>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <!-- * Position Temp -->
                                </thead>
                                <tbody id="positions-wrapper"></tbody>
                            </table>
                        </div>
                        <!-- * TV Positions -->
                        <!-- TV Orders -->
                        <div data-scrollbar class="tab-pane fade scrollbar" id="pending-orders" role="tabpanel">
                            <table class="table">
                                <thead id="fixed">
                                <tr>
                                    <th><?= $_L->T('Symbol','webapp') ?></th>
                                    <th><?= $_L->T('Type','webapp') ?></th>
                                    <th><?= $_L->T('Volume','webapp') ?></th>
                                    <th><?= $_L->T('Order_Price','webapp') ?></th>
                                    <th>SL</th>
                                    <th>TP</th>
                                    <th><?= $_L->T('Current_Price','webapp') ?></th>
                                    <th><?= $_L->T('Trigger_Price','webapp') ?></th>
                                    <th><?= $_L->T('Action','webapp') ?></th>
                                </tr>
                                <tr id="order-temp" class="d-hide">
                                    <td id="symbol"></td>
                                    <td id="action"></td>
                                    <td id="volume"></td>
                                    <td id="price-order"></td>
                                    <td id="sl"></td>
                                    <td id="tp"></td>
                                    <td id="price-current"></td>
                                    <td id="price-trigger"></td>
                                    <td>
                                        <div class="btn-group action-button" role="group">
                                            <button type="button" class="order-detail btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#orderDetail">
                                                <ion-icon name="alert-circle-outline"></ion-icon> <?= $_L->T('Detail','webapp') ?>
                                            </button>
                                            <button type="button" class="order-edit btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#orderEdit">
                                                <ion-icon name="create-outline"></ion-icon> <?= $_L->T('Edit','webapp') ?>
                                            </button>
                                            <button type="button" class="order-cancel btn btn-sm btn-danger">
                                                <ion-icon name="close-outline"></ion-icon> <?= $_L->T('Cancel','webapp') ?>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                </thead>
                                <tbody id="orders-wrapper"></tbody>
                            </table>
                        </div>
                        <!-- * TV Orders -->
                        <!-- TV History -->
                        <div data-scrollbar class="tab-pane fade scrollbar" id="account-history" role="tabpanel">
                            <table class="table">
                                <thead id="fixed">
                                <tr>
                                    <th><?= $_L->T('Symbol','webapp') ?></th>
                                    <th><?= $_L->T('Open_Time','webapp') ?></th>
                                    <th><?= $_L->T('Open_Price','webapp') ?></th>
                                    <th><?= $_L->T('Type','webapp') ?></th>
                                    <th><?= $_L->T('Volume','webapp') ?></th>
                                    <th>SL</th>
                                    <th>TP</th>
                                    <th><?= $_L->T('Close_Time','webapp') ?></th>
                                    <th><?= $_L->T('Open_Price','webapp') ?></th>
                                    <th><?= $_L->T('Profit','webapp') ?></th>
                                    <th><?= $_L->T('Swap','webapp') ?></th>
                                </tr>
                                <!-- Deal Temp -->
                                <tr id="deal-temp" class="d-hide">
                                    <td id="symbol"></td>
                                    <td id="time-open"></td>
                                    <td id="price-open"></td>
                                    <td id="action"></td>
                                    <td id="volume"></td>
                                    <td id="sl"></td>
                                    <td id="tp"></td>
                                    <td id="time-close"></td>
                                    <td id="price-close"></td>
                                    <td id="profit"></td>
                                    <td id="storage"></td>
                                </tr>
                                <!-- * Deal Temp -->
                                </thead>
                                <tbody id="deals-wrapper"></tbody>
                            </table>
                            <button class="show-section btn btn-block btn-primary" screen="trade" section="history"><?= $_L->T('Filter_History','webapp') ?></button>
                        </div>
                        <!-- * TV History -->
                        <!-- TV Operations -->
                        <div data-scrollbar class="tab-pane fade scrollbar" id="balance-operations" role="tabpanel">
                             <table class="table">
                                <thead id="fixed">
                                <tr>
                                    <th><?= $_L->T('Time','webapp') ?></th>
                                    <th><?= $_L->T('Type','webapp') ?></th>
                                    <th><?= $_L->T('Comment','webapp') ?></th>
                                    <th><?= $_L->T('Amount','webapp') ?></th>
                                </tr>
                                <!-- Operations Temp -->
                                <tr id="operation-temp" class="d-hide">
                                    <td id="time"></td>
                                    <td id="type"></td>
                                    <td id="comment"></td>
                                    <td id="amount"></td>
                                </tr>
                                <!-- * Operations Temp -->
                                </thead>
                                <tbody id="operations-wrapper"></tbody>
                            </table>
                        </div>
                        <!-- * TV Operations -->
                    </div>
                </div>
                <div id="tvAccountSummary" class="card-footer text-muted" style="width: 98% !important;">
                    <span class="loading2"></span>
                    <strong class="ms-1"><?= $_L->T('Balance','webapp') ?>:</strong> <span id="Balance"></span> USD /
                    <strong class="ms-1"><?= $_L->T('Equity','webapp') ?>:</strong> <span id="Equity"></span> USD /
                    <strong class="ms-1"><?= $_L->T('Margin','webapp') ?>:</strong> <span id="Margin"></span> /
                    <strong class="ms-1"><?= $_L->T('Free_Margin','webapp') ?>:</strong> <span id="MarginLeverage"></span> /
                    <strong class="ms-1"><?= $_L->T('Margin_Level','webapp') ?>:</strong> <span id="MarginLevel"></span> %
                </div>
            </div>
            <!-- * Account Tabs -->
        </div>
        <div class="col-3">
            <!-- Market Symbols -->
            <div id="market-sidebar" class="card">
                <div class="ms-1 mt-1 mb-2 col">
                    <?php $screen="trade";$section="tv"; include 'inc/tv-selected-tp.php'; ?>
                    <div class="card flex-column market-prices">
                        <div class="form-group basic pb-0">
                            <div class="input-wrapper ">
                                <label class="label ps-1 pt-1" for="filter-symbol"><?= $_L->T('Symbol_Search','webapp') ?></label>
                                <input list="symbols-list" type="search" class="form-control ps-1" id="filter-symbol" placeholder="Example: XAUUSD" autocomplete="off">
                            </div>
                            <datalist id="symbols-list"></datalist>
                        </div>
                        <div id="watchlist-wrapper" class="d-flex justify-content-center my-3">
                            <button type="button" onclick="watchlistSelect(`all`)" class="watchlist-all btn btn-sm text-secondary px-3">
                                <ion-icon class="small text-primary" size="small" name="menu-outline"></ion-icon> <?= $_L->T('All','webapp') ?>
                            </button>
                            <button type="button" onclick="watchlistSelect(`top`)" class="watchlist-top btn btn-sm text-secondary px-3">
                                <ion-icon class="small text-danger" size="small" name="flame-outline"></ion-icon> <?= $_L->T('Hot','webapp') ?>
                            </button>
                            <button type="button" onclick="watchlistSelect(`personal`)" class="watchlist-personal btn btn-sm text-secondary px-3">
                                <ion-icon class="small text-warning" size="small" name="star"></ion-icon> <?= $_L->T('Personal','webapp') ?>
                            </button>
                        </div>
                        <div data-scrollbar class="table-responsive symbol-list scrollbar">
                            <table class="table tscroll">
                                <thead>
                                <tr>
                                    <th colspan="2"><?= $_L->T('Symbol','webapp') ?></th>
                                    <th><?= $_L->T('Bid','webapp') ?></th>
                                    <th><?= $_L->T('Ask','webapp') ?></th>
                                    <th><?= $_L->T('Spread','webapp') ?></th>
                                </tr>
                                </thead>
                                <tbody id="symbols-wrapper"></tbody>
                            </table>
                        </div>
                        <div id="tv-footer-left" class="card-footer text-muted">
                            <div class="float-start">
                                <span>
                                    <?= $_L->T('Last_Update','webapp') ?> : <span id="updateTime">-</span>
                                </span>
                            </div>
                            <div class="float-end">
                                <div class="btn-group">
                                    <span class="btn btn-lg btn-icon text-secondary text-muted border">
                                        <span class="loading float-sm-end"></span>
                                    </span>
                                    <span onclick="startMarketLoop()" class="d-hide market-loop-start btn btn-lg btn-icon text-secondary text-muted border border-radius: 10px;"><ion-icon name="caret-forward-circle-outline"></ion-icon></span>
                                    <span onclick="stopMarketLoop()"  class="market-loop-stop btn btn-lg btn-icon text-secondary text-muted border border-radius: 10px;"><ion-icon name="pause-circle-outline"></ion-icon></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- * Market Symbols -->
        </div>
    </div>
</section>