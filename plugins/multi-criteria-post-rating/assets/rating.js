jQuery(document).ready(function($) {
    $('#mcpr-form').on('submit', function(e) {
        e.preventDefault();
        const post_id = $(this).data('post-id');
        const content = $(this).find('input[name="content"]').val();
        const image = $(this).find('input[name="image"]').val();
        const helpful = $(this).find('input[name="helpful"]').val();

        $.post(mcpr_ajax.ajax_url, {
            action: 'mcpr_submit',
            nonce: mcpr_ajax.nonce,
            post_id,
            content,
            image,
            helpful
        }, function(response) {
            if (response.success) {
                $('#mcpr-message').text(response.data.msg).css('color', 'green');
                $('#mcpr-form').hide();
            } else {
                $('#mcpr-message').text(response.data.msg).css('color', 'red');
            }
        });
    });
});