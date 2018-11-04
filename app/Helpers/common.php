<?php
//namespace App\Helpers;

/*
|--------------------------------------------------------------------------
| Common global system helper functions
|--------------------------------------------------------------------------
|
| This file contains system helper functions that might be used in this
| application. Some helpers could be short-handed by easy-to-remember
| functions instead of calling long class names and methods.
|
 */

if (!function_exists('app_curl_get')) {
    /**
     * Send GET request by cURL.
     *
     * @author  xnohat
     *
     * @param   string $url
     * @return  string
     */
    function app_curl_get($url) {

        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $url); // use Random to generate unique URL every connect
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2; rv:17.0) Gecko/20100101 Firefox/17.0');
        //curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
        //curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // follow 302 header
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE); //Don't use cache version, "Cache-Control: no-cache"
        //curl_setopt($ch, CURLOPT_VERBOSE, 1); //for get header
        //curl_setopt($ch, CURLOPT_HEADER, 1); //for get header
        // grab URL and pass it to the browser
        $response = curl_exec($ch);

        // Then, after your curl_exec call:
        //$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        //$header = substr($response, 0, $header_size);
        //$body = substr($response, $header_size);

        //Log::info($header);

        // close cURL resource, and free up system resources
        curl_close($ch);

        //return (string) $body;
        return (string) $response;
    }
}

if (!function_exists('app_curl_del')) {
    /**
     * Send GET request by cURL.
     *
     * @author  xnohat
     *
     * @param	string $url
     * @return  string
     */
    function app_curl_del($url) {

		$ch = curl_init();

		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, $url); // use Random to generate unique URL every connect
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE'); 
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2; rv:17.0) Gecko/20100101 Firefox/17.0');
		//curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
		//curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // follow 302 header
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE); //Don't use cache version, "Cache-Control: no-cache"
        //curl_setopt($ch, CURLOPT_VERBOSE, 1); //for get header
        //curl_setopt($ch, CURLOPT_HEADER, 1); //for get header
		// grab URL and pass it to the browser
		$response = curl_exec($ch);

        // Then, after your curl_exec call:
        //$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        //$header = substr($response, 0, $header_size);
        //$body = substr($response, $header_size);

        //Log::info($header);

        // close cURL resource, and free up system resources
        curl_close($ch);

        //return (string) $body;
        return (string) $response;
    }
}

if (!function_exists('app_curl_post')) {
    /**
     * Send post request by cURL.
     *
     * @author  xnohat
     *
     * @param   string $url
     * @param   string $postdata
     * @return  string
     */
    function app_curl_post($url,$postdata) {

        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $url); // use Random to generate unique URL every connect
        curl_setopt($ch, CURLOPT_POST, count($postdata));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2; rv:17.0) Gecko/20100101 Firefox/17.0');
        //curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
        //curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // follow 302 header
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE); //Don't use cache version, "Cache-Control: no-cache"
        // grab URL and pass it to the browser
        $response = curl_exec($ch);
        // close cURL resource, and free up system resources
        curl_close($ch);


        return (string) $response;
    }
}


if (!function_exists('slack_file_downloader')) {
    /**
     * Download slack attachment file by cURL.
     *
     * @author  xnohat
     *
     * @param   string $url
     * @param   string $filepath
     * @param   string $access_token
     * @return  bool
     */
    function slack_file_downloader($url) {

        $attachmentfolder = 'attachments/';
        $relativeuploaddir_path = date('Y').'/'.date('m').'/'.date('d').'/';
        $fulluploaddir_path = public_path($attachmentfolder.$relativeuploaddir_path);
        $newfilename = basename($url);
        $savepath = $fulluploaddir_path.$newfilename;
        
        if (!file_exists($fulluploaddir_path)) {
            mkdir($fulluploaddir_path, 0777, true);
        }

        $retrytime = 0;
        retry_from_here:

        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $url); // use Random to generate unique URL every connect
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".config('services.slack.token')));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2; rv:17.0) Gecko/20100101 Firefox/17.0');
        //curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
        //curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // follow 302 header
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE); //Don't use cache version, "Cache-Control: no-cache"
        //curl_setopt($ch, CURLOPT_VERBOSE, 1); //for get header
        //curl_setopt($ch, CURLOPT_HEADER, 1); //for get header
        // grab URL and pass it to the browser
        $response = curl_exec($ch);

        if($response == '' AND $retrytime <= 3){
            $retrytime++;
            goto retry_from_here;
        }

        $fp = fopen($savepath, 'w');
        fwrite($fp, $response);
        fclose($fp);

        return $attachmentfolder.$relativeuploaddir_path.$newfilename;

    }
}

if (!function_exists('convert_timestamp_timezone')) {
    /**
     * Convert Timestamp to Timestamp in a specific Timezone.
     *
     * @author  xnohat
     *
     * @param	string $timestamp
     * @param	string $timezone
     * @return  Object Datetime
     */
    function convert_timestamp_timezone($timestamp, $timezone) {

	    $objTimezone = new \DateTimeZone($timezone);
        $objTimestamp = new \DateTime($timestamp);
        $objTimestamp->setTimezone($objTimezone);

        return $objTimestamp;
    }
}

if (!function_exists('convert_timestamp')) {
    /**
     * Convert Unix Timestamp to Timestamp.
     *
     * @author  xnohat
     *
     * @param   string $unixtimestamp
     * @return  Object Datetime
     */
    function convert_timestamp($unixtimestamp) {

        $objTimestamp = date("Y-m-d H:i:s", $unixtimestamp);

        return $objTimestamp;
    }
}


if (!function_exists('issetor')) {
    /**
     * Check variable isset or not, if not assign default value to variable
     *
     * @author  xnohat
     *
     * @param   any $var
     * @param   any $default
     * @return  $var or $default
     */

    function issetor(&$var, $default = false) {
        return isset($var) ? $var : $default;
    }

}


/**
 * Recursive convert object to array
 * 
 *
 * @param object $variable
 * @return converted array $variable
 */
function object_to_array($d) {
    if (is_object($d))
        $d = get_object_vars($d);

    return is_array($d) ? array_map(__FUNCTION__, $d) : $d;
}

/**
 * Recursive convert array to object
 * 
 *
 * @param array $variable
 * @return converted object $variable
 */
function array_to_object($d) {
    return is_array($d) ? (object) array_map(__FUNCTION__, $d) : $d;
}

?>