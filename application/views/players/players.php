<div class="container">
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
                    <tr>
                        <td><span><?php echo $player->rank;?></span></td>
                        <td>
                            <?php if ($player->team_tag == NULL) { ?>
                            <span><a href="<?php echo site_url('player/id/' . $player->id); ?>"><?php echo $player->name;?></a></span>
                            <?php } else { ?>
                                <span><a href="<?php echo site_url('player/id/' . $player->id); ?>"><?php echo $player->team_tag . '.' . $player->name;?></a></span>
                            <?php } ?>
                            <?php if ($player->country != NULL) { ?>
                                <span ><?php echo $player->country;?></span>
                            <?php } ?>
                        </td>
                        <td><span><?php echo $player->division;?></span></td>
                        <td><span><?php echo $player->solo_mmr;?></span></td>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
    <script>
        $(document).ready(function(){
            $(function(){
                $("#playersTable").tablesorter();
            });
        });
    </script>