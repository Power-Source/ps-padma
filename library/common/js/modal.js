// Minimalistisches, natives Modal für Bild- und Inline-Content
// Einbindung: <script src="/library/common/js/modal.js"></script>
(function(){
	let currentModal = null;
	let originalParent = null;
	let originalDisplay = null;
	
	function openModal(content) {
		let modal = document.createElement('div');
		modal.className = 'ps-modal-overlay';
		modal.innerHTML = '<div class="ps-modal"><button class="ps-modal-close" aria-label="Schließen">×</button><div class="ps-modal-content"></div></div>';
		
		// Wenn content ein Element ist (nicht geklont), speichern wir das Original
		if(content instanceof Element) {
			originalParent = content.parentElement;
			originalDisplay = content.style.display || '';
			content.style.display = 'block';
		}
		
		modal.querySelector('.ps-modal-content').appendChild(content);
		document.body.appendChild(modal);
		currentModal = modal;
		
		modal.querySelector('.ps-modal-close').onclick = function() {
			closeModal();
		};
		modal.onclick = function(e) {
			if(e.target === modal) closeModal();
		};
	}
	function closeModal() {
		let modal = document.querySelector('.ps-modal-overlay');
		if(modal) {
			// Wenn wir das Original-Element bewegt haben, setzen wir es zurück
			if(originalParent && originalDisplay !== null) {
				let contentDiv = modal.querySelector('.ps-modal-content');
				let originalElement = contentDiv.firstElementChild;
				if(originalElement && originalParent) {
					originalParent.appendChild(originalElement);
					originalElement.style.display = originalDisplay || 'none';
				}
				originalParent = null;
				originalDisplay = null;
			}
			modal.remove();
			currentModal = null;
		}
	}
	window.PSModal = { open: openModal, close: closeModal };
})();
