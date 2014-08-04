<?php 

class GoogleUtil {


    public static function FetchGoogleJSON( $url ) {    	
      $response = Fetcher::quickFetch($url);
      if (!($response instanceof FetchResult)) {
      	throw new Exception('Fetch returned not a FetchResult object!');
      }
      elseif ($response->getError() != 0) {
      	throw new Exception('Fetch was not successful due to CURL error', $response->getError());
      }
      else {
      	switch ($response->getHTTPCode()) {
      		case 404:
      			return null; //to be handled afterwards by PlusPersonCore 
      		case 200:
	      		$response = GoogleUtil::CleanGoogleJSON( $response->getContent());
	      		return json_decode( $response, true );
	      	default:
	      		throw new Exception('Fetch was not successful due to HTTP error', $response->getHTTPCode());
	      }
	    }
    }
    
    public static function CleanGoogleJSON( $googlejson ) {
        //delete anti-xss junk ")]}'\n" (5 chars);
        $googlejson = substr( $googlejson, 5 );

        //pass through result and turn empty elements into nulls
        //echo strlen( $googlejson ) . '<br>';
        $instring = false;
        $inescape = false;
        $lastchar = '';
        $output = "";
        for ( $x=0; $x<strlen( $googlejson ); $x++ ) {

            $char = substr( $googlejson, $x, 1 );

            //toss unnecessary whitespace
            if ( !$instring && ( preg_match( '/\s/', $char ) ) ) {
                continue;
            }
            
            //handle strings
            if ( $instring ) {
                if ( $inescape ) {
                    $output .= $char;
                    $inescape = false;
                } else if ( $char == '\\' ) {
                    $output .= $char;
                    $inescape = true;
                } else if ( $char == '"' ) {
                    $output .= $char;
                    $instring = false;
                } else {
                    $output .= $char;
                }
                $lastchar = $char;
                continue;
            }


            switch ( $char ) {
           
                case '"':
                    $output .= $char;
                    $instring = true;
                    break;

                case ',':
                    if ( $lastchar == ',' || $lastchar == '[' || $lastchar == '{' ) { 
                        $output .= 'null';
                    }
                    $output .= $char;
                    break;

                case ']':
                case '}':
                    if ( $lastchar == ',' ) { 
                        $output .= 'null';
                    }
                    $output .= $char;
                    break;

                default:
                    $output .= $char;
                    break;
            }
            $lastchar = $char;
        }
	$output = preg_replace('/\{([0-9]+)/','{"\\1"', $output);
        return $output;
    }
}
