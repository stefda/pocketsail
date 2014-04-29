<?
global $i;
$i = 0;
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Edit Template</title>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/jquery-ui.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>
        <script src="/application/js/controllers/Test.js"></script>
        <script>
            $(function() {
                
                $('.detailsButton').click(function(e) {
                    e.preventDefault();
                    var wrapper = $(this).siblings('.detailsWrapper');
                    if (wrapper.is(':visible')) {
                        $(this).html('details &#8595;');
                        wrapper.hide();
                    }
                    else {
                        $(this).html('details &#8594;');
                        wrapper.show();
                        load_map(wrapper.find('.detailsMap').get(0))
                    }
                });
                
                $('.addPlaceButton').live('click', function() {
                    var wrapper = $(this).closest('.detailsWrapper');
                    var html = Test.get_template('showers', 'nested_edit', 0);
                    var move = $(html);
                    var old = wrapper.find('.form');
                    move.find('.form').html(old);
                    var current = wrapper.siblings('.nestedPlacesWrapper').append(move);
                    var newi = Test.get_template('showers', 'edit', i++);
                    wrapper.html($(newi));
                    load_map(wrapper.find('.detailsMap').get(0));
                    //console.log(wrapper.find('.form').html());
                    //var type = $(this).attr('placetype');
                    //var html = Test.get_template(type, 'edit', i++);
                });
                
                function load_map(div) {
                    var map = new google.maps.Map(div,{
                        zoom: 16,
                        center: new google.maps.LatLng(44.119234, 15.22897),
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        streetViewControl: false,
                        zoomControl: false,
                        mapTypeControl: false
                    });
                }
                
                var M = new google.maps.Map(document.getElementById('map'),{
                    zoom: 16,
                    center: new google.maps.LatLng(44.119234, 15.22897),
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    streetViewControl: false,
                    //zoomControl: false,
                    mapTypeControl: false,
                    panControl: false,
                    zoomControlOptions: {
                        style: google.maps.ZoomControlStyle.SMALL
                    }
                });
                
                var div = $('#mapWrapper');
                
                $('#resize').draggable({
                    axis: 'y',
                    containment: [0, 90, 0, 500],
                    drag: function(event, ui) {
                        div.css('height', ui.position.top + 1);
                    },
                    stop: function(event, ui) {
                        google.maps.event.trigger(M, 'resize');
                    }
                });
                
                $('.nestedDetailsButton').live('click', function(e) {
                    e.preventDefault();
                    var wrapper = $(this).closest('.nestedPlace');
                    var header = wrapper.find('.header');
                    var body = wrapper.find('.body');
                    if (body.is(':visible')) {
                        body.hide();
                    }
                    else {
                        body.show();
                        load_map(wrapper.find('.detailsMap').get(0));
                    }
                });
            });
        </script>
        <style>
            body { font-family: Arial; font-size: 14px; }
            textarea { width: 500px; height: 40px; font-family: Arial; font-size: 13px; resize: none; margin: 0px 0 5px 0; }
            input { font-size: 13px; margin: 0; }
            h1 { font-size: 16px; margin-bottom: 10px; }
            h2 { font-size: 14px; margin-bottom: 10px; }
            ul { list-style: none; list-style-type: none; margin: 5px 0 0; padding: 0; }
            li { min-height: 30px; }
            label { font-size: 12px; vertical-align: text-top; }
            .detailsButton { font-size: 12px; text-decoration: none; color: steelblue; }
            .detailsWrapper { padding-top: 10px; display: none; }
            .detailsMapWrapper { width: 506px; height: 200px; margin-bottom: 5px; }
            .detailsMap { width: 100%; height: 100%; }
            .addPlaceButton { margin-bottom: 5px; }
            .place { padding: 1px 3px; font-size: 10px; background-color: palegoldenrod; border-radius: 2px; width: 200px; }
            .nestedPlace {  }
            .nestedPlace .header { width: 500px; padding: 2px 4px; background-color: #e3e4e5; }
            .nestedPlace .nestedDetailsButton { float: right; }
            .nestedPlace .body { display: none; border: solid 1px #e3e4e5; width: 506px; }
        </style>
    </head>
    <body>

        <div id="mapWrapper" style="position: relative; width: 500px; height: 83px; border: solid 1px #d9d9d9;">
            <div id="map" style="width: 100%; height: 100%;"></div>
            <div id="resize" style="position: absolute; bottom: -10px; left: 229px; width: 42px; height: 11px; cursor: s-resize; background-image: url('/application/images/handle.png')"></div>
        </div>

        <form action="/test/submit_edit_template" method="post">
            
            <!--
            <div>
                <h1>Description</h1>
                <textarea name="info[description][text]"></textarea>
            </div>

            <div>
                <h1>Navigation and Pilotage</h1>
                <textarea name="info[navigation][text]"></textarea>
            </div>

            <div>
                <h1>Berthing</h1>
                <textarea name="info[berthing][text]"></textarea>
                <ul>
                    <li>
                        Sea berths
                        <input type="text" name="info[berthing][number]" placeholder="Number" style="width: 60px;">
                        <select name="info[berthing][type]">
                            <option value="">Select one</option>
                            <option value="sternto">Stern-to</option>
                            <option value="alongside">T/Alongside</option>
                        </select>
                        <a href="" class="detailsButton">details &#8595;</a>
                        <div class="detailsWrapper">
                            <textarea name="info[berthing][electricity][text]"></textarea>
                        </div>
                    </li>
                    <li>
                        Dry berths
                        <input type="text" name="info[berthing][number]" placeholder="Number" style="width: 60px;">
                        <a href="" class="detailsButton">details &#8595;</a>
                        <div class="detailsWrapper">
                            <textarea name="info[berthing][electricity][text]"></textarea>
                        </div>
                    </li>
                    <li>
                        Electricity
                        <input type="radio" name="info[berthing][electricity]" value="nk" checked="true" />
                        <label for="nk">n/k</label>
                        <input type="radio" name="info[berthing][electricity]" value="yes" />
                        <label for="yes">yes</label>
                        <input type="radio" name="info[berthing][electricity]" vaklue="no" />
                        <label for="yes">no</label>
                        <a href="" class="detailsButton">details &#8595;</a>
                        <div class="detailsWrapper">
                            <textarea name="info[berthing][electricity][text]"></textarea>
                        </div>
                    </li>
                    <li>
                        Water
                        <input type="radio" name="info[berthing][water]" value="nk" checked="true" />
                        <label for="nk">n/k</label>
                        <input type="radio" name="info[berthing][water]" value="yes" />
                        <label for="yes">yes</label>
                        <input type="radio" name="info[berthing][water]" vaklue="no" />
                        <label for="yes">no</label>
                        <a href="" class="detailsButton">details &#8595;</a>
                        <div class="detailsWrapper">
                            <textarea name="info[berthing][water][text]"></textarea>
                        </div>
                    </li>
                </ul>
            </div>
            -->

            <div>
                <h1>Services and Facilities</h1>
                <textarea name="info[services][text]"></textarea>

                <h2>Utilities</h2>
                <ul>
                    <li>
                        Showers
                        <input type="radio" name="info[utilities][showers]" value="nk" checked="true" />
                        <label for="nk">n/k</label>
                        <input type="radio" name="info[utilities][showers]" value="yes" />
                        <label for="yes">yes</label>
                        <input type="radio" name="info[utilities][showers]" vaklue="no" />
                        <label for="yes">no</label>
                        <a href="" class="detailsButton">details &#8595;</a>
                        <div class="detailsWrapper">
                            <?= include_template('showers_template', 'edit', [], $i++) ?>
                        </div>
                        <div class="nestedPlacesWrapper"></div>
                    </li>
                    <li>
                        Toilets
                        <input type="radio" name="info[utilities][toilets]" value="nk" checked="true" />
                        <label for="nk">n/k</label>
                        <input type="radio" name="info[utilities][toilets]" value="yes" />
                        <label for="yes">yes</label>
                        <input type="radio" name="info[utilities][toilets]" vaklue="no" />
                        <label for="yes">no</label>
                        <a href="" class="detailsButton">details &#8595;</a>
                        <div class="detailsWrapper">
                            <textarea name="place[<?= $i++ ?>][utilities][toilets][text]"></textarea><br />
                            <div class="detailsMapWrapper">
                                <div class="detailsMap"></div>
                            </div>
                            <input class="addPlaceButton" placetype="toilets" type="button" value="Add another toilet" />
                        </div>
                    </li>
                </ul>

                <h2>Officials</h2>
                <ul>
                    <li>
                        Reception
                        <input type="radio" name="info[officials][reception]" value="nk" checked="true" />
                        <label for="nk">n/k</label>
                        <input type="radio" name="info[officials][reception]" value="yes" />
                        <label for="yes">yes</label>
                        <input type="radio" name="info[officials][reception]" vaklue="no" />
                        <label for="yes">no</label>
                        <a href="" class="detailsButton">details &#8595;</a>
                        <div class="detailsWrapper">
                            <textarea name="place[<?= $i++ ?>][officials][reception][text]"></textarea><br />
                            <div class="detailsMapWrapper">
                                <div class="detailsMap"></div>
                            </div>
                        </div>
                    </li>
                    <li>
                        Custsoms
                        <input type="radio" name="info[officials][customs]" value="nk" checked="true" />
                        <label for="nk">n/k</label>
                        <input type="radio" name="info[officials][customs]" value="yes" />
                        <label for="yes">yes</label>
                        <input type="radio" name="info[officials][customs]" vaklue="no" />
                        <label for="yes">no</label>
                        <a href="" class="detailsButton">details &#8595;</a>
                        <div class="detailsWrapper">
                            <textarea name="place[<?= $i++ ?>][officials][customs][text]"></textarea><br />
                            <div class="detailsMapWrapper">
                                <div class="detailsMap"></div>
                            </div>
                        </div>
                    </li>
                </ul>

            </div>

            <input type="submit" value="Save" />

        </form>

        <script>
            var i = <?= $i ?>;
        </script>

    </body>
</html>