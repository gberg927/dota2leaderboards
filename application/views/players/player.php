<div class="container">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>
                    <?php if ($team_tag == NULL){echo $name;}else{echo '<small>' . $team_tag . '</small>' . $name;} ?>
                    <?php if ($country != NULL) { ?>
                        <img class="superscript" src="<?php echo base_url() . 'images/countries/' . $country . '.png'; ?>" alt="<?php echo $country; ?>" title="<?php echo $country; ?>">
                    <?php } ?>
                </h1>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <span title="Rank">Rank: <?php echo $rank; ?></span>
                    </div>
                    <div class="col-md-4">
                        <span title="Solo MMR">Solo MMR: <?php echo $solo_mmr; ?></span>
                    </div>
                    <div class="col-md-4">
                        <span>Region: <a href="<?php echo site_url('region/' . $region) ?>"><?php echo format_regions($region); ?></a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">Rank</div>
            <div class="panel-body">
                <div class="graph" id="rankGraph"></div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">Solo MMR</div>
            <div class="panel-body">
                <div class="graph" id="solo_mmrGraph"></div>
            </div>
        </div>
    </div>
    <script>        
        $(document).ready(function() {
            var margin = {top: 20, right: 50, bottom: 30, left: 50},
                width = 960 - margin.left - margin.right,
                height = 500 - margin.top - margin.bottom;
    
            var parseDate = d3.time.format("%d-%m-%Y").parse,
            bisectDate = d3.bisector(function(d) { return d.date; }).left;

            var x = d3.time.scale()
                .range([0, width]);

            var xAxis = d3.svg.axis()
                .tickFormat(d3.time.format("%b %e"))
                .scale(x)
                .orient("bottom");

            var yRank = d3.scale.linear()
                .range([0, height]);
            var ySolo_mmr = d3.scale.linear()
                .range([height, 0]);

            var yAxisRank = d3.svg.axis()
                .tickFormat(d3.format("d"))
                .scale(yRank)
                .orient("left");
            var yAxisSolo_mmr = d3.svg.axis()
                .tickFormat(d3.format("d"))
                .scale(ySolo_mmr)
                .orient("left");

            var lineRank = d3.svg.line()
                .x(function(d) { return x(d.date); })
                .y(function(d) { return yRank(d.rank); });
            var lineSolo_mmr = d3.svg.line()
                .x(function(d) { return x(d.date); })
                .y(function(d) { return ySolo_mmr(d.solo_mmr); });

            var svgRank = d3.select("#rankGraph").append("svg")
                .attr("width", width + margin.left + margin.right)
                .attr("height", height + margin.top + margin.bottom)
                .append("g")
                    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
            var svgSolo_mmr = d3.select("#solo_mmrGraph").append("svg")
                .attr("width", width + margin.left + margin.right)
                .attr("height", height + margin.top + margin.bottom)
                .append("g")
                    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
            
            d3.json("<?php echo site_url('player/data/' . $playerID); ?>", function(error, data) {
                data.forEach(function(d) {
                    d.date = parseDate(d.date);
                    d.solo_mmr = +d.solo_mmr;
                });

                data.sort(function(a, b) {
                    return a.date - b.date;
                });

                x.domain([data[0].date, data[data.length - 1].date]);
                yRank.domain(d3.extent(data, function(d) { return d.rank; }));
                ySolo_mmr.domain(d3.extent(data, function(d) { return d.solo_mmr; }));

                svgRank.append("g")
                    .attr("class", "x axis")
                    .attr("transform", "translate(0," + height + ")")
                    .call(xAxis);
                svgSolo_mmr.append("g")
                    .attr("class", "x axis")
                    .attr("transform", "translate(0," + height + ")")
                    .call(xAxis);

                svgRank.append("g")
                    .attr("class", "y axis")
                    .call(yAxisRank)
                    .append("text")
                        .attr("transform", "rotate(-90)")
                        .attr("y", 6)
                        .attr("dy", ".71em")
                        .style("text-anchor", "end")
                        .text("Rank");
                svgSolo_mmr.append("g")
                    .attr("class", "y axis")
                    .call(yAxisSolo_mmr)
                    .append("text")
                        .attr("transform", "rotate(-90)")
                        .attr("y", 6)
                        .attr("dy", ".71em")
                        .style("text-anchor", "end")
                        .text("Solo MMR");

                svgRank.append("path")
                    .datum(data)
                    .attr("class", "line")
                    .attr("d", lineRank);
                svgSolo_mmr.append("path")
                    .datum(data)
                    .attr("class", "line")
                    .attr("d", lineSolo_mmr);

                var focusRank = svgRank.append("g")
                    .attr("class", "focus")
                    .style("display", "none");
                var focusSolo_mmr = svgSolo_mmr.append("g")
                    .attr("class", "focus")
                    .style("display", "none");
            
                focusRank.append("circle")
                    .attr("r", 4.5);
                focusSolo_mmr.append("circle")
                    .attr("r", 4.5);

                focusRank.append("text");
                focusSolo_mmr.append("text");

                svgRank.append("rect")
                    .attr("class", "overlay")
                    .attr("width", width)
                    .attr("height", height)
                    .on("mouseover", function() { focusRank.style("display", null); })
                    .on("mouseout", function() { focusRank.style("display", "none"); })
                    .on("mousemove", mousemoveRank);
                svgSolo_mmr.append("rect")
                    .attr("class", "overlay")
                    .attr("width", width)
                    .attr("height", height)
                    .on("mouseover", function() { focusSolo_mmr.style("display", null); })
                    .on("mouseout", function() { focusSolo_mmr.style("display", "none"); })
                    .on("mousemove", mousemoveSolo_mmr);
            
                function mousemoveRank() {
                    var x0 = x.invert(d3.mouse(this)[0]),
                        i = bisectDate(data, x0, 1),
                        d0 = data[i - 1],
                        d1 = data[i],
                        d = x0 - d0.date > d1.date - x0 ? d1 : d0;
                    focusRank.attr("transform", "translate(" + x(d.date) + "," + yRank(d.rank) + ")");
                    focusRank.select("text").text(d.rank);
                }
                function mousemoveSolo_mmr() {
                    var x0 = x.invert(d3.mouse(this)[0]),
                        i = bisectDate(data, x0, 1),
                        d0 = data[i - 1],
                        d1 = data[i],
                        d = x0 - d0.date > d1.date - x0 ? d1 : d0;
                    focusSolo_mmr.attr("transform", "translate(" + x(d.date) + "," + ySolo_mmr(d.solo_mmr) + ")");
                    focusSolo_mmr.select("text").text(d.solo_mmr);
                }
            }); 
        });
    </script>