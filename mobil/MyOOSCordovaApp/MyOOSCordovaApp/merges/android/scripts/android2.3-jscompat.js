// Polyfill für "Function.prototype.bind()"-Unterstützung unter Android 2.3
(function () {
    if (!Function.prototype.bind) {
        Function.prototype.bind = function (thisValue) {
            if (typeof this !== "function") {
                throw new TypeError(this + " cannot be bound as it is not a function");
            }

            // "bind()" lässt auch vorangestellte Argumente für den Aufruf zu
            var preArgs = Array.prototype.slice.call(arguments, 1);

            // Die tatsächliche Funktion zum Binden des "this"-Werts und der "this"-Argumente an
            var functionToBind = this;
            var noOpFunction = function () { };

            // Das zu verwendende "this"-Argument
            var thisArg = this instanceof noOpFunction && thisValue ? this : thisValue;

            // Die resultierende gebundene Funktion
            var boundFunction = function () {
                return functionToBind.apply(thisArg, preArgs.concat(Array.prototype.slice.call(arguments)));
            };

            noOpFunction.prototype = this.prototype;
            boundFunction.prototype = new noOpFunction();

            return boundFunction;
        };
    }
}());
