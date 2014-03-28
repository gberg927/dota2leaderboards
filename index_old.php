
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<link rel="shortcut icon" href="/favicon.ico" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=9" />
	<link href="http://www.dota2.com/public/css/global.css?v=988192991" rel="stylesheet" type="text/css" >
<link href="http://www.dota2.com/public/css/publicheader.css?v=1737646907" rel="stylesheet" type="text/css" >
<link href="http://cdn.steamcommunity.com/public/shared/css/buttons.css?v=3818641943" rel="stylesheet" type="text/css" >
<link href="http://cdn.steamcommunity.com/public/shared/css/shared_global.css?v=1015216316" rel="stylesheet" type="text/css" >
<link href="http://www.dota2.com/public/css/leaderboards_external.css?v=1929575950" rel="stylesheet" type="text/css" >
	<script type="text/javascript" src="http://cdn.dota2.com/apps/dota2/javascript/static/jquery-1.7.1.min.js?v=35"></script>
<script type="text/javascript">$J = jQuery;
</script><script type="text/javascript" src="http://cdn.steamcommunity.com/public/shared/javascript/shared_global.js?v=3496127634&amp;l=english"></script>
	<script type="text/javascript" src="http://cdn.dota2.com/apps/dota2/javascript/static/jquery.ba-bbq.min.js?v=35"></script>
	<script type="text/javascript" src="http://cdn.dota2.com/apps/dota2/javascript/static/moment-with-langs.min.js?v=35"></script>
	<title>Dota 2 - Leaderboards</title>
<script language="javascript">
$( function() {

	var sLoadedDivision = '';
	var idxRow;
	var tableBody = $( '#leaderboard_body' );

	var CreateTableRow = function()
	{
		idxRow += 1;
		return $('<tr bgcolor="#' + ( ( idxRow % 2 ) ? '181818' : '202020' ) + '">');
	}

	var LoadDivisionData = function()
	{
		var sDivisionToLoad = $.param.fragment();
		if ( sDivisionToLoad == '' )
		{
			var tz = ( new Date() ).getTimezoneOffset() / 60;
			console.log( tz );
			if ( ( 1 <= tz && tz <= 11 ) || tz < -13 )
				sDivisionToLoad = 'americas';
			else if ( tz >= -5 )
				sDivisionToLoad = 'europe';
			else
				sDivisionToLoad = 'china';
		}
		if ( sDivisionToLoad == sLoadedDivision )
			return;
		sLoadedDivision = sDivisionToLoad;
		if ( sLoadedDivision != window.location.hash )
			window.location.hash = sLoadedDivision;

		$( 'a.selected_division' ).removeClass( 'selected_division' ).addClass( 'unselected_division' );
		$( 'a[href="#' + sDivisionToLoad + '"]' ).addClass( 'selected_division' ).removeClass( 'unselected_division' );

		$( '#leaderboard_status' ).html( 'Loading leaderboard...' );

		idxRow = 0;
		tableBody.empty();
		for ( var i = 0 ; i < 200 ; ++i )
		{
			tableBody.append( CreateTableRow().html('<td>&nbsp</td><td/><td/>') );
		}
                
		var url = 'www.dota2.com/webapi/ILeaderboard/GetDivisionLeaderboard/v0001?division=' + sDivisionToLoad;
		
		$.ajax({
			url: 'http://dota2.dennisglasberg.com/proxy.php?u=' + encodeURIComponent(url),
			dataType: 'json',
			success: function( data )
				{
					if ( data['leaderboard'] )
					{
						var gmtCalculated = parseInt( data['time_posted'] );
						var dateCalculated = new Date( gmtCalculated * 1000 );

						var gmtServerTime = parseInt( data['server_time'] );
						var gmtNextScheduled = parseInt( data['next_scheduled_post_time'] );
						var dateScheduled = new Date( gmtNextScheduled * 1000 );

						$( '#leaderboard_status' ).html(
							'Last Updated: ' + dateCalculated.toLocaleString() + '<br/>' +
							'Next Update: ' + dateScheduled.toLocaleString()
						);

						idxRow = 0;
						tableBody.empty();
						var players = data['leaderboard'];
						for ( var idx in players )
						{
							var player = players[idx];
							
							var tr = CreateTableRow();
							tr.append( '<td align="center">' + player['rank'] + '</td>' );
							var nameTD = $( '<td align="left" style="overflow:hidden" width="300"/>' );
							nameTD.html( '&nbsp;&nbsp;' );
							if ( 'name' in player )
							{
								var playerText = '';
								if ( 'team_tag' in player && player['team_tag'] )
									playerText = '<span clas="team_tag">' + player['team_tag'] + '.</span>';
									//nameTD.append( $( '<span/>' ).addClass('team_tag').text( player['team_tag'] + '.' ) );
								playerText += '<span class="player_name">' + player['name'] + '</span>';
								//nameTD.append( $( '<span/>' ).addClass('player_name').text( player['name'] ) );
								nameTD.append('<a href="http://dotabuff.com/search?q=' + escape(player['name']) + '">' + playerText + '</a>');
								if ( 'sponsor' in player && player['sponsor'] )
									nameTD.append( $( '<span/>' ).addClass('sponsor').text( '.' + player['sponsor'] ) );
								if ( 'country' in player )
									nameTD.append( $( '<div/>' ).css('float', 'right').html( '<img src="http://cdn.steamcommunity.com/public/images/countryflags/' + player['country'] + '.gif">&nbsp;&nbsp;' ) );
							}
							else
							{
								nameTD.addClass('no_official_data');
								nameTD.append('Waiting for player to submit official profile');
							}

							tr.append( nameTD )

							tr.append( '<td align="center">' + player['solo_mmr'] + '</td>' );
							tableBody.append( tr );
						}
					}
					else
					{
						$( '#leaderboard_status' ).html( 'This leaderboard is currently unavailable.' );
					}
				},
			error: function( data, status, xhr )
				{
					$( '#leaderboard_status' ).html( 'This leaderboard is currently unavailable.' );
				}
		});
	}

	$( window ).bind( 'hashchange', function (e) {
		LoadDivisionData();
	} )

	LoadDivisionData();
} );


</script>
</head>
<body>
<center>
    <h1>TESTER</h1>


	<div id="navBarBGRepeat">
					<div id="navBarShadow"></div>
				<div id="navBarBG">
			<div id="navBar">
				<div id="navLoginAndLanguage">

																												<a href="https://steamcommunity.com/oauth/login?client_id=9B2C1229&response_type=token&state=leaderboards">Login</a>
															&nbsp;|&nbsp;&nbsp;

					<div id="languageSelector">
						<a href="javascript:void(0)">Language</a> <img style="padding-bottom: 2px;" src="http://cdn.dota2.com/apps/dota2/images/header/btn_arrow_down.png" width="9" height="4" border="0" /><br />
						<div style="display:none;" id="languageList">
																						<a class="languageItem" href="?l=brazilian">Português-Brasil (Portuguese-Brazil)</a>
																						<a class="languageItem" href="?l=bulgarian">Български (Bulgarian)</a>
																						<a class="languageItem" href="?l=czech">čeština (Czech)</a>
																						<a class="languageItem" href="?l=danish">Dansk (Danish)</a>
																						<a class="languageItem" href="?l=dutch">Nederlands (Dutch)</a>
																													<a class="languageItem" href="?l=finnish">Suomi (Finnish)</a>
																						<a class="languageItem" href="?l=french">Français (French)</a>
																						<a class="languageItem" href="?l=german">Deutsch (German)</a>
																						<a class="languageItem" href="?l=greek">Ελληνικά (Greek)</a>
																						<a class="languageItem" href="?l=hungarian">Magyar (Hungarian)</a>
																						<a class="languageItem" href="?l=italian">Italiano (Italian)</a>
																						<a class="languageItem" href="?l=japanese">日本語 (Japanese)</a>
																						<a class="languageItem" href="?l=koreana">한국어 (Korean)</a>
																						<a class="languageItem" href="?l=norwegian">Norsk (Norwegian)</a>
																						<a class="languageItem" href="?l=polish">Polski (Polish)</a>
																						<a class="languageItem" href="?l=portuguese">Português (Portuguese)</a>
																						<a class="languageItem" href="?l=russian">Русский (Russian)</a>
																						<a class="languageItem" href="?l=romanian">Română (Romanian)</a>
																						<a class="languageItem" href="?l=schinese">简体中文 (Simplified Chinese)</a>
																						<a class="languageItem" href="?l=spanish">Español (Spanish)</a>
																						<a class="languageItem" href="?l=swedish">Svenska (Swedish)</a>
																						<a class="languageItem" href="?l=tchinese">繁體中文 (Traditional Chinese)</a>
																						<a class="languageItem" href="?l=thai">ไทย (Thai)</a>
																						<a class="languageItem" href="?l=turkish">Türkçe (Turkish)</a>
													</div>
					</div>

															
														</div>
									<span class="navItem">
						<a class="navBarItem" href="http://blog.dota2.com/">
							<img border="0" class="top" src="http://cdn.dota2.com/apps/dota2/images/nav/en_dota_logo_over.png"/>
							<img border="0" class="bottom" src="http://cdn.dota2.com/apps/dota2/images/nav/en_dota_logo.png"/>
						</a>
					</span>

																		<span class="navItem">
								<a class="navBarItem" href="http://www.dota2.com/store/">Store</a>
															</span>
																								<span class="navItem">
								<a class="navBarItem" href="javascript:void(0)">Heropedia</a>
								<div style="display:none;" class="menuSubItemList">
																				<a href="http://www.dota2.com/heroes/">Heroes</a>
																				<a href="http://www.dota2.com/items/">Item Explorer</a>
																				<a href="http://www.dota2.com/workshop/builds">Builds</a>
																				<a href="http://steamcommunity.com/app/570/guides" target="_blank">Steam Guides</a>
																				<a href="http://www.dota2.com/quiz">The Shopkeeper's Quiz</a>
														</div>
								<img class="dn_arrow" src="http://cdn.dota2.com/apps/dota2/images/nav/arrow.png"/>
							</span>
																								<span class="navItem">
								<a class="navBarItem" href="http://steamcommunity.com/workshop/dota2/" target="_blank">Workshop</a>
								<div class="external_img"><img src="http://cdn.dota2.com/apps/dota2/images/trans.gif" width="10" height="8" border="0" /></div>							</span>
																								<span class="navItem">
								<a class="navBarItem" href="http://www.playdota.com/forums/" target="_blank">Forums</a>
								<div class="external_img"><img src="http://cdn.dota2.com/apps/dota2/images/trans.gif" width="10" height="8" border="0" /></div>							</span>
																		</div>
		</div>
	</div>
<script language="javascript">

	function MM_preloadImages() { //v3.0
	  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
		var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
		if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
	}

	function PreloadHeaderImages()
	{
		//  Preload header rollover images
		MM_preloadImages(
			'http://cdn.dota2.com/apps/dota2/images/nav/en_dota_logo_over.png'
		);
	}

			if ( document.addEventListener ) {
		document.addEventListener( "DOMContentLoaded", PreloadHeaderImages, false );
	} else if ( document.attachEvent ) {
		document.attachEvent( 'onDomContentLoaded', PreloadHeaderImages );
	}

</script>



	<script language="javascript">
		function closeSubMenu( target ) {
			$(target).find('.dn_arrow').attr( "src", "http://cdn.dota2.com/apps/dota2/images/nav/arrow.png" );
			$(target).find('.menuSubItemList').hide();
		}

		function closeAllSubMenus() {
			$('.navItem').each( function() {
				closeSubMenu( $(this) );
			});
		}

		function openSubMenu( target ) {
			closeAllSubMenus();

			$(target).find('.dn_arrow').attr( "src", "http://cdn.dota2.com/apps/dota2/images/nav/arrow_over.png" );

			// Show the sub-menu
			var subItemList = $(target).find('.menuSubItemList');
			if ( subItemList.length ) {
				var itemTop = $(target).position().top + $(target).outerHeight();
				var itemLeft = $(target).position().left;
				subItemList
					.css({
						"top": itemTop,
						"left": itemLeft
					})
					.fadeIn( 'fast' );
			}
		}

		$(document).ready( function () {
			// Deal with the nav bars
			$('.navItem').each( function() {
				$(this).hover( function () {
					$(this)
						.find('a.navBarItem img.top')
						.animate({
							"opacity": 1
						}, 100 );
					if ( $(this).find('.menuSubItemList').length !== 0 ) {
						openSubMenu( this );
					}
				},
				function () {
					$(this)
					.find('a.navBarItem img.top')
					.animate({
						"opacity": 0
					}, 100 );
					if ( $(this).find('.menuSubItemList').length !== 0 ) {
						closeSubMenu(this);
					}
				}
				);
			});

			// Deal with header drop-downs
			var strMenus = '#languageSelector';
			
			$(strMenus).each( function() {
				$(this).hover( function () {
					//show its submenu
					$( this ).find( 'div' ).fadeIn( 'fast' );
				},
				function () {
					//hide its submenu
					$( this ).find( 'div' ).hide();
				}
				);
			});
		});

	</script>

</center>
<div id="outerContainer">
	<div id="contentContainer">
		<div id="content">
			<img id="globe" src="http://cdn.dota2.com/apps/dota2/images/leaderboards/globe.png" width="339" height="339" border="0" />
			<h1>World Leaderboards</h1>
			<h2>Top 200 Players by Solo Ranked MMR</h2>
						<div id="regionSelect">
				<span id="link_americas"><a class="unselected_division" href="#americas">Americas</a></span>
				<span id="link_europe"><a class="unselected_division" href="#europe">Europe</a></span>
				<span id="link_se_asia"><a class="unselected_division" href="#se_asia">SE Asia</a></span>
				<span id="link_china"><a class="unselected_division" href="#china">China</a></span>
			</div>
			<div style="position:relative;z-index:2;">
				<div id="leaderboard_status" style="height:60px">
				</div>
				<table style="position:relative;z-index:2;margin:0 auto;" border="2" bordercolor="#3b3a38" cellspacing="0" cellpadding="2">
					<thead>
					<tr>
						<th align="center">
							&nbsp;&nbsp;Division&nbsp;&nbsp;<br>Rank						</th>
						<th valign="middle" width="400" align="left">
							&nbsp;&nbsp;Player						</th>
						<th valign="middle">
							&nbsp;&nbsp;Solo MMR&nbsp;&nbsp;
						</th>
					</tr>
					</thead>
					<tbody id="leaderboard_body" />
				</table>
			</div>
			<div id="faqArea">
				<p><br /></p>

		        <div class="question">Who is eligible to appear on the leaderboard?</div>
       			<div class="answer">To qualify, a player must have all of the following:
       				<ul>
       					<li>At least 300 lifetime matchmade games played. (Unranked or ranked PvP matches only.)</li>
       					<li>At least 100 lifetime solo ranked games</li>
       					<li>At least 15 solo ranked games in the last 21 days in the same division</li>
       					<li>Official player info on file</li>
       				</ul></div> 
       			
		        <div class="question">How do I know what division I'm in?</div>
       			<div class="answer">It's the division in which you have played the most solo ranked games in the past 21 days. (In case of a tie, we use the division that has the more recent match.)</div> 
       			
		        <div class="question">Does a match still qualify towards the recency requirement if somebody abandons, times out due to network problems, etc?</div>
       			<div class="answer">Yes, provided that MMRs are updated.  If the match is thrown out for any reason, then it is not a qualifying match.</div> 
       			
		        <div class="question">How do I give you my official player info?</div>
       			<div class="answer">If your solo MMR puts you within reach of a leaderboard, and you meet the eligibility requirements but have not provided your official information, we'll send you a notification in game that will make it possible to provide this information.</div> 
       			
		        <div class="question">Which server regions are assigned to which divisions?</div>
       			<div class="answer">
					Americas: US West, US East, South America<br />
					Europe: Europe West, Europe East, Russia, South Africa<br />
					China: Perfect World Telecom, Perfect World Unicom<br />
					Southeast Asia: South Korea, SE Asia, Australia<br />
</div> 
       			
		        <div class="question">When are leaderboards updated?</div>
       			<div class="answer">Daily at 22:00 GMT.</div> 

		        <div class="question">Where's the global leaderboard?</div>
       			<div class="answer">The MMR of each division is on a different scale, and comparing MMRs across divisions is not currently meaningful.</div> 
       			
			</div>
		</div>
	</div>
</div>
<div id="bottomContainer_1"><div id="bottomContainer_2">
    <div id="logoValve"><a href="http://www.valvesoftware.com/"><img src="http://cdn.dota2.com/apps/dota2/images//spoilsofwar/logo_valve.png"></a></div>	
    <div id="legal">&copy; Valve Corporation, all rights reserved. Valve, the Valve logo, Steam, the Steam logo, Dota and the Dota logo are trademarks<br />and/or registered trademarks of Valve Corporation.</div>
</div></div>
</body>
</html>
