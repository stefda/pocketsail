<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script src="/application/js/jquery/jquery.js"></script>
        <script src="/application/js/jquery/jquery-ui.js"></script>
        <script src="/application/js/jquery/ajax.js"></script>
        <script src="/application/js/controllers/API.js"></script>
        <script src="/application/js/controllers/Admin.js"></script>

        <script src="/application/js/map/style.js"></script>
        <script src="/application/js/map/MapManager.js"></script>
        <script src="/application/js/labelling/Marker.js"></script>
        <script src="/application/js/labelling/LabellerUtils.js"></script>
        <script src="/application/js/labelling/Label.js"></script>
        <script src="/application/js/labelling/LabelDescriptor.js"></script>
        <script src="/application/js/labelling/LabelShape.js"></script>
        <script src="/application/js/labelling/LabelBBox.js"></script>
        <script src="/application/js/labelling/Labeller.js"></script>

        <script src = "/application/js/geo/Projector.js" ></script>
        <script src="/application/js/geo/Position.js"></script>
        <script src="/application/js/geo/LatLng.js"></script>
        <script src="/application/js/geo/Bounds.js"></script>
        <script src="/application/js/geo/Polygon.js"></script>
        <script src="/application/js/geo/Point.js"></script>

        <link type="text/css" rel="stylesheet" href="/application/js/jquery/ui/smoothness/jquery-ui-1.10.4.custom.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/map.css" />

        <script type="text/javascript" src="/application/js/admin/add_poi.js"></script>
    </head>

    <body oncontextmenu="return false;">

        <div id="canvas" style="width: 100%; height: 100%;"></div>

	<div id="zoomOut" style="font-family: Arial; font-size: 14px; position: absolute; bottom: 22px; right: 40px; background-color: #fff; border: solid 1px #bbb; border-radius: 2px; box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3); padding: 3px 5px;">10</div>
 
        <div id="addPoiDialog" style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: #fff;">
        </div>

        <div style="position: absolute; left: 10px; top: 10px;">
            <input type="button" id="labellingButton" value="Do labelling" />
        </div>

        <?= include_view('admin/add_poi_menu') ?>
        <?= include_view('admin/marker_menu') ?>

    </body>
</html>