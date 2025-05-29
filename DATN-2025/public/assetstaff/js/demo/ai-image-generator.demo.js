/*
Template Name: STUDIO ASP - Responsive Bootstrap 5 Admin Template
Version: 5.0.0
Author: Sean Ngu
Website: http://www.seantheme.com/studio/
*/

var toggleAiGenerateImage = function() {
	$(document).on('submit', '[data-form-submit="generate-image"]', function(e) {
		e.preventDefault();
		
		const aiContainer = document.getElementById('aiGeneratedContainer');
		const aiGenerating = document.getElementById('aiGeneratingResult');
		const aiGenerated = document.getElementById('aiGeneratedResult');
		
		if (aiContainer && aiGenerating && aiGenerated) {
			aiContainer.classList.remove('d-none');
			
			aiGenerating.classList.remove('d-none');
			aiGenerating.classList.add('show');
			
			const aiContainerPosition = aiContainer.getBoundingClientRect().top + window.scrollY;
  		window.scrollTo({ top: aiContainerPosition - 100, behavior: "smooth" });
		
			setTimeout(() => {
				aiGenerating.classList.add('d-none');
				aiGenerated.classList.remove('d-none');
			}, 3000);
		}
	});
};

var toggleAiDropdownSelection = function() {
	$(document).on('click', '[data-select="ai-dropdown-selection"]', function(e) {
		e.preventDefault();
		
		const targetValue = $(this).attr('data-value');
		const targetContainer = $(this).attr('data-target');
		
		$(targetContainer).html(targetValue);
	});
};

var previewImage = function(event) {
	const file = event.target.files[0];
	if (file) {
		const reader = new FileReader();
		reader.onload = function (e) {
			const img = document.getElementById('previewImage');
			if (img) {
				img.src = e.target.result;
				img.classList.remove('d-none');
			} else {
				img.classList.add('d-none');
			}
		};
		reader.readAsDataURL(file);
	}
}

$(document).ready(function() {
	toggleAiGenerateImage();
	toggleAiDropdownSelection();
});