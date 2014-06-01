
var mapStyle = [
// Labels
    {
        featureType: 'all',
//        elementType: 'labels.text.fill',
        elementType: 'labels',
        stylers: [
            {
//                lightness: 50
                visibility: 'off'
            }
        ]
    },
    {
        featureType: 'all',
        elementType: 'labels.icon',
        stylers: [
            {
                visibility: 'off'
            }
        ]
    },
// Admin
    {
        featureType: 'administrative',
        elementType: 'all',
        stylers: [
            {
                visibility: 'off'
            }
        ]
    },
// POI
    {
        featureType: 'poi',
        elementType: 'all',
        stylers: [
            {
                visibility: 'off'
            }
        ]
    },
// Water
//    {
//        featureType: 'water',
//        elementType: 'all',
//        stylers: [
//            {
//                color: '#89c6dc'
//                        //color: '#6eade4'
//                        //color: '#9cc3fe'
//            }
//        ]
//    },
//    {
//        featureType: 'water',
//        elementType: 'labels',
//        stylers: [
//            {
//                visibility: 'off'
//            }
//        ]
//    },
// Landscape
//{
//    featureType: 'landscape',
//    elementType: 'all',
//    stylers: [
//    {
//        color: '#f9efa3'
//    },
//    {
//        lightness: 0
//    }
//    ]
//},
//{
//    featureType: 'landscape',
//    elementType: 'geometry.stroke',
//    stylers: [
//    {
//        color: '#000000'
//    },
//    {
//        lightness: 0
//    },
//    {
//        weight: 10
//    }
//    ]
//},

    {
        featureType: 'landscape',
        elementType: 'labels',
        stylers: [
            {
                visibility: 'off'
            }
        ]
    },
// Shipping lanes
    {
        featureType: 'transit.line',
        //elementType: 'labels',
        elementType: 'all',
        stylers: [
            {
                visibility: 'off'
            }
        ]
    },
    {
        featureType: 'transit.line',
        elementType: 'all',
        stylers: [
            {
                color: '#bb9f7a'
            }
        ]
    }
];