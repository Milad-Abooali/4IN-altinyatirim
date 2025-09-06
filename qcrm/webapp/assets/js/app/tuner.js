var tuner = function(callback, options) {
    this.callback = callback;
    return this;
}

tuner.prototype.debounce = function(delay, arg = null) {
    let that = this;
    return function() {
        clearTimeout(that.timeout);
        that.timeout = setTimeout(that.callback(arg), delay);
    };
}

tuner.prototype.throttle = function(limit, arg = null) {
    this.throttling = false;
    let that = this;
    return function() {
        if (!that.throttling) {
            that.callback(arg);
            that.throttling = true;
            setTimeout(() => that.throttling = false, limit);
        }
    };
}