const loopMan = function(calledFunc, options) {
    this.isZambi = false;
    this.calledFunc = calledFunc;
    this.assignValues(options);
    return this;
}

loopMan.prototype.start = function(options) {
    this.assignValues(options);
    if ( typeof this.interval === 'undefined' ||
        this.isRunning() ) {
        return;
    }
    let counter=-1;
    if ( typeof this.limit !== 'undefined' ) {
        counter = this.limit;
    }
    this.isZambi = false;
    let that = this;
    function loop(){
        if(that.isZambi || counter===0){
            clearTimeout(that.timeout);
            that.timeout = null;
        }
        else {
            that.counter = counter;
            if(--counter===0) {
                clearTimeout(that.timeout);
                that.timeout = null;
                if( typeof that.limitCallback !== 'undefined' ) {
                    that.limitCallback();
                }
            } else {
                that.calledFunc();
                that.timeout = setTimeout(loop, that.interval);
            }
            that.counter = counter;
        }
    }
    loop();
    return this;
}

loopMan.prototype.stop = function() {
    if ( this.isRunning() ) {
        clearTimeout(this.timeout);
        this.timeout = null;
    }
    return this;
}

loopMan.prototype.restart = function(options) {
    this.stop();
    this.start(options);
    return this;
}

loopMan.prototype.restartDelay = function(options) {
    if ( typeof options['delay'] !== 'undefined'  ){
        let that = this;
        setTimeout(()=>that.restart(options) , options['delay']);
    }
    return this;
}

loopMan.prototype.kill = function(isZambi=true) {
    this.isZambi = isZambi;
    return this;
}

loopMan.prototype.assignValues = function(options) {
    if ( typeof options !== 'undefined' ){
        if ( typeof options['interval'] !== 'undefined' ) {
            this.interval = options['interval'];
        }
        if ( typeof options['limit'] !== 'undefined' ) {
            this.limit = options['limit'];
        }
        if ( typeof options['limitCallback'] !== 'undefined' ) {
            this.limitCallback = options['limitCallback'];
        }
    }
    return this;
}

loopMan.prototype.isRunning = function(options) {
    return ( typeof this.timeout !== 'undefined' &&
        this.timeout != null );
}
