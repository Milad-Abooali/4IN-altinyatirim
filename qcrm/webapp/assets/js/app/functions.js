function initial(){
    if(APP.client.id>0){
        if(!APP.client.agreement) setTimeout(ladTermsFile, 50);
        localStorage.clientId = APP.client.id;
        if(localStorage?.appSelectedAccount){
            APP.selectedAccount = localStorage.appSelectedAccount;
            const data = {
                session:  APP.client.session,
                account:  APP.selectedAccount
            }
            socket.emit(`selectAccount`, data);
        }
        syncSetting();
        if(getSetting('autoSizing')){
            if(window.matchMedia("(max-width: 767px)").matches){
                screenMobile();
            } else {
                screenTv();
            }
        }
        else {
            screenMobile();
        }
    } else {
        changeScreen(APP.screen, APP.section);
    }
    socket.emit('linkClient', APP.client, (response) => {
        if(cLog) console.log('Socket > linkClient', response);
        const avatar = response?.webapp?.avatar;
        APP.client.avatar = (avatar) ? avatar : 'webapp/assets/img/avatar.jpg';
        $('img.avatar').attr('src',APP.client.avatar);
        if(response.webapp.user){
            $('#sidebarPanel .username').html(response.webapp.user.username);
            $('#sidebarPanel .fname').html(response.webapp.user.user_extra.fname);
        }
        // Makeup Page - Update Country List
        countriesList();
        // Makeup Page - Notification Counter
        updateNotificationsCounter();
        if(APP.selectedAccount){
            LOOPS.syncRepAccountSummery.restart({limit:5});
        }
    });
}
// Screen Size - TV
function screenTv(){
    changeScreen('trade', 'tv');
}
// Screen Size - Mobile
function screenMobile(){
    if(localStorage.appScreen==='trade' && localStorage.appSection==='tv'){
        changeScreen('home', 'start');
    } else {
        if(typeof localStorage.appScreen!==`undefined`){
            changeScreen(localStorage.appScreen, localStorage.appSection, localStorage.appScreenParams);
        }
        else {
            changeScreen('home', 'start');
        }
    }
}
// Alt Header
function altHeader(full=false) {
    const header = $('#app-header');
    const capsule = $('#appCapsule');
    const sticky = $('.sticky-top');
    if(full) {
        header.css("top", "0");
        capsule.css("padding-top", "56px");
    } else {
        header.css("top", "-56px");
        sticky.css("z-index", "998");
        capsule.css("padding-top", "3px");
    }
}
// Alt Header Float
function altHeaderFloat(active=true){
    if(active){
        $('#app-header-alt').show();
        altHeader();
    }else{
        $('#app-header-alt').hide();
        altHeader(1);
    }
}
// Alt Footer
function altFooter(full) {
    const footer = $('#app-footer');
    const capsule = $('#appCapsule');
    if(full) {
        footer.css("bottom", "0");
        capsule.css("padding-bottom", "56px");
        $('#app-footer-alt button').show();
        $('#app-footer-alt div').hide();
    } else {
        footer.css("bottom", "-56px");
        capsule.css("padding-bottom", "5px");
        $('#app-footer-alt button').hide();
        $('#app-footer-alt div').show();
    }

}
// Alt Footer Float
function altFooterFloat(active=true){
    if(active){
        $('#app-footer-alt').show();
        altFooter();
    }else{
        $('#app-footer-alt').hide();
        altFooter(1);
    }
}
// Alt Toggle
function toggleMenu(){
    if( $('#app-footer-alt button').is(":visible") ){
        altFooter(0);
    } else {
        altFooter(1);
    }
    if( parseInt($('#app-header').css("top"))===0 ){
        altHeader(0);
    } else {
        altHeader(1);
    }
}


/**
 * Local Storage
 */
 // APP Settings
function updateSetting(key,val){
    APP.settings[key] = val;
    localStorage.appSettings = JSON.stringify(APP.settings);
    setTimeout(()=>{
        changeSection(APP.screen, APP.section, APP.screenParams);
        $(this).parent().addClass('was-validated');
        setTimeout(()=>{
            $('#PanelAppSettings .was-validated').removeClass('was-validated');
        },2200);
    },300);
}
function getSetting(key){
    if(key in APP.settings) return APP.settings[key];
    return APP.settingsDef[key];
}
function delSetting(key){
    delete APP.settings[key];
    localStorage.appSettings = JSON.stringify(APP.settings);
 }
function syncSetting(){
    if(typeof(localStorage.appSettings)==='undefined') return false;
    APP.settings = JSON.parse(localStorage.appSettings);
}


/**
 * Status
 */
// Status - Update APP
function updateCoreStatus(engine, status){
    APP.status[engine] = status;
    let textClass = 'muted';
    switch(status) {
        case 1:
            textClass = `success`;
            break;
        case 0:
            textClass = `danger`;
            break;
        case -1:
            textClass = `warning`;
            break;
        default:
            textClass = 'muted';
    }
    $(`#PanelDev #status-${engine}`).attr(`class`,`spinner-grow spinner-grow-sm text-${textClass}`);
}
// Status - CRM | Deprecated from v0.9
function crmStatus() {
    updateCoreStatus('crm', -1);
    $.get(APP.crm).done(function () {
        updateCoreStatus('crm', 1);
    }).fail(function () {
        updateCoreStatus('crm', 0);
    });
}
// Status - Meta | Deprecated from v0.9
function metaStatus() {
    updateCoreStatus('meta', -1);
    socket.emit("metaTime", null, (response) => {
        if ( response.hasOwnProperty(`e`) ) {
            updateCoreStatus('meta', 0);
        }
        else{
            updateCoreStatus('meta', 1);
        }
    });
}
// Status - Counters
function syncCounters(){
    preLoader('#sidebarPanel #trade-menu .badge','light');
    const data = {
        session: APP.client.session,
        account: APP.selectedAccount
    }
    socket.emit("syncCounters", data, (response) => {
        console.log(response);
        if ( response.hasOwnProperty(`e`) ) {
            if(cLog) console.log(response);
        }
        else {
            appUI.$.iSidebarPanel
                .find(`#account-counts`)
                .html( (response.account>0) ? response.account : '' );
        }
    });
}


/**
 * Notification
 */
// Delete Notification
function deleteNotification(id) {
    $('#DialogConfirmation').modal('hide');
    const data = {
        session:  APP.client.session,
        id:       id
    }
    socket.emit("deleteNotification", data, (response) => {
        if ( response.hasOwnProperty(`e`) ) {
            flyNotify('danger', LanguageT.Error, response.e,'close-circle-outline');
        }
        else{
            flyNotify('success',LanguageT.Successful, LanguageT.Notification_deleted,'checkmark-circle-outline');
            $(`#PanelNotifications .item[data-id="${id}"]`).parent('li').remove();
            setTimeout(()=>{
                $('#ModalNotification').modal('hide');
            }, 1000);
            updateNotificationsCounter();
        }
    });
}
// Update Notifications Counter
function updateNotificationsCounter(){
    const data = {
        session:  APP.client.session,
    }
    socket.emit("countNotifications", data, (response) => {
        if ( response.hasOwnProperty(`e`) ) {
            if(cLog) console.log(response);
        }
        else{
            if(notificationsCounter!==response) {
                notificationsCounter = response;
                if(response>0){
                    $('#notifications-unread').html(notificationsCounter).show();
                } else {
                    $('#notifications-unread').hide();
                }
            }
        }
    });
}
/**
 * Meta Market - Get Login Data
 */
function metaMarketEmit_GetLoginData(){
    if(APP.selectedAccount.length>0){
        feed.emit("getLoginData", {login:APP.selectedAccount}, (response) => {
            if(response.hasOwnProperty(`e`)){
                appAlert(`error`,`Feed Error`, response.e );
            }
            else {
                if(cLog) console.log(`getLoginData`, response);
            }
        });
    }
}


/**
 * TP Account
 */
// TP Account | Force Selecting TP
function forceToSelectAccount( alert=false ){
    if(APP.selectedAccount) {
        appUI.$.iActivSection
            .find('.select-tp').fadeOut().end()
            .find('.selected-tp').fadeIn().end()
            .find('.selected-tp-account').html(APP.selectedAccount);
        return true;
    }
    else {
        if( appUI.$.iActivSection.find('.select-tp').length > 0 ){
            appUI.$.iActivSection
                .find('.select-tp').fadeIn().end()
                .find('.selected-tp').fadeOut().end()
                .find('.selected-tp-account').html(``);
        }
        else if(alert) {
            flyNotify('secondary', LanguageT.Error,  LanguageT.force_selecting_TP,'close-circle-outline');
        }
        return false;
    }
}
// TP Account | Fill Account Summery Repo
function syncRepAccountSummery() {
    if(APP.selectedAccount.length!==0) {
        const data = {
            session:  APP.client.session,
            account:  APP.selectedAccount
        }
        socket.emit(`s0AccountSummery`, data, (response) => {
            if ( response.hasOwnProperty(`e`) ) {
                flyNotify('danger',LanguageT.Error, `${LanguageT.Account} <strong>${APP.selectedAccount}</strong> ${LanguageT.not_found}!`,'close-circle-outline');
                clearSelectedAccount(true);
            }
            else if(Object.keys(response).length>0) {
                repAccountSummery = response;
            }
            else {
                flyNotify('danger',LanguageT.Error, `${LanguageT.Account} <strong>${APP.selectedAccount}</strong> ${LanguageT.not_found}!`,'close-circle-outline');
                clearSelectedAccount(true);
            }
        });
    }
    else {
        clearRepository();
        LOOPS.syncRepAccountSummery.kill();
    }
}
// TP Account | Update Account Summary Modal
function updateAccountSummary(account=APP.selectedAccount){
    if(updateAccountSummaryLoop===false) {
        updateAccountSummaryLoop=true;
        return;
    }
    if(cLog) console.log(`updateAccountSummary ${account}`);

    appUI.$.iAccountSummary_title.html(account);
    appUI.$.iAccountSummary_tradeStatus.html('');
    preLoader('#accountSummary .loading');
    const data = {
        session:  APP.client.session,
        account:  account
    }
    if(parseInt(account)===parseInt(APP.selectedAccount)){
        if (Object.keys(repAccountSummery).length>0) {
            if(repAccountSummery.isTradeDisabled===1){
                appUI.$.iAccountSummary_tradeStatus.html(LanguageT.Account_Trade_Disabled);
            } else{
                appUI.$.iAccountSummary_tradeStatus.html('');
            }
            DEBOUNES.emitSelectedAccount();
            for(const property in repAccountSummery) {
                appUI.$.iAccountSummary_listview.find(`span#${property}`).html(repAccountSummery[property]);
            }
            liveLoader('#accountSummary .loading', `success`);
            setTimeout(()=>{
                updateAccountSummary(account);
            },1050);
        } else {
            if(cLog) console.warn(`updateAccountSummary`, `Wait for the repo filling`);
            liveLoader('#accountSummary .loading', `muted`);
            setTimeout(()=>{
                updateAccountSummary(account);
            },1050);
        }
    }
    else {
        console.log(`o Account ${account}`);

        socket.emit(`s0AccountSummery`, data, (response) => {
            if ( response.hasOwnProperty(`e`) ) {
                flyNotify('danger',LanguageT.Error, `${LanguageT.Account} <strong>${APP.selectedAccount}</strong> ${LanguageT.not_found}!`,'close-circle-outline');
                appUI.$.iAccountSummary_span.html(`<span class="text-danger">???</span>`);
                appUI.$.iAccountSummary.modal('hide');
            }
            else if (Object.keys(response).length>0) {
                for (const property in response) {
                    appUI.$.iAccountSummary_listview.find(`span#${property}`).html(response[property]);
                    if(response.isTradeDisabled===1){
                        appUI.$.iAccountSummary_tradeStatus.html(LanguageT.Account_Trade_Disabled);
                    } else{
                        appUI.$.iAccountSummary_tradeStatus.html('');
                    }
                }
                liveLoader('#accountSummary .loading', `success`);
                setTimeout(()=>{
                    updateAccountSummary(account);
                },1050);
            }
            else {
                flyNotify('danger',LanguageT.Error, `${LanguageT.Account} <strong>${account}</strong> ${LanguageT.not_found}!`,'close-circle-outline');
                appUI.$.iAccountSummary_span.html(`<span class="text-danger">???</span>`);
                appUI.$.iAccountSummary.modal('hide');
            }
        });
    }
}
// TP Account | Emit Selected Account
function emitSelectedAccount(){

    if(APP.selectedAccount.length!==0) {
        const data = {
            session: APP.client.session,
            account: APP.selectedAccount
        }
        socket.emit(`selectAccount`, data);
        if(cLog) console.log(`emitSelectedAccount`, data);
    }
    else {
        if(cLog) console.log(`emitSelectedAccount null`);
    }
}
// TP Account | Clear Selected Account
function clearSelectedAccount(deep){
    if(cLog) console.log(`clearSelectedAccount ?deep`, deep);
    LOOPS.syncRepAccountSummery.stop();
    APP.selectedAccount = '';
    if(deep) localStorage.removeItem(`appSelectedAccount`);
    $(`#trade-accounts #clearSelectedAccount`).addClass('d-hide');
    clearRepository();
    initial();
}


/**
 * TP History
 */
// TP History | Fill History Repo ( Last 24H )
function syncRepAccountH24() {
    if(APP.section!==`history` && APP.section!==`tv`){
        LOOPS.syncRepAccountH24.kill();
    }
    if(APP.selectedAccount.length!==0) {
        const data = {
            session:  APP.client.session,
            account:  APP.selectedAccount,
            from:  getToday(),
            to:  getToday(1)
        }
        socket.emit(`s0AccountHistory`, data, (response) => {
            if(response.hasOwnProperty(`e`)){
                if(cLog) console.log(response.e);
            }
            else if (Object.keys(response).length>0) {
                for(const property in response){
                    repAccountHistory[response[property].Deal] = response[property];
                }
            }
        });
    }
    else {
        clearRepository();
        LOOPS.syncRepAccountH24.kill();
    }
}
// TP History | Fill History Repo ( Full ) - Not Used
function syncRepAccountHistory() {
    if(APP.selectedAccount.length!==0) {
        const data = {
            session:  APP.client.session,
            account:  APP.selectedAccount,
            from:  '0000-00-00',
            to:  getToday(1)
        }
        socket.emit(`s0AccountHistory`, data, (response) => {
            if(response.hasOwnProperty(`e`)){
                if(cLog) console.log(response.e);
            }
            else {
                for (const property in response) {
                    repAccountHistory[response[property].Deal] = response[property];
                }
            }
        });
    }
    else {
        clearRepository();
        LOOPS.syncRepAccountHistory.kill();
    }
    if(APP.section!==`history` && APP.section!==`tv`){
            LOOPS.syncRepAccountHistory.kill();
    }
}
// TP History | Custom Account History
function customAccountHistory(){
    if(cLog) console.log(`customAccountHistory start.`);
    appUI.$.iActivSection.find(`#deals-wrapper`).html('');
    preLoader('#trade-history .loading');
    const data = {
        session:  APP.client.session,
        account:  APP.selectedAccount,
        from:  appUI.$.iActivSection.find(`#start-date`).val(),
        to: appUI.$.iActivSection.find(`#end-date`).val()
    }
    if(cLog) console.log(`customAccountHistory data`, data);
    socket.emit(`s0AccountHistory`, data, (response) => {
        if(cLog) console.log(response);
        if ( response.hasOwnProperty(`e`) ) {
            flyNotify('danger',LanguageT.Error, response.e,'close-circle-outline');
            appUI.$.iActivSection.find(`.loading`).html('');
        }
        else if (Object.keys(response).length>0) {
            const instance = $('#trade-history #deal-temp').html();

            // Drop Old Deals
            for (const property in proAccountDeals) {
                if(response[property]!==undefined) {
                    dropDeal(property);
                }
            }

            for (const i in response) {
                // Fill repo
                repAccountHistory[response[i].Deal] = response[i];

                // Skip start items
                if( response[i].Entry === '0' ) {
                    if(cLog) console.log(`Start Order/Deals Skipped.`);
                    continue;
                }

                const dataNew = response[i];
                const dataOld = proAccountDeals[response[i].Order];

                let dealRow = `#trade-history #deals-wrapper .deal-row[deal="${dataNew.Deal}"]`;

                // Skip Not Changed Deals
                if( JSON.stringify(dataNew) === JSON.stringify(dataOld) ) {
                    if(cLog) console.log(`Deals Skipped.`);
                    continue;
                }

                // Make Row
                if(!dataOld || $(dealRow).length===0){
                    const html  = `<div class="deal-row item border border-dark" deal="${dataNew.Deal}">${instance}</div>`;
                    $('#trade-history #deals-wrapper').prepend(html);
                    $(`${dealRow} #symbol`).html(dataNew.Symbol);

                    for (const j in response) {
                        if( response[j].Entry === '1' ) {
                            continue;
                        }
                        if(response[j].Order === dataNew.PositionID){
                            const datetimeOpen = new Date( parseInt(response[j].TimeMsc)-APP.serverTimeZoneOffset );
                            $(`${dealRow} #time-open`).html(datetimeOpen.toLocaleString('sv'));
                            $(`${dealRow} #price-open`).html(response[j].Price);
                            $(`${dealRow} #action`).html(tradeAction[response[j].Action]);
                            continue;
                        }

                    }

                    const datetimeClose = new Date( parseInt(dataNew.TimeMsc)-APP.serverTimeZoneOffset );
                    $(`${dealRow} #time-close`).html(datetimeClose.toLocaleString('sv'));
                    if(cLog) console.log(`${dataNew.Symbol} New Row.`);
                }
                $(`${dealRow} #price-close`).html(dataNew.Price);
                let profitColor = (dataNew.Profit>0) ? 'success' : 'danger';
                $(`${dealRow} #profit`).html(dataNew.Profit).attr('class',`text-${profitColor}`);
                $(`${dealRow} #volume`).html(parseFloat(dataNew.Volume)/10000);
                $(`${dealRow} #storage`).html(dataNew.Storage);
                $(`${dealRow} #sl`).html(dataNew.PriceSL);
                $(`${dealRow} #tp`).html(dataNew.PriceTP);

                // Fill Holder
                proAccountDeals[response[i].Deal] = response[i];
            }
            setTimeout(()=>{
                appUI.$.iActivSection.find(`.loading`).html('');
            },50);
        }
        else {
            if(cLog) console.log(`Deals data not changed.`);
            appUI.$.iActivSection.find(`.loading`).html('');
        }
    });
}
// TP History | Custom Account Operation
function customAccountOperation(){
    let cLog=1;
    appUI.$.iActivSection.find(`#operations-wrapper`).html('');
    preLoader('#trade-operation .loading');
    const data = {
        session:  APP.client.session,
        account:  APP.selectedAccount,
        from:  $('#trade-operation #start-date').val(),
        to:  $('#trade-operation #end-date').val()
    }
    socket.emit(`s0AccountHistory`, data, (response) => {
        if(cLog) console.log(response);
        if ( response.hasOwnProperty(`e`) ) {
            flyNotify('danger',LanguageT.Error, response.e,'close-circle-outline');
            setTimeout(()=>{
                appUI.$.iActivSection.find(`.loading`).html('');
            },50);
        }
        else if (Object.keys(response).length>0) {
            appUI.$.iActivSection.find(`.loading`).html('');

            const instance = appUI.$.iActivSection.find(`#operation-temp`).html();

            // Drop Old Deals
            for (const property in proAccountOperations) {
                if(response[property]!==undefined) {
                    dropDeal(property);
                }
            }

            for (const i in response) {
                // Fill repo
                repAccountHistory[response[i].Deal] = response[i];

                // Skip start items
                if( response[i].Action !== '2' && response[i].Action !== '6') {
                    if(cLog) console.log(`Order/Deals Skipped.`, response[i]);
                    continue;
                }

                const dataNew = response[i];
                const dataOld = proAccountOperations[response[i].Order];


                let operationRow = `#trade-operation #operations-wrapper .operation-row[deal="${dataNew.Deal}"]`;


                // Skip Not Changed Deals
                if( JSON.stringify(dataNew) === JSON.stringify(dataOld) ) {
                    if(cLog) console.log(`Operation Skipped.`);
                    continue;
                }

                // Make Row
                if(!dataOld || $(dealRow).length===0){
                    const html  = `<div class="operation-row item" deal="${dataNew.Deal}">${instance}</div>`;
                    appUI.$.iActivSection.find('#operations-wrapper').prepend(html);

                    const datetime = new Date( parseInt(dataNew.TimeMsc)-APP.serverTimeZoneOffset );
                    $(`${operationRow} #time`).html(datetime.toLocaleString('sv'));
                    if(dataNew.Profit>0){
                        $(`${operationRow} #type`).html('Deposit').addClass('text-success');
                        $(`${operationRow} #amount`).html('+'+dataNew.Profit).addClass('text-success');
                    } else {
                        $(`${operationRow} #type`).html('Withdrawals').addClass('text-danger');;
                        $(`${operationRow} #amount`).html(dataNew.Profit).addClass('text-danger');
                    }
                    $(`${operationRow} #comment`).html(dataNew.Comment);
                }

                // Fill Holder
                proAccountOperations[response[i].Deal] = response[i];
            }
            setTimeout(()=>{
                appUI.$.iActivSection.find(`.loading`).html('');
            },50);
        }
        else {
            if(cLog) console.log(`Operation data not changed.`);
            setTimeout(()=>{
                appUI.$.iActivSection.find(`.loading`).html('');
            },50);
        }
    });
}


/**
 * TP Position
 */
// TP Position | Fill Position Repo
function syncRepAccountPosition() {
    if(APP.section!==`positions` && APP.section!==`tv`){
        LOOPS.syncRepAccountPosition.kill();
    }
    if(APP.selectedAccount.length!==0) {
        const data = {
            session:  APP.client.session,
            account:  APP.selectedAccount
        }
        socket.emit(`s0OpenPositions`, data, (response) => {
            if(response.hasOwnProperty(`e`)){
                if(cLog) console.log(response.e);
            }
            else {
                repAccountPosition = response;

                // Update Counter UI
                appUI.$.iSidebarPanel
                    .find(`#position-counts`)
                    .html( Object.keys(repAccountPosition).length );

            }
        });
    }
    else {
        clearRepository();
        LOOPS.syncRepAccountPosition.kill();
    }
}
// TP Positions | Update Open Positions Section
function updateOpenPositions(){
    if(APP.section===`positions`){
        preLoader('#trade-positions .loading');
        let counter = Object.keys(repAccountPosition).length;
        if (counter>0) {
            // Drop Missed Positions
            for (const property in proAccountPositions) {
                if(repAccountPosition[property]!==undefined) {
                    dropPosition(property);
                    delete proAccountPositions[property];
                }
            }
            // Update Positions UI
            for (const i in repAccountPosition) {
                if(!--counter){
                    liveLoader('#trade-positions .loading','success');
                    setTimeout(()=>{
                        updateOpenPositions();
                    },1050);
                }
                const dataNew = repAccountPosition[i];
                const dataOld = proAccountPositions[repAccountPosition[i].Position];
                // Skip Not Changed Position
                if( JSON.stringify(dataNew) === JSON.stringify(dataOld) ) {
                    if(cLog) console.log(`position Skipped.`);
                    continue;
                }

                let positionRow = `#trade-positions #positions-wrapper .position-row[position="${dataNew.Position}"]`;
                let uiPositionRow = $(positionRow);
                // Make Row
                if(!dataOld || uiPositionRow.length===0){
                    const html  = `<div class="position-row item border border-info" position="${dataNew.Position}">${appUI.$.iTradePositions_temp}</div>`;
                    appUI.$.iTradePositions_wrapper.prepend(html);
                    uiPositionRow = $(positionRow);
                    uiPositionRow
                        .find(`#symbol`).html(dataNew.Symbol).end()
                        .find(`button`).attr({
                            symbol:dataNew.Symbol,
                            position:dataNew.Position
                        });
                }
                uiPositionRow
                    .find(`#price-current`).html(dataNew.PriceCurrent).end()
                    .find(`#volume`).html(parseFloat(dataNew.Volume)/10000).end()
                    .find(`#action`).html( (parseInt(dataNew.Action)) ? LanguageT.Sell : LanguageT.Buy ).end()
                    .find(`#profit`).html(dataNew.Profit)
                    .attr('class',`text-${(dataNew.Profit>0) ? 'success' : 'danger'}`).end()
                    .find(`#sl`).html(dataNew.PriceSL).end()
                    .find(`#tp`).html(dataNew.PriceTP).end()
                    .find(`#Storage`).html(dataNew.Storage);
                // Fill Holder
                proAccountPositions[repAccountPosition[i].Position] = repAccountPosition[i];
            }
        }
        else {
            if(cLog) console.warn(`updateOpenPositions`, `Wait for the repo filling`);
            liveLoader('#trade-positions .loading','muted');
            setTimeout(()=>{
                updateOpenPositions();
            },3050);
        }
    }
    else {
        LOOPS.syncRepAccountPosition.stop().kill();
    }
}
// TP Position | ~Close Position
function closePosition(position){
    $(`#positionDetail`).modal('hide');
    $(`#trade-positions .position-close[position='${position}']`).prepend(`<span class="spinner-border spinner-border-sm me-05" role="status" aria-hidden="true"></span>`);
    const data = {
        session:  APP.client.session,
        position:  position,
        account:  APP.selectedAccount,
        symbol:  proAccountPositions[position].Symbol
    }
    socket.emit("s1ClosePosition", data, (response) => {
        if ( response.hasOwnProperty(`e`) ) {
            flyNotify('danger',LanguageT.Error, response.e,'close-circle-outline');
        }
        else{
            flyNotify('success',LanguageT.Successful, `${LanguageT.Position_closed}(${response})`,'checkmark-circle-outline');
            setTimeout(()=>{
                dropPosition(position);
                aCharLinePositions[position]?.remove();
            },50);
        }
    });
}
// TP Position | ~Edit Position
function editPosition(position){
    const data = {
        session:  APP.client.session,
        symbol:  proAccountPositions[position].Symbol,
        position:  position,
        tp:  $('#positionEdit #TP').val(),
        sl:  $('#positionEdit #SL').val()
    }
    const sTypeEn = (proAccountPositions[position].Action==='1') ? `Sell` : `Buy`;
    const sType = (proAccountPositions[position].Action==='1') ? LanguageT.Sell : LanguageT.Buy;
    let price = proAccountPositions[position].PriceOpen;
    if( proAccountPositions[position].Profit >0){
        price = proAccountPositions[position].PriceCurrent;
    }
    if(data.tp>0){
        if ( checkTp(sTypeEn, price, data.tp) ) return;
    }
    if(data.sl>0){
        if ( checkSl(sTypeEn, price, data.sl) ) return;
    }
    socket.emit("s1EditPosition", data, (response) => {
        if ( response.hasOwnProperty(`e`) ) {
            flyNotify('danger',LanguageT.Error, response.e,'close-circle-outline');
        }
        else{
            flyNotify('success',LanguageT.Successful, LanguageT.Position_updated,'checkmark-circle-outline');
            setTimeout(()=>{
                if(APP.section==='tv'){
                    updateTvPositions();
                } else if(APP.section==='positions'){
                    updateOpenPositions();
                }
            },50);
        }
    });
}
// TP Position | ~Position Detail
function positionDetail(position){
    $(`#positionDetail .modal-title small`).html(position);
    $(`#positionDetail .act-edit`).attr('position', position);
    $(`#positionDetail .act-close`).attr('position', position);
    $(`#positionDetail .modal-body #symbol`).html(proAccountPositions[position].Symbol);
    $(`#positionDetail .modal-body #profit`).html(proAccountPositions[position].Profit);
    $(`#positionDetail .modal-body #storage`).html(proAccountPositions[position].Storage);
    $(`#positionDetail .modal-body #volume`).html((proAccountPositions[position].Volume)/10000);
    $(`#positionDetail .modal-body #open-price`).html(proAccountPositions[position].PriceOpen);
    $(`#positionDetail .modal-body #current-price`).html(proAccountPositions[position].PriceCurrent);
    $(`#positionDetail .modal-body #sl`).html(proAccountPositions[position].PriceSL);
    $(`#positionDetail .modal-body #tp`).html(proAccountPositions[position].PriceTP);
    $(`#positionDetail .modal-body #margin-rate`).html(parseFloat(proAccountPositions[position].RateMargin));

    const datetime = new Date( parseInt(proAccountPositions[position].TimeCreateMsc) );
    $(`#positionDetail .modal-body #time`).html(datetime.toLocaleString('sv'));
}


/**
 * TP Pending
 */
// TP Pending | Fill Pending Repo
function syncRepAccountPending() {
    if(APP.section!==`pending` && APP.section!==`tv`){
        LOOPS.syncRepAccountPosition.kill();
    }
    if(APP.selectedAccount.length!==0) {
        const data = {
            session:  APP.client.session,
            account:  APP.selectedAccount
        }
        socket.emit(`s0PendingOrders`, data, (response) => {
            if(response.hasOwnProperty(`e`)){
                if(cLog) console.log(response.e);
            }
            else {
                repAccountPending = response;

                // Update Counter UI
                appUI.$.iSidebarPanel
                    .find(`#order-counts`)
                    .html( Object.keys(repAccountPending).length );

            }
        });
    }
    else {
        clearRepository();
        LOOPS.syncRepAccountPending.kill();
    }
}
// TP Positions | Update Pending Orders Section
function updatePendingOrders(){
    if(APP.section===`pending`) {
        preLoader('#trade-pending .loading');
        let counter = Object.keys(repAccountPending).length;
        if (counter>0) {
            // Drop Missed Orders
            for (const property in proAccountOrders) {
                if(repAccountPending[property]!==undefined) {
                    dropOrder(property);
                    delete repAccountPending[property];
                }
            }
            // Update Orders UI
            for (const i in repAccountPending) {
                if(!--counter){
                    liveLoader('#trade-pending .loading','success');
                    setTimeout(()=>{
                        updatePendingOrders();
                    },1050);
                }
                const dataNew = repAccountPending[i];
                const dataOld = proAccountOrders[repAccountPending[i].Order];
                // Skip Not Changed Orders
                if( JSON.stringify(dataNew) === JSON.stringify(dataOld) ) {
                    if(cLog) console.log(`Order Skipped.`);
                    continue;
                }
                let orderRow = `#trade-pending #orders-wrapper .order-row[order="${dataNew.Order}"]`;
                let uiOrderRow = $(orderRow);
                // Make Row
                if(!dataOld || uiOrderRow.length===0){
                    const html  = `<div class="order-row item border border-warning" order="${dataNew.Order}">${appUI.$.iTradePendings_temp}</div>`;
                    appUI.$.iTradePendings_wrapper.prepend(html);
                    uiOrderRow = $(orderRow);
                    uiOrderRow
                        .find(`#symbol`).html(dataNew.Symbol).end()
                        .find(`button`).attr({
                            symbol:dataNew.Symbol,
                            order:dataNew.Order
                        });
                }
                uiOrderRow
                    .find(`#price-current`).html(dataNew.PriceCurrent).end()
                    .find(`#volume`).html(parseFloat(dataNew.VolumeCurrent)/10000).end()
                    .find(`#action`).html(tradeAction[dataNew.Type]).end()
                    .find(`#sl`).html(dataNew.PriceSL).end()
                    .find(`#tp`).html(dataNew.PriceTP).end()
                    .find(`#price-order`).html(dataNew.PriceOrder).end()
                    .find(`#price-trigger`).html(dataNew.PriceTrigger);
                // Fill Holder
                proAccountOrders[repAccountPending[i].Order] = repAccountPending[i];
            }
        }
        else {
            if(cLog) console.warn(`updatePendingOrders`, `Wait for the repo filling`);
            liveLoader('#trade-pending .loading','muted');
            setTimeout(()=>{
                updatePendingOrders();
            },3050);
        }
    }
    else {
        LOOPS.syncRepAccountPending.stop().kill();
    }
}
// TP Positions | ~Cancel Order
function cancelOrder(order){
    $(`#orderDetail`).modal('hide');
    $(`#trade-pending .order-cancel[order='${order}']`).prepend(`<span class="spinner-border spinner-border-sm me-05" role="status" aria-hidden="true"></span>`);
    const data = {
        session:  APP.client.session,
        symbol:  proAccountOrders[order].Symbol,
        order:  order,
        account:  APP.selectedAccount
    }
    socket.emit("s1CancelOrder", data, (response) => {
        if ( response.hasOwnProperty(`e`) ) {
            flyNotify('danger',LanguageT.Error, response.e,'close-circle-outline');
        }
        else{
            flyNotify('success',LanguageT.Successful, LanguageT.Order_canceled,'checkmark-circle-outline');
            setTimeout(()=>{
                dropOrder(order);
                dropOrder(order);
                aCharLinePendings[order]?.remove();
            },50);
        }
    });
}
// TP Positions | ~Edit Order
function editOrder(order){
    const data = {
        session:  APP.client.session,
        symbol:  proAccountOrders[order].Symbol,
        order:  order,
        tp:  $('#orderEdit #TP').val(),
        sl:  $('#orderEdit #SL').val(),
        Volume:  $('#orderEdit #volume').val(),
        PriceOrder:  $('#orderEdit #PriceOrder').val(),
        PriceTrigger:  $('#orderEdit #PriceTrigger').val(),
        TimeExpiration:  $('#orderEdit #TimeExpiration').val(),
        TypeTime:  $('#orderEdit #time-type option:selected').val()
    }
    let sType =  $('#orderEdit #p-order-type option:selected').val();
    if(data.tp>0){
        if ( checkTp(tradeAction[sType], data.PriceOrder, data.tp) ) return;
    }
    if(data.sl>0){
        if ( checkSl(tradeAction[sType], data.PriceOrder, data.sl) ) return;
    }
    socket.emit("s1EditOrder", data, (response) => {
        if(cLog) console.log(response);
        if ( response.hasOwnProperty(`e`) ) {
            flyNotify('danger',LanguageT.Error, response.e,'close-circle-outline');
        }
        else{
            flyNotify('success',LanguageT.Successful, LanguageT.Order_updated,'checkmark-circle-outline');
            setTimeout(()=>{
                if(APP.section==='tv'){
                    updateTvOrders();
                } else if(APP.section==='pending'){
                    updatePendingOrders();
                }
                $('#orderEdit').modal('hide');
            },50);
        }
    });
}
// TP Positions | ~Edit Order Price
function editOrderPrice(order, Price){
    const data = {
        session:  APP.client.session,
        symbol:  proAccountOrders[order].Symbol,
        order:  order,
        PriceOrder:  Price
    }
    socket.emit("s1EditOrderPrice", data, (response) => {
        if(cLog) (response);
        if ( response.hasOwnProperty(`e`) ) {
            flyNotify('danger',LanguageT.Error, response.e,'close-circle-outline');
        }
        else{
            flyNotify('success',LanguageT.Successful, LanguageT.Order_updated,'checkmark-circle-outline');
            setTimeout(()=>{
                if(APP.section==='tv'){
                    updateTvOrders();
                } else if(APP.section==='pending'){
                    updatePendingOrders();
                }
            },50);
        }
    });
}
// TP Positions | ~Order Detail
function orderDetail(order){
    $(`#orderDetail .modal-title small`).html(order);
    $(`#orderDetail .act-edit`).attr('order', order);
    $(`#orderDetail .act-cancel`).attr('order', order);
    $(`#orderDetail .modal-body #symbol`).html(proAccountOrders[order].Symbol);
    $(`#orderDetail .modal-body #type`).html(tradeAction[proAccountOrders[order].Type]);
    $(`#orderDetail .modal-body #volume`).html(parseFloat(proAccountOrders[order].VolumeCurrent)/10000);
    $(`#orderDetail .modal-body #order-price`).html(proAccountOrders[order].PriceOrder);
    $(`#orderDetail .modal-body #current-price`).html(proAccountOrders[order].PriceCurrent);
    $(`#orderDetail .modal-body #trigger-price`).html(proAccountOrders[order].PriceTrigger);
    $(`#orderDetail .modal-body #sl`).html(proAccountOrders[order].PriceSL);
    $(`#orderDetail .modal-body #tp`).html(proAccountOrders[order].PriceTP);
    const datetime = new Date( parseInt(proAccountOrders[order].TimeSetupMsc) );
    $(`#orderDetail .modal-body #time`).html(datetime.toLocaleString('sv'));
}


/**
 *  TP General
 */
// TP General | Show Symbols
function showSymbolsRows(){
    if( appUI.$.iActivSection.find(`#filter-symbol`).val().length>APP.filterStartOn ) {return;}
    appUI.$.iActivSection
        .find(`.remove-symbol`).hide().end()
        .find(`.add-symbol`).show().end()
        .find(`.symbol-row`).hide();
    let rowSelector = (APP.section===`tv`) ? appUI.$.hSymbolRows : appUI.$.hSymbolRows;
    const personalWatchlist = JSON.parse(localStorage.personalWatchlist);
    if(personalWatchlist.length>0){
        for (const symbol in personalWatchlist) {
            if ( ! rowSelector.hasOwnProperty( personalWatchlist[symbol] ) ) continue;
            rowSelector[ personalWatchlist[symbol] ]
                .find(`.remove-symbol`).show().end()
                .find(`.add-symbol`).hide();
        }
    }

    if(APP.watchlist===`all`){
        appUI.$.iActivSection.find(`.symbol-row`).show();
    }
    else if(APP.watchlist===`top`){
        if(watchlistTop.length>0){
            for (const symbol in watchlistTop) {
                if ( ! rowSelector.hasOwnProperty( watchlistTop[symbol] ) ) continue;
                rowSelector[ watchlistTop[symbol] ].show();
            }
        }
    }
    else if(APP.watchlist===`personal`){
        if(personalWatchlist.length>0){
            for (const symbol in personalWatchlist) {
                if ( ! rowSelector.hasOwnProperty( personalWatchlist[symbol] ) ) continue;
                rowSelector[personalWatchlist[symbol]].show();
            }
        }
    }
}
// TP General | Drop Symbol Row - Not Used
function dropSymbolRow(symbol){
    appUI.$.iActivSection.find(`.symbol-row[symbol="${symbol}"]`).remove();
    delete procMarketSymbols[symbol];
    if(appUI.$.hSymbolRows.hasOwnProperty(symbol)){
        delete appUI.$.hSymbolRows[symbol];
        delete uiRenderedSymbols[symbol];
    }
}
// TP TV | Drop Deal
function dropDeal(deal) {
    appUI.$.iActivSection.find(`.deal-row[deal="${deal}"]`).remove();
    delete proAccountDeals[deal];
    if(appUI.$.hDealRows.hasOwnProperty(deal)){
        delete appUI.$.hDealRows[deal];
    }
}
// TP TV | Drop Operation
function dropOperation (deal) {
    appUI.$.iActivSection.find(`.operation-row[deal="${deal}"]`).remove();
    delete proAccountOperations[deal];

    if(appUI.$.hOperationRows.hasOwnProperty(deal)){
        delete appUI.$.hOperationRows[deal];
    }
}
// TP TV | Drop Position
function dropPosition(position) {
    appUI.$.iActivSection.find(`.position-row[position="${position}"]`).remove();
    delete proAccountPositions[position];
    repAccountPosition = repAccountPosition.filter(obj => {return obj.Position !== `${position}`});

    if(appUI.$.hPositionRows.hasOwnProperty(position)){
        delete appUI.$.hPositionRows[position];
    }
}
// TP TV | Drop Order
function dropOrder(order) {
    appUI.$.iActivSection.find(`.order-row[order="${order}"]`).remove();
    delete proAccountOrders[order];
    repAccountPending = repAccountPending.filter(obj => {return obj.Order !== `${order}`});

    if(appUI.$.hOrderRows.hasOwnProperty(order)) {
        delete appUI.$.hOrderRows[order];
    }
}
// TP General | Update Symbol Diff
function updateSymbolDiff(dataOld, dataNew){
    // Update Last Direction
    dataNew.lastIcon = '';
    dataNew.lastColor = (APP.section==='tv') ? '' : 'primary';
    if(parseInt(dataNew.LastDir)===1) {
        dataNew.lastIcon = 'trending-up-outline';
        dataNew.lastColor = 'success';
    }
    else if(parseInt(dataNew.LastDir)===2) {
        dataNew.lastIcon = 'trending-down-outline';
        dataNew.lastColor = 'danger';
    }
    // Update BID
    dataNew.bidIcon  = 'reorder-two-outline';
    dataNew.bidColor = (APP.section==='tv') ? '' : 'primary';
    if(dataOld?.Bid) {
        if(dataNew.Bid > dataOld.Bid) {
            dataNew.bidIcon = 'arrow-up-outline';
            dataNew.bidColor = 'success';
        }
        else if(dataNew.Bid < dataOld.Bid) {
            dataNew.bidIcon = 'arrow-down-outline';
            dataNew.bidColor = 'danger';
        }
    }
    // Update ASK
    dataNew.askIcon  = 'reorder-two-outline';
    dataNew.askColor = (APP.section==='tv') ? '' : 'primary';
    if(dataOld?.Ask) {
        if(dataNew.Ask > dataOld.Ask) {
            dataNew.askIcon = 'arrow-up-outline';
            dataNew.askColor = 'success';
        }
        else if(dataNew.Ask < dataOld.Ask) {
            dataNew.askIcon = 'arrow-down-outline';
            dataNew.askColor = 'danger';
        }
    }
    // Update Spread
    dataNew.spreadIcon  = 'reorder-two-outline';
    dataNew.spreadColor =  (APP.section==='tv') ? '' : 'primary';
    dataNew.Spread = parseFloat(dataNew.Ask)-parseFloat(dataNew.Bid).toFixed(5);
    if(dataOld?.Spread) {
        if(dataNew.Spread > dataOld.Spread) {
            dataNew.spreadIcon = 'arrow-up-outline';
            dataNew.spreadColor = 'success';
        }
        else if(dataNew.Spread < dataOld.Spread) {
            dataNew.spreadIcon = 'arrow-down-outline';
            dataNew.spreadColor = 'danger';
        }
    }
    // Fill Holder
    procMarketSymbols[dataNew.Symbol] = dataNew;
}
// TP General | Cache Symbol Row
function cacheSymbolRow(symbol){
    appUI.$.hSymbolRows[symbol] = appUI.$.iActivSection.find(`#symbol-${symbol.replaceAll(`.`, `_d_`)}`);
}
// TP General | Filter Symbols
function filterSymbols(filterInput){
    if(filterInput.length>APP.filterStartOn){
        setTimeout(
            ()=>{
                appUI.$.iActivSection.find(`.symbol-row`).hide();
                for (const symbol in procMarketSymbols) {
                    if(symbol.toLowerCase().replaceAll(`.`, `_d_`).includes(filterInput)){
                        if(cLog) console.log( symbol );
                        if(appUI.$.hSymbolRows.hasOwnProperty(symbol)){
                            appUI.$.hSymbolRows[symbol].show();
                        }
                    }
                }
            }, 50);
    } else if (filterInput.length<3) {
        showSymbolsRows();
    }
}
// TP General | Start Market Loop
function startMarketLoop() {
    $(`.market-loop-stop`).removeClass(`d-hide`);
    $(`.market-loop-start`).addClass(`d-hide`);
    if(APP.section===`market`){
        LOOPS.updateMarketSymbols.restartDelay({delay:50});
    }
    if(APP.section===`tv`){
        LOOPS.updateTvSymbols.restartDelay({delay:50});
    }
}
// TP General | Stop Market Loop
function stopMarketLoop() {
    $(`.market-loop-stop`).addClass(`d-hide`);
    $(`.market-loop-start`).removeClass(`d-hide`);
    if(APP.section===`market`){
        liveLoader('#trade-market .loading','danger');
        LOOPS.updateMarketSymbols.stop().kill();
    }
    if(APP.section===`tv`){
        liveLoader('#trade-tv .loading','danger');
        LOOPS.updateTvSymbols.stop().kill();
    }
}


/**
 *  Watchlist
 */
// Watchlist | Define
function watchlistDefine(){
    if(typeof localStorage.personalWatchlist === 'undefined'){
        localStorage.personalWatchlist = JSON.stringify([]);
    }
    if(typeof localStorage.watchlist === 'undefined'){
        localStorage.watchlist = APP.watchlist;
    }
    else {
        APP.watchlist = localStorage.watchlist;
        watchlistSelect(APP.watchlist);
    }
}
// Watchlist | Select
function watchlistSelect(watchlist) {
    localStorage.watchlist = watchlist;
    APP.watchlist = watchlist;
    appUI.$.iActivSection
        .find(`#filter-symbol`).val('').end()
        .find(`#watchlist-wrapper button`).removeClass(`btn-outline-secondary`).end()
        .find(`#watchlist-wrapper button.watchlist-${watchlist}`)
        .addClass(`btn-outline-secondary`);
    if(watchlist===`all`){
        stopMarketLoop();
        flyNotify('info', LanguageT.Sync_Sopped,LanguageT.watchlist_all_mode_alert, 'close-circle-outline',null, 5000);
    } else {
        if(Object.keys(procMarketSymbols).length>1) {
            startMarketLoop();
        }
        closeNotificationBox();
    }
    showSymbolsRows();
}
// Watchlist | Add Personal
function addPersonalWatchlist(event, caller){
    event.stopPropagation();
    const symbol = $(caller).attr('symbol');
    let personalWatchlist = JSON.parse(localStorage.personalWatchlist);
    personalWatchlist.push(symbol);
    localStorage.personalWatchlist = JSON.stringify(personalWatchlist);
    if( APP.section==='market' ){
        appUI.$.hSymbolRows[symbol]
            .find(`.remove-symbol`).show().end()
            .find(`.add-symbol`).hide();
    }
    else if ( APP.section==='tv' ){
        appUI.$.hSymbolRows[symbol]
            .find(`.remove-symbol`).show().end()
            .find(`.add-symbol`).hide();
    }
    showSymbolsRows();
}
// Watchlist | Remove Personal
function removePersonalWatchlist(event, caller){
    event.stopPropagation();
    const symbol = $(caller).attr('symbol');
    let personalWatchlist = JSON.parse(localStorage.personalWatchlist);
    localStorage.personalWatchlist = JSON.stringify(
        personalWatchlist.filter(x => x !== symbol)
    );
    if( APP.section==='market' ){
        appUI.$.hSymbolRows[symbol]
            .find(`.remove-symbol`).hide().end()
            .find(`.add-symbol`).show();
    }
    else if ( APP.section==='tv' ){
        appUI.$.hSymbolRows[symbol]
            .find(`.remove-symbol`).hide().end()
            .find(`.add-symbol`).show();
    }
    showSymbolsRows();
}


/**
 * TP Market
 */
// TP Market | List Symbol for Filter | datalist
function updateMarketSymbolList(){
    let uiDatalist = appUI.$.iActivSection.find(`#symbols-list`);
    for (const i in procMarketSymbols ) {
        if( ! uiRenderedSymbols.has(i) ){
            uiRenderedSymbols.add(i);
            uiDatalist.append(`<option value="${i}">`);
        }
    }
}
// TP Market | !~~~~~~~Show Market Symbols UI
function showMarketSymbols(){

}
// TP Market | Creat Market Symbol Row UI
function creatMarketSymbolRowUI(symbol){
    // Time
    const datetime = new Date( parseInt(symbol.DatetimeMsc)-APP.serverTimeZoneOffset );
    appUI.$.iTradeMarket_temp
        .clone()
        .attr({
            id: `symbol-${symbol.Symbol.replaceAll(`.`, `_d_`)}`,
            symbol: symbol.Symbol
        })
        .find(`#symbol-pair`).html(symbol.Symbol).end()
        .find(`button, input`).attr('symbol', symbol.Symbol).end()
        .find(`#close-price`).html(symbol.PriceClose).end()
        .find(`#open-price`).html(symbol.PriceOpen).end()
        .find(`#price-icon`).attr({
        class: `md hydrated text-${symbol.lastColor}`,
        name: symbol.lastIcon
    }).end()
        .find(`#ask`).attr('class', `card bg-${symbol.askColor} p-1`).end()
        .find(`#ask #ask-icon`).attr('name', symbol.askIcon).end()
        .find(`#ask #ask-price`).html(symbol.Ask).end()
        .find(`#bid`).attr('class', `card bg-${symbol.bidColor} p-1`).end()
        .find(`#bid #bid-icon`).attr('name', symbol.bidIcon).end()
        .find(`#bid #bid-price`).html(symbol.Bid).end()
        .find(`#datetime`).html(datetime.toLocaleString('sv')).end()
        .appendTo(appUI.$.iTradeMarket_wrapper);
}
// TP Market | Update Symbol Row UI
function updateSymbolRowUI(symbol){
    if ( APP.watchlist===`all` ) return;
    if( ! appUI.$.hSymbolRows.hasOwnProperty(symbol.Symbol)) return;
    if( ! appUI.$.hSymbolRows[symbol.Symbol].is(':visible') ) return;
    const datetime = new Date( parseInt(symbol.DatetimeMsc)-APP.serverTimeZoneOffset );
    appUI.$.hSymbolRows[symbol.Symbol]
        .find(`#close-price`).html(symbol.PriceClose).end()
        .find(`#open-price`).html(symbol.PriceOpen).end()
        .find(`#price-icon`).attr({
            class: `md hydrated text-${symbol.lastColor}`,
            name: symbol.lastIcon
        }).end()
        .find(`#ask`).attr('class', `card bg-${symbol.askColor} p-1`).end()
        .find(`#ask #ask-icon`).attr('name', symbol.askIcon).end()
        .find(`#ask #ask-price`).html(symbol.Ask).end()
        .find(`#bid`).attr('class', `card bg-${symbol.bidColor} p-1`).end()
        .find(`#bid #bid-icon`).attr('name', symbol.bidIcon).end()
        .find(`#bid #bid-price`).html(symbol.Bid).end()
        .find(`#datetime`).html(datetime.toLocaleString('sv'));
}
// TP Market | Creat Market Symbols
function creatMarketSymbols(){
    if(cLog) console.log(`creatMarketSymbols`, `Start`);
    if(APP.section===`market`) {
        preLoader('#trade-market .loading', `danger`);
        if ( repMarketSymbols.hasOwnProperty(`mix`))
        {
            preLoader('#trade-market .loading', `secondary`);
            let counter = {
                repo: Object.keys(repMarketSymbols.mix).length,
                skipped : 0,
                created: 0,
                loop: Object.keys(repMarketSymbols.mix).length
            };
            if (counter.repo>0)
            {
                if(cLog) console.time(`creatMarketSymbols`)
                for(const i in repMarketSymbols.mix) {
                    if(!--counter.loop){
                        if(cLog) console.timeEnd(`creatMarketSymbols`);
                        showSymbolsRows();
                        liveLoader('#trade-market .loading','success');
                        setTimeout(()=>{
                            if(cLog) console.info(`creatMarketSymbols`, `Update is called.`, counter);
                            updateMarketSymbolList();
                            LOOPS.updateMarketSymbols.start();
                        },550);
                    }

                    // Store Last Data
                    const dataNew = repMarketSymbols.mix[i];

                    // Skip Old Symbols
                    if( parseInt(dataNew.Datetime) < last7day && !exceptionSymbols.has(i)) {
                        counter.skipped++;
                        if(cLog) console.log(`skipped: dataNew.Symbol`);
                        continue;
                    }
                    counter.created++;

                    // Update Diff
                    updateSymbolDiff(procMarketSymbols[i], dataNew);

                    // Make Row UI
                    creatMarketSymbolRowUI(dataNew);

                    // Cache Symbol Row UI
                    cacheSymbolRow(i);
                }
            }
            else {
                if(cLog) console.warn(`creatMarketSymbols`, `Empty Repo Mix > Retry in 50ms`);
                liveLoader('#trade-market .loading','warning');
                setTimeout(()=>{
                    creatMarketSymbols();
                },50);
            }
        }
        else {
            if(cLog) console.warn(`creatMarketSymbols`, `No Repo Mix > Retry in 750ms`);
            liveLoader('#trade-market .loading','danger');
            setTimeout(()=>{
                creatMarketSymbols();
            },750);
        }
    }
    else {
        LOOPS.updateMarketSymbols.stop().kill();
    }
}
// TP Market | Update Market Symbols
function updateMarketSymbols(){
    if(cLog) console.log(`updateMarketSymbols`, `Start`);
    if ( APP.watchlist===`all` ) return;
    if(APP.section===`market`) {
        preLoader('#trade-market .loading', `danger`);
        const procMarketSymbolsItems = Object.keys(procMarketSymbols).length;
        if ( repMarketSymbols.hasOwnProperty(`mix`) && procMarketSymbolsItems>0 )
        {
            preLoader('#trade-market .loading', `secondary`);
            let counter = {
                repo: procMarketSymbolsItems,
                skipped : 0,
                created: 0,
                loop: procMarketSymbolsItems
            };
            if (counter.repo>0)
            {
                if(cLog) console.time(`updateMarketSymbols`)
                for(const i in procMarketSymbols) {
                    if(!--counter.loop){
                        if(cLog) console.timeEnd(`updateMarketSymbols`);
                        if(cLog) console.log(`updateMarketSymbols`,counter);
                        liveLoader('#trade-market .loading','success');
                        showSymbolsRows();
                    }

                    // Store Last Data
                    const dataNew = repMarketSymbols.mix[i];

                    // Skip Old Symbols
                    counter.created++;

                    // Update Diff
                    updateSymbolDiff(procMarketSymbols[i], dataNew);

                    // Make Row UI
                    updateSymbolRowUI(dataNew);
                }
            }
            else {
                if(cLog) console.warn(`updateMarketSymbols`, `Empty Repo Mix > Retry in 50ms`);
                liveLoader('#trade-market .loading','warning');
                LOOPS.updateMarketSymbols.restartDelay({delay:550})
            }
        }
        else {
            if(cLog) console.warn(`updateMarketSymbols`, `No Repo Mix > Retry in 750ms`);
            liveLoader('#trade-market .loading','danger');
            LOOPS.updateMarketSymbols.restartDelay({delay:550})
        }
    }
    else {
        LOOPS.updateMarketSymbols.stop().kill();
    }
}


/**
 * TP aChart
 */
// TP aChart | Update Positions Diff
function updatePositionsDiff(){
    if (Object.keys(repAccountPosition).length>0) {
        // Drop Missed Positions
        for (const property in proAccountPositions) {
            if(repAccountPosition[property]!==undefined) {
                delete proAccountPositions[property];
            }
        }
        // Update Positions
        for (const i in repAccountPosition) {
            const dataNew = repAccountPosition[i];
            const dataOld = proAccountPositions[repAccountPosition[i].Position];
            // Skip Not Changed Position
            if( JSON.stringify(dataNew) === JSON.stringify(dataOld) ) continue;
            // Fill Holder
            proAccountPositions[repAccountPosition[i].Position] = repAccountPosition[i];
        }
    }
}
// TP aChart | Update Pending Orders Diff
function updatePendingOrdersDiff(){
    if (Object.keys(repAccountPending).length>0) {
        // Drop Missed Pending Orders
        for (const property in proAccountOrders) {
            if(repAccountPending[property]!==undefined) {
                delete proAccountOrders[property];
            }
        }
        // Update Pending Orders
        for (const i in repAccountPending) {
            const dataNew = repAccountPending[i];
            const dataOld = proAccountOrders[repAccountPending[i].Order];
            // Skip Not Changed Pending Orders
            if( JSON.stringify(dataNew) === JSON.stringify(dataOld) ) continue;
            // Fill Holder
            proAccountOrders[repAccountPending[i].Order] = repAccountPending[i];
        }
    }
}
// TP aChart | Update Symbol
function updateAChartSymbol(){
    if(cLog) console.log(`updateAChartSymbol`, `Start`);
    if(APP.section===`achart`)
    {
        if(APP.selectedSymbol.length>3) {
            if ( repMarketSymbols.hasOwnProperty(`mix`))
            {
                if (repMarketSymbols.mix.hasOwnProperty(APP.selectedSymbol))
                {
                    // Store Last Data
                    const dataNew = repMarketSymbols.mix[APP.selectedSymbol];
                    // Update Symbol Diff
                    updateSymbolDiff(procMarketSymbols[APP.selectedSymbol], dataNew);
                    // Update Positions Diff
                    updatePositionsDiff();
                    // Update Pending Orders Diff
                    updatePendingOrdersDiff();
                }
                else {
                    if(cLog) console.warn(`updateAChartSymbol`, `Symbole Not In Repo Mix > Retry in 50ms`);
                    LOOPS.updateAChartSymbol.restartDelay({delay:550})
                }
            }
            else {
                if(cLog) console.warn(`updateAChartSymbol`, `No Repo Mix > Retry in 750ms`);
                LOOPS.updateAChartSymbol.restartDelay({delay:550})
            }
        }
        else {
            if(cLog) console.warn(`updateAChartSymbol`, `No Selected Symbol`);
            LOOPS.syncRepAccountPosition.stop().kill();
            LOOPS.syncRepAccountPending.stop().kill();
            LOOPS.updateAChartSymbol.stop().kill();
        }
    }
    else {
        LOOPS.syncRepAccountPosition.stop().kill();
        LOOPS.syncRepAccountPending.stop().kill();
        LOOPS.updateAChartSymbol.stop().kill();
    }
}


/**
 * TP TV
 */
// TP TV | Creat Tv Symbol Row UI
function updateTVSymbolRowUI(symbol){
    if ( APP.watchlist===`all` ) return;
    if( ! appUI.$.hSymbolRows.hasOwnProperty(symbol.Symbol)) return;
    if( ! appUI.$.hSymbolRows[symbol.Symbol].is(':visible') ) return;
    appUI.$.hSymbolRows[symbol.Symbol]
        .find(`#ask-price`).html(symbol.Ask).attr('class', `text-${symbol.askColor}`).end()
        .find(`#bid-price`).html(symbol.Bid).attr('class', `text-${symbol.bidColor}`).end()
        .find(`#spread`).html(symbol.Spread.toFixed(5)).attr('class', `text-${symbol.spreadColor}`).end();
}
// TP TV | Creat Tv Symbol Row UI
function creatTvSymbolRowUI(symbol){
    appUI.$.iTradeTv_tempSymbol
        .clone()
        .attr({
            id: `symbol-${symbol.Symbol}`,
            symbol: symbol.Symbol
        })
        .find(`#symbol-pair`).html(symbol.Symbol).end()
        .find(`button, input`).attr('symbol', symbol.Symbol).end()
        .find(`#ask-price`).html(symbol.Ask).attr('class', `text-${symbol.askColor}`).end()
        .find(`#bid-price`).html(symbol.Bid).attr('class', `text-${symbol.bidColor}`).end()
        .find(`#spread`).html(symbol.Spread.toFixed(5)).attr('class', `text-${symbol.spreadColor}`).end()
        .appendTo(appUI.$.iTradeTv_symbolsWrapper);
}
// TP TV | Creat Tv Symbols
function creatTvSymbols(){
    if(cLog) console.log(`creatTvSymbols`, `Start`);
    if(APP.section===`tv`) {
        appUI.$.iTradeTv_updateTime.html( moment().format('YYYY-MM-DD h:mm:ss'));

        preLoader('#trade-tv .loading', `danger`);
        if ( repMarketSymbols.hasOwnProperty(`mix`))
        {
            preLoader('#trade-tv .loading', `secondary`);
            let counter = {
                repo: Object.keys(repMarketSymbols.mix).length,
                skipped : 0,
                created: 0,
                loop: Object.keys(repMarketSymbols.mix).length
            };
            if (counter.repo>0)
            {
                if(cLog) console.time(`creatTvSymbols`);
                for(const i in repMarketSymbols.mix) {
                    if(!--counter.loop){
                        if(cLog) console.timeEnd(`creatTvSymbols`);
                        showSymbolsRows();
                        liveLoader('#trade-tv .loading','success');
                        setTimeout(()=>{
                            if(cLog) console.info(`creatTvSymbols`, `Update is called.`, counter);
                            LOOPS.updateTvSymbols.restart({delay:500});
                        },550);
                    }

                    // Store Last Data
                    const dataNew = repMarketSymbols.mix[i];

                    // Skip Not Changed Symbols
                    if( parseInt(dataNew.Datetime) < last7day && !exceptionSymbols.has(i)) {
                        counter.skipped++;
                        continue;
                    }
                    counter.created++;

                    // Update Diff
                    updateSymbolDiff(procMarketSymbols[i], dataNew);

                    // Make Row UI
                    creatTvSymbolRowUI(dataNew);

                    // Cache Symbol Row UI
                    cacheSymbolRow(i);
                }
            }
            else {
                if(cLog) console.warn(`creatTvSymbols`, `Empty Repo Mix > Retry in 50ms`);
                liveLoader('#trade-tv .loading','warning');
                setTimeout(()=>{
                    creatTvSymbols();
                },50);
            }
        }
        else {
            if(cLog) console.warn(`creatTvSymbols`, `No Repo Mix > Retry in 750ms`);
            liveLoader('#trade-tv .loading','danger');
            setTimeout(()=>{
                creatTvSymbols();
            },750);
        }
    }
    else {
        LOOPS.updateTvSymbols.stop().kill();
    }
}
// TP TV | Update Tv Symbols
function updateTvSymbols(){
    if(cLog) console.log(`updateTvSymbols`, `Start`);
    if ( APP.watchlist===`all` ) return;
    if(APP.section===`tv`) {
        const procMarketSymbolsItems = Object.keys(procMarketSymbols).length;
        appUI.$.iTradeTv_updateTime.html( moment().format('YYYY-MM-DD h:mm:ss'));
        preLoader('#trade-tv .loading', `danger`);
        if ( repMarketSymbols.hasOwnProperty(`mix`) && procMarketSymbolsItems>0 )
        {
            preLoader('#trade-tv .loading', `secondary`);
            let counter = {
                repo: procMarketSymbolsItems,
                skipped : 0,
                created: 0,
                loop: procMarketSymbolsItems
            };
            if (counter.repo>0)
            {
                if(cLog) console.time(`updateTvSymbols`)
                for(const i in repMarketSymbols.mix) {
                    if(!--counter.loop){
                        if(cLog) console.timeEnd(`updateTvSymbols`);
                        if(cLog) console.log(`updateTvSymbols`,counter);
                        liveLoader('#trade-tv .loading','success');
                        showSymbolsRows();
                    }

                    // Store Last Data
                    const dataNew = repMarketSymbols.mix[i];

                    // Skip Old Symbols
                    counter.created++;

                    // Update Diff
                    updateSymbolDiff(procMarketSymbols[i], dataNew);

                    // Make Row UI
                    updateTVSymbolRowUI(dataNew);
                }
            }
            else {
                if(cLog) console.warn(`updateTvSymbols`, `Empty Repo Mix > Retry in 50ms`);
                liveLoader('#trade-tv .loading','warning');
                LOOPS.updateTvSymbols.restartDelay({delay:550})
            }
        }
        else {
            if(cLog) console.warn(`updateTvSymbols`, `No Repo Mix > Retry in 750ms`);
            liveLoader('#trade-tv .loading','danger');
            LOOPS.updateTvSymbols.restartDelay({delay:550})
        }
    }
    else {
        LOOPS.updateTvSymbols.stop().kill();
    }
}



/**
 * User
 */
// User - Upload Document
function uploadDoc(title, type, file){
    const data = new FormData();
    data.append('session', APP.client.session);
    data.append('title', title);
    data.append('type', type);
    data.append('file', file);
    appAjax('crmUploadDoc', data,(response)=>{
        if ( response.hasOwnProperty(`e`) && response['e'] !== false) {
            $('#user-docs form').fadeIn();
            if(cLog) console.log(response);
            flyNotify('danger',LanguageT.Error, response.e,'close-circle-outline');
            $('#user-docs .loading').html('');
        }
        else{
            flyNotify('success',LanguageT.Successful, LanguageT.NEW_DOCS,'checkmark-circle-outline');
            if(title==='id'){
                $('#user-docs .id-uploader').html(`<img class="img-thumbnail" src="${response.res}">`);
            }
            else {
                $('#user-docs .addr-uploader').html(`<img class="img-thumbnail" src="${response.res}">`);
            }
            setTimeout(()=>{
                $('#user-docs .do-upload-file').hide();
                $('#user-docs .loading').html('');
                $('#user-docs form').fadeIn();
            },2000);
        }
    });
}

// User - Upload Avatar
function uploadAvatar(base64){
    const data = {
        session:  APP.client.session,
        avatar:  base64
    }
    socket.emit("crmUploadAvatar", data, (response) => {
        if ( response.hasOwnProperty(`e`) ) {
            if(cLog) console.log(response);
            flyNotify('danger',LanguageT.Error, response.e,'close-circle-outline');
        }
        else{
            APP.client.avatar = response;
            $('img.avatar').attr('src', APP.client.avatar);
            flyNotify('success',LanguageT.Successful, LanguageT.avatar_updated,'checkmark-circle-outline');
            $('#profile-avatar .avatar').fadeIn();
        }
    });
}
function logout(){
    localStorage.removeItem("clientId");
    $('#DialogConfirmation').modal('hide');
    window.location.replace("?do=logout");
}

/**
 * Trade
 */
// Trade - Get Platform Groups | Deprecated from v0.9
function getPlatformGroups(){
    $('#openAccount form button[type="submit"]').attr('disabled', true);
    $('#openAccount form #group').html('<option>Loading ...</option>');
    const data = {
        session:  APP.client.session,
        server:  $('#openAccount form #platform').val(),
        type:  $('#openAccount form #type').val()
    }
    socket.emit(`s1PlatformGroups`, data, (response) => {
        if ( response.hasOwnProperty(`e`) ) {
            $('#openAccount form #group').html(`<option value="">Please Select Type & Platform</option>`);
            flyNotify('danger',LanguageT.Error, response.e,'close-circle-outline');
        }
        else{
            if(response.length>1){
                $('#openAccount form #group').html(response);
                $('#openAccount form button[type="submit"]').attr('disabled', false);
            } else {
                $('#openAccount form #group').html(`<option value="">Please Select Type & Platform</option>`);
                flyNotify('warning',LanguageT.Notice, LanguageT.no_platform_group,'information-circle-outline');
            }
        }
    });
}


// Trade - Order
function order() {
    preLoader('#tradeForm .loading');
    $('#tradeForm #trade-action').hide();
    const data = APP?.temp;
    delete APP.temp;
    data.session = APP?.client?.session;
    socket.emit(`s1Order${data.oType}`, data, (response) => {
        if ( response.hasOwnProperty(`e`) ) {
            flyNotify('danger',LanguageT.Error, response.e,'close-circle-outline');
            $('#tradeForm #trade-action').show();
        }
        else{
            flyNotify('success',LanguageT.Successful, `${data.sType} <strong>${data.volume}</strong> Lot ${data.symbol} - ${response}`,'checkmark-circle-outline');
            $('#tradeForm').modal('hide');
        }
        $('#tradeForm .loading').html('');
    });
}

// Trade - Simple Chart
function simpleChart(symbol, time=30) {
    if(simpleSymbolChart.helperIsRendered) {
        simpleSymbolChart.destroy();
    }
    preLoader('#tradeChart .loading');
    $('#tradeChart #simple-chart').html('');

    const to   =  Math.round(Date.now()/1000.0);
    let from   =  Math.round((Date.now()/1000.0)-(60*parseInt(time)));

    let server = getSetting('networkServer');
    const data = {
        session:  APP.client.session,
        symbol:  symbol,
        from:  from,
        to:  to
    }
    socket.emit(`${server}SymbolChart`, data, (response) => {
        if ( response.hasOwnProperty(`e`) ) {
            flyNotify('danger',LanguageT.Error, response.e,'close-circle-outline');
        } else if (Object.keys(response).length>0) {
            $('#tradeChart .loading').html('');
            let series = response.map((num) => {
                return {
                    y:num[1],
                    x:(new Date( parseInt(num[0])*1000 )).toLocaleString('sv')
                }
            });
            const chartOption = {
                series: [{data: series.slice()}],
                chart: {
                    height: 280,
                    type: 'line',
                    animations: {
                        enabled: true,
                        easing: 'linear',
                        dynamicAnimation: {
                            speed: 1000
                        }
                    },
                    toolbar: {
                        show: true
                    },
                    zoom: {
                        enabled: false
                    },
                    sparkline: {
                        enabled: false
                    }
                },
                forecastDataPoints: {
                    count: 1
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                yaxis: {
                    tooltip: {
                        enabled: false
                    }
                },
                xaxis: {
                    type: 'category',
                    // categories: series.x,
                    tooltip: {
                        enabled: false
                    },
                    labels: {
                        show:false
                    },
                    axisBorder:{
                        show:false
                    },
                    axisTicks:{
                        show:false
                    },
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        gradientToColors: [ '#FDD835'],
                        shadeIntensity: 1,
                        type: 'horizontal',
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100, 100, 100]
                    },
                }
            };
            simpleSymbolChart = new ApexCharts(document.querySelector("#simple-chart"), chartOption);
            simpleSymbolChart.render().then(() => simpleSymbolChart.helperIsRendered = true);;

        } else {
            if(cLog) console.log(`Chart data not received.`);
            $('#tradeChart .loading').html('');
        }
    });
}
var customLineLoop={};
// Trade - Advanced Chart
function aChartLoader(targetSymbol) {
    // APP.selectedSymbol = targetSymbol;
    let datafeed = new Datafeeds.UDFCompatibleDatafeed(APP.udf, 100 , {
        maxResponseLength: 10000000,
        expectedOrder: 'latestFirst',
        //expectedOrder: 'earliestFirst',
    });
    aChart = new TradingView.widget({
        auto_save_delay: 5,
        width: capsuleWidth(),
        height: capsuleHeight(),
        debug: !!(cLog),
        fullscreen: false,
        symbol: targetSymbol,
        interval: '1000',
        container: "tv_chart_container",
        theme: (localStorage.FinappDarkmode==='1') ? "dark" : "light",
        datafeed: datafeed,
        library_path: "achart/charting_library/",
        locale: "en",
        disabled_features: [
            'widget_logo',
            'border_around_the_chart'
        ],
        //timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
        timezone: 'exchange',
        time_frames: [
            { text: "1y", resolution: "1D", description: "1 Year" },
            { text: "1m", resolution: "60", description: "1 Month" },
            { text: "1w", resolution: "5", description: "1 Week" },
            { text: "1d", resolution: "1", description: "1 Day" },
        ],
        custom_css_url: '../../webapp/assets/css/achart.css'
    });

    aChart.headerReady().then(function() {
        aChartLineAsk = '';
        aCharLinePositions = {};
        aCharLinePendings = {};

        let buttonBUY = aChart.createButton();
        buttonBUY.setAttribute('class','do-trade do-buy');
        buttonBUY.setAttribute('title', 'Buy');
        buttonBUY.addEventListener('click', function() {
            doTrade(targetSymbol, 'Buy');
            $('#tradeForm').modal('toggle');
        });
        buttonBUY.textContent = LanguageT.Buy;

        let buttonSELL = aChart.createButton();
        buttonSELL.setAttribute('class','do-trade do-sell');
        buttonSELL.setAttribute('title', 'Sell');
        buttonSELL.addEventListener('click', function() {
            doTrade(targetSymbol, 'Sell');
            $('#tradeForm').modal('toggle');
        });
        buttonSELL.textContent = LanguageT.Sell;

        aChart.activeChart().onSymbolChanged().subscribe(
            null,
            () => {
                targetSymbol = aChart.activeChart().symbolExt().symbol;
                APP.selectedSymbol = targetSymbol;
                if(cLog) console.log(`The symbol is changed: ${targetSymbol}`)
                setTimeout(()=>{
                    /**
                     * Custom Lines
                     */
                    customLineLoop = setInterval(()=>{
                        if(aChartLineAsk?.length>0){
                            try {
                                if(procMarketSymbols.hasOwnProperty(targetSymbol)){
                                    aChart.activeChart()?.getShapeById(aChartLineAsk).changePoint({price: parseFloat(procMarketSymbols[targetSymbol].Ask)},0);
                                }
                            } catch(err) {
                                if(cLog) console.info(err);
                                clearInterval(customLineLoop);
                            }
                        }
                        else {
                            if('activeChart' in aChart){
                                try {
                                    aChartLineAsk = aChart.activeChart().createShape({ price: 0 }, {
                                        lock:true,
                                        disableSave:true,
                                        disableUndo: true,
                                        disableSelection:true,
                                        showInObjectsTree:false,
                                        overrides: {
                                            linecolor: 'rgba(128,0,0,0.9)',
                                            linewidth: 1.0,
                                            linestyle: 0,
                                            showPrice: true,
                                            showLabel: true,
                                            textcolor: 'rgba(250,0,0,0.9)',
                                            fontsize: 12,
                                            bold: false,
                                            italic: false,
                                            horzLabelsAlign: 'right',
                                            vertLabelsAlign: 'middle'
                                        },
                                        text:'Ask',
                                        filled:1,
                                        shape: 'horizontal_line'
                                    });
                                } catch(err) {
                                    if(cLog) console.log(err);
                                    clearInterval(customLineLoop);
                                }
                            } else {
                                clearInterval(customLineLoop);
                            }
                        }

                        if(!$.isEmptyObject(repAccountPosition)){
                            for (const i in repAccountPosition) {
                                if(repAccountPosition[i].Symbol!==targetSymbol) continue;
                                if(!$.isEmptyObject(aCharLinePositions[repAccountPosition[i].Position])){
                                    try {
                                        aCharLinePositions[repAccountPosition[i].Position]
                                            .setReverseTooltip(`TP:${repAccountPosition[i].PriceTP} SL:${repAccountPosition[i].PriceSL}`)
                                            .setQuantity(repAccountPosition[i].Profit)
                                            .setPrice(repAccountPosition[i].PriceOpen)
                                            .setText( ((parseInt(repAccountPosition[i].Action)===1)?LanguageT.Sell:LanguageT.Buy) +" | "+repAccountPosition[i].Volume/10000)
                                            .setQuantityBackgroundColor((repAccountPosition[i].Profit>0) ? '#25d50a' : '#ff0000');
                                    } catch(err) {
                                        if(cLog) console.info(err);
                                        clearInterval(customLineLoop);
                                    }
                                }
                                else {
                                    try {
                                        const datetime = new Date( parseInt(repAccountPosition[i].TimeCreateMsc) );

                                        aCharLinePositions[repAccountPosition[i].Position] = aChart.activeChart().createPositionLine()
                                            .onModify(function() {
                                                positionDetail(repAccountPosition[i].Position);
                                                $('#positionDetail').modal('show');
                                            })
                                            .onReverse("onReverse called", function(text) {
                                                const stepDigits = 1 / Math.pow(10, repAccountPosition[i].Digits);
                                                $('#positionEdit button').attr('position', repAccountPosition[i].Position);
                                                $('#positionEdit #SL').val(repAccountPosition[i].PriceSL).attr('step',stepDigits);
                                                $('#positionEdit #TP').val(repAccountPosition[i].PriceTP).attr('step',stepDigits);
                                                $('#positionEdit').modal('show');
                                            })
                                            .onClose("onClose called", function(text) {
                                                if(getSetting('confirm4closePosition')){
                                                    getConfirmation('Close position ?', `closePosition(${repAccountPosition[i].Position})`, 'Close', 'danger');
                                                } else {
                                                    closePosition(repAccountPosition[i].Position);
                                                }
                                            })
                                            .setText( ((parseInt(repAccountPosition[i].Action)===1)?LanguageT.Sell:LanguageT.Buy) +" | "+repAccountPosition[i].Volume/10000)
                                            .setTooltip("Created on: "+ datetime.toLocaleString('sv'))
                                            .setProtectTooltip("Profit")
                                            .setCloseTooltip("Close position")
                                            .setReverseTooltip(`TP:${repAccountPosition[i].PriceTP} SL:${repAccountPosition[i].PriceSL}`)
                                            .setQuantity(repAccountPosition[i].Profit)
                                            .setPrice(repAccountPosition[i].PriceOpen)
                                            .setExtendLeft(false)
                                            .setLineStyle(0)
                                            .setDirection((parseInt(repAccountPosition[i].Action)===1)?'Sell':'Buy')
                                            .setLineLength(25)
                                            .setQuantityBackgroundColor((repAccountPosition[i].Profit>0) ? '#25d50a' : '#ff0000');
                                    }
                                    catch(err) {
                                        if(cLog) console.info(err);
                                    }
                                }
                            }
                        }

                        // PriceOrder
                        //PriceTrigger

                        if(!$.isEmptyObject(repAccountPending)){
                            for (const i in repAccountPending) {
                                if(repAccountPending[i].Symbol!==targetSymbol) continue;

                                if(!$.isEmptyObject(aCharLinePendings[repAccountPending[i].Order])){
                                    try {
                                        aCharLinePendings[repAccountPending[i].Order]
                                            .setQuantity(repAccountPending[i].VolumeCurrent/10000)
                                            .setPrice(repAccountPending[i].PriceOrder);
                                    } catch(err) {
                                        if(cLog) console.info(err);
                                        clearInterval(customLineLoop);
                                    }
                                }
                                else {
                                    try {
                                        const datetime = new Date( parseInt(repAccountPending[i].TimeSetupMsc) );

                                        aCharLinePendings[repAccountPending[i].Order] = aChart.activeChart().createOrderLine()
                                            .onMove(function() {
                                                editOrderPrice(repAccountPending[i].Order, this.getPrice())
                                            })
                                            .onModify("onModify called", function(text) {
                                                orderDetail(repAccountPending[i].Order);
                                                $('#orderDetail').modal('show');
                                            })
                                            .onCancel("onCancel called", function(text) {
                                                if(getSetting('confirm4closePosition')){
                                                    getConfirmation('Cancel order ?', `cancelOrder(${repAccountPending[i].Order})`, 'Cancel', 'danger');
                                                } else {
                                                    cancelOrder(repAccountPending[i].Order);
                                                }
                                            })
                                            .setText( tradeAction[repAccountPending[i].Type] )
                                            .setQuantity(repAccountPending[i].VolumeCurrent/10000)
                                            .setTooltip('Move To Modify')
                                            .setModifyTooltip('Order Detail')
                                            .setCancelTooltip(`Cancel Order`)
                                            .setPrice(repAccountPending[i].PriceOrder)

                                            .setExtendLeft(false)
                                            .setLineStyle(0)
                                            .setLineLength(25);
                                    } catch(err) {
                                        if(cLog) console.info(err);
                                    }
                                }
                            }
                        }

                    },1);
                },6*1000);
            },
            false
        );

    });

}


// Trade - TV Screen
function updateTvScreen(){
    if( APP.section!==`tv` ) {
        LOOPS.updateTvScreen.kill();
    } else {
        updateTvAccountSummary();
        updateTvPositions();
        updateTvOrders();
        updateTvH24();
        updateTvOperation();
    }
}
// Trade - TV Account
function updateTvAccountSummary(){
    const preLoaderSelector = `#trade-tv #tvAccountSummary .loading2`;
    if (Object.keys(repAccountSummery).length>0) {
        for (const property in repAccountSummery) {
            appUI.$.iTradeTv_accountSummary
                .find(`span#${property}`).html(repAccountSummery[property]);
        }
        liveLoader(preLoaderSelector, 'success');
    }
    else {
        liveLoader(preLoaderSelector, 'danger');
        appUI.$.iTradeTv_accountSummary
            .find(`span`).html(`<span class="text-danger">?????</span>`);
    }
}
// Trade - TV Positions
function updateTvPositions(){
    if ( appUI.$.iActivSection.find('#open-positions').is(`:visible`) ) {
        if (Object.keys(repAccountPosition).length>0) {
            const instance = $('#trade-tv #open-positions .position-temp').html();
            // Drop Closed Position
            for (const property in proAccountPositions) {
                if(repAccountPosition[property]!==undefined) {
                    dropPosition(property);
                }
            }

            for (const i in repAccountPosition) {
                const dataNew = repAccountPosition[i];
                const dataOld = proAccountPositions[repAccountPosition[i].Position];

                // Skip Not Changed Position
                if( JSON.stringify(dataNew) === JSON.stringify(dataOld) ) {
                    if(cLog) console.log(`position Skipped.`);
                    continue;
                }

                let positionRow = `#trade-tv #positions-wrapper .position-row[position="${dataNew.Position}"]`;

                // Make Row
                if(!dataOld || $(positionRow).length===0){
                    const html  = `<tr class="position-row" position="${dataNew.Position}">${instance}</tr>`;
                    $('#trade-tv #positions-wrapper').prepend(html);
                    $(`${positionRow} #symbol`).html(dataNew.Symbol);
                    if(cLog) console.log(`${dataNew.Symbol} New Row.`);
                }
                $(`${positionRow} #price-open`).html(dataNew.PriceOpen);
                $(`${positionRow} #price-current`).html(dataNew.PriceCurrent);

                $(`${positionRow} #volume`).html(parseFloat(dataNew.Volume)/10000);
                $(`${positionRow} #action`).html( (parseInt(dataNew.Action)) ? LanguageT.Sell : LanguageT.Buy );
                let profitColor = (dataNew.Profit>0) ? 'success' : 'danger';
                $(`${positionRow} #profit`).html(dataNew.Profit).attr('class',`text-${profitColor}`);
                $(`${positionRow} #sl`).html(dataNew.PriceSL);
                $(`${positionRow} #tp`).html(dataNew.PriceTP);
                $(`${positionRow} #storage`).html(dataNew.Storage);
                $(`${positionRow} .action-button button`).attr('position',dataNew.Position);

                const datetime = new Date( parseInt(dataNew.TimeCreateMsc) );
                $(`${positionRow} #time`).html(datetime.toLocaleString('sv'));

                // Trend from Market
                $(`${positionRow} #price-icon`).attr('class', `me-1 text-${procMarketSymbols[dataNew.Symbol]?.lastColor}`);
                $(`${positionRow} #price-icon`).attr('name', procMarketSymbols[dataNew.Symbol]?.lastIcon);

                // Fill Holder
                proAccountPositions[repAccountPosition[i].Position] = repAccountPosition[i];
            }
        }
        else {
            if(cLog) console.log(`Positions data not changed.`);
        }
    }
}
// Trade - TV Orders
function updateTvOrders(){
    if ( appUI.$.iActivSection.find('#pending-orders').is(`:visible`) ) {
        if (Object.keys(repAccountPending).length>0) {
            const instance = $('#trade-tv #pending-orders #order-temp').html();

            // Drop Old Orders
            for (const property in proAccountOrders) {
                if(repAccountPending[property]!==undefined) {
                    dropOrder(property);
                }
            }

            for (const i in repAccountPending) {
                const dataNew = repAccountPending[i];
                const dataOld = proAccountOrders[repAccountPending[i].Order];

                // Skip Not Changed Orders
                if( JSON.stringify(dataNew) === JSON.stringify(dataOld) ) {
                    if(cLog) console.log(`Order Skipped.`);
                    continue;
                }

                let orderRow = `#trade-tv #orders-wrapper .order-row[order="${dataNew.Order}"]`;

                // Make Row
                if(!dataOld || $(orderRow).length===0){
                    const html  = `<tr class="order-row" order="${dataNew.Order}">${instance}</tr>`;
                    $('#trade-tv #orders-wrapper').prepend(html);
                    $(`${orderRow} #symbol`).html(dataNew.Symbol);
                    if(cLog) console.log(`${dataNew.Symbol} New Row.`);
                }
                $(`${orderRow} #price-current`).html(dataNew.PriceCurrent);

                $(`${orderRow} #volume`).html(parseFloat(dataNew.VolumeCurrent)/10000);

                $(`${orderRow} #action`).html(tradeAction[dataNew.Type]);
                $(`${orderRow} #price-order`).html(dataNew.PriceOrder);
                $(`${orderRow} #price-trigger`).html(dataNew.PriceTrigger);
                $(`${orderRow} #sl`).html(dataNew.PriceSL);
                $(`${orderRow} #tp`).html(dataNew.PriceTP);
                $(`${orderRow} .action-button button`).attr('order',dataNew.Order);

                // Fill Holder
                proAccountOrders[repAccountPending[i].Order] = repAccountPending[i];
            }
        }
        else {
            if(cLog) console.log(`Positions data not changed.`);
        }
    }
}
// Trade - TV H24
function updateTvH24() {
    if ( appUI.$.iActivSection.find('#account-history').is(`:visible`) ){
        if (Object.keys(repAccountHistory).length>0) {
            const instance = $('#trade-tv #deal-temp').html();

            // Drop Old Deals
            for (const property in proAccountDeals) {
                if(repAccountHistory[property]!==undefined) {
                    dropDeal(property);
                }
            }
            $('#trade-tv #deals-wrapper').html('');

            for (const i in repAccountHistory) {
                // Skip start items
                if( repAccountHistory[i].Entry === '0' ) {
                    if(cLog) console.log(`Start Order/Deals Skipped.`);
                    continue;
                }

                const dataNew = repAccountHistory[i];
                const dataOld = proAccountDeals[repAccountHistory[i].Order];

                let dealRow = `#trade-tv #deals-wrapper .deal-row[deal="${dataNew.Deal}"]`;

                // Skip Not Changed Deals
                if( JSON.stringify(dataNew) === JSON.stringify(dataOld) ) {
                    if(cLog) console.log(`Deals Skipped.`);
                    continue;
                }

                // Make Row
                if(!dataOld || $(dealRow).length===0){
                    const html  = `<tr class="deal-row" deal="${dataNew.Deal}">${instance}</tr>`;
                    $('#trade-tv #deals-wrapper').prepend(html);
                    $(`${dealRow} #symbol`).html(dataNew.Symbol);

                    for (const j in repAccountHistory) {
                        if( repAccountHistory[j].Entry === '1' ) {
                            continue;
                        }
                        if(repAccountHistory[j].Order === dataNew.PositionID){
                            const datetimeOpen = new Date( parseInt(repAccountHistory[j].TimeMsc)-APP.serverTimeZoneOffset );
                            $(`${dealRow} #time-open`).html(datetimeOpen.toLocaleString('sv'));
                            $(`${dealRow} #price-open`).html(repAccountHistory[j].Price);
                            $(`${dealRow} #action`).html(tradeAction[repAccountHistory[j].Action]);
                        }
                    }

                    const datetimeClose = new Date( parseInt(dataNew.TimeMsc)-APP.serverTimeZoneOffset );
                    $(`${dealRow} #time-close`).html(datetimeClose.toLocaleString('sv'));
                    if(cLog) console.log(`${dataNew.Symbol} New Row.`);
                }
                $(`${dealRow} #price-close`).html(dataNew.Price);
                let profitColor = (dataNew.Profit>0) ? 'success' : 'danger';
                $(`${dealRow} #profit`).html(dataNew.Profit).attr('class',`text-${profitColor}`);
                $(`${dealRow} #volume`).html(parseFloat(dataNew.Volume)/10000);
                $(`${dealRow} #storage`).html(dataNew.Storage);
                $(`${dealRow} #sl`).html(dataNew.PriceSL);
                $(`${dealRow} #tp`).html(dataNew.PriceTP);

                // Fill Holder
                proAccountDeals[repAccountHistory[i].Deal] = repAccountHistory[i];
            }
        } else {
            if(cLog) console.log(`Deals data not changed.`);
            let html = '<tr>There is not any deal in the last 24H.</tr>'
            appUI.$.iActivSection.find('#deals-wrapper').html(html);
        }
    }
}
// Trade - TV Operation
function updateTvOperation() {
    if ( appUI.$.iActivSection.find('#balance-operations').is(`:visible`) ) {
        if (Object.keys(repAccountHistory).length>0) {
            const instance = appUI.$.iActivSection.find('#operation-temp').html();

            // Drop Old Operations
            for (const property in proAccountOperations) {
                if(repAccountHistory[property]!==undefined) {
                    dropOperation(property);
                }
            }

            for (const i in repAccountHistory) {
                // Skip other Operations
                if( repAccountHistory[i].Action !== '2' &&  repAccountHistory[i].Action !== '6') {
                    if(cLog) console.log(`Order/Deals Skipped.`);
                    continue;
                }

                const dataNew = repAccountHistory[i];
                const dataOld = proAccountOperations[repAccountHistory[i].Order];

                let operationRow = `#trade-tv #operations-wrapper .operation-row[deal="${dataNew.Deal}"]`;

                // Skip Not Changed Deals
                if( JSON.stringify(dataNew) === JSON.stringify(dataOld) ) {
                    if(cLog) console.log(`Operation Skipped.`);
                    continue;
                }

                // Make Row
                if(!dataOld || $(operationRow).length===0){
                    const html  = `<tr class="operation-row" deal="${dataNew.Deal}">${instance}</tr>`;
                    appUI.$.iActivSection.find('#operations-wrapper').prepend(html);

                    const datetime = new Date( parseInt(dataNew.TimeMsc)-APP.serverTimeZoneOffset );
                    $(`${operationRow} #time`).html(datetime.toLocaleString('sv'));
                    if(dataNew.Profit>0){
                        $(`${operationRow} #type`).html('Deposit').addClass('text-success');
                        $(`${operationRow} #amount`).html('+'+dataNew.Profit).addClass('text-success');
                    } else {
                        $(`${operationRow} #type`).html('Withdrawals').addClass('text-danger');;
                        $(`${operationRow} #amount`).html(dataNew.Profit).addClass('text-danger');
                    }
                    $(`${operationRow} #comment`).html(dataNew.Comment);

                    if(cLog) console.log(`${dataNew.Symbol} New Row.`);
                }

                // Fill Holder
                proAccountOperations[repAccountHistory[i].Deal] = repAccountHistory[i];
            }
        }
        else {
            if(cLog) console.log(`Operation data not changed.`);
            let html = '<tr>There is not any Operation in the selected account.</tr>'
            appUI.$.iActivSection.find('#operations-wrapper').html(html);
        }
    }
}
// Trade - TV Left Chart
function tvLeftChartLoader(targetSymbol) {
    let datafeedUrl = APP.udf;
    tvLeftChart = new TradingView.widget({
        auto_save_delay: 15,
        width: tvChartWidth(),
        // height: 500,
        // width: "100%",
        // height: document.getElementById('tradingview_container').offsetHeight,
        autosize: true,
        debug: !!(cLog),
        fullscreen: false,
        symbol: targetSymbol,
        interval: '1',
        container: "left-chart",
        theme: (localStorage.FinappDarkmode==='1') ? "dark" : "light",
        datafeed: new Datafeeds.UDFCompatibleDatafeed(datafeedUrl, 10000 , {
            maxResponseLength: 10000000,
            expectedOrder: 'latestFirst',
        }),
        library_path: "achart/charting_library/",
        locale: "en",
        disabled_features: [
            'widget_logo',
            'border_around_the_chart'
        ],
        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
        time_frames: [
            { text: "1y", resolution: "1D", description: "1 Year" },
            { text: "1m", resolution: "60", description: "1 Month" },
            { text: "1w", resolution: "5", description: "1 Week" },
            { text: "1d", resolution: "1", description: "1 Day" },
        ],
        custom_css_url: '../../webapp/assets/css/achart.css'
    });
    tvLeftChart.headerReady().then(function() {
        tvLeftChart.activeChart().onSymbolChanged().subscribe(null, () =>
            localStorage.tvLeftChart   = tvLeftChart.activeChart().symbolExt().symbol
        );
    });
}
// Trade - TV Right Chart
function tvRightChartLoader(targetSymbol) {
    return 1;
    let datafeedUrl = APP.udf;
    tvRightChart = new TradingView.widget({
        auto_save_delay: 15,
        width: tvChartWidth(),
        // height: 500,
        // width: "100%",
        // height: document.getElementById('tradingview_container').offsetHeight,
        autosize: true,
        debug: !!(cLog),
        fullscreen: false,
        symbol: targetSymbol,
        interval: '1',
        container: "right-chart",
        theme: (localStorage.FinappDarkmode==='1') ? "dark" : "light",
        datafeed: new Datafeeds.UDFCompatibleDatafeed(datafeedUrl, 10000 , {
            maxResponseLength: 10000000,
            expectedOrder: 'latestFirst',
        }),
        library_path: "achart/charting_library/",
        locale: "en",
        disabled_features: [
            'widget_logo',
            'border_around_the_chart'
        ],
        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
        time_frames: [
            { text: "1y", resolution: "1D", description: "1 Year" },
            { text: "1m", resolution: "60", description: "1 Month" },
            { text: "1w", resolution: "5", description: "1 Week" },
            { text: "1d", resolution: "1", description: "1 Day" },
        ],
        custom_css_url: '../../webapp/assets/css/achart.css'
    });
    tvRightChart.headerReady().then(function() {
        tvRightChart.activeChart().onSymbolChanged().subscribe(null, () =>
            localStorage.tvRightChart   = tvRightChart.activeChart().symbolExt().symbol
        );
    });
}

// Trade - Order Form
function doTrade(symbol, action, size='0.01'){
    APP.selectedSymbol = symbol;
    if(cLog) console.log(action,symbol,size);
    $('#tradeForm form').trigger("reset");
    $('#tradeForm #advanced-order').hide();
    $('#tradeForm .advance-mode').val(`Market`);
    $('#tradeForm #trade-action').show();
    $('#tradeForm .modal-title').html(symbol);
    $('#tradeForm #volume').val(size);
    let actionColor = (action==='Buy') ? 'success' : 'danger';
    $('#tradeForm #trade-action').html(LanguageT[action]).attr({
        class : `btn btn-${actionColor} btn-block btn-lg`,
        acttype : action
    });
    const stepDigits = 1 / Math.pow(10, procMarketSymbols[symbol].Digits);
    let actionPrice = (action==='Buy') ? 'BidLow' : 'AskHigh';
    $('#tradeForm #PriceOrder').attr({
        step : stepDigits
    }).val( procMarketSymbols[symbol][actionPrice] );
    setFocusBar(true);
}
// Trade - Check TP
function checkTp(sType,price, takeProfit){
    let disorder=0;
    if(parseFloat(price)>0){
        if(tradeActionsBuy.includes(sType)){
            disorder = (parseFloat(takeProfit)>parseFloat(price)) ? 0 : 1;
        }
        else if(tradeActionsSell.includes(sType)) {
            disorder = (parseFloat(takeProfit)<parseFloat(price)) ? 0 : 1;
        }
        else {
            disorder = 2;
            flyNotify('danger',LanguageT.Error, LanguageT.Type_Error,'close-circle-outline');
        }
        if(disorder===1) {
            flyNotify('danger',LanguageT.Error, LanguageT.TP_out_range,'close-circle-outline');
        }
    }
    else {
        disorder = 1;
    }
    return disorder;
}
// Trade - Check SL
function checkSl(sType, price, stopLoss){
    let disorder=0;
    if(parseFloat(price)>0){
        if(tradeActionsBuy.includes(sType)){
            disorder = (parseFloat(stopLoss)<parseFloat(price)) ? 0 : 1;
        }
        else if(tradeActionsSell.includes(sType)) {
            disorder = (parseFloat(stopLoss)>parseFloat(price)) ? 0 : 1;
        }
        else {
            disorder = 2;
            flyNotify('danger',LanguageT.Error, LanguageT.Type_Error,'close-circle-outline');
        }
        if(disorder===1) {
            flyNotify('danger',LanguageT.Error, LanguageT.SL_out_range,'close-circle-outline');
        }
    }
    else {
        disorder = 1;
    }
    return disorder;
}

// Transaction - Request
function requestTransaction(data) {
    socket.emit("crmTransactionRequest", data, (response) => {
        if ( response.hasOwnProperty(`e`) ) {
            flyNotify('danger',LanguageT.Error, response.e,'close-circle-outline');
        } else {
            flyNotify('success',LanguageT.Successful, LanguageT.request_submitted,'checkmark-circle-outline');
            setTimeout(()=>{
                changeScreen(APP.screen, APP.section, APP.screenParams);
            }, 2500);
        }
    });
}
// Transaction - Cancel
function cancelTransaction(tid) {
    const data = {
        session:  APP.client.session,
        transaction_id:   tid
    };
    socket.emit("crmTransactionCancel", data, (response) => {
        if ( response.hasOwnProperty(`e`) ) {
            flyNotify('danger',LanguageT.Error, response.e,'close-circle-outline');
        } else {
            flyNotify('success',LanguageT.Successful, LanguageT.request_canceled,'checkmark-circle-outline');
            setTimeout(()=>{
                changeScreen(APP.screen, APP.section, APP.screenParams);
            }, 2500);
        }
    });
}
// Transaction - History
function historyTransaction() {
    const data = {
        session:  APP.client.session
    };
    socket.emit("crmTransactionsHistory", data, (response) => {
        if ( response.hasOwnProperty(`e`) ) {
            flyNotify('danger',LanguageT.Error, response.e,'close-circle-outline');
        } else {
            let htmlStage = $('#transaction-history .transactions');
            for (const i in response) {
                let html = '';
                let icon = '';
                let color = '';
                let sign = '';
                if(response[i].type==='Deposit'){
                    icon = 'add-outline';
                    color = 'success';
                    sign='+ $';
                }
                else {
                    icon = 'arrow-down-outline';
                    color = 'danger';
                    sign='- $';
                }
                html = `
                            <div class="item">
                                <div class="detail">
                                    <div class="in">
                                        <div class="border border-right me-3 rounded-circle text-${color}">
                                            <ion-icon name="${icon}" size="large"></ion-icon>
                                        </div>
                                    </div>
                                    <div>
                                        <strong id="Type">${response[i].type}</strong>
                                        <p><span id="time">${response[i].created_at}</span></p>
                                    </div>
                                </div>
                                <div class="right">
                                    <div class="price text-${color}">${sign} ${response[i].amount}</div>
                                    <div id="status">${response[i].status}</div>
                                </div>
                            </div>
                `;
                htmlStage.append(html);
            }
        }
    });
}


/**
 * APP UI
 */
// APP UI | Change Screen
function changeScreen(screen, section, params={}){
    if( reloadNeed.has(APP.section) )   // Force Reload
    {
        if(APP.screen !== 'guest'){
            localStorage.appScreen = screen;
            localStorage.appSection = section;
            localStorage.appScreenParams = params;
        }
        reloadPage();
    }
    else {
        window.scrollTo({ top: 0, behavior: 'smooth' });
        const data = {
            session:  APP.client.session,
            screen:   screen,
            section:  section,
            params:   params,
            version:  APP.version
        }
        socket.emit("getScreen", data, (response) => {
            if  ( response.hasOwnProperty(`e`) ) {
                if(response.e ==='Need to login first!'){
                    changeSection(APP.screen, APP.section);
                } else {
                    setTimeout(()=>{
                        flyNotify('danger',LanguageT.Error, response.e,'close-circle-outline');
                    },50);
                }
            }
            else{
                changeSection(screen, section, params, response);
            }
        });
    }
}
// APP UI | Change Section
function changeSection(screen, section, params={}, html=null ){
    if(cLog) console.log({"Screen": screen, "Section":section, "Params":params});

    if( reloadNeed.has(APP.section) )   // Force Reload
    {
        if(APP.screen !== 'guest'){
            localStorage.appScreen = screen;
            localStorage.appSection = section;
            localStorage.appScreenParams = params;
        }
        reloadPage();
    }
    else {
        APP.screen       = screen;
        APP.section      = section;
        APP.screenParams = params;

        document.title = sectionTitle[APP.screen][APP.section];
        if(html !== null)   // Update APP UI
        {
            appUI.$.iScreenWrapper.html(html);
        }
        appUI.$.iActivSection = appUI.$.iScreenWrapper.find(`section#${screen}-${section}`);

        // Hide All Panels / Sections
        appUI.$.cModal.not('#PanelAppSettings').modal('hide');
        appUI.$.iScreenWrapper.find('section').addClass('d-hide');

        // Show Active Section
        appUI.$.iActivSection.removeClass('d-hide');

        if(screen==='guest')
        {
            appUI.$.iAppFooter.hide();
            appUI.$.iAppHeader.hide();
            if(section==='login'){
                APP.client.id = localStorage?.clientId;
                if(APP.client.id>0){
                    const data = {
                        session:  APP.client.session,
                        userId:  APP.client.id
                    }
                    socket.emit("crmReLogin", data, (response) => {
                        if ( response.hasOwnProperty(`e`) ) {
                            flyNotify('danger',LanguageT.Error, response.e,'close-circle-outline');
                        }
                        else{
                            flyNotify('success',LanguageT.Successful, LanguageT.submit_login_success,'checkmark-circle-outline');
                            setTimeout(()=>{
                                location.reload();
                            }, 2000);
                        }
                    });
                }
            }
            else if(section==='register'){
                if(APP.client.id>0){
                    appUI.$.iDoLogout.trigger('click');
                }
            }
            else if(section==='recovery'){

            }
        }
        else {
            appUI.$.iAppFooter.fadeIn();
            appUI.$.iAppHeader.fadeIn();
            // Update Alt Header / Footer
            altHeaderFloat(getSetting('floatHeader'));
            altFooterFloat(getSetting('floatFooter'));

            if(screen==='home')
            {
                if(section==='start'){
                    if(getSetting('autoSizing')){
                        if(!window.matchMedia("(max-width: 767px)").matches){
                            screenTv();
                        }
                    }
                }
            }
            else if(screen==='user')
            {
                if(section==='logout'){
                    if(getSetting('confirm4logout')){
                        getConfirmation('Log Out ?', `logout()`, 'Yes', 'danger');
                    } else {
                        logout();
                    }
                }
            }
            else if(screen==='transaction')
            {
                if( forceToSelectAccount() ){
                    if(section==='deposit'){

                    }
                    if(section==='history'){
                        historyTransaction();
                    }
                }
            }
            else if(screen==='chart')
            {
                if( forceToSelectAccount(true) ){
                    if(section==='achart'){
                        APP.selectedSymbol = (APP.selectedSymbol || localStorage.selectedSymbol) || 'EURUSD';
                        LOOPS.syncRepAccountPosition.start();
                        LOOPS.syncRepAccountPending.start();
                        setTimeout(()=>LOOPS.updateAChartSymbol.start(),
                            (repMarketSymbols.hasOwnProperty(`mix`)) ? 1050 : 50);
                        aChartLoader(APP.selectedSymbol);
                    }
                }
            }
            else if(screen==='trade')
            {
                // UI
                $("body").css("overflow", "auto");

                // Clear Holders
                procMarketSymbols={};
                proAccountPositions={};
                proAccountOrders={};
                proAccountDeals={};
                proAccountOperations={};

                if( forceToSelectAccount() )
                {
                    // Trade Sections
                    if(section==='accounts'){
                        appUI.$.iActivSection
                            .find(`.do-selectTPA`).removeClass('disabled').html('Select').end()
                            .find(`#clearSelectedAccount`).removeClass('d-hide').end()
                            .find(`.do-selectTPA[account=${APP.selectedAccount}]`)
                            .addClass('disabled').html('Selected');
                    }
                    else if(section==='positions'){
                        LOOPS.syncRepAccountPosition.start();

                        ////// !~~~ Need Fix to only use active section  //////////////////////////////
                        appUI.$.iTradePositions         = $('#trade-positions');
                        appUI.$.iTradePositions_wrapper = $('#trade-positions #positions-wrapper');
                        appUI.$.iTradePositions_temp    = $('#trade-positions #position-temp').html();
                        appUI.$.iTradePositions_wrapper.html('');

                        setTimeout(THROTTLES.updateOpenPositions, 50);
                    }
                    else if(section==='pending'){
                        LOOPS.syncRepAccountPending.start();

                        ////// !~~~ Need Fix to only use active section  //////////////////////////////
                        appUI.$.iTradePendings         = $('#trade-pending');
                        appUI.$.iTradePendings_wrapper = $('#trade-pending #orders-wrapper');
                        appUI.$.iTradePendings_temp    = $('#trade-pending #order-temp').html();
                        appUI.$.iTradePendings_wrapper.html('');

                        setTimeout(THROTTLES.updatePendingOrders, 50);
                    }
                    else if(section==='history'){

                        ////// !~~~ Need Fix UI
                        ////// !~~~ Need Fix Temp From Main

                    }
                    else if(section==='operation'){

                        ////// !~~~ Need Fix UI
                        ////// !~~~ Need Fix Temp From Main

                    }
                    else if(section==='market'){
                        last7day = Math.round((Date.now() / 1000 )-604800 );

                        // Clear Symbols Market UI
                        uiRenderedSymbols.clear();

                        ////// !~~~ Need Fix to only use active section  //////////////////////////////
                        appUI.$.iTradeMarket                = $('#trade-market');
                        appUI.$.iTradeMarket_wrapper        = $('#trade-market #symbols-wrapper');
                        appUI.$.hSymbolRows                 = {};

                        setTimeout(THROTTLES.creatMarketSymbols,
                            (repMarketSymbols.hasOwnProperty(`mix`)) ? 1050 : 50);

                        // Filter Listener
                        document.getElementById("filter-symbol")
                            .addEventListener("search", function(event) {
                                showSymbolsRows();
                            });

                        // Watchlist
                        watchlistDefine();
                    }
                    else if(section==='tv'){
                        last7day = Math.round((Date.now() / 1000 )-604800 );

                        // UI
                        $("body").css("overflow", "hidden");
                        appUI.$.cNotification.css({
                            width:350,
                            margin:`auto`
                        });

                       // altHeaderFloat(1);
                       // altFooterFloat(1);
                        $('#app-footer-alt, #app-footer').hide();

                        // Clear Symbols TV UI
                        uiRenderedSymbols.clear();

                        // View
                        Scrollbar.initAll();

                        ////// !~~~ Need Fix to only use active section  //////////////////////////////
                        appUI.$.iTradeTv                = $('#trade-tv');
                        appUI.$.iTradeTv_updateTime     = $('#trade-tv #updateTime');
                        appUI.$.iTradeTv_symbolsWrapper = $('#trade-tv #symbols-wrapper');
                        appUI.$.iTradeTv_accountSummary = $('#trade-tv #tvAccountSummary');
                        appUI.$.hSymbolRows             = {};


                        setTimeout(THROTTLES.creatTvSymbols,
                            (repMarketSymbols.hasOwnProperty(`mix`)) ? 1050 : 50);

                        // Loops
                        LOOPS.syncRepAccountSummery.restart({delay:50});
                        LOOPS.syncRepAccountPosition.start({delay:250});
                        LOOPS.syncRepAccountPending.restartDelay({delay:400});
                        LOOPS.syncRepAccountH24.restartDelay({delay:500});

                        // Tv Screen
                        LOOPS.updateTvScreen.restart({delay:1050});
                        setTimeout(()=>{


                            // Charts
                            const chartLeftSymbol = localStorage?.tvLeftChart || watchlistTop[0];
                            const chartRightSymbol = localStorage?.tvRightChart || watchlistTop[1];
                            tvLeftChartLoader(chartLeftSymbol);
                            tvRightChartLoader(chartRightSymbol);
                        }, 3500);

                        // Watchlist
                        watchlistDefine();
                    }

                }
            }

            localStorage.appScreen = APP.screen;
            localStorage.appSection = APP.section;
            localStorage.appScreenParams = APP.screenParams;

        }
    }
}

/**
 * APP Tools
 */
// APP Tools - Clear Repository
function clearRepository(){
    repAccountSummery={};
    repAccountPosition={};
    repAccountPending={};
    repAccountHistory={};
}
// APP Tools - Check Heap Size | --- Mobile unstable
function checkHeapSize(){
    if( performance.memory.usedJSHeapSize > APP.maxHeapSize ){
        reloadPage();
    }
}
// APP Tools - Countries List
function countriesList(updateUl = true) {
    socket.emit("listCountries", (res) => {
        if(res)
        {
            countriesLib = res;
            if(updateUl) {
                let html = '';
                for(let key in res) {
                    html += `<li class="dropdown-item" data-country="${key}">${res[key].flag}<span>${res[key].country}</span></li>`;
                }
                $('.countries-list').html(html);
            }
            return countriesLib;
        } else {
            if(cLog) console.log(res);
        }
    });
}
// APP Tools - Fly Notify
function flyNotify(type, title, body, icon=null, time=null, timer=10000){
    $('#notification-main #type').attr('class',`notification-dialog ios-style bg-${type}`)
    if(icon)
        $('#notification-main #icon').attr('name', icon)
    $('#notification-main #title').html(title);
    $('#notification-main #body').html(body);
    if(time)
        $('#notification-main #time').html(time);
    notification('notification-main', timer)
}
// APP Tools - PreLoader
function preLoader(target, type='secondary'){
    const oldHtml = $(target).html();
    const html =  `<div class="spinner-border spinner-border-sm text-${type}" role="status"></div>`;
    setTimeout(()=>{
        $(target).html(html);
    },50);
    return oldHtml;
}
// APP Tools - Live Loader
function liveLoader(target, type='secondary'){
    const oldHtml = $(target).html();
    const html =  `<div class="spinner-grow spinner-grow-xs text-${type}" role="status"></div>`;
    setTimeout(()=>{
        $(target).html(html);
    },50);
    return oldHtml;
}
// App Tools - Calculate App Capsule Height
function capsuleHeight(){
    let headerHeight = document.getElementById('app-header').offsetHeight;
    let footerHeight = document.getElementById('app-footer').offsetHeight;
    if(screen.height>900) headerHeight += 120;
    return screen.height - (headerHeight+footerHeight);
}
// App Tools - Calculate App Capsule Width
function capsuleWidth(){
    return screen.width;
}
// App Tools - Calculafte TV Chart Width
function tvChartWidth(){
    return $('#tv-chart #right-chart').width();
}
// APP Tools - Confirmation
function getConfirmation(title, action, actionKey=LanguageT.YES, type='danger') {
    $('#DialogConfirmation .do-confirm').attr('onClick', action);
    $('#DialogConfirmation .modal-title').html(title);
    $('#DialogConfirmation .do-confirm span').attr('class', 'text-'+type);
    $('#DialogConfirmation .do-confirm span').html(actionKey);
    $('#DialogConfirmation').modal('show');
}
//  APP Tools - Get Base64
function getBase64(file, callback) {
    let reader = new FileReader();
    reader.onload = function () {
        callback(reader.result);
    };
    reader.onerror = function (error) {
        if(cLog) console.log('Error: ', error);
    };
    reader.readAsDataURL(file);
}
// APP Tools - Today
function getToday(end=false) {
    var today = new Date();
    var tomorrow = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();
    var tomday = tomorrow.getDate();
    var tommonth = tomorrow.getMonth() + 1;
    var tomyear = tomorrow.getFullYear();
    if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = yyyy+'-'+mm+'-'+dd;
    if(tomday<10){tomday='0'+tomday} if(tommonth<10){tommonth='0'+tommonth} tomorrow = tomyear+'-'+tommonth+'-'+tomday;
    return (end) ? tomorrow : today;
}

/**
 * Focus Bar
 */
// Focus Bar | Update
function updateFocusBar(){
    appUI.$.iFocusBar_spread
        .html( procMarketSymbols[ APP.selectedSymbol ].Spread.toFixed(5) )
        .removeClass()
        .addClass( `text-${procMarketSymbols[ APP.selectedSymbol ].spreadColor}` );

    appUI.$.iFocusBar_ask
        .html( procMarketSymbols[ APP.selectedSymbol ].Ask )
        .removeClass()
        .addClass( `text-${procMarketSymbols[ APP.selectedSymbol ].askColor}` );

    appUI.$.iFocusBar_bid
        .html( procMarketSymbols[ APP.selectedSymbol ].Bid )
        .removeClass()
        .addClass( `text-${procMarketSymbols[ APP.selectedSymbol ].bidColor}` );
}
// Focus Bar | Set
function setFocusBar(show){
    if(show){
        LOOPS.updateFocusBar.start();
        appUI.$.iFocusBar.fadeIn();
    } else {
        LOOPS.updateFocusBar.stop();
        appUI.$.iFocusBar.hide();
        appUI.$.iFocusBar_ask.html( `. . .` ).removeClass();
        appUI.$.iFocusBar_bid.html( `. . .` ).removeClass();
        appUI.$.iFocusBar_spread.html( `. . .` ).removeClass();
    }
}


// Dev - Clear Cache
function clearCache(){
    localStorage.clear();
    reloadPage();
}
// Dev - Reload Screen
function reloadScreen(){
    changeScreen(APP.screen, APP.section);
}
// Dev - Reload Page
function reloadPage(){
    window.location.reload();
}
// Dev - Force Reload Page
function forceReloadPage(){
    socket.emit("forceReloadPage");
}
// Update Ready
function UpdateReady(version) {
    flyNotify('info',LanguageT.Notice, LanguageT.update_ready_alert,'information-circle-outline',null,1000000);
}
// Alert Market Time
function symbolTimeError(){
    flyNotify('danger','Market Time', LanguageT.SymbolClosed,'close-circle-outline');
}

/**
 * Tuner
 */
// Tuner | Debounce - run one time with a delay from the last call
function debounce(func, delay) {
    let timeout;
    return function(arg=null) {
        clearTimeout(timeout);
        timeout = setTimeout(()=>{func(arg)}, delay);
    };
}
// Tuner | Throttle - run one time with a delay from the first call
function throttle(func, limit) {
    let throttling = false;
    return function(arg=null) {
        if (!throttling) {
            func(arg);
            throttling = true;
            setTimeout(() => throttling = false, limit);
        }
    };
}

/** Test */
// Dev - Force Reload Page
function symbolTimeCheck(symbol){
    let data =
        {
            symbol: symbol
        };
    socket.emit("s0SymbolTime",data, (response) => {
        if(!response){
            symbolTimeError()
        }
        return response;
    });
}

function appAjax(callFunction, data=null, callback) {
    $.ajax({
        type: "POST",
        url: `lib/ajax.php?c=webapp&f=${callFunction}&t=${APP.client['token']}`,
        data: data,
        cache: false,
        global: false,
        contentType: false,
        processData: false,
        enctype: 'multipart/form-data',
        dataType: "json",
        async: true,
        success: callback,
        error: function(request, status, error) {
            console.log(error);
        }
    });
}

/** Agreement **/
function ladTermsFile(){
    $('#agreement-wrapper div#agreement').load(APP.agreement);
}
function acceptAgreement(){
    $.ajax({
        type: "POST",
        url: `lib/ajax.php?c=global&f=agreeTerms&t=${APP.client['token']}`,
        data: null,
        cache: false,
        global: false,
        contentType: false,
        processData: false,
        enctype: 'multipart/form-data',
        dataType: "json",
        async: true,
        success: function(response){
            if(response.res) reloadPage();
        },
        error: function(request, status, error) {
            console.log(error);
        }
    });
}