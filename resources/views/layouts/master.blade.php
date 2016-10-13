
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Dota 2 Leaderboards</title>
    <script src="https://code.jquery.com/jquery-3.1.0.js"   integrity="sha256-slogkvB1K3VOkzAI8QITxV3VzpOnkeNVsKvtkYLMjfk="   crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs/jq-2.2.3/dt-1.10.12/datatables.min.js"></script>
    <script src="{{ URL::asset('js/custom.js') }}"></script>

    <link href="https://bootswatch.com/simplex/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jq-2.2.3/dt-1.10.12/datatables.min.css"/>
    <link rel="stylesheet" href="{{ URL::asset('css/style.css') }}" />
  </head>

  <body>

  <div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a href="../" class="navbar-brand">Dota 2 Leaderboards</a>
          <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
          <ul class="nav navbar-nav">
            <li class="{{ Request::is('region/americas') || Request::is('/')  ? 'active' : '' }}"><a href="{{ url('region/americas') }}">Americas</a></li>
            <li class="{{ Request::is('region/europe') ? 'active' : '' }}"><a href="{{ url('region/europe') }}">Europe</a></li>
            <li class="{{ Request::is('region/se_asia') ? 'active' : '' }}"><a href="{{ url('region/se_asia') }}">SE Asia</a></li>
            <li class="{{ Request::is('region/china') ? 'active' : '' }}"><a href="{{ url('region/china') }}">China</a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="container content">
    
    @yield('content')

    <footer>
      <p>&copy; <?php echo date("Y") ?> Dennis Glasberg</p>
    </footer>

    </div>
  </body>
</html>
