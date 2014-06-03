
<style type="text/css">
    .addPoiDialogWrapper { width: 900px; margin: 20px auto; font-family: Arial; }
    .section { margin-top: 10px; }
    .sectionLabel { margin-bottom: 3px; font-size: 13px; font-weight: bold; color: #333; }
    textarea { font-family: Arial; font-size: 12px; }
</style>

<script type="text/javascript">

    var id = <?= printJS($id) ?>;
    var latLng = LatLng.deserialize(<?= printJS($latLng) ?>);
    var boundary = Polygon.deserialize(<?= printJS($boundary) ?>);
    var cat = '<?= $cat ?>';
    var sub = '<?= $sub ?>';
    var addMap = null;
    var position = new google.maps.LatLng(latLng.lat, latLng.lng);
    var marker = null;
    var polyline = null;
    var polygon = null;

    $(function() {

        addMap = new google.maps.Map(document.getElementById('saveMapCanvas'), {
            zoom: map.getZoom(),
            center: new google.maps.LatLng(latLng.lat, latLng.lng),
            mapTypeControl: false,
            panControl: false,
            streetViewControl: false,
            maxZoom: 18,
            draggableCursor: 'crosshair'
        });
        addMap.mapTypes.set('map_style', styledMap);
        addMap.setMapTypeId('map_style');

        addMapManager = new MapManager(addMap);

        // Add marker
        marker = new google.maps.Marker({
            map: addMap,
            position: position,
            draggable: true
        });

        // Add boundary if not null
        if (boundary !== null) {
            polygon = new google.maps.Polygon({
                map: addMap,
                path: boundary.toGooglePath(),
                clickable: true,
                draggable: true,
                editable: true,
                strokeColor: 'red'
            });
            google.maps.event.addListener(polygon, 'rightclick', function(e) {
                if (e.vertex !== undefined) {
                    this.getPath().removeAt(e.vertex);
                }
            });
        }

        google.maps.event.addListener(marker, 'dragend', function(e) {
            latLng = LatLng.fromGoogleLatLng(e.latLng);
        });

        google.maps.event.addListener(addMap, 'click', function(e) {
            if (polyline === null) {
                polyline = new google.maps.Polyline({
                    map: addMap,
                    path: [e.latLng],
                    clickable: true,
                    draggable: true,
                    editable: true,
                    strokeColor: 'red'
                });
                google.maps.event.addListener(polyline, 'rightclick', function(e) {
                    if (e.vertex !== undefined) {
                        this.getPath().removeAt(e.vertex);
                    }
                });
                google.maps.event.addListener(polyline, 'click', function(e) {
                    if (e.vertex === 0) {
                        polygon = new google.maps.Polygon({
                            map: addMap,
                            path: polyline.getPath(),
                            clickable: true,
                            draggable: true,
                            editable: true,
                            strokeColor: 'red'
                        });
                        google.maps.event.addListener(polygon, 'rightclick', function(e) {
                            if (e.vertex !== undefined) {
                                this.getPath().removeAt(e.vertex);
                            }
                        });
                    }
                });
            }
            else if (polyline !== null) {
                polyline.getPath().push(e.latLng);
            }
        });

        // Select preselected category
        $('#selectCat').val(cat);
        // Select preselected subcategory
        $('#selectSub').val(sub);

        // Confirm on cancel
        $('#cancelButton').click(function() {
//            if (confirm("Changes aren't saved. Do you still want to cancel?")) {
            closeAddPoiDialog();
//            }
//            else {
//                return false;
//            }
        });

        // Confirm on leave
//        window.onbeforeunload = confirmLeave;
//        function confirmLeave() {
//            return "Changes aren't saved. Do you still want to leave/reload?";
//        }

        // Copy name over to label
        $('#name').blur(function() {
            if ($('#label').val() === '') {
                $('#label').val($(this).val());
            }
        });

        function add(s, n, v) {
            s[s.length] = {name: n, value: v};
        }

        $('#saveButton').click(function() {
            
            var countryId = $('#selectCountry').val();
            var nearbyId = $('#selectNearby').val();
            var cat = $('#selectCat').val();
            var sub = $('#selectSub').val();
            if (polygon !== null) {
                boundary = Polygon.fromGooglePath(polygon.getPath());
            }
            
            if (sub !== 'country' && sub !== 'region' && (countryId === '' || nearbyId === '')) {
                alert('Please select country and nearby');
                return false;
            }
            if ((cat === 'admin' || cat === 'geo' || cat === 'berthing')
                    && boundary === null) {
                alert('Please select boundary');
                return false;
            }
            
            var features = $('[name^="feature"]').serialize();
            var data = $.param({
                id: id,
                cat: cat,
                sub: sub,
                countryId: countryId,
                nearbyId: nearbyId,
                name: $('#name').val(),
                label: $('#label').val(),
                latLng: latLng.serialize(),
                boundary: boundary !== null ? boundary.serialize() : null
            });

            Admin.save_poi({
                post: data + '&' + features,
                success: function(res) {
                    //console.log(res);
                    closeAddPoiDialog();
                }
            });
        });
    });

</script>

<div class="addPoiDialogWrapper">

    <div id="saveMapCanvas" style="float: right; width: 530px; height: 400px;"></div>

    <div style="width: 400px;">

        <div class="section">
            <div class="sectionLabel">
                Category
            </div>
            <select id="selectCat">
                <option>Select one</option>
                <? foreach ($cats AS $cat): ?>
                    <option value="<?= $cat->id ?>"><?= $cat->name ?></option>
                <? endforeach; ?>
            </select>
        </div>

        <div class="section">
            <div class="sectionLabel">
                Subcategory
            </div>
            <select id="selectSub">
                <option>Select one</option>
                <? foreach ($subs AS $sub): ?>
                    <option value="<?= $sub->id ?>"><?= $sub->name ?></option>
                <? endforeach; ?>
            </select>
        </div>

        <div class="section">
            <div class="sectionLabel">
                Country
            </div>
            <select id="selectCountry">
                <? foreach ($countries AS $country): ?>
                    <option value="<?= $country->get_id() ?>" <?= $countryId == $country->get_id() ? 'selected' : '' ?>><?= $country->get_name() ?></option>
                <? endforeach; ?>
            </select>
        </div>

        <div class="section">
            <div class="sectionLabel">
                Nearby
            </div>
            <select id="selectNearby">
                <option value="">Select one</option>
                <? foreach ($nearbys AS $nearby): ?>
                    <option value="<?= $nearby->get_id() ?>" <?= $nearbyId == $nearby->get_id() ? 'selected' : '' ?>><?= $nearby->get_name() ?> (<?= $nearby->get_sub() ?>)</option>
                <? endforeach; ?>
            </select>
        </div>

        <div class="section">
            <div class="sectionLabel">
                Name
            </div>
            <input type="text" id="name" value="<?= $name ?>" />
        </div>

        <div class="section">
            <div class="sectionLabel">
                Label
            </div>
            <input type="text" id="label" value="<?= $label ?>" />
        </div>

        <div class="section">
            <div class="sectionLabel">
                Description
            </div>
            <textarea id="description" class="feature" name="feature[description]" style="width: 300px; height: 100px;"><?= @$ft->description ?></textarea>
        </div>

        <div class="section">
            <div class="sectionLabel">
                References
            </div>
            <textarea id="references" class="feature" name="feature[references]" style="width: 200px; height: 50px;"><?= @$ft->references ?></textarea>
        </div>

        <div style="margin-top: 20px;">
            <input type="button" id="saveButton" value="Save" style="margin-right: 3px;" />
            <input type="button" id="cancelButton" value="Cancel" />
        </div>

    </div>

</div>