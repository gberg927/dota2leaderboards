$(document).ready(function() {
        // Tooltip only Text
        $('.country-flag').hover(function(){
                var title = $(this).attr('title');
                if (title.length == 2) {
                        var url = 'https://restcountries.eu/rest/v1/alpha/' + title;
                        $.ajax({ 
                                type: 'GET', 
                                url: url, 
                                dataType: 'json',
                                context: $(this),
                                success: function (data) { 
                                        if (data['name'] != null) {
                                                $(this).attr('title', data['name']);
                                        }
                                }
                        });
                }
        });
});