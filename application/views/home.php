<div class="grid_12 regionSelector">
    <a href="<?php echo site_url('leaderboards/americas') ?>" <?php if ($region == 'americas') {echo 'class="selected"';} else {echo 'class="unselected"';} ?>>Americas</a>
    <a href="<?php echo site_url('leaderboards/europe') ?>" <?php if ($region == 'europe') {echo 'class="selected"';} else {echo 'class="unselected"';} ?>>Europe</a>
    <a href="<?php echo site_url('leaderboards/se_asia') ?>" <?php if ($region == 'se_asia') {echo 'class="selected"';} else {echo 'class="unselected"';} ?>>SE Asia</a>
    <a href="<?php echo site_url('leaderboards/china') ?>" <?php if ($region == 'china') {echo 'class="selected"';} else {echo 'class="unselected"';} ?>>China</a>
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
                        <span class="tblPlayerName"><a href="<?php echo site_url('player/id/' . $player->id); ?>"><?php echo $player->name;?></a></span>
                        <?php } else { ?>
                            <span class="tblPlayerName"><a href="<?php echo site_url('player/id/' . $player->id); ?>"><?php echo $player->team_tag . '.' . $player->name;?></a></span>
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