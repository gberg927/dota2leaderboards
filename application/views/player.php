<div class="grid_12"><h3><?php echo $name; ?></h3></div>
<div class="grid_12"><?php echo $region; ?> - <?php echo $country; ?></div>
<div class="grid_12">
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Rank</th>
                <th>Solo MMR</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ranks as $rank):?>
                <tr>
                    <td><?php echo $rank->date;?></td>
                    <td><?php echo $rank->rank;?></td>
                    <td><?php echo $rank->solo_mmr;?></td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>