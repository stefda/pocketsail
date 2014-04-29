
function IconStyle(type, size, imageLeftOffset, imageRightOffset) {

    this.type = type;
    this.size = size;
    this.imageLeftOffset = imageLeftOffset;
    this.imageRightOffset = imageRightOffset;

    this.getPixelWidth = function() {
        return IconStyle.Size[this.size];
    };
}

IconStyle.Type = {
    BOUND: 1,
    FREE: 2
};

/**
 * ICON SIZES
 */
IconStyle.Size = {
    o: 10,
    s: 16,
    m: 20,
    l: 22,
    x: 24
};

function TextStyle(size, weight, style) {

    this.size = size;
    this.weight = weight;
    this.style = style;

    this.getPixelSize = function() {
        return TextStyle.Size[this.size];
    };
}

/**
 * FONT SIZES
 */
TextStyle.Size = {
    s: 9,
    m: 11,
    l: 13
};

TextStyle.SizeFactor = {
    n: {
        s: 4.5,
        m: 5,
        l: 6.1
    },
    b: {
        s: 5.2,
        m: 6.3,
        l: 8.1
    }
};

function LabelDescriptor(padding, spacing, types, boundIconStyle, freeIconStyle, textStyle) {

    this.getPadding = function() {
        return padding;
    };

    this.getSpacing = function() {
        return spacing;
    };

    this.getTypes = function() {
        return types;
    };
    
    this.getFirstType = function() {
        return types[0];
    };

    this.getIconStyle = function() {
        if (this.hasBoundIcon()) {
            return boundIconStyle;
        }
        return freeIconStyle;
    };
    
    this.getBoundIconStyle = function() {
        return boundIconStyle;
    };
    
    this.getFreeIconStyle = function() {
        return freeIconStyle;
    };

    this.hasBoundIcon = function() {
        return boundIconStyle !== null;
    };

    this.hasFreeIcon = function() {
        return freeIconStyle !== null;
    };

    this.hasText = function() {
        return textStyle !== null;
    };

    this.getBoundIconPixelWidth = function() {
        if (boundIconStyle === null) {
            return 0;
        }
        return boundIconStyle.getPixelWidth();
    };

    this.getBoundIconImageLeftOffset = function() {
        if (boundIconStyle === null) {
            return 0;
        }
        return boundIconStyle.imageLeftOffset;
    };

    this.getBoundIconImageRightOffset = function() {
        if (boundIconStyle === null) {
            return 0;
        }
        return boundIconStyle.imageRightOffset;
    };

    this.getFreeIconPixelWidth = function() {
        if (freeIconStyle === null) {
            return 0;
        }
        return freeIconStyle.getPixelWidth();
    };

    this.getFreeIconImageLeftOffset = function() {
        if (freeIconStyle === null) {
            return 0;
        }
        return freeIconStyle.imageLeftOffset;
    };

    this.getFreeIconImageRightOffset = function() {
        if (freeIconStyle === null) {
            return 0;
        }
        return freeIconStyle.imageRightOffset;
    };

    this.getTextSize = function() {
        if (textStyle === null) {
            return '';
        }
        return textStyle.size;
    };

    this.getTextPixelSize = function() {
        if (textStyle === null) {
            return 0;
        }
        return textStyle.getPixelSize();
    };

    this.getTextWeight = function() {
        if (textStyle === null) {
            return '';
        }
        return textStyle.weight;
    };

    this.getTextStyle = function() {
        if (textStyle === null) {
            return '';
        }
        return textStyle.style;
    };
}

LabelDescriptor.fromString = function(desc) {

    var pattern = /^L((-?\d+)(,(-?\d+))?)?([R|L|T|B|X|I|F]{1,7})(i([o|s|m|l|x])(-?\d+),(-?\d+))?(f([o|s|m|l|x])(-?\d+),(-?\d+))?(t([s|m|l])([n|b])([n|i]))?$/;
    var matcher = pattern.exec(desc);

    if (matcher === null) {
        return null;
    }

    var boundIconStyle = null;
    var freeIconStyle = null;
    var textStyle = null;

    var padding = matcher[2] === undefined ? 0 : parseInt(matcher[2]);
    var spacing = matcher[4] === undefined ? 0 : parseInt(matcher[4]);
    var types = matcher[5];

    // Bounds icon
    if (matcher[6] !== undefined) {
        var size = matcher[7].charAt(0);
        var imageLeftOffset = parseInt(matcher[8]);
        var imageTopOffset = parseInt(matcher[9]);
        boundIconStyle = new IconStyle(IconStyle.Type.BOUND, size, imageLeftOffset, imageTopOffset);
    }

    // Free icon
    if (matcher[10] !== undefined) {
        var size = matcher[11].charAt(0);
        var imageLeftOffset = parseInt(matcher[12]);
        var imageTopOffset = parseInt(matcher[13]);
        freeIconStyle = new IconStyle(IconStyle.Type.FREE, size, imageLeftOffset, imageTopOffset);
    }

    // Text
    if (matcher[14] !== undefined) {
        var size = matcher[15];
        var weigth = matcher[16];
        var style = matcher[17];
        textStyle = new TextStyle(size, weigth, style);
    }
    
    return new LabelDescriptor(padding, spacing, types, boundIconStyle, freeIconStyle, textStyle);
};