const stater = function(selectorId) {
    this.parent = document.getElementById(selectorId);
    const loader = document.createElement(`span`);
    loader.setAttribute(`id`, `${selectorId}-stater`);
    this.parent.appendChild(loader);
    this.stater = document.getElementById(`${selectorId}-stater`);
    return this;
}

stater.prototype._hide = function() {
    this.stater.style.display = "none";
    return this;
}

stater.prototype._show = function() {
    this.stater.style.display = "block";
    return this;
}

stater.prototype._addClass = function(className) {
    if ( typeof className !== 'undefined' ) {
        this.stater.classList.add(className);
    }
    return this;
}

stater.prototype._removeClass = function(className) {
    if ( typeof className !== 'undefined' ) {
        this.stater.classList.remove(className);
    }
    return this;
}

stater.prototype._toggleClass = function(className) {
    if ( typeof className !== 'undefined' ){
        this.stater.classList.toggle(className);
    }
    return this;
}