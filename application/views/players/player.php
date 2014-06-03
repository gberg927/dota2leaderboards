<div class="container">
    <div class="row">
        <div class="col-md-12">
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
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Rank</div>
                <div class="panel-body graphParent">
                    <svg class="graph" id="rankGraph"></svg>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Solo MMR</div>
                <div class="panel-body graphParent">
                    <svg class="graph" id="solo_mmrGraph"></svg>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        var margin = 60,
            width = parseInt(d3.select("#solo_mmrGraph").style("width")) - margin * 2,
            height = parseInt(d3.select("#solo_mmrGraph").style("height")) - margin * 2;
    
        var parseDate = d3.time.format("%d-%m-%Y").parse,
            bisectDate = d3.bisector(function(d) { return d.date; }).left;
    
        var xScale = d3.time.scale()
            .range([0, width])
            .nice();
        var xAxis = d3.svg.axis()
            .tickFormat(d3.time.format("%b %e"))
            .scale(xScale)
            .orient("bottom");
    
        var yScaleRank = d3.scale.linear()
            .range([height, 0])
            .nice();
        var yScaleSolo_mmr = d3.scale.linear()
            .range([height, 0])
            .nice();

        var yAxisRank = d3.svg.axis()
            .tickFormat(d3.format("d"))
            .scale(yScaleRank)
            .orient("left");
        var yAxisSolo_mmr = d3.svg.axis()
            .tickFormat(d3.format("d"))
            .scale(yScaleSolo_mmr)
            .orient("left");
    
        var lineRank = d3.svg.line()
            .x(function(d) { return xScale(d.date); })
            .y(function(d) { return yScaleRank(d.rank); });
        var lineSolo_mmr = d3.svg.line()
            .x(function(d) { return xScale(d.date); })
            .y(function(d) { return yScaleSolo_mmr(d.solo_mmr); });
    
        var graphRank = d3.select("#rankGraph").append("svg")
            .attr("width", width + margin * 2)
            .attr("height", height + margin * 2)
            .append("g")
                .attr("transform", "translate(" + margin + "," + margin + ")");
        var graphSolo_mmr = d3.select("#solo_mmrGraph").append("svg")
            .attr("width", width + margin * 2)
            .attr("height", height + margin * 2)
            .append("g")
                .attr("transform", "translate(" + margin + "," + margin + ")");
        
        var vLineRank = graphRank.append('line')
            .attr({
                'x1': 0,
                'y1': 0,
                'x2': 0,
                'y2': height
            })
            .attr("stroke", "#FFFFFF");
        var vLineSolo_mmr = graphSolo_mmr.append('line')
            .attr({
                'x1': 0,
                'y1': 0,
                'x2': 0,
                'y2': height
            })
            .attr("stroke", "#FFFFFF");
        
        d3.json("<?php echo site_url('player/data/' . $playerID) ?>", function(error, data) {
            data.forEach(function(d) {
                d.date = parseDate(d.date);
                d.rank = +d.rank;        
                d.solo_mmr = +d.solo_mmr;
            });

            xScale.domain(d3.extent(data, function(d) { return d.date; }));
            
            yScaleRank.domain(d3.extent(data, function(d) { return d.rank; }));
            yScaleSolo_mmr.domain(d3.extent(data, function(d) { return d.solo_mmr; }));

            graphRank.append("g")
                .attr("class", "x axis")
                .attr("transform", "translate(0," + height + ")")
                .call(xAxis);
            graphSolo_mmr.append("g")
                .attr("class", "x axis")
                .attr("transform", "translate(0," + height + ")")
                .call(xAxis);
        
            graphRank.append("g")
                .attr("class", "y axis")
                .call(yAxisRank)
                .append("text")
                    .attr("transform", "rotate(-90)")
                    .attr("y", 6)
                    .attr("dy", ".71em")
                    .style("text-anchor", "end")
                    .text("Rank");
            graphSolo_mmr.append("g")
                .attr("class", "y axis")
                .call(yAxisSolo_mmr)
                .append("text")
                    .attr("transform", "rotate(-90)")
                    .attr("y", 6)
                    .attr("dy", ".71em")
                    .style("text-anchor", "end")
                    .text("Solo MMR");
            
            dataPerPixel = data.length / width;
            dataResampled = data.filter(
                function(d, i) { return i % Math.ceil(dataPerPixel) == 0; }
            );

            graphRank.append("path")
                .datum(dataResampled)
                .attr("class", "line")
                .attr("d", lineRank);
            graphSolo_mmr.append("path")
                .datum(dataResampled)
                .attr("class", "line")
                .attr("d", lineSolo_mmr);
        
            var focusRank = graphRank.append("g")
                .attr("class", "focus")
                .style("display", "none");
            var focusSolo_mmr = graphSolo_mmr.append("g")
                .attr("class", "focus")
                .style("display", "none");
            
            focusRank.append("circle")
                .attr("r", 4.5);
            focusSolo_mmr.append("circle")
                .attr("r", 4.5);
        
            graphRank.append("rect")
                .attr("class", "overlay")
                .attr("width", width)
                .attr("height", height)
                .on("mousemove", mousemove);
            graphSolo_mmr.append("rect")
                .attr("class", "overlay")
                .attr("width", width)
                .attr("height", height)
                .on("mousemove", mousemove);
        
            function mousemove() {
                var xPos = d3.mouse(this)[0];
                vLineRank.attr("transform", "translate(" + xPos + ",0)");
                vLineSolo_mmr.attr("transform", "translate(" + xPos + ",0)");
                
                var x0 = xScale.invert(d3.mouse(this)[0]);
                if (x0 <= data[data.length - 1].date) {
                    var i = bisectDate(data, x0, 1);
                    var d0 = data[i - 1];
                    var d1 = data[i];
                    var d = x0 - d0.date > d1.date - x0 ? d1 : d0;
                    
                    focusRank.attr("transform", "translate(" + xScale(d.date) + "," + yScaleRank(d.rank) + ")");
                    focusRank.select("text").text(d.rank);
                    focusRank.style("display", null);
                    
                    focusSolo_mmr.attr("transform", "translate(" + xScale(d.date) + "," + yScaleSolo_mmr(d.solo_mmr) + ")");
                    focusSolo_mmr.select("text").text(d.solo_mmr);
                    focusSolo_mmr.style("display", null);
                }
            }
        
            var firstRecordRank = data[data.length - 1], 
                lastRecordRank = data[0];
            var firstRecordSolo_mmr = data[data.length - 1], 
                lastRecordSolo_mmr = data[0];
                
            focusRank.append("text");
            focusSolo_mmr.append("text");
                
            var firstRank = graphRank.append("g")
                .attr("class", "first")
                .style("display", "none");
            var firstSolo_mmr = graphSolo_mmr.append("g")
                .attr("class", "first")
                .style("display", "none");

            firstRank.append("text")
                .attr("x", -8)
                .attr("y", 4)
                .attr("text-anchor", "end")
                .text("$" + firstRecordRank.rank);
            firstSolo_mmr.append("text")
                .attr("x", -8)
                .attr("y", 4)
                .attr("text-anchor", "end")
                .text("$" + firstRecordSolo_mmr.solo_mmr);
        
            firstRank.append("circle")
                .attr("r", 4);
            firstSolo_mmr.append("circle")
                .attr("r", 4);

            var lastRank = graphRank.append("g")
                .attr("class", "last")
                .style("display", "none");
            var lastSolo_mmr = graphSolo_mmr.append("g")
                .attr("class", "last")
                .style("display", "none");

            lastRank.append("text")
                .attr("x", 8)
                .attr("y", 4)
                .text("$" + lastRecordRank.rank);
            lastSolo_mmr.append("text")
                .attr("x", 8)
                .attr("y", 4)
                .text("$" + lastRecordSolo_mmr.solo_mmr);
        
            lastRank.append("circle")
                .attr("r", 4);
            lastSolo_mmr.append("circle")
                .attr("r", 4);
            
            function resize() {
                var width = parseInt(d3.select("#solo_mmrGraph").style("width")) - margin * 2,
                height = parseInt(d3.select("#solo_mmrGraph").style("height")) - margin * 2;                
        
                xScale.range([0, width]).nice();
                yScaleRank.range([height, 0]).nice();
                yScaleSolo_mmr.range([height, 0]).nice();

                if (width < 300 && height < 80) {
                    graphRank.select('.x.axis').style("display", "none");
                    graphSolo_mmr.select('.x.axis').style("display", "none");
                    
                    graphRank.select('.y.axis').style("display", "none");
                    graphSolo_mmr.select('.y.axis').style("display", "none");

                    graphRank.select(".first")
                        .attr("transform", "translate(" + xScale(firstRecordRank.date) + "," + yScaleRank(firstRecordRank.rank) + ")")
                        .style("display", "initial");
                    graphSolo_mmr.select(".first")
                        .attr("transform", "translate(" + xScale(firstRecordSolo_mmr.date) + "," + yScaleSolo_mmr(firstRecordSolo_mmr.solo_mmr) + ")")
                        .style("display", "initial");
                
                    graphRank.select(".last")
                        .attr("transform", "translate(" + xScale(lastRecordRank.date) + "," + yScaleRank(lastRecordRank.rank) + ")")
                        .style("display", "initial");
                    graphSolo_mmr.select(".last")
                        .attr("transform", "translate(" + xScale(lastRecordSolo_mmr.date) + "," + yScaleSolo_mmr(lastRecordSolo_mmr.solo_mmr) + ")")
                        .style("display", "initial");
                } else {
                    graphRank.select('.x.axis').style("display", "initial");
                    graphSolo_mmr.select('.x.axis').style("display", "initial");
                    
                    graphRank.select('.y.axis').style("display", "initial");
                    graphSolo_mmr.select('.y.axis').style("display", "initial");
                    
                    graphRank.select(".last")
                        .style("display", "none");
                    graphSolo_mmr.select(".last")
                        .style("display", "none");
                
                    graphRank.select(".first")
                        .style("display", "none");
                    graphSolo_mmr.select(".first")
                        .style("display", "none");
                }

                yAxisRank.ticks(Math.max(height / 50, 2));
                yAxisSolo_mmr.ticks(Math.max(height / 50, 2));
                
                xAxis.ticks(Math.max(width / 100, 2));

                graphRank
                    .attr("width", width + margin * 2)
                    .attr("height", height + margin * 2);
                graphSolo_mmr
                    .attr("width", width + margin * 2)
                    .attr("height", height + margin * 2);

                graphRank.select('.x.axis')
                    .attr("transform", "translate(0," + height + ")")
                    .call(xAxis);
                graphSolo_mmr.select('.x.axis')
                    .attr("transform", "translate(0," + height + ")")
                    .call(xAxis);
            
                graphRank.select('.y.axis')
                    .call(yAxisRank);
                graphSolo_mmr.select('.y.axis')
                    .call(yAxisSolo_mmr);

                dataPerPixel = data.length / width;
                dataResampled = data.filter(
                    function(d, i) { return i % Math.ceil(dataPerPixel) == 0; }
                );

                graphRank.selectAll('.line')
                    .datum(dataResampled)
                    .attr("d", lineRank);
                graphSolo_mmr.selectAll('.line')
                    .datum(dataResampled)
                    .attr("d", lineSolo_mmr);
            }

            d3.select(window).on('resize', resize); 

            resize();
        });
    </script>