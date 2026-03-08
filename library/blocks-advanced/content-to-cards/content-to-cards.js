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
			if( triggerNav.hasClass('project-open') ) {
				//close project
				projectsContainer.removeClass('project-open').find('.selected').removeClass('selected').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
					$(this).children('.ve-card-post-info').scrollTop(0).removeClass('has-boxshadow');

				});
				triggerNav.add(logo).removeClass('project-open');
			}
		});

		projectsContainer.on('click', '.single-post', function(){
			var selectedProject = $(this);		
			//open project
			selectedProject.addClass('selected');
			projectsContainer.add(triggerNav).add(logo).addClass('project-open');
		});

		projectsContainer.on('click', '.ve-card-scroll', function(){
			//scroll down when clicking on the .ve-card-scroll arrow
			var visibleProjectContent =  projectsContainer.find('.selected').children('.ve-card-post-info'),
				windowHeight = $(window).height();

			visibleProjectContent.animate({'scrollTop': windowHeight}, 300); 
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