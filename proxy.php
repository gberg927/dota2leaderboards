<?php
    //$c = file_get_contents((urldecode($_REQUEST['u'])));
    $c = file_get_contents_curl((urldecode($_REQUEST['u'])));
    $content_type = 'Content-Type: text/plain';
    for ($i = 0; $i < count($http_response_header); $i++) {
        if (preg_match('/content-type/i',$http_response_header[$i])) {
            $content_type = $http_response_header[$i];
        }
    }
    if ($c) {
        header($content_type);
        echo $c;
    }
    else {
        header("content-type: text/plain");
        echo 'There was an error satisfying this request.';
    }
	
    function file_get_contents_curl($url) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
        curl_setopt($ch, CURLOPT_URL, $url);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }
?>