@extends('layouts.master')

@section('content')
<div class="row region">
	<div class="col-md-6">
 		<h3>{{ $region->description }}</h3>
 	</div>
 	<div class="col-md-6 text-right rank-date">
 		<span>Current Rank Date: {{ date('m/d/Y', strtotime($rankDate->value)) }}</span>
 	</div>
</div>
<table id="region-ranking-table" class="table table-hover table-striped">
	<thead>
		<tr>
			<th>Division Rank</th>
			<th>Player</th>
			<th>Solo MMR</th>
		</tr>
	</thead>
	<tbody>
		@foreach($rankings as $ranking)
			<tr>
				<td>{{$ranking->rank}}</td>
				<td>
					{{ (! empty($ranking->player->team->name)) ? $ranking->player->team->name . '.' : '' }}
					<a href="{{ url('player/' . $ranking->player->id )}}">
						<span class="player-name">{{ $ranking->player->name }}</span>
					</a>
					@if( ! empty($ranking->player->country))
						<img src="{{ URL::asset('images/flags/small/' . $ranking->player->country . '.png') }}" alt="{{$ranking->player->country}}" title="{{$ranking->player->country}}" class="flag-icon country-flag"/>
					@endif
				</td>
				<td>{{$ranking->solo_mmr}}</td>
			</tr>
		@endforeach
	</tbody>
</table>

<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$('#region-ranking-table').DataTable(
			{
				paging: false,
				searching: false,
				"order": [[ 0, 'asc' ]]
			});
	});
</script>
@stop