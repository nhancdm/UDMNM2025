jQuery(document).ready(function($) {
    $('.mci-chat-toggle').click(function() {
        $('.mci-chat-buttons').toggleClass('active');
        $(this).find('span').text($('.mci-chat-buttons').hasClass('active') ? '×' : '+');
    });
});