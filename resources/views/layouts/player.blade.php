@extends('layouts.master')

@section('content')

<div class="row player">
	<div class="col-md-9 col-lg-6 col-sm-12 col-xs-12">
		<div class="player-country-flag">
			<img src="{{ URL::asset('images/flags/medium/' . $player->country . '.png') }}" alt="{{$player->country}}" title="{{$player->country}}" class="country-flag"/>
		</div>
		<div class="player-info">
			<h3 class="player-name">{{ $player->name }}</h3>
			<span>{{ (! empty($player->team->name)) ? $player->team->name : '' }}</span>
		</div>
	</div>
	<div class="col-md-3 col-lg-2 col-sm-4 col-xs-12">
		<div class="player-data">
			<div class="score"><h3>{{ $player->rankings->first()->solo_mmr }}</h3></div>
			<div class="title">Solo MMR</div>
		</div>
	</div>
	<div class="col-md-3 col-lg-2 col-sm-4 col-xs-12">
		<div class="player-data">
			<div class="score"><h3>{{ $player->rankings->first()->rank }}</h3></div>
			<div class="title">Rank</div>
		</div>
	</div>
	<div class="col-md-3 col-lg-2 col-sm-4 col-xs-12">
		<div class="player-data">
			<div class="score"><h3>{{ date('m/d/Y', strtotime($player->rankings->first()->rank_date)) }}</h3></div>
			<div class="title">Most Recent Ranking</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
 		<h4>Previous rankings</h4>
 	</div>
</div>
<table id="player-rankings-table" class="table table-striped">
	<thead>
		<tr>
			<th>Date</th>
			<th>Division Rank</th>
			<th>Solo MMR</th>
		</tr>
	</thead>
	<tbody>
		@foreach($player->rankings as $ranking)
			<tr>
				<td>{{ date('m/d/Y', strtotime($ranking->rank_date)) }}</td>
				<td>{{ $ranking->rank }}</td>
				<td>{{ $ranking->solo_mmr }}</td>
			</tr>
		@endforeach
	</tbody>
</table>

<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$('#player-rankings-table').DataTable(
			{
				searching: false,
				"order": [[ 0, 'asc' ]],
				"pageLength": 10
			});
	});
</script>
@stop