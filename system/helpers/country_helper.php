<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('format_regions'))
{
    function format_regions($str)
    {
        $region = '';
        switch ($str) {
            case 'americas':
                $region = 'Americas';
                break;
            case 'europe':
                $region = 'Europe';
                break;
            case 'se_asia':
                $region = 'SE Asia';
                break;
            case 'china':
                $region = 'China';
                break;
        }
            return trim($region);
    }
}