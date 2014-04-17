<div class="grid_12"><h3><?php echo $name; ?></h3></div>
<div class="grid_12"><?php echo $region; ?> - <?php echo $country; ?></div>

<div class="grid_12"><h3>Rank</h3></div>
<div class="grid_12" id="rankGraph"></div>
<div class="grid_12"><h3>Solo MMR</h3></div>
<div class="grid_12" id="solo_mmrGraph"></div>

<script>
    $(document).ready(function() {
        var margin = {top: 20, right: 30, bottom: 30, left: 50},
            width = 940 - margin.left - margin.right,
            height = 500 - margin.top - margin.bottom;

        var parseDate = d3.time.format("%d-%m-%Y").parse;
        
        //both
        var x = d3.time.scale()
            .range([0, width]);
        var xAxis = d3.svg.axis()
            .tickFormat(d3.time.format("%b %e"))
            .scale(x)
            .orient("bottom");
    
        //rank specific
        var rankY = d3.scale.linear()
            .range([0, height]);
        var rankYAxis = d3.svg.axis()
            .tickFormat(d3.format("d"))
            .scale(rankY)
            .orient("left");
        var rankLine = d3.svg.line()
            .x(function(d) { return x(d.date); })
            .y(function(d) { return rankY(d.rank); });
        var rankSvg = d3.select("#rankGraph").append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
            .attr("class", "graph-svg")
            .append("g")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
        
        //solo_mmr specific
        var solo_mmrY = d3.scale.linear()
            .range([height, 0]);
        var solo_mmrYAxis = d3.svg.axis()
            .tickFormat(d3.format("d"))
            .scale(solo_mmrY)
            .orient("left");
        var solo_mmrLine = d3.svg.line()
            .x(function(d) { return x(d.date); })
            .y(function(d) { return solo_mmrY(d.solo_mmr); });
        var solo_mmrSvg = d3.select("#solo_mmrGraph").append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
            .attr("class", "graph-svg")
            .append("g")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

        d3.json("<?php echo site_url('player/data/' . $playerID) ?>", function(error, data) {
            data.forEach(function(d) {
                d.date = parseDate(d.date);
                d.rank = +d.rank;
                d.solo_mmr = +d.solo_mmr;
            });
            x.domain(d3.extent(data, function(d) { return d.date; }));
            rankY.domain(d3.extent(data, function(d) { return d.rank; }));
            solo_mmrY.domain(d3.extent(data, function(d) { return d.solo_mmr; }));

            //rank specific
            rankSvg.append("g")
                .attr("class", "x axis")
                .attr("transform", "translate(0," + height + ")")
                .call(xAxis);

            rankSvg.append("g")
                .attr("class", "y axis")
                .call(rankYAxis)
                .append("text")
                    .attr("transform", "rotate(-90)")
                    .attr("y", 6)
                    .attr("dy", ".71em");

            rankSvg.append("path")
                .datum(data)
                .attr("class", "line")
                .attr("d", rankLine);
        
            //solo_mmr specific
            solo_mmrSvg.append("g")
                .attr("class", "x axis")
                .attr("transform", "translate(0," + height + ")")
                .call(xAxis);

            solo_mmrSvg.append("g")
                .attr("class", "y axis")
                .call(solo_mmrYAxis)
                .append("text")
                    .attr("transform", "rotate(-90)")
                    .attr("y", 6)
                    .attr("dy", ".71em");

            solo_mmrSvg.append("path")
                .datum(data)
                .attr("class", "line")
                .attr("d", solo_mmrLine);
        });
    });
</script>