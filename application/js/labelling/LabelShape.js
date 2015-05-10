
function LabelShape(type, bboxes) {

    this.bboxes = bboxes;

    this.getType = function() {
        return type;
    };
    
    this.isFree = function() {
        return type === 'F';
    };

    this.getBBoxes = function() {
        return bboxes;
    };

    this.wrapAroundPlane = function(planeWidth) {
        var wrappedBBoxes = [];
        for (var i = 0; i < bboxes.length; i++) {
            var bbox = bboxes[i];
            if (bbox.left < 0 || bbox.right > planeWidth) {
                if (bbox.left < 0) {
                    wrappedBBoxes.push(new LabelBBox(bbox.top, planeWidth, bbox.bottom, planeWidth + bbox.left));
                }
                if (bbox.right > planeWidth) {
                    wrappedBBoxes.push(new LabelBBox(bbox.top, bbox.right - planeWidth, bbox.bottom, 0));
                }
            }
        }
        for (var i = 0; i < wrappedBBoxes.length; i++) {
            bboxes.push(wrappedBBoxes[i]);
        }
    };

    this.overlaps = function(shape) {
        for (var i = 0; i < bboxes.length; i++) {
            var masterBBox = bboxes[i];
            for (var j = 0; j < shape.bboxes.length; j++) {
                var slaveBBox = shape.bboxes[j];
                if (masterBBox.overlaps(slaveBBox)) {
                    return true;
                }
            }
        }
        return false;
    };

    this.toBBox = function() {
        if (bboxes.length === 0) {
            return null;
        }
        var bbox = new LabelBBox(bboxes[0].top, bboxes[0].right, bboxes[0].bottom, bboxes[0].left);
        for (var i = 1; i < bboxes.length; i++) {
            bbox.top = Math.min(bbox.top, bboxes[i].top);
            bbox.right = Math.max(bbox.right, bboxes[i].right);
            bbox.bottom = Math.max(bbox.bottom, bboxes[i].bottom);
            bbox.left = Math.min(bbox.left, bboxes[i].left);
        }
        return bbox;
    };
}

//LabelShape.computeTextWidthFx = LabellerUtils.computePreciseTextWidth;
LabelShape.computeTextWidthFx = LabellerUtils.computeRoughTextWidth;

LabelShape.buildBShape = function(shapes) {
    if (shapes.length === 0 || shapes[0].isFree()) {
        return null;
    }
    var bbox = shapes[0].toBBox();
    for (var i = 1; i < shapes.length; i++) {
        var nextBbox = shapes[i].toBBox();
        bbox.top = Math.min(bbox.top, nextBbox.top);
        bbox.right = Math.max(bbox.right, nextBbox.right);
        bbox.bottom = Math.max(bbox.bottom, nextBbox.bottom);
        bbox.left = Math.min(bbox.left, nextBbox.left);
    }
    return new LabelShape('', [bbox]);
};

LabelShape.buildShapes = function(desc, text, pos) {
    
    var shapes = [];
    
    if (arguments.length === 1) {
        shapes.push(new LabelShape(desc.getFirstType()), []);
        return shapes;
    }

    var iw = 0;
    var tw = 0;
    var th = 0;
    var iw2 = 0;
    var tw2 = 0;
    var th2 = 0;
    
    var left = pos.x();
    var top = pos.y();
    var pad = desc.getPadding();
    var spc = desc.getSpacing();

    if (desc.hasBoundIcon()) {
        iw = desc.getBoundIconPixelWidth();
        iw2 = Math.ceil(iw / 2);
    }

    if (desc.hasText()) {
        th = desc.getTextPixelSize();
        tw = Math.round(LabelShape.computeTextWidthFx(text, "Arial", desc.getTextSize(),
                desc.getTextWeight(), desc.getTextStyle()));
        th2 = Math.ceil(th / 2);
        tw2 = Math.ceil(tw / 2);
    }

    var typesArray = desc.getTypes().split('');
    for (var i = 0; i < typesArray.length; i++) {
        var type = typesArray[i];
        switch (type) {
            case 'F':
                shapes.push(LabelShape.buildFreeIconShape(left, top, iw2));
                break;
            case 'I':
                shapes.push(LabelShape.buildIconOnlyShape(left, top, desc.getPadding(), iw2));
                break;
            case 'X':
                shapes.push(LabelShape.buildTextOnlyShape(left, top, pad, th2, tw2));
                break;
            case 'R':
                shapes.push(LabelShape.buildTextRightShape(left, top, pad, spc, iw2, th2, tw));
                break;
            case 'L':
                shapes.push(LabelShape.buildTextLeftShape(left, top, pad, spc, iw2, th2, tw));
                break;
            case 'T':
                shapes.push(LabelShape.buildTextTopShape(left, top, pad, spc, iw2, th, tw2));
                break;
            case 'B':
                shapes.push(LabelShape.buildTextBottomShape(left, top, pad, spc, iw2, th, tw2));
                break;
        }
    }
    return shapes;
};

LabelShape.buildFreeIconShape = function(left, top, iw2) {
    var bboxes = [];
    bboxes.push(new LabelBBox(top - iw2, left + iw2, top + iw2, left - iw2));
    var shape = new LabelShape('F', bboxes);
    return shape;
};

LabelShape.buildIconOnlyShape = function(left, top, pad, iw2) {
    var bboxes = [];
    bboxes.push(new LabelBBox(top - iw2 - pad, left + iw2 + pad, top + iw2 + pad, left - iw2 - pad));
    var shape = new LabelShape('I', bboxes);
    return shape;
};

LabelShape.buildTextOnlyShape = function(left, top, pad, th2, tw2) {
    var bboxes = [];
    bboxes.push(new LabelBBox(top - th2 - pad, left + tw2 + pad, top + th2 + pad, left - tw2 - pad));
    var shape = new LabelShape('X', bboxes);
    return shape;
};

LabelShape.buildTextRightShape = function(left, top, pad, spc, iw2, th2, tw) {
    var bboxes = [];
    bboxes.push(new LabelBBox(top - iw2 - pad, left + iw2 + pad, top + iw2 + pad, left - iw2 - pad));
    bboxes.push(new LabelBBox(top - th2 - pad, left + iw2 + spc + tw + pad, top + th2 + pad, left + iw2 + spc - pad));
    var shape = new LabelShape('R', bboxes);
    return shape;
};

LabelShape.buildTextLeftShape = function(left, top, pad, spc, iw2, th2, tw) {
    var bboxes = [];
    bboxes.push(new LabelBBox(top - iw2 - pad, left + iw2 + pad, top + iw2 + pad, left - iw2 - pad));
    bboxes.push(new LabelBBox(top - th2 - pad, left - iw2 - spc + pad, top + th2 + pad, left - iw2 - spc - tw - pad));
    var shape = new LabelShape('L', bboxes);
    return shape;
};

LabelShape.buildTextTopShape = function(left, top, pad, spc, iw2, th, tw2) {
    var bboxes = [];
    bboxes.push(new LabelBBox(top - iw2 - pad, left + iw2 + pad, top + iw2 + pad, left - iw2 - spc));
    bboxes.push(new LabelBBox(top - iw2 - spc - th - pad, left + tw2 + pad, top - iw2 - spc + pad, left - tw2 - pad));
    var shape = new LabelShape('T', bboxes);
    return shape;
};

LabelShape.buildTextBottomShape = function(left, top, pad, spc, iw2, th, tw2) {
    var bboxes = [];
    bboxes.push(new LabelBBox(top - iw2 - pad, left + iw2 + pad, top + iw2 + pad, left - iw2 - pad));
    bboxes.push(new LabelBBox(top + iw2 + spc - pad, left + tw2 + pad, top + iw2 + spc + th + pad, left - tw2 - pad));
    var shape = new LabelShape('B', bboxes);
    return shape;
};