
function Label(id, name, cat, sub, latLng, desc, shapes, bShape) {

    this.id = id;
    this.name = name;
    this.cat = cat;
    this.sub = sub;
    this.latLng = latLng;

    this.getFirstShape = function() {
        if (!this.isVisible()) {
            return null;
        }
        return shapes[0];
    };

    this.getFirstType = function() {
        var firstShape = this.getFirstShape();
        if (firstShape === null) {
            return desc.getFirstType();
        }
        return firstShape.getType();
    };

    this.hasBoundIcon = function() {
        var firstShape = this.getFirstShape();
        if (firstShape === null) {
            return false;
        }
        return firstShape.getType().match(/[R|L|T|B|I]/);
    };

    this.hasFreeIcon = function() {
        var firstShape = this.getFirstShape();
        if (firstShape === null) {
            return false;
        }
        return firstShape.getType() === 'F';
    };

    this.hasText = function() {
        var firstShape = this.getFirstShape();
        if (firstShape === null) {
            return false;
        }
        return firstShape.getType().match(/[R|L|T|B|X]/);
    };

    this.getIconStyle = function() {
        if (this.hasBoundIcon()) {
            return desc.getBoundIconStyle();
        }
        if (this.hasFreeIcon()) {
            return desc.getFreeIconStyle();
        }
        return null;
    };

    this.getDescriptor = function() {
        return desc;
    };

    this.getShapes = function() {
        return shapes;
    };

    this.canOverlap = function() {
        return shapes.length > 0 && shapes[0].getType() !== 'F';
    };

    this.isVisible = function() {
        return shapes.length > 0;
    };

    this.getBShape = function() {
        return bShape;
    };

    this.intrudes = function(slave) {
        var slaveBShape = slave.getBShape();
        if (bShape === null || slaveBShape === null) {
            return false;
        }
        return slaveBShape.overlaps(bShape);
    };

    this.eliminateOverlaps = function(slave) {
        var masterShape = this.getFirstShape();
        var slaveShapes = slave.getShapes();
        // Do nothing if label has no shapes or is persistent
        if (masterShape === null || masterShape.getType() === 'F') {
            return;
        }
        for (var i = 0; i < slaveShapes.length; i++) {
            var slaveShape = slaveShapes[i];
            if (slaveShape.getType() !== 'F' && masterShape.overlaps(slaveShape)) {
                slaveShapes.splice(i--, 1);
            }
        }
    };
}

Label.deserialize = function(sLabel, zoom, lType) {

    var id = sLabel.id;
    var name = sLabel.name;
    var cat = sLabel.cat;
    var sub = sLabel.sub;
    var latLng = LatLng.deserialize(sLabel.latLng);
    var desc = LabelDescriptor.fromString(sLabel.desc);

    if (lType === 'static') {
        var shapes = LabelShape.buildShapes(desc);
        return new Label(id, name, cat, sub, latLng, desc, shapes, null);
    }

    var pos = Projector.mercator(latLng, zoom);
    var shapes = LabelShape.buildShapes(desc, name, pos);
    var bShape = LabelShape.buildBShape(shapes);
    var planeWidth = Math.pow(2, zoom) * 256;
    for (var i = 0; i < shapes.length; i++) {
        shapes[i].wrapAroundPlane(planeWidth);
    }
    bShape.wrapAroundPlane(planeWidth);
    return new Label(id, name, cat, sub, latLng, desc, shapes, bShape);
};