// Eine Einführung zur leeren Vorlage finden Sie in der folgenden Dokumentation:
// http://go.microsoft.com/fwlink/?LinkID=397704
// So debuggen Sie Code beim Seitenladen in Ripple oder auf Android-Geräten/-Emulatoren: Starten Sie die App, legen Sie Haltepunkte fest, 
// und führen Sie dann "window.location.reload()" in der JavaScript-Konsole aus.
(function () {
    "use strict";

    document.addEventListener( 'deviceready', onDeviceReady.bind( this ), false );

    function onDeviceReady() {
        // Verarbeiten der Cordova-Pause- und -Fortsetzenereignisse
        document.addEventListener( 'pause', onPause.bind( this ), false );
        document.addEventListener( 'resume', onResume.bind( this ), false );
        
        // TODO: Cordova wurde geladen. Führen Sie hier eine Initialisierung aus, die Cordova erfordert.
        var element = document.getElementById("deviceready");
        element.innerHTML = 'Device Ready';
        element.className += ' ready';
    };

    function onPause() {
        // TODO: Diese Anwendung wurde ausgesetzt. Speichern Sie hier den Anwendungszustand.
    };

    function onResume() {
        // TODO: Diese Anwendung wurde erneut aktiviert. Stellen Sie hier den Anwendungszustand wieder her.
    };
} )();