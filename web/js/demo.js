(function($){
    $('#myform').submit(function(e){
        var val = $(this).find('#in').val();
        $('ul.list').append('<li>' + val + '</li>');
        e.preventDefault();
    });
})(jQuery);