<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dota 2 - Leaderboards</title>
    <meta charset="utf-8">
    <meta name="description" content="Dota 2 Leaderboars with some modifications" />
    <meta name="keywords" content="dota 2, dota, 2, leaderboards, leader boards, leader, boards" />
    <link rel="stylesheet" href="<?php echo base_url('css/960.css'); ?>"/>
    <link rel="stylesheet" href="<?php echo base_url('css/style.css'); ?>"/>
    <link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css'>
</head>
<body>
    <div class="container_12">
        <div class="grid_12" id="header">
            <h1>Dota 2 Leaderboards</h1>
        </div>
        <div class="grid_12 regionSelector">
            <a id="link_americas" href="#americas">Americas</a>
            <a id="link_europe" href="#europe">Europe</a>
            <a id="link_se_asia" href="#se_asia">SE Asia</a>
            <a id="link_china" href="#china">China</a>
        </div>
        <div class="grid_12" id="leaderboard_status"></div>
        <div class="grid_12">
            <table>
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Player</th>
                        <th>Solo MMR</th>
                    </tr>
                </thead>
                <tbody id="leaderboard_body"></tbody>
            </table>
        </div>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>js/leaderboards.js"></script>
</body>
</html>
