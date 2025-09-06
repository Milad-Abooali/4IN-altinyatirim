var pingServers = {};

/**
 * Socket WebApp
 */
var pingSocketServer = 0;
var SocketConnectionCounter = 0;
const socket = io(APP.socket, {
    'reconnection': true,
    'reconnectionDelay': 3000,
    'reconnectionAttempts': 1000,
    'rejectUnauthorized': false
});
socket.on("connect", () => {
    socket.sendBuffer = [];
    if(cLog) console.log('Socket > connect');
    updateCoreStatus('socket', 1);
    SocketConnectionCounter = 0;
    $('div#socket-reconnecting #error-text').html('');
    $('div#socket-reconnecting').fadeOut();
    initial();
    if(APP.pingServersCycel>0){
        pingServers.socket = setInterval(() => {
            const start = Date.now();
            socket.emit("ping", () => {
                pingSocketServer = Date.now() - start;
                if(cLog) console.log(`Ping Socket`, pingSocketServer);
            });
        }, APP.pingServersCycel*1000);
    }
});
socket.on("disconnect", () => {
    if(APP.pingServersCycel>0){
        clearInterval(pingServers.socket);
    }
    socket.sendBuffer = [];
    updateCoreStatus('socket', -1);
    if(cLog) console.log('Socket > disconnect');
    //initial();
});
socket.on('connect_error', (e) => {
    updateCoreStatus('socket', 0);
    if(cLog) console.log('Socket > connect_error', e);
    SocketConnectionCounter++;
    if(SocketConnectionCounter===5){
        $('div#socket-reconnecting #error-text').html(e);
        $('div#socket-reconnecting').fadeIn();
    }
});
socket.on('syncRoles', (response) => {
    if(cLog) console.log('Socket > syncRoles', response);
    let list='';
    for (const row in response) {
        const [key, value] = row;
        list += `<li><strong>${value}</strong> ${key}</li>`
    }
    $('#PanelDev #roles').html(list);
    const total = Object.values(response).reduce((a, b) => a + b, 0);
    $('#PanelDev #online-total').html(total);
});
socket.on('reloadPage', () => {
    reloadPage();
});
socket.on('UpdateReady', (response) => {
    UpdateReady(response);
});
socket.on('symbolTimeClosed', (response) => {
    symbolTimeError();
});


/**
 * Feed
 */
var pingFeedServer = 0;
var feedConnectionCounter = 0;
const feed = io(APP.feed, {
    'timeout': 60000,
    'reconnection': true,
    'reconnectionDelay': 10000,
    'reconnectionAttempts': 1000,
    'rejectUnauthorized': false
});
feed.on("connect", () => {
    feed.sendBuffer = [];
    if(cLog) console.log('Feed > connect');
    updateCoreStatus('feed', 1);
    feedConnectionCounter = 0;
    $('div#socket-reconnecting #error-text').html('');
    $('div#socket-reconnecting').fadeOut();
    metaMarketEmit_GetLoginData();
if(APP.pingServersCycel>0){
    pingServers.feed = setInterval(() => {
        const start = Date.now();
        feed.emit("ping", () => {
            pingFeedServer = Date.now() - start;
            if(cLog) console.log(`Ping Feed`, pingFeedServer);
        });
    }, APP.pingServersCycel*1000);
}

});
feed.on("disconnect", () => {
    if(APP.pingServersCycel>0){
        clearInterval(pingServers.feed);
    }
    feed.sendBuffer = [];
    updateCoreStatus('feed', -1);
    if(cLog) console.log('Feed > disconnect');
    //initial();
});
feed.on('connect_error', (e) => {
    updateCoreStatus('feed', 0);
    if(cLog) console.log('Feed > connect_error', e);
    feedConnectionCounter++;
    if(feedConnectionCounter===5){
        $('div#socket-reconnecting #error-text').html(e);
        $('div#socket-reconnecting').fadeIn();
    }
});

feed.on('syncMarketLast', (response) => {
    if(cLog) console.log(`sync Market Last`, response.time, JSON.stringify(response).length );
    repMarketSymbols.last=response.symbols;
});
feed.on('syncMarketStat', (response) => {
    if(cLog) console.log(`sync Market Stat`, response.time, JSON.stringify(response).length);
    repMarketSymbols.stat=response.symbols;
});
feed.on('syncMarketMix', (response) => {
    if(cLog) console.log(`sync Market Stat`, response.time, JSON.stringify(response).length);
    repMarketSymbols.mix=response.symbols;
});

