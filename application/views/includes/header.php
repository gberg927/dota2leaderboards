<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
        <meta name="description" content="Dota 2 Leaderboars with some modifications" />
        <meta name="keywords" content="dota 2, dota, 2, leaderboards, leader boards, leader, boards" />
	<title>Dota 2 World Leaders</title>
        <link rel="stylesheet" href="<?php echo base_url() ?>css/bootstrap.min.css"/>
        <link rel="stylesheet" href="<?php echo base_url() ?>css/style.css"/>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="<?php echo base_url() ?>js/jquery.tablesorter.min.js"></script>
        <script src="<?php echo base_url() ?>js/bootstrap.min.js"></script>
        <script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
</head>
<body>
    <div class="navbar navbar-inverse navbar-static-top" role="navigation">
        <div class="container">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="navbar-header">
                <a class="navbar-brand" href="<?php echo base_url() ?>">Dota 2 World Leaders</a>
            </div>
            <div class="navbar-collapse collapse">
                <form class="navbar-form navbar-right" role="search" method="post" accept-charset="utf-8" action="<?php echo site_url('search/find'); ?>">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search" name="search">
                    </div>
                    <button type="submit" class="btn btn-default">Submit</button>
                </form>
                <ul class="nav navbar-nav navbar-right">
                    <li <?php if ($this->router->fetch_class() == 'player') echo 'class="active"' ?>><a href="<?php echo site_url('player') ?>">Players</a></li>
                    <li <?php if ($this->router->fetch_class() == 'region') echo 'class="active"' ?> class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Regions <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo site_url('region/americas') ?>">Americas</a></li>
                            <li><a href="<?php echo site_url('region/europe') ?>">Europe</a></li>
                            <li><a href="<?php echo site_url('region/se_asia') ?>">SE Asia</a></li>
                            <li><a href="<?php echo site_url('region/china') ?>">China</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>