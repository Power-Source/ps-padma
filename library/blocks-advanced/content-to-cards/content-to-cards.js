jQuery(document).ready(function($){
	// Robust init with retry for VE context
	function initContentToCards() {
		if (typeof jQuery === 'undefined' || !jQuery.fn) {
			setTimeout(initContentToCards, 100);
			return;
		}

		var projectsContainer = $('.ve-card-posts-container'),		
			triggerNav = $('.cd-nav-trigger'),
			logo = $('.cd-logo');
		
		if (!projectsContainer.length) {
			return; // No container found
		}
		
		triggerNav.on('click', function(){
			projectsContainer.find('.single-post.selected').removeClass('selected').find('.ve-card-post-info').scrollTop(0).removeClass('has-boxshadow');
			triggerNav.add(logo).removeClass('project-open');
		});

		projectsContainer.on('click', '.single-post > .cd-title', function(e){
			e.preventDefault();

			var selectedProject = $(this).closest('.single-post');
			var alreadyOpen = selectedProject.hasClass('selected');

			projectsContainer.find('.single-post.selected').removeClass('selected').find('.ve-card-post-info').scrollTop(0).removeClass('has-boxshadow');

			if (!alreadyOpen) {
				selectedProject.addClass('selected');
				projectsContainer.add(triggerNav).add(logo).addClass('project-open');
			} else {
				projectsContainer.add(triggerNav).add(logo).removeClass('project-open');
			}
		});

		projectsContainer.on('click', '.ve-card-scroll', function(){
			if (!projectsContainer.find('.single-post.selected').length) {
				return;
			}
			
			var selectedCard = $(this).closest('.single-post');
			if (!selectedCard.hasClass('selected')) {
				return;
			}

			//scroll down when clicking on the .ve-card-scroll arrow
			var visibleProjectContent = selectedCard.children('.ve-card-post-info'),
				scrollStep = Math.max(140, Math.round(visibleProjectContent.innerHeight() * 0.65));

			visibleProjectContent.animate({'scrollTop': visibleProjectContent.scrollTop() + scrollStep}, 280); 
		});

		//add/remove the .has-boxshadow to the project content while scrolling 
		var scrolling = false;
		projectsContainer.find('.ve-card-post-info').on('scroll', function(){
			if( !scrolling ) {
				(!window.requestAnimationFrame) ? setTimeout(updateProjectContent, 300) : window.requestAnimationFrame(updateProjectContent);
				scrolling = true;
			}
		});

		function updateProjectContent() {
			var visibleProject = projectsContainer.find('.selected').children('.ve-card-post-info'),
				scrollTop = visibleProject.scrollTop();
			( scrollTop > 0 ) ? visibleProject.addClass('has-boxshadow') : visibleProject.removeClass('has-boxshadow');
			scrolling = false;
		}
	}

	// Init immediately
	initContentToCards();
});