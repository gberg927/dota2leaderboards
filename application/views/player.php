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

<div class="grid_12"><h3>Rank</h3></div>
<div class="grid_12" id="rankGraph"></div>
<div class="grid_12"><h3>Solo MMR</h3></div>
<div class="grid_12" id="solo_mmrGraph"></div>

<script>
    $( document ).ready(function() {
        var rankArray = <?php echo $rankArray; ?>;
        var solo_mmrArray = <?php echo $solo_mmrArray; ?>;
        
        var rankGraph = new Rickshaw.Graph( {
            element: document.querySelector("#rankGraph"),
            width: 580,
            height: 250,
            series: [ {
                    color: 'steelblue',
                    data: rankArray
            } ]
        } );

        var axes = new Rickshaw.Graph.Axis.Time({graph: rankGraph});

        rankGraph.render();
        
        var solo_mmrGraph = new Rickshaw.Graph( {
            element: document.querySelector("#solo_mmrGraph"),
            width: 580,
            height: 250,
            series: [ {
                    color: 'steelblue',
                    data: solo_mmrArray
            } ]
        });

        axes = new Rickshaw.Graph.Axis.Time({graph: solo_mmrGraph});

        solo_mmrGraph.render();
    });
</script>