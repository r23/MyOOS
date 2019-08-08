<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2019 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: pannellum.html 
   ----------------------------------------------------------------------
   Pannellum
   https://pannellum.org/


   Copyright © Matthew Petroff(http://mpetroff.net/)
   ----------------------------------------------------------------------
   The MIT License
   ---------------------------------------------------------------------- */
?>
<script>
pannellum.viewer('panorama', {
    "type": "equirectangular",
    "panorama": "https://pannellum.org/images/alma.jpg",
    "strings": {
		"loadButtonLabel": "Klicke hier, um\nPanorama\nzu laden",
		"loadingLabel": "Lade...",
		"bylineLabel": "von %s",    
		"noPanoramaError": "Es wurde kein Panorama angegeben.",
		"fileAccessError": "Die Datei %s konnte nicht geöffnet werden.",
		"malformedURLError": "Da stimmt etwas nicht mit der Panorama URL.",
		"iOS8WebGLError": "Wegen der fehlerhaften WebGL Implementierung von iOS8 funktionieren nur progressiv enkodierte JPEGs auf Ihrem Gerät (dieses Panorama benutzt Standard Enkodierung).",
		"genericWebGLError": "Ihr Browser hat nicht die nötige WebGL Unterstützung, um das Panorama anzeigen zu können.",
		"textureSizeError": "Dieses Panorama ist zu groß für Ihr Gerät! Das Panorama ist %spx breit, ihr Gerät unterstützt allerdings nur eine maximal Größe von %spx. Versuchen Sie ein anderes Gerät. (Falls Sie der Autor sind, versuchen Sie, das Bild herunterzuskalieren.)", 
		"unknownError": "Unbekannter Fehler. Schauen Sie in die Entwicklerkonsole."		
    }		
});
</script>

