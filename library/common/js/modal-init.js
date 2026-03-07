// Initialisierung für native Modals in Padma Theme
// Öffnet Inline-Content oder Bilder als Modal
(function(){
	document.addEventListener('DOMContentLoaded', function() {
		// Inline-Content (z.B. Templates)
		document.body.addEventListener('click', function(e) {
			var trigger = e.target.closest('.ps-modal-trigger');
			if(trigger) {
				e.preventDefault();
				var targetId = trigger.getAttribute('data-modal-target');
				var content = document.getElementById(targetId);
				if(content) {
					// Originales Element verwenden, nicht klonen - damit jQuery Event Handler funktionieren
					window.PSModal.open(content);
				}
			}
		});
		// Bilder
		document.body.addEventListener('click', function(e) {
			var imgTrigger = e.target.closest('.ps-modal-image-trigger');
			if(imgTrigger) {
				e.preventDefault();
				var imgUrl = imgTrigger.getAttribute('data-modal-image');
				if(imgUrl) {
					var img = document.createElement('img');
					img.src = imgUrl;
					window.PSModal.open(img);
				}
			}
		});
	});
})();
