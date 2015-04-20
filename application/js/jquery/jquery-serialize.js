
String.prototype.endsWith = function (suffix) {
    return this.indexOf(suffix, this.length - suffix.length) !== -1;
};

jQuery.fn.serialize = function () {

    var res = {};

    $(this).find('[data-class], [data-attribute]').not($(this).find('[data-class] [data-class], [data-class] [data-attribute]')).each(function () {

        // Resolve element type
        var isClass = typeof $(this).data('class') !== "undefined";

        if (isClass) { // If current element is a class
            var doc = $(this).serialize();
            var className = $(this).data('class');
            if (className.endsWith("[]")) {
                var netClassName = className.substring(0, className.length - 2);
                if (res[netClassName]) {
                    res[netClassName].push(doc);
                } else {
                    res[netClassName] = [doc];
                }
            } else {
                res[className] = doc;
            }
        } else { // Else it must be an attribute
            var attribute = $(this).data('attribute');
            var value = $(this).val();
            var numeric = +value;
            // Use numeric only if the value represents a valid number
            if (value !== "" && value !== null && !isNaN(numeric)) {
                value = numeric;
            }
            if (attribute.endsWith("[]")) {
                var netAttribute = attribute.substring(0, attribute.length - 2);
                if (res[netAttribute]) {
                    res[netAttribute].push(value);
                } else {
                    res[netAttribute] = [value];
                }
            } else {
                res[attribute] = value;
            }
        }
    });

    return res;
};