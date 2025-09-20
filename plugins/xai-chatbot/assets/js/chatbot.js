jQuery(document).ready(function($) {
    const $chatbot = $('#xai-chatbot');
    const $messages = $chatbot.find('.chatbot-messages');
    const $input = $chatbot.find('.chatbot-input input');
    const $sendBtn = $chatbot.find('.chatbot-input button');

    // Display welcome message
    appendMessage('bot', xaiChatbot.welcome_message);

    // Send message on button click
    $sendBtn.on('click', sendMessage);
    $input.on('keypress', function(e) {
        if (e.which === 13) sendMessage();
    });

    function sendMessage() {
        const message = $input.val().trim();
        if (!message) return;

        appendMessage('user', message);
        $input.val('');

        // AJAX request to server
        $.ajax({
            url: xaiChatbot.ajax_url,
            method: 'POST',
            data: {
                action: 'xai_chatbot_message',
                nonce: xaiChatbot.nonce,
                message: message,
            },
            success: function(response) {
                if (response.success) {
                    appendMessage('bot', response.data.message);
                } else {
                    appendMessage('bot', 'Error: ' + response.data.message);
                }
            },
            error: function() {
                appendMessage('bot', 'Failed to connect to the server.');
            }
        });
    }

    function appendMessage(sender, text) {
        const $message = $('<div>').addClass('message').addClass(sender).text(text);
        $messages.append($message);
        $messages.scrollTop($messages[0].scrollHeight);
    }

    // Rate limiting
    let lastRequest = 0;
    const minInterval = 1000; // 1 second
    $sendBtn.on('click', function() {
        const now = Date.now();
        if (now - lastRequest < minInterval) {
            appendMessage('bot', 'Please wait a moment before sending another message.');
            return;
        }
        lastRequest = now;
    });
});