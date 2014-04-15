<div class="grid_12"><h3><?php echo $name; ?></h3></div>
<div class="grid_12"><?php echo $region; ?> - <?php echo $country; ?></div>
<?php /*
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
 */ ?>

<div class="grid_12"><h3>Rank</h3></div>
<div class="grid_12" id="rankGraph"></div>
<div class="grid_12"><h3>Solo MMR</h3></div>
<div class="grid_12" id="solo_mmrGraph"></div>

<script>
    var margin = {top: 20, right: 20, bottom: 30, left: 50},
        width = 960 - margin.left - margin.right,
        height = 500 - margin.top - margin.bottom;

    var parseDate = d3.time.format("%d-%b-%y").parse;

    var x = d3.time.scale()
        .range([0, width]);

    var y = d3.scale.linear()
        .range([height, 0]);

    var xAxis = d3.svg.axis()
        .scale(x)
        .orient("bottom");

    var yAxis = d3.svg.axis()
        .scale(y)
        .orient("left");

    var line = d3.svg.line()
        .x(function(d) { return x(d.date); })
        .y(function(d) { return y(d.solo_mmr); });

    var svg = d3.select("body").append("svg")
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
    
    d3.json("<?php echo site_url('player/solo_mmr/' . $playerID); ?>", function(error, data) {
        data.forEach(function(d) {
            d.date = parseDate(d.date);
            d.solo_mmr = +d.solo_mmr;
        });
        
        x.domain(d3.extent(data, function(d) { return d.date; }));
        y.domain(d3.extent(data, function(d) { return d.solo_mmr; }));
        
        svg.append("g")
            .attr("class", "x axis")
            .attr("transform", "translate(0," + height + ")")
            .call(xAxis);

        svg.append("g")
            .attr("class", "y axis")
            .call(yAxis)
            .append("text")
                .attr("transform", "rotate(-90)")
                .attr("y", 6)
                .attr("dy", ".71em")
                .style("text-anchor", "end")
                .text("Price ($)");

        svg.append("path")
            .datum(data)
            .attr("class", "line")
            .attr("d", line);
    });
</script>