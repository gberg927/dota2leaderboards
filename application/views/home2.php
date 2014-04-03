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
            <a href="<?php echo site_url('leaderboards/americas') ?>" <?php if ($region == 'americas') {echo 'class="selected"';} ?>>Americas</a>
            <a href="<?php echo site_url('leaderboards/europe') ?>" <?php if ($region == 'europe') {echo 'class="selected"';} ?>>Europe</a>
            <a href="<?php echo site_url('leaderboards/se_asia') ?>" <?php if ($region == 'se_asia') {echo 'class="selected"';} ?>>SE Asia</a>
            <a href="<?php echo site_url('leaderboards/china') ?>" <?php if ($region == 'china') {echo 'class="selected"';} ?>>China</a>
        </div>
        <div class="grid_12">
            <?php if (count($players) > 0) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Player</th>
                        <th>Solo MMR</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($players as $player):?>
                        <tr>
                            <td><?php echo $player->rank;?></td>
                            <td>
                                <?php if ($player->team_tag == NULL) { ?>
                                    <span class="tblPlayerName"><?php echo $player->name;?></span>
                                <?php } else { ?>
                                    <span class="tblPlayerName"><?php echo $player->team_tag . '.' . $player->name;?></span>
                                <?php } ?>
                                <?php if ($player->country != NULL) { ?>
                                    <span class="tblCountry"><?php echo $player->country;?></span>
                                <?php } ?>
                            </td>
                            <td><?php echo $player->solo_mmr;?></td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
            <?php } ?>
        </div>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</body>
</html>
