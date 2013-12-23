(function(d, w) {
	var addEvent = function( obj, type, fn ) {
			if (obj.addEventListener)
					obj.addEventListener(type, fn, false);
			else if (obj.attachEvent)
					obj.attachEvent('on' + type, function() { return fn.call(obj, w.event);});
	};
	addEvent(w, 'load', function() {
		try {
			var ab = d.getElementById('wpadminbar');
			if(typeof ab != 'undefined') {
				var cm = d.getElementById('wpucssmagic').getElementsByClassName('wpucssmagic')[0];
				cm.insertBefore(ab, cm.firstChild);
			}	
		} catch(e) {}
	});
})(document, window);
