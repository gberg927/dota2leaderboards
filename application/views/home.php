<div class="jumbotron">
      <div class="container">
        <h3>World Stats</h3>
        <img src="<?php echo base_url() ?>images/world.png" class="img-responsive" alt="World Map">
      </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <h2>Americas</h2>
                <h3><?php echo $americas['name']; ?></h3>
                <p><a class="btn btn-default" href="<?php echo site_url('player/id/' . $americas['playerID']) ?>" role="button">Player Info &raquo;</a></p>
            </div>
            <div class="col-md-3">
                <h2>Europe</h2>
                <h3><?php echo $europe['name']; ?></h3>
                <p><a class="btn btn-default" href="<?php echo site_url('player/id/' . $europe['playerID']) ?>" role="button">Player Info &raquo;</a></p>
            </div>
            <div class="col-md-3">
                <h2>SE Asia</h2>
                <h3><?php echo $se_asia['name']; ?></h3>
                <p><a class="btn btn-default" href="<?php echo site_url('player/id/' . $se_asia['playerID']) ?>" role="button">Player Info &raquo;</a></p>
            </div>
            <div class="col-md-3">
                <h2>China</h2>
                <h3><?php echo $china['name']; ?></h3>
                <p><a class="btn btn-default" href="<?php echo site_url('player/id/' . $china['playerID']) ?>" role="button">Player Info &raquo;</a></p>
            </div>
        </div>