
function LabellerUtils() {
    // Left empty (for now)
}

//LabellerUtils.computePreciseTextWidth = function(text, family, size, weight, style) {
//    var div_ = document.getElementById('computeTextWidth_');
//    if (div_ === null) {
//        var div_ = document.createElement('div');
//        div_.id = 'computeTextWidth_';
//        div_.style.position = 'absolute';
//        div_.style.left = '-1000px';
//        div_.style.top = '-1000px';
//        document.body.appendChild(div_);
//    }
//    div_.style.fontFamily = family;
//    div_.style.fontSize = size + 'px';
//    div_.style.fontWeight = weight === 'b' ? 'bold' : 'normal';
//    div_.style.fontWeight = style === 'i' ? 'italic' : 'normal';
//    div_.innerHTML = text;
//    return div_.clientWidth;
//};

LabellerUtils.computeRoughTextWidth = function(text, family, size, weight, style) {
    return text.length * TextStyle.SizeFactor[weight][size];
};