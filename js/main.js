$(document).ready(function () {
    // fancybox
    $(".fancybox").fancybox();
    // contributors
    $.ajax({
        type: 'GET',
        url: 'https://api.github.com/repos/cornernote/yii-audit-module/contributors',
        dataType: 'json',
        success: function(result) {
            for(i in result) {
                $('#contributors ul').append('<li class="thumbnail"><a href="' + result[i].html_url + '" target="_blank"><img src="' + result[i].avatar_url + 's=64" title="' + result[i].login + ' (' + result[i].contributions + ' contributions)"></a></li>');
            }
            $('#contributors h5').html(result.length + ' developer'+ (result.length>1 ? 's' : '') + ' contributed to this project:');
        }
    });
});
