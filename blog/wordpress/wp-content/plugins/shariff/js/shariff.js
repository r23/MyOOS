// main shariff function
function shariff_share_counts() {
    // enabled strict mode
    "use strict";
    // get all shariff containers
    var containers = document.getElementsByClassName("shariff");
    // init request object
    var requests = {};
    // loop through all containers, create each request url and add all to request array
    for ( var c = 0; containers[c]; c++ ) {
        var share_url = containers[c].dataset.url;
        var services = containers[c].dataset.services;
        var timestamp = containers[c].dataset.timestamp;
        // check if an external share count api is set
        var api = containers[c].dataset.backendurl;
        if ( typeof api === "undefined" ) {
            api = '/wp-json/shariff/v1/share_counts?';
        }
        // build request url
        var request_url = api + 'url=' + share_url + '&services=' + services + '&timestamp=' + timestamp;
        // check if we have backend services at all
        if ( typeof services !== "undefined" ) {
            // check if the url is already in requests to avoid duplicated requests
            if ( requests[ share_url ] ) {
                // add additional services to services
                services = requests[ share_url ][ 1 ] + '|' + services;
                // remove duplicates
                var service_array = services.split("|");
                service_array = service_array.filter( function( elem, pos, arr ) {
                    return arr.indexOf( elem ) == pos;
                });
                services = service_array.join('|');
                // update request url
                request_url = api + 'url=' + share_url + '&services=' + services + '&timestamp=' + timestamp;
                // add to requests
                requests[ share_url ] = [ share_url, services, timestamp, request_url ];
            }
            else {
                requests[ share_url ] = [ share_url, services, timestamp, request_url ];
            }
        }
    }
    // get share counts
    for ( var request in requests ) {
        if ( requests.hasOwnProperty( request ) ) {
            shariff_get_share_counts( requests[ request ][ 0 ], requests[ request ][ 3 ], containers );
        }
    }
}
// get share counts
function shariff_get_share_counts( share_url, request_url, containers ) {
    // new XMLHttpRequest
    var request = new XMLHttpRequest();
    // load asynchronously
    request.open( 'GET', request_url, true );
    // actions after answer
    request.onload = function() {
        // check if successful
        if ( request.status >= 200 && request.status < 400 ) {
            // add to buttons
            shariff_add_share_counts( share_url, JSON.parse( request.responseText ), containers );
        }
    };
    // start request
    request.send();
}
// add share counts
function shariff_add_share_counts( share_url, data, containers ) {
    // add share counts to buttons
    for ( var d = 0; containers[d]; d++ ) {
        // check if it is the corresponding button set
        if ( containers[d].dataset.url == share_url ) {
            // update total in total number spans
            var shariff_totalnumber = containers[d].getElementsByClassName("shariff-totalnumber");
            for ( var n = 0; shariff_totalnumber[n]; n++ ) {
                if ( data !== null && typeof data.total !== 'undefined' ) {
                    shariff_totalnumber[n].innerHTML = data.total;
                }
            }
            // update total in shariff headline
            var shariff_total = containers[d].getElementsByClassName("shariff-total");
            for ( var t = 0; shariff_total[t]; t++ ) {
                if ( data !== null && typeof data.total !== 'undefined' ) {
                    shariff_total[t].innerHTML = data.total;
                }
            }
            // loop through all button in this container
            var shariff_count = containers[d].getElementsByClassName("shariff-count");
            for ( var s = 0; shariff_count[s]; s++ ) {
                // add share count, if we have one, and make it visible
                if ( data !== null && typeof data[shariff_count[s].dataset.service] !== 'undefined' && ( typeof containers[d].dataset.hidezero === 'undefined' || ( containers[d].dataset.hidezero == '1' && data[shariff_count[s].dataset.service] > 0 ) ) ) {
                    shariff_count[s].innerHTML = data[shariff_count[s].dataset.service];
                    shariff_count[s].style.opacity = '1';
                }
            }
        }
    }
}
// add event listener to call main shariff function after DOM
document.addEventListener( "DOMContentLoaded", shariff_share_counts, false );
