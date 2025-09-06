window.scrollTo({ top: 0, behavior: 'smooth' });

/**
 * Var & Const
 */
// repository
var repAccountSummery={};
var repAccountPosition={};
var repAccountPending={};
var repAccountHistory={};
var repMarketSymbols={};

// Loop Holders
var loopServStatus={};
var loopAccountMarket={};
var loopRepository={};
var updateAccountSummaryLoop = true;

// Data Holder
var procMarketSymbols={};
var proAccountPositions={};
var proAccountOrders={};
var proAccountDeals={};
var proAccountOperations={};
var uiRenderedSymbols = new Set();

// Countries Lib
var countriesLib = {};
// Notifications
var notifications = {};
// Trade watchlist Top Symbols
var watchlistTop = [
    'EURUSD',
    'GBPUSD',
    'USDJPY',
    'USDTRY',
    'XAUUSD',
    'USDCHF',
    'AUDUSD',
    'USDCAD'
];
// Trade Actions
const tradeAction = [
    'Buy',
    'Sell',
    'Buy Limit',
    'Sell Limit',
    'Buy Stop',
    'Sell Stop',
    'Buy Stop Limit',
    'Sell Stop Limit',
    'Close'
];
// Trade Actions Buy
const tradeActionsBuy = [
    'Buy',
    'Buy Limit',
    'Buy Stop',
    'Buy Stop Limit'
];
// Trade Actions Sell
const tradeActionsSell = [
    'Sell',
    'Sell Limit',
    'Sell Stop',
    'Sell Stop Limit'
];
// Trade Simple Chart
var simpleSymbolChart = {};
// Advanced Chart
var aChart = {};
var aChartLineAsk = '';
var aCharLinePositions = {};
var aCharLinePendings = {};
// TV Left Chart
var tvLeftChart = {};
// TV Right Chart
var tvRightChart = {};
// Section Titles
var sectionTitle = {
    'ai':{
        'ruby':'Ruby'
    },
    'chart':{
        'achart':'Advanced Chart'
    },
    'guest':{
        'login':'Login',
        'recovery':'Recovery',
        'register':'Register'
    },
    'home':{
        'start':'Dashboard'
    },
    'info':{
        'web':"Tell Friends",
        'faq':"FAQ"
    },
    'trade':{
        'accounts':"Platform Accounts",
        'market':"Market View",
        'positions':"Open Positions",
        'pending':"Pending Orders",
        'history':"Deal History"
    },
    'transaction':{
        'deposit':"Deposit",
        'withdraw':"Withdraw",
        'history':"Transactions history",
        'wallet':"My Wallet"

    },
    'user':{
        'avatar':'Set Avatar',
        'profile':'My Profile'
    }
}

$(function() {

    /**
     * Tuner
     */
    DEBOUNES.emitSelectedAccount   = debounce( emitSelectedAccount, 60000 );

    THROTTLES.creatMarketSymbols   = throttle( creatMarketSymbols, 1000 );
    THROTTLES.updateMarketSymbols  = throttle( updateMarketSymbols, 300 );
    DEBOUNES.filterSymbols   = debounce( (filterInput)=>filterSymbols(filterInput), 200 );

    THROTTLES.updateAChartSymbol   = throttle( updateAChartSymbol, 300 );

    THROTTLES.updatePendingOrders  = throttle( updatePendingOrders, 1000 );

    THROTTLES.updateOpenPositions  = throttle( updateOpenPositions, 1000 );

    THROTTLES.creatTvSymbols   = throttle( creatTvSymbols, 1000 );
    THROTTLES.updateTvSymbols  = throttle( updateTvSymbols, 300 );


    /**
     * Loops
     * - Must clear 'run' in the function after promise point.
     */
    // Loops | Account Summery
    LOOPS.syncRepAccountSummery = new loopMan(
        syncRepAccountSummery,
        {interval:1500}
    );
    // Loops | Account History Last 24H
    LOOPS.syncRepAccountH24 = new loopMan(
        syncRepAccountH24,
        {interval:30000}
    );
    // Loops | Account Position
    LOOPS.syncRepAccountPosition = new loopMan(
        syncRepAccountPosition,
        {interval:1500}
    );
    // Loops | Account Pending
    LOOPS.syncRepAccountPending = new loopMan(
        syncRepAccountPending,
        {interval:1500}
    );
    // Loops | Account Pending
    LOOPS.syncRepAccountPending = new loopMan(
        syncRepAccountPending,
        {interval:1500}
    );
    // Loops | Account Update Market
    LOOPS.updateMarketSymbols = new loopMan(
        THROTTLES.updateMarketSymbols,
        {interval:1500}
    );
    // Loops | aChart Update Selected Symbol
    LOOPS.updateAChartSymbol = new loopMan(
        THROTTLES.updateAChartSymbol,
        {interval:1500}
    );
    // Loops | TV Account Update Market
    LOOPS.updateTvSymbols = new loopMan(
        THROTTLES.updateTvSymbols,
        {interval:1500}
    );
    // Loops | TV Account Update TV Svreen
    LOOPS.updateTvScreen = new loopMan(
        updateTvScreen,
        {interval:1500}
    );
    // Loops | Update Focus Bar
    LOOPS.updateFocusBar = new loopMan(
        updateFocusBar,
        {interval:300}
    );

    /**
     * Makeup Page
     */
    // Makeup Page - hide-onload Objects
    $('.hide-onload').fadeOut('fast');
    // Makeup Page - Update Country List
    $('body').on('click', '.allow-focus', function(e) {e.stopPropagation();});
    // Makeup Page - Country selector
    $(`body`).on(`click`,`ul.countries-list .dropdown-item`, function() {
        let country = $(this).data('country');
        $(this).closest("ul.countries-list").prev(".country-selector").html(countriesLib[country].flag+' '+countriesLib[country].country);
    });
    // Makeup Page - Show Section
    $(`body`).on(`click`,`.show-section`, function() {
        let screen  = $(this).attr('screen');
        let section = $(this).attr('section');
        let params  = $(this).attr('params');
        callback = $(this).attr('callback');
        if(callback) {
            APP.callback = $(this).attr('callback');
        } else {
            APP.callback = {};
        }
        if( (APP.screen !== screen) || params) {
            changeScreen(screen, section, params);
        } else {
            changeSection(screen, section);
        }
    });
    // Makeup Page - Hide Sidebar Panel
    $(`body`).on(`click`,`#sidebarPanel a`, function() {
        $(this).closest('.modal').modal('hide');
    });
    // Makeup Page - Scroll Event
    $(document).on("scroll", function(){
        const MaxScroll = $(window).outerHeight()
        if(window.scrollY) {
            $(".go-top").fadeIn();
        } else {
            $(".go-top").hide();
        }
    })
    // Makeup Page - Scroll Top
    $(`body`).on(`click`,`.go-top`, function() {
        $("body").get(0).scrollIntoView({behavior: 'smooth'});
    });

    $(`body`).on('hidden.bs.modal', function(){
        setFocusBar(false);
    });

    $(`body`).on('#sidebarPanel hidden.bs.modal', function(){
        if(APP.section!==`positions` || APP.section!==`tv`){
            LOOPS.syncRepAccountPosition.stop();
        }
        if(APP.section!==`pending` || APP.section!==`tv`){
            LOOPS.syncRepAccountPending.stop();
        }
    });
    $(`body`).on('#sidebarPanel shown.bs.modal', function(){
        if(APP.selectedAccount){
            LOOPS.syncRepAccountPosition.start();
            LOOPS.syncRepAccountPending.start();
        }
    });

    /**
     * Settings
     */
    $(`body`).on(`click`,`.show-PanelAppSettings`, function() {
        preLoader('#PanelAppSettings .loading');
        syncSetting();
        $('#PanelAppSettings .app-setting-item').each(async function(i, obj) {
            const key = $(obj).attr('id');
            const type = $(obj).attr('type');
            let val = getSetting(key);
            if(type==='checkbox'){
                $(obj).attr('checked', !!(val))
            } else {
                $(obj).val(val);
            }
        })
        .promise()
        .done( function() {
            setTimeout(()=>{
                $('#PanelAppSettings .loading').html('');
            },150);
        });
    });
    $(`body`).on(`change keyup`,`.app-setting-item`, function() {
        const key = $(this).attr('id');
        const type = $(this).attr('type');
        let val = '';
        if(type==='checkbox'){
            val = $(this).is(':checked') ? true : false;
        } else {
            val = $(this).val();
        }
        updateSetting(key, val);
        $(this).parent().addClass('was-validated');
    });
    $(`body`).on(`change`,`#darkmodeSwitch`, function() {
        const themeColor = (localStorage.FinappDarkmode==='1') ? "dark" : "light";
        if( !$.isEmptyObject(aChart) )
            aChart.changeTheme(themeColor);
        if( !$.isEmptyObject(tvLeftChart) )
            tvLeftChart.changeTheme(themeColor);
        if( !$.isEmptyObject(tvRightChart) )
            tvRightChart.changeTheme(themeColor);
    });

    /**
     * Notifications
     */
    // Notifications - Get Notifications List
    $(`body`).on(`click`,`.show-PanelNotifications, #moreNotifications`, function() {
        preLoader('#PanelNotifications .loading');
        const data = {
            session:  APP.client.session,
            offset:   $('.show-PanelNotifications').data('last-id')
        }
        socket.emit("getUserNotifications", data, (response) => {
            if ( ( response.hasOwnProperty(`e`) ) ) {
                flyNotify('danger',LanguageT.Error, response.e,'close-circle-outline');
                appUI.$.iPanelNotifications.find('.loading').html('Show Older').prop( "disabled", false );
            } else {

                let counter = Object.keys(response).length;
                if(counter>0){
                    appUI.$.iPanelNotifications.find('.loading').html('Show Older').prop( "disabled", false );
                    $('.show-PanelNotifications').data('last-id', Object.values(response).pop().id);
                    let html = [];
                    for (const i in response) {
                        notifications[response[i].id] = response[i];
                        if(response[i].status==='-1') continue;
                        const unread = (response[i].status==='0')  ? `<span class="badge badge-${response[i].type} badge-empty"></span>` : '';
                        html.push(
                            `<li class=" ">
                                <a href="#" class="item" data-id="${response[i].id}" data-bs-toggle="modal" data-bs-target="#ModalNotification">
                                    <div class="icon-box bg-${response[i].type}">
                                        <ion-icon name="${response[i].icon}" role="img" class="md hydrated"></ion-icon>
                                    </div>
                                    <div class="in">
                                        <div>
                                            <div class="mb-05"><strong>${response[i].title}</strong></div>
                                            <div class="text-small mb-05">${response[i].notion}</div>
                                            <div class="text-xsmall">${response[i].created_at}</div>
                                        </div>
                                        ${unread}
                                    </div>
                                </a>
                            </li>`
                        );
                        if(--counter===0){
                            appUI.$.iPanelNotifications.find(`ul.listview`).append(html.join(''));
                            setTimeout(()=>{
                                appUI.$.iPanelNotifications = $('#PanelNotifications');
                            }, 50);
                        }
                    }
                }
                else {
                    appUI.$.iPanelNotifications.find('.loading').html('No More ...').prop( "disabled", true );
                }
                updateNotificationsCounter();
            }
        });
    });
    // Notifications - Show Notification Modal
    $(`body`).on(`click`,`#PanelNotifications .item`, function() {
        preLoader('#ModalNotification .loading');
        const id = $(this).data('id');
        const data = {
            session:  APP.client.session,
            id:       id
        }
        socket.emit("readNotification", data);
        $(this).find('.badge').remove();
        notifications[id].status = 1;
        $('#ModalNotification .modal-body .section').html('');
        $('#PanelNotifications ul.listview li').removeClass('active');
        $(this).parent().addClass('active');
        $('#ModalNotification .del-notification').attr('data-id',id);
        const html = `<div class="listed-detail mt-3">
                        <div class="icon-wrapper">
                            <div class="iconbox bg-${notifications[id].type} bg-gradient">
                                <ion-icon name="${notifications[id].icon}" role="img" class="md hydrated"></ion-icon>
                            </div>
                        </div>
                        <h3 class="text-center mt-2">${notifications[id].title}</h3>
                    </div>

                    <ul class="listview simple-listview no-space mt-3">
                        <li>
                            <span>Date</span>
                            <strong>${notifications[id].created_at}</strong>
                        </li>
                        <li>
                            <span>Notin</span>
                            <strong>${notifications[id].notion}</strong>
                        </li>                        
                    </ul>
                    ${notifications[id].content}`;
        updateNotificationsCounter();
        setTimeout(()=>{
            appUI.$.iPanelNotifications.find('.loading').html('Show Older');
            $('#ModalNotification .modal-body .section').html(html);
        },150);
    });
    // Notifications - Delete Notifications
    $(`body`).on(`click`,`#ModalNotification .del-notification`, function() {
        let id = $(this).attr('data-id');
        if(getSetting('confirm4notify')){
            getConfirmation(LanguageT.Delete_Notifications+'?', `deleteNotification(${id})`, LanguageT.DELETE, 'danger');
        } else {
            deleteNotification(id);
        }
        updateNotificationsCounter();
    });
    // Notifications - Mark All Seen
    $(`body`).on(`click`,`#PanelNotifications #notification-seen-all`, function() {
        preLoader('#PanelNotifications .loading');
        const data = {
            session:  APP.client.session,
        }
        socket.emit("seenAllNotifications", data, (response) => {
            appUI.$.iPanelNotifications.find('.loading').html('Show Older');
            if ( ( response.hasOwnProperty(`e`) ) ) {
                flyNotify('danger', LanguageT.Error, response.e,'close-circle-outline');
            } else {
                appUI.$.iPanelNotifications.find(`ul.listview li`).find('.badge').remove();
                updateNotificationsCounter();
            }
        });
    });
    // Notifications - Delete All
    $(`body`).on(`click`,`#PanelNotifications #notification-delete-all`, function() {
        preLoader('#PanelNotifications .loading');
        const data = {
            session:  APP.client.session,
        }
        socket.emit("deleteAllNotifications", data, (response) => {
            appUI.$.iPanelNotifications.find('.loading').html('No More ...').prop( "disabled", true );
            if ( ( response.hasOwnProperty(`e`) ) ) {
                flyNotify('danger',LanguageT.Error, response.e,'close-circle-outline');
            } else {
                appUI.$.iPanelNotifications.find(`ul.listview`).html('');
                updateNotificationsCounter();
            }
        });
    });

    /**
     * User
     */
    // User - Logout
    $('body').on('click', '#do-logout', function(e) {
        if(getSetting('confirm4logout')){
            getConfirmation(LanguageT.Logout+'?', `logout()`, LanguageT.Yes, 'danger');
        } else {
            logout();
        }
    });
    $('body').on('click', '.do-logout ', function(e) {
        $(`#do-logout`).click();
    });


    /**
     * Guest
     */
    // Guest - Submit Login
    $('body').on('submit', '#guest-login form#crm-login', function(e) {
        e.preventDefault();
        const data = {
            session:  APP.client.session,
            username:  $('#guest-login #login-email').val(),
            password:  $('#guest-login #password').val()
        }
        socket.emit("crmLogin", data, (response) => {
            if ( response.hasOwnProperty(`e`) ) {
                flyNotify('danger',LanguageT.Error, response.e,'close-circle-outline');
            }
            else{
                flyNotify('success', LanguageT.Successful, LanguageT.submit_login_success,'checkmark-circle-outline');
                setTimeout(()=>{
                    location.reload();
                }, 3000);
            }
        });
    });
    // Guest - Submit Recovery
    $('body').on('submit', '#guest-recovery form#crm-recovery', function(e) {
        e.preventDefault();
        const data = {
            session:  APP.client.session,
            username:  $('#guest-recovery #recovery-email').val(),
        }
        socket.emit("crmRecovery", data, (response) => {
            if ( response.hasOwnProperty(`e`) ) {
                flyNotify('danger',LanguageT.Error, response.e,'close-circle-outline');
            }
            else{
                flyNotify('success', LanguageT.Successful, LanguageT.crmRecovery_success,'checkmark-circle-outline');
                setTimeout(()=>{
                    if(cLog) console.log(response);
                }, 3000);
            }
        });

    });
    // Guest - Register / Select Country
    $(`body`).on(`click`,`#guest-register ul.countries-list .dropdown-item`, function() {
        let country = $(this).data('country');
        $('#guest-register #phone-p').val(countriesLib[country].dialCode.substring(1));
        $('#guest-register #country').val(countriesLib[country].country);
    });
    // Guest - Submit Register
    $("body").on("submit","#guest-register form#crm-register", function(e) {
        e.preventDefault();
        const data = {
            session:  APP.client.session,
            fname:    $('#guest-register #fname').val(),
            lname:    $('#guest-register #lname').val(),
            email:    $('#guest-register #register-email').val(),
            phone:    $('#guest-register #phone-p').val() + $('#guest-register #phone').val(),
            country:  $('#guest-register #country').val(),
            unit_id:  1 // Turkish
        }
        socket.emit("crmRegister", data, (response) => {
            if ( response.hasOwnProperty(`e`) ) {
                flyNotify('danger',LanguageT.Error, response.e,'close-circle-outline');
            } else {
                flyNotify('success', LanguageT.Successful, LanguageT.crmRegister_success,'checkmark-circle-outline');
                setTimeout(()=>{
                    location.reload();
                }, 3000);
            }
        });
    });


    /**
     * TP Account
     */
    // TP Account | E> Show Modal - Account Summery
    appUI.$.iAccountSummary.on('shown.bs.modal', function(){
        updateAccountSummaryLoop = true;
        LOOPS.syncRepAccountSummery.assignValues({limit:-1}).start();
    });
    // TP Account | E> Hide Modal - Account Summery
    appUI.$.iAccountSummary.on('hidden.bs.modal', function(){
        updateAccountSummaryLoop = false;
        LOOPS.syncRepAccountSummery.stop().kill();
        appUI.$.iAccountSummary_listview.find(`li>span`).html(`-`);

    });
    // TP Account | E> Select Account
    $('body').on('click', '#trade-accounts .do-selectTPA', function(e) {
        APP.selectedAccount = $(this).attr('account');
        localStorage.appSelectedAccount = APP.selectedAccount;
        clearRepository();
        LOOPS.syncRepAccountSummery.restart({limit:5});
        $(`#trade-accounts .do-selectTPA`).removeClass('disabled')
            .html('Select');
        $(this).addClass('disabled').html('Selected');
        $(`#trade-accounts #clearSelectedAccount`)
            .removeClass('d-hide');
        if(!$.isEmptyObject(APP.callback)){
            const callback = JSON.parse(APP.callback);
            changeScreen(callback.screen, callback.section);
            APP.callback = {};
        }
        metaMarketEmit_GetLoginData();
    });

    /**
     * Trade
     */
    // Trade - Make New Account Form
    $("body").on("change","#openAccount form #platform", function(e) {
        e.preventDefault();
        getPlatformGroups();
    });
    $("body").on("change","#openAccount form #type", function(e) {
        e.preventDefault();
        getPlatformGroups();
    });
    $('#openAccount').on('shown.bs.modal', function(){
        getPlatformGroups();
    });
    // Trade - Meta Open TP
    $("body").on("submit","#openAccount form", function(e) {
        e.preventDefault();
        const data = {
            session:  APP.client.session,
            platform:  $('#openAccount form #platform').val(),
            type:  $('#openAccount form #type').val(),
            group:  $('#openAccount form #group').val(),
            amount:  $('#openAccount form #amount').val()
        }
        socket.emit("s1OpenAccount", data, (response) => {
            if(cLog) console.log(response);
            if ( response.hasOwnProperty(`e`) ) {
                flyNotify('danger',LanguageT.Error, response.e,'close-circle-outline');
            }
            else{
                flyNotify('success', LanguageT.Successful, LanguageT.openAccount_success+response?.Login,'checkmark-circle-outline');
                setTimeout(()=>{
                    changeScreen(APP.screen, APP.section, APP.screenParams);
                },3000);
            }
        });
    });

    // Trade - Filter Symbols
    $("body").on('change, keyup','#trade-market #filter-symbol, #trade-tv #filter-symbol', function(e) {
        e.preventDefault();
        const filterInput = $(this).val().toLowerCase().replaceAll(`.`, `_d_`);
        DEBOUNES.filterSymbols(filterInput);
    });

    // Trade - Open Form
    $('body').on('click', '.do-trade', function(e) {
        const symbol = $(this).attr('symbol');
        const action = $(this).attr('trade');
        const size = $(`.symbol-row[symbol='${symbol}'] .lotsize`).val();
        doTrade(symbol, action, size);
    });
    // Trade - Update Volume
    $('body').on('click', '#tradeForm .update-volume', function(e) {
        let value = $(this).attr('value');
        let volume = $('#tradeForm #volume').val();
        const newVolume = (parseFloat(value)+parseFloat(volume)).toFixed(2);
        $('#tradeForm #volume').val( (newVolume>.01) ? newVolume : .01);
    });
    // Trade - Advance Mode
    $('body').on('change', '#tradeForm .advance-mode', function(e) {
        let type = $(this).val();
        if(type==='Pending'){
            $('#tradeForm #advanced-order').show();
            $('#tradeForm #trade-action')
                .html(`Order`)
                .attr('class',`btn btn-primary btn-block btn-lg`);
        } else {
            let action = $('#tradeForm #trade-action').attr('acttype');
            let actionColor = (action==='Buy') ? 'success' : 'danger';
            $('#tradeForm #trade-action')
                .html(LanguageT[action])
                .attr('class',`btn btn-${actionColor} btn-block btn-lg`);
            $('#advanced-order').hide();
        }
    });
    // Trade - Advance Mode Time Type
    $('body').on('change', '#tradeForm #time-type', function(e) {
        let timeType = $(this).val();
        if(timeType==='0' || timeType==='1'){
            $("#tradeForm .spe-datetime").hide();
        } else if(timeType==='2'){
            $("#tradeForm .spe-datetime").prop('type','datetime-local').show();
        } else if(timeType==='3'){
            $("#tradeForm .spe-datetime").prop('type','date').show();
        }
    });
    // Trade - Submit Form
    $('body').on('submit', '#tradeForm form#trade', function(e) {
        e.preventDefault();
        closeNotificationBox();
        let sType          = $('#tradeForm #trade-action').attr('acttype');
        const symbol       = $('#tradeForm .modal-title').html();
        const oType        = $("#tradeForm #order-type").val();
        const takeProfit   = $(`#tradeForm #TP`).val();
        const stopLoss     = $(`#tradeForm #SL`).val();
        let data = {};
        let priceOnAct = 0;
        if(oType==='Market') {
            data = {
                oType       : oType,
                login       : APP.selectedAccount,
                sType       : sType,
                aType       : (sType==="Buy") ? 0 : 1,
                symbol      : symbol,
                volume      : $(`#tradeForm #volume`).val(),
                digits      : procMarketSymbols[symbol].Digits,
                takeProfit  : parseFloat(takeProfit).toFixed(procMarketSymbols[symbol].Digits),
                stopLoss    : parseFloat(stopLoss).toFixed(procMarketSymbols[symbol].Digits)
            }
            priceOnAct = (sType==="Buy") ? procMarketSymbols[symbol].Ask : procMarketSymbols[symbol].Bid;
        }
        else if(oType==='Pending'){
            const type_P            = $("#tradeForm #p-order-type").val();
            const TypeTime          = $("#tradeForm #time-type").val();
            const TimeExpiration    = $("#tradeForm #TimeExpiration").val();
            const PriceTrigger      = $("#tradeForm #PriceTrigger").val();
            data = {
                oType          : oType,
                login          : APP.selectedAccount,
                sType          : sType,
                aType          : (sType==="Buy") ? 0 : 1,
                symbol         : symbol,
                volume         : $(`#tradeForm #volume`).val(),
                digits         : procMarketSymbols[symbol].Digits,
                type           : type_P,
                PriceOrder     : $("#tradeForm #PriceOrder").val(),
                TypeTime       : TypeTime,
                takeProfit     : parseFloat(takeProfit).toFixed(procMarketSymbols[symbol].Digits),
                stopLoss       : parseFloat(stopLoss).toFixed(procMarketSymbols[symbol].Digits),
                TimeExpiration : TimeExpiration,
                PriceTrigger   : PriceTrigger
            }
            priceOnAct = data.PriceOrder;
            sType = tradeAction[type_P];
        }
        if(data.takeProfit>0){
            if ( checkTp(sType, priceOnAct, data.takeProfit) ) return;
        }
        if(data.stopLoss>0){
            if ( checkSl(sType, priceOnAct, data.stopLoss) ) return;
        }
        APP.temp = data;
        if(getSetting('confirm4orders')){
            getConfirmation(LanguageT.Place_an_order, `order()`, LanguageT[sType], (sType===`Buy`)?'success':`danger`);
        } else {
            order();
        }
    });

    // Trade - Close Position
    $('body').on('click', '#trade-tv .position-close, #trade-positions .position-close', function(e) {
        let position = $(this).attr('position');
        if(getSetting('confirm4closePosition')){
            getConfirmation(LanguageT.Close_Position+'?', `closePosition(${position})`, LanguageT.Close, 'danger');
        } else {
            closePosition(position);
        }
    });
    // Trade - Edit Position
    $('body').on('click', '.position-edit', function(e) {
        let position = $(this).attr('position');
        const stepDigits = 1 / Math.pow(10, proAccountPositions[position].Digits);
        $('#positionEdit button').attr('position', position);
        $('#positionEdit #SL').val(proAccountPositions[position].PriceSL).attr('step',stepDigits);
        $('#positionEdit #TP').val(proAccountPositions[position].PriceTP).attr('step',stepDigits);
    });
    $('body').on('change keyup', '#positionEdit input', function(e) {
      $('#positionEdit button').attr('disabled',false);
    });
    $('body').on('click', '#positionEdit button', function(e) {
        e.preventDefault();
        let position = $(this).attr('position');
        if(getSetting('confirm4updateTrade')){
            getConfirmation(LanguageT.Edit_Position+'?', `editPosition(${position})`, LanguageT.Yes, 'danger');
        } else {
            editPosition(position);
        }
    });
    // Trade - Position Detail
    $('body').on('click', '.position-detail', function(e) {
        let position = $(this).attr('position');
        positionDetail(position);
    });
    // Trade - Position Detail act Close
    $('body').on('click', '#positionDetail .act-close', function(e) {
        $(`#positionDetail`).modal('hide');
        let position = $(this).attr('position');
        $(`.position-row[position='${position}'] .position-close`).trigger('click');
    });
    // Trade - Position Detail act Edit
    $('body').on('click', '#positionDetail .act-edit', function(e) {
        let position = $(this).attr('position');
        $(`#positionDetail`).modal('hide');
        $(`.position-row[position='${position}'] .position-edit`).trigger('click');
    });

    // Trade - Cancel Order
    $('body').on('click', '.order-cancel', function(e) {
        let order = $(this).attr('order');
        if(getSetting('confirm4cancelOrder')){
            getConfirmation(LanguageT.Cancel_the_order+'?', `cancelOrder(${order})`, LanguageT.Cancel, 'danger');
        } else {
            cancelOrder(order);
        }
    });
    // Trade - Edit Order Time Type
    $('body').on('change', '#orderEdit #time-type', function(e) {
        let timeType = $(this).val();
        if(timeType==='0' || timeType==='1'){
            $("#orderEdit .spe-datetime").hide();
        } else if(timeType==='2'){
            $("#orderEdit .spe-datetime").prop('type','datetime-local').show();
        } else if(timeType==='3'){
            $("#orderEdit .spe-datetime").prop('type','date').show();
        }
    });
    // Trade - Edit Order Volume
    $('body').on('click', '#orderEdit .update-volume', function(e) {
        let value = $(this).attr('value');
        let volume = $('#orderEdit #volume').val();
        const newVolume = (parseFloat(value)+parseFloat(volume)).toFixed(2);
        $('#orderEdit #volume').val( (newVolume>.01) ? newVolume : .01);
    });
    // Trade - Edit Order
    $('body').on('click', '.order-edit', function(e) {
        let order = $(this).attr('order');
        const datetime = new Date( parseInt(proAccountOrders[order].TimeExpiration)*1000 );
        const stepDigits = 1 / Math.pow(10, proAccountOrders[order].Digits);
        $('#orderEdit button').attr('order', order);
        $('#orderEdit #SL').val(proAccountOrders[order].PriceSL).attr('step',stepDigits);
        $('#orderEdit #TP').val(proAccountOrders[order].PriceTP).attr('step',stepDigits);
        $('#orderEdit #volume').val(proAccountOrders[order].VolumeCurrent/10000);
        $('#orderEdit #PriceOrder').val(proAccountOrders[order].PriceOrder);
        $('#orderEdit #p-order-type').val(proAccountOrders[order].Type).change();
        $('#orderEdit #time-type').val(proAccountOrders[order].TypeTime).change();
        if(proAccountOrders[order].TypeTime==='2'){
            $('#orderEdit #TimeExpiration').val( datetime.toLocaleString('sv') );
        }
        else if(proAccountOrders[order].TypeTime==='3'){
            $('#orderEdit #TimeExpiration').val( datetime.toLocaleString('en-CA',{year:"numeric",month:"2-digit",day:"2-digit"}) );
        }
        $('#orderEdit #PriceTrigger').val(proAccountOrders[order].PriceTrigger);
    });
    $('body').on('click', '#orderEdit button', function(e) {
        e.preventDefault();
        let order = $(this).attr('order');
        if(getSetting('confirm4updateTrade')){
            getConfirmation(LanguageT.Edit_Order+'?', `editOrder(${order})`, LanguageT.Yes, 'danger');
        } else {
            editOrder(order);
        }
    });
    // Trade - Order Detail
    $('body').on('click', '.order-detail', function(e) {
        let order = $(this).attr('order');
        orderDetail(order);
    });
    // Trade - Order Detail act Cancel
    $('body').on('click', '#orderDetail .act-cancel', function(e) {
        $(`#orderDetail`).modal('hide');
        let order = $(this).attr('order');
        $(`.order-row[order='${order}'] .order-cancel`).trigger('click');
    });
    // Trade - Order Detail act Edit
    $('body').on('click', '#orderDetail .act-edit', function(e) {
        let order = $(this).attr('order');
        $(`#orderDetail`).modal('hide');
        $(`.order-row[order='${order}'] .order-edit`).trigger('click');
    });

    // Trade - Filter History
    $('body').on('click', '#trade-history #update', function(e) {
        customAccountHistory();
    });
    // Trade - Filter Operation
    $('body').on('click', '#trade-operation #update', function(e) {
        customAccountOperation();
    });

    // Trade - Symbol Detail
    $('body').on('click', '.symbol-detail', function(e) {
        let symbol = $(this).attr('symbol');
        let server = 's0';
        const data = {
            session:  APP.client.session,
            account:  APP.selectedAccount,
            symbol:  symbol
        }
        $('#symbolDetail .modal-title small').html(symbol);
        socket.emit(`${server}SymbolDetail`, data, (response) => {
            if ( response.hasOwnProperty(`e`) ) {
                flyNotify('danger',LanguageT.Error, response.e,'close-circle-outline');
                $('#symbolDetail .loading').html('');
                const eHtml=`<span class="text-danger">???</span>`;
                $(`#symbolDetail ul.listview span`).html(eHtml);
            } else if (Object.keys(response).length>0) {
                for (const property in response) {
                    $(`#symbolDetail ul.listview span#${i}`).html(response[property]);
                }
                setTimeout(()=>{
                    liveLoader('#symbolDetail .loading',(getSetting('cycleAccountSummery')>0) ? 'success' : 'muted');
                },10);
            } else {
                flyNotify('danger',LanguageT.Error, `<strong>${symbol}</strong> ${LanguageT.not_found}`,'close-circle-outline');
                $('#symbolDetail .loading').html('');
                const eHtml=`<span class="text-danger">???</span>`;
                $(`#symbolDetail ul.listview span`).html(eHtml);
            }
        });
    });

    // Trade - Chart
    $('body').on('click', '.show-chart', function(e) {
        let symbol = $(this).attr('symbol');
        $('#tradeChart .chart-time').attr('symbol',symbol);
        $('#tradeChart .open-achart').attr('params',`{"symbol":"${symbol}"}`);
        APP.selectedSymbol = symbol;
        localStorage.selectedSymbol = symbol;
        $('#tradeChart #trading-view').attr('symbol',symbol);
        $('#tradeChart .modal-title small').html(symbol);
        simpleChart(symbol);
    });
    // Trade - Char Time
    $('body').on('click', '#tradeChart .chart-time', function(e) {
        if($(this).hasClass('active')) return;
        $('#tradeChart #simple-chart').html('');
        $('#tradeChart .chart-time').removeClass('active');
        $(this).addClass('active');
        let symbol = $(this).attr('symbol');
        let time = $(this).attr('time');
        simpleChart(symbol, time);
    });

    // Trade - Advanced Chart
    $('body').on('click', '.open-achart', function(e) {
        let symbol = $(this).attr('symbol');
    });

    /**
     * Transaction
     */
    // Transaction - Deposit
    $('body').on('submit', 'form#deposit', function(e) {
        e.preventDefault();
        let files = $('form#deposit #doc').prop('files');
        if(files.length) {
            let data = {
                session:  APP.client.session,
                type:   'deposit',
                tp:   APP.selectedAccount,
                amount:   $('form#deposit #amount').val(),
                docs :     [],
                comment:  $('form#deposit #comment').val(),
                gateway: 1
            };
            for(const index in files) {
                if(typeof(files[index])==='object'){
                    getBase64(files[index], function(b64){
                        data.docs[index] = b64;
                        if(index===Object.keys(files).pop()){
                            requestTransaction(data);
                        }
                    });
                }
            }
        } else {
            flyNotify('danger',LanguageT.Error, LanguageT.deposit_error,'close-circle-outline');
        }
    });

    // Transaction - Withdraw
    $('body').on('submit', 'form#withdraw', function(e) {
        e.preventDefault();
        let data = {
            session:  APP.client.session,
            type:   'withdraw',
            tp:   APP.selectedAccount,
            bankAccount:   $('form#withdraw #bankAccount').val(),
            amount:   $('form#withdraw #amount').val(),
            docs :     [],
            comment:  $('form#withdraw #comment').val(),
        };
        let files = $('form#withdraw #docw').prop('files');
        if(files.length) {
            for(const index in files) {
                if(typeof(files[index])==='object'){
                    getBase64(files[index], function(b64){
                        data.docs[index] = b64;
                        if(index===Object.keys(files).pop()){
                            requestTransaction(data);
                        }
                    });
                }
            }
        } else {
            requestTransaction(data);
        }
    });

    // Transaction - Cancel
    $('body').on('click', '#transaction-deposit .do-cancel, #transaction-withdraw .do-cancel', function(e) {
        e.preventDefault();
        let transactionId = $(this).data('tid');
        if(getSetting('confirm4cancelTransaction')){
            getConfirmation(LanguageT.Cancel_the_transaction_request+'?', `cancelTransaction(${transactionId})`, LanguageT.Cancel, 'danger');
        } else {
            cancelTransaction(transactionId);
        }
    });

    /**
     * TV
     */
    // TV - Symbol Menu
    $('body').on('click', '#trade-tv .symbol-row', function(e) {
        const symbol = $(this).attr('symbol');
        if(cLog) console.log(symbol);
        if($(`.action-tr[symbol="${symbol}"]`).length === 0){
            $(`.action-tr`).remove();
            $(this).after(
                `<tr symbol="${symbol}" class="action-tr">
                                <td class="text-center bg-success" data-bs-toggle="modal" data-bs-target="#tradeForm" onclick="doTrade('${symbol}', 'Buy')">${LanguageT.Buy}</td>
                                <td class="text-center bg-danger" data-bs-toggle="modal" data-bs-target="#tradeForm" onclick="doTrade('${symbol}', 'Sell')">${LanguageT.Sell}</td>
                                <td class="text-center bg-dark show-chart" symbol="${symbol}" data-bs-toggle="modal" data-bs-target="#tradeChart">${LanguageT.Chart}</td>
                                <td colspan="2" class="text-center bg-secondary symbol-detail" symbol="${symbol}" data-bs-toggle="modal" data-bs-target="#symbolDetail">${LanguageT.Details}</td>
                        </tr>`
            );
        } else {
            $(`.action-tr`).remove();
        }
    });

    /**
     * Dev Tools
     */
    // Dev Tools - App Info
    $('body').on('click', '.show-PanelDev', function(e) {
            $('#dev-app-info .dev-session').html(APP.client.session);
            $('#dev-app-info .dev-uid').html(APP.client.id);
            $('#dev-app-info .dev-screen').html(APP.screen);
            $('#dev-app-info .dev-section').html(APP.section);
    });
    // Dev Tools - Socket Time
    $('body').on('click', '#PanelDev #status-socket', function(e) {
        socket.emit("socketTime",null, (response) => {
            if ( response.hasOwnProperty(`e`) ) {
                flyNotify('danger','Socket Time', response.e,'close-circle-outline',10000);
                updateCoreStatus('socket', 0);
            } else {
                flyNotify('info','Socket Time', response.time,'close-circle-outline',10000);
                updateCoreStatus('socket', 1);
            }
            if(cLog) console.log(response);
        });
    });
    // Dev Tools - CRM Time
    $('body').on('click', '#PanelDev #status-crm', function(e) {
        socket.emit("crmTime",null, (response) => {
            if ( response.hasOwnProperty(`e`) ) {
                flyNotify('danger','CRM Time', response.e,'close-circle-outline',10000);
                updateCoreStatus('crm', 0);
            } else {
                flyNotify('info','CRM Time', response.time,'close-circle-outline',10000);
                updateCoreStatus('crm', 1);
            }
            if(cLog) console.log(response);
        });
    });
    // Dev Tools - Meta Time
    $('body').on('click', '#PanelDev #status-meta', function(e) {
        socket.emit("metaTime",null, (response) => {
            if ( response.hasOwnProperty(`e`) ) {
                flyNotify('danger','MT5 Time', response?.code,'close-circle-outline',10000);
                updateCoreStatus('meta', 0);
            } else {
                flyNotify('info','MT5 Time', response.time,'close-circle-outline',10000);
                updateCoreStatus('meta', 1);
            }
            if(cLog) console.log(response);
        });
    });
    // Dev Tools - Reload Screen
    $('body').on('click', '.do-reScreen', function(e) {
        changeScreen(APP.screen, APP.section, APP.screenParams);
    });

});

