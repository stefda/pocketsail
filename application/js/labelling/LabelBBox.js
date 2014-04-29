
function LabelBBox(top, right, bottom, left) {

    this.top = top;
    this.right = right;
    this.bottom = bottom;
    this.left = left;

    this.overlaps = function(bbox) {
        return bbox.top < this.bottom
                && bbox.bottom > this.top
                && bbox.right > this.left
                && bbox.left < this.right;
    };
}