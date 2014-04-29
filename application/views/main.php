<!DOCTYPE html>
<html>
    <head>

        <title>PocketSail</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <script type="text/javascript" src="/application/js/controllers/Service.js"></script>
        <script type="text/javascript" src="/application/js/jquery/jquery.js"></script>
        <script type="text/javascript" src="/application/js/jquery/jquery-ui.js"></script>
        <script type="text/javascript" src="/application/js/jquery/ajax.js"></script>
        <script type="text/javascript" src="/application/js/jquery/scrollbar.js"></script>
        <script type="text/javascript" src="/application/js/jquery/jquery-autosize.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script src="/application/js/map/map-style.js"></script>
        <script type="text/javascript" src="/application/js/main.js"></script>

        <link href="/application/js/jquery/scrollbar.css" rel="stylesheet" type="text/css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/main.css" />

    <body>

        <div style="position: fixed; z-index: 9; width: 100%; height: 100%; background-color: #e0e1e2;">

            <div id="veil" style="display: none; z-index: 1; position: fixed; width: 100%; height: 100%; background-color: #f0f1f2; opacity: 0.7;"></div>
            <div id="map" style="position: fixed; width: 100%; height: 100%; min-width: 1024px;"></div>

            <div style="z-index: 9999; position: absolute; top: 63px; left: 10px;">
                <div style="position: absolute; top: 11px; right: 9px; width: 14px; height: 14px; background-image: url('/application/images/search-icon-small.png');"></div>
                <input type="text" id="search-input" style="border: solid 1px #fff; width: 291px; border-radius: 2px; padding: 5px 30px 5px 7px; box-shadow: 0 1px 6px rgba(0, 0, 0, 0.4); outline: none; font-size: 16px; font-family: 'Helvetica Neue', Helvetica, Verdana, Arial, sans-serif; color: #333; " />
            </div>

            <div id="searchres" style="display: none; position: absolute; z-index: 99999; top: 98px; left: 10px; width: 330px; height: 402px; background-color: #fff; border-radius: 2px; box-shadow: 0 1px 6px rgba(0, 0, 0, 0.4); ">
                <div style="padding: 15px;">
                    <div style="height: 30px;"></div>
                </div>
            </div>

            <div id="left-pane" style="z-index: 9997; position: absolute; top: 96px; bottom: 0;">

                <div class="card" poi="2">
                    <div class="title">
                        <div style="float: left;">
                            <img src="/application/images/info-icons/marina-small.png" />
                        </div>
                        <div style="margin-left: 40px;">
                            <div style="font-size: 15px; padding-top: 0px;">ACI Marina Palmežana</div>
                            <div style="font-size: 11px;"><img src="/application/images/rating.png" style="padding-top: 2px;" /></div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- INFO RIGHT -->
            <div id="infoRight">
                <div style="width: 750px; margin: 0 auto;">
                    <div style="float: right; width: 300px; margin-right: -112px;">

                        <div id="infoMap" style="width: 100%; height: 250px; margin-right: -112px; background-color: #fff; border-radius: 3px; box-shadow: 0 1px 6px rgba(0, 0, 0, 0.4);">
                        </div>

                        <div id="infoContact"></div>

                        <div id="infoMenu">
                            
                            <div class="wrapper top">
                                <a class="active top" href="summary">
                                    <span class="icon summary"></span>
                                    <span class="label">Summary</span>
                                </a>
                            </div>
                            
                            <div class="wrapper">
                                <a class="" href="fullview">
                                    <span class="icon fullview"></span>
                                    <span class="label">Full View</span>
                                </a>
                            </div>

                            <div id="navMenu" style="display: none;">
                            </div>

                            <div class="wrapper">
                                <a href="edit">
                                    <span class="icon edit"></span>
                                    <span class="label">Edit</span>
                                </a>
                            </div>
                            
                            <div id="editMenu" style="display: none;">
                                <div class="wrapper sub">
                                    <a href="save">Save Changes</a>
                                </div>
                                <div class="wrapper sub">
                                    <a href="cancel">Cancel</a>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            <!-- /INFO RIGHT -->

        </div>

        <div class="header">
            <div style="padding: 10px 0 0 17px;">
                <img src="/application/images/logo.png" />
            </div>
        </div>

        <div style="position: relative; z-index: 9; overflow-x: hidden; width: 100%; pointer-events: none;">

            <div style="position: relative; z-index: 9; width: 1250px; margin: 0 auto;">

                <!-- INFO LEFT -->
                <div id="infoLeft">
                    <div id="infoContent">
                    </div>
                </div>
                <!-- /INFO LEFT -->

            </div>
        </td>
    </tr>
</table>
</div>

</body>
</html>

<!--
<div class="info-block warning">
    <div class="inner-wrapper">
        <div style="background-color: #fff; padding: 7px 10px 12px 10px; border-radius: 2px;">
            <a id="warning"></a>
            <h1>Warning</h1>
            Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
        </div>
    </div>
</div>
-->
