$(document).ready(function() {
    populateLeaderBoards();
});

function populateLeaderBoards() {
    var url = 'www.dota2.com/webapi/ILeaderboard/GetDivisionLeaderboard/v0001?division=americas';//' + sDivisionToLoad;
    var tableBody = $( '#leaderboard_body' );

    $.ajax({
        url: 'http://dota2.dennisglasberg.com/proxy.php?u=' + encodeURIComponent(url),
        dataType: 'json',
        success: function( data ) {
            if ( data['leaderboard'] ) {
                var gmtCalculated = parseInt(data['time_posted']);
                var dateCalculated = new Date(gmtCalculated * 1000);

                var gmtServerTime = parseInt(data['server_time']);
                var gmtNextScheduled = parseInt(data['next_scheduled_post_time']);
                var dateScheduled = new Date(gmtNextScheduled * 1000);

                $('#leaderboard_status').html(
                        'Last Updated: ' + dateCalculated.toLocaleString() + '<br/>' +
                        'Next Update: ' + dateScheduled.toLocaleString()
                );

                idxRow = 0;
                tableBody.empty();
                var players = data['leaderboard'];
                for (var idx in players) {
                    var player = players[idx];

                    var tr = $('<tr></tr>');
                    tr.append( '<td align="center">' + player['rank'] + '</td>' );
                    var nameTD = $( '<td/>' );
                    nameTD.html('&nbsp;&nbsp;');
                    if ('name' in player) {
                        if ('team_tag' in player && player['team_tag']) {
                            nameTD.append($('<span/>').html('<a href="http://dotabuff.com/search?q=' + escape(player['name']) + '">' + player['team_tag'] + '.' + player['name'] + '</a>'))
                        }
                        else {
                            nameTD.append($('<span/>').html('<a href="http://dotabuff.com/search?q=' + escape(player['name']) + '">' + player['name'] + '</a>'))
                        }
                        if ( 'sponsor' in player && player['sponsor'] )
                            nameTD.append($('<span/>').text(player['sponsor']));
                        if ( 'country' in player )
                            nameTD.append($('<span/>').text(player['country']));
                    }
                    else {
                        nameTD.addClass('no_official_data');
                        nameTD.append('Waiting for player to submit official profile');
                    }

                    tr.append(nameTD)

                    tr.append('<td>' + player['solo_mmr'] + '</td>');
                    tableBody.append(tr);
                }
            }
            else {
                $('#leaderboard_status').html( 'This leaderboard is currently unavailable.');
            }
        },
        error: function(data, status, xhr) {
            $('#leaderboard_status').html( 'This leaderboard is currently unavailable.' );
        }
    });
}