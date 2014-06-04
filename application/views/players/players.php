<div class="container">
    <h3>All Players</h3>
    <div class="table-responsive">
        <table id="playersTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Name</th>
                    <th>Region</th>
                    <th>Solo MMR</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($players as $player):?>
                    <tr class="player">
                        <td><span><?php echo $player->rank;?></span></td>
                        <td>
                            <?php if ($player->team_tag == NULL) { ?>
                            <span><a href="<?php echo site_url('player/id/' . $player->id); ?>"><?php echo $player->name;?></a></span>
                            <?php } else { ?>
                                <span><a href="<?php echo site_url('player/id/' . $player->id); ?>"><?php echo $player->team_tag . '.' . $player->name;?></a></span>
                            <?php } ?>
                            <?php if ($player->country != NULL) { ?>
                                <span class="country">
                                    <img src="<?php echo base_url() . 'images/countries/' . $player->country . '.png'; ?>" class="img-responsive country" alt="<?php echo $player->country; ?>" title="<?php echo $player->commonName; ?>">
                                </span>
                            <?php } ?>
                        </td>
                        <td><span><?php echo format_regions($player->division);?></span></td>
                        <td><span><?php echo $player->solo_mmr;?></span></td>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
    
    <div clas="row">
        <div class="col-md-12">
            <span>Last Updated: <?php echo $lastUpdate; ?></span>
        </div>
    </div>
    <div clas="row">
        <div class="col-md-12">
            <span>Next Update: <?php echo $nextUpdate; ?></span>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $(function(){
                $("#playersTable").tablesorter();
            });
        });
    </script>