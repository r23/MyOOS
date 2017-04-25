(function() {
	tinymce.create('tinymce.plugins.sketchfab', {
		
		init : function(ed, url){
			ed.addButton('sketchfab', {
				title : 'R23 Sketchfab',
				onclick : function() {
					var ed = tinyMCE.activeEditor;
					ed.focus();
					var sel = ed.selection;
					var content = sel.getContent();
					content='[sketchfab id="2996aa43a7794a889ea8bbbe62d9140e"]';
					sel.setContent(content);
				},
				image: url + "/icon_shortcodes.png"
			}	
		);
	},
});

tinymce.PluginManager.add('sketchfab', tinymce.plugins.sketchfab);
})();
