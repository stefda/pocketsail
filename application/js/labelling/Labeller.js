
function Labeller() {
    // Left blank
}

Labeller.doLabelling = function(labels) {

    var top = labels.shift();
    top.important = true;

    for (var i = 0; i < labels.length - 1; i++) {
        if (!labels[i].canOverlap()) {
            continue;
        }
        // Do an exhaustive comparison with all other labels
        for (var j = i + 1; j < labels.length; j++) {
            if (!labels[j].intrudes(labels[i])) {
                continue;
            }
            if (labels[j].canOverlap()) {
                labels[i].eliminateOverlaps(labels[j]);
                if (!labels[j].isVisible()) {
                    labels.splice(j--, 1);
                }
            }
        }
    }
    
    labels.unshift(top);
};