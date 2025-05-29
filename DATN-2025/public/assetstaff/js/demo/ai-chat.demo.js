/*
Template Name: STUDIO ASP - Responsive Bootstrap 5 Admin Template
Version: 5.0.0
Author: Sean Ngu
Website: http://www.seantheme.com/studio/
*/

var toggleAiTab = function(tabActive, input) {
	const tabArray = ['aiChatIntro', 'aiChatNew', 'aiChatPrev'];
	
	tabArray.forEach(tab => {
		const elm = document.getElementById(tab);
		const ps = (elm) ? elm.perfectScrollbar : '';
		
		if (elm) {
			if (tabActive && tabActive == tab) {
				elm.classList.add('show', 'active');
				
				elm.scrollTop = elm.scrollHeight;
				
				if (ps) {
					ps.update();
				}
				if (input) {
					aiSendMessage(input);
				}
			} else {
				elm.classList.remove('show', 'active');
			}
		}
	});
}

var toggleAiTabClick = function() {
	const aiChatSidebar = new bootstrap.Offcanvas('#aiChatSidebar');
	
	$(document).on('click', '[data-toggle-ai-tab]', function(e) {
		e.preventDefault();
		
		const tabActive = $(this).attr('data-toggle-ai-tab');
		const input = $(this).attr('data-toggle-ai-input');
		
		if (tabActive == 'aiChatPrev') {
			const elm = $(this).closest('div');
			$(elm).toggleClass('bg-light');
			$('[data-toggle-ai-tab="aiChatPrev"]').not(this).closest('div').removeClass('bg-light');
		} else {
			$('[data-toggle-ai-tab="aiChatPrev"]').closest('div').removeClass('bg-light');
			$('#aiChatNew .d-flex').remove();
		}
		
		toggleAiTab(tabActive, input);
		
		if (aiChatSidebar) {
			aiChatSidebar.hide();
		}
	});
};

var aiInputButton = function() {
	document.getElementById('sendButton').addEventListener('click', (e) => {
		const input = document.getElementById('userInput');
		
		aiSendMessage(input.value);
		input.value = '';
	});
	document.getElementById('userInput').addEventListener('keypress', (e) => {
		const input = document.getElementById('userInput');
		if (e.key === 'Enter') {
			aiSendMessage(input.value);
			input.value = '';
		}
	});
}

var aiSendMessage = function(input) {
	const messages = document.getElementById('aiChatNew');
	
	toggleAiTab('aiChatNew');
	
	if (input) {
		messages.innerHTML += `
			<div class="d-flex justify-content-end align-items-end mb-3">
				<div class="rounded-4 px-3 py-2 bg-body mw-75">
					${input}
				</div>
				<div>
					<div class="w-30px h-30px my-2px ms-2 me-2 rounded-circle text-white bg-dark d-flex align-items-center justify-content-center">
						S
					</div>
				</div>
			</div>
		`;

		document.querySelectorAll('.typing-dots-container').forEach(el => el.remove());

		const typingIndicator = document.createElement('div');
		typingIndicator.className = "d-flex justify-content-start mb-3 ai-spinner";
		typingIndicator.innerHTML = `<div class="px-2"><div class="spinner-grow spinner-grow-sm"></div></div>`;
		messages.appendChild(typingIndicator);

		messages.scrollTop = messages.scrollHeight;

		setTimeout(() => {
			document.querySelector('.ai-spinner')?.remove();

			const aiResponses = [
				"That's an interesting question! Let me think...",
				"Good point! What else do you think about this?",
				"That makes sense. Can you clarify a bit more?",
				"Great perspective! Here’s my take on it...",
				"Good question! I believe the answer is...",
				"I appreciate your input! Let’s explore this further.",
				"That's a unique thought! Here’s another angle to consider...",
				"Nice one! Have you considered looking at it from this perspective?"
			];
			const aiResponse = aiResponses[Math.floor(Math.random() * aiResponses.length)];
			const aiMessage = document.createElement('div');
			aiMessage.className = 'mb-3';
			aiMessage.innerHTML = `
				<div class="d-flex justify-content-start align-items-end">
					<div>
						<div class="w-30px h-30px my-2px ms-2 fs-16px me-2 rounded-circle bg-theme text-theme-color d-flex align-items-center justify-content-center">
							<i class="fa fa-shekel-sign"></i>
						</div>
					</div>
					<div class="rounded-4 px-3 py-2 bg-body mw-75">
						<div class="typing-animation">${aiResponse}</div>
					</div>
				</div>
			`;

			messages.appendChild(aiMessage);
			const typingText = aiMessage.querySelector('.typing-animation');
			typingText.style.animation = `typing .25s steps(20, end), blink-caret 0.75s step-end infinite`;

			typingText.addEventListener('animationend', () => {
				typingText.style.animation = "none";
			});

			messages.scrollTop = messages.scrollHeight;
		}, 2000);
	}
}

$(document).ready(function() {
	toggleAiTabClick();
	aiInputButton();
});