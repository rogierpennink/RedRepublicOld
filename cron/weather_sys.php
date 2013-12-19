<?
/**
 * This file will run Red Republic's weather system updater. What we do is use PHP's bsd sockets wrapper to
 * connect to the site that provides weather data and put all the data we need into the locations table. This
 * data may then be used for further interpretation by other scripts.
 * Note: The script does a little more than just download the data. After all new data is in place, a handler
 * function will be called that will check if any weather related events should occur.
 * @date	26/09/2007
 * @author	Rogier Pennink
 */

include_once "../includes/utility.inc.php";

/**
 * First, query the weather server and connect to it.
 */
$setting = $db->fetch_array( $db->query( "SELECT value FROM settings_general WHERE setting='weather_data_server'" ) );

/**
 * Second, query all locations and get the hyperlinks to which to connect.
 */
$dbresult = $db->query( "SELECT * FROM locations" );
while ( $location = $db->fetch_array( $dbresult ) )
{
	/**
	 * Try to connect to the weather server on port 80. If this doesn't succeed, send an email to admin@red-republic.com.
	 */
	$conn = @fsockopen( $setting['value'], 80 );
	if ( !$conn )
	{
		break;
	}

	/**
	 * Construct a HTTP request header.
	 */
	$header = "GET /". $location['weather_url'] ." HTTP/1.1\r\n";
	$header .= "Host: ". $setting['value'] ."\r\n";
	$header .= "Connection: Close\r\n\r\n";

	/**
	 * Send the header.
	 */
	fwrite( $conn, $header );

	/**
	 * Wait for an answer.
	 */
	$response = "";
	while ( !feof( $conn ) )
		$response .= fgets( $conn, 2048 );

	/**
	 * Close the connection.
	 */
	fclose( $conn );

	/**
	 * Send the response to the parser along with the location id.
	 */
	parseWeatherHTML( $response, $location['id'] );
}

/**
 * The parseWeatherHTML function will parse the relevant weather data out of the response HTML from 
 * the weather server.
 */
function parseWeatherHTML( $response, $loc_id )
{
	global $weather_wind, $db;

	/**
	 * First, the variables we're going to fill.
	 */
	$low_temp = 0; $high_temp = 0;
	$rain_amount = 0;
	$gust = 0; $gust_dir = WIND_NORTH;
	$avg_wind = 0; $avg_wind_dir = WIND_NORTH;
	$pressure = 0;
	$dewpoint = 0;
	$windchill = 0;
	$humidity = 0;
	$sunrise = "";
	$sunset = "";

	/* Find the start position of the minimum temperature. */
	$searchstr = "<span id=\"divLo\">";
	$searchstrlen = strlen( $searchstr );
	if ( ( $pos0 = strpos( $response, $searchstr ) ) === false )
	{
		echo "<strong>Parse error</strong> in weather parser: No low temperature found.";
		return;
	}

	/* Find the end position of the minimum temperature. */
	$searchstr = "&deg;";
	if ( ( $pos1 = strpos( $response, $searchstr, $pos0 ) ) === false )
	{
		echo "<strong>Parse error</strong> in weather parser: No low temperature found.";
		return;
	}

	/* Get a start position and a length for the substr call. */
	$pos = $pos0 + $searchstrlen;
	$len = $pos1 - $pos;

	/* Get the low temperature. */
	$low_temp = substr( $response, $pos, $len );

	/* Find the start position of the maximum temperature. */
	$searchstr = "<span id=\"divHi\">";
	$searchstrlen = strlen( $searchstr );
	if ( ( $pos0 = strpos( $response, $searchstr ) ) === false )
	{
		echo "<strong>Parse error</strong> in weather parser: No high temperature found.";
		return;
	}

	/* Find the end position of the minimum temperature. */
	$searchstr = "&deg;";
	if ( ( $pos1 = strpos( $response, $searchstr, $pos0 ) ) === false )
	{
		echo "<strong>Parse error</strong> in weather parser: No high temperature found.";
		return;
	}

	/* Get a start position and a length for the substr call. */
	$pos = $pos0 + $searchstrlen;
	$len = $pos1 - $pos;

	/* Get the low temperature. */
	$high_temp = substr( $response, $pos, $len );

	/* Find the start position of the amount of rain. */
	$searchstr = "<span id=\"divRain\">";
	$searchstrlen = strlen( $searchstr );
	if ( ( $pos0 = strpos( $response, $searchstr ) ) === false )
	{
		echo "<strong>Parse error</strong> in weather parser: No rain amount found.";
		return;
	}

	/* Find the end position of the minimum temperature. */
	$searchstr = "mm";
	if ( ( $pos1 = strpos( $response, $searchstr, $pos0 ) ) === false )
	{
		echo "<strong>Parse error</strong> in weather parser: No rain amount found.";
		return;
	}

	/* Get a start position and a length for the substr call. */
	$pos = $pos0 + $searchstrlen;
	$len = $pos1 - $pos;

	/* Get the low temperature. */
	$rain_amount = substr( $response, $pos, $len );

	/* Find the start position of the direction and size of gust. */
	$searchstr = "<span id=\"divGust\">";
	$searchstrlen = strlen( $searchstr );
	if ( ( $pos0 = strpos( $response, $searchstr ) ) === false )
	{
		echo "<strong>Parse error</strong> in weather parser: No gust found.";
		return;
	}

	/* Find the end position of the minimum temperature. */
	$searchstr = "</span>";
	if ( ( $pos1 = strpos( $response, $searchstr, $pos0 ) ) === false )
	{
		echo "<strong>Parse error</strong> in weather parser: No gust found.";
		return;
	}

	/* Get a start position and a length for the substr call. */
	$pos = $pos0 + $searchstrlen;
	$len = $pos1 - $pos;

	/* Get the gust variables. */
	$gust = substr( $response, $pos, $len );
	$gust = explode( " ", $gust );	
	$gust_dir = $gust[0]; $gust = $gust[1];

	/* Find the start position of the average wind. */
	$searchstr = "<span id=\"divAvgWind\">";
	$searchstrlen = strlen( $searchstr );
	if ( ( $pos0 = strpos( $response, $searchstr ) ) === false )
	{
		echo "<strong>Parse error</strong> in weather parser: No average wind found.";
		return;
	}

	/* Find the end position of the average wind. */
	$searchstr = "</span>";
	if ( ( $pos1 = strpos( $response, $searchstr, $pos0 ) ) === false )
	{
		echo "<strong>Parse error</strong> in weather parser: No average wind found.";
		return;
	}

	/* Get a start position and a length for the substr call. */
	$pos = $pos0 + $searchstrlen;
	$len = $pos1 - $pos;

	/* Get the gust variables. */
	$avg_wind = substr( $response, $pos, $len );
	$avg_wind = explode( " ", $avg_wind );	
	$avg_wind_dir = $avg_wind[1]; $avg_wind = $avg_wind[0];

	/* Find the start position of the air pressure. */
	$searchstr = "<span id=\"divPressure\">";
	$searchstrlen = strlen( $searchstr );
	if ( ( $pos0 = strpos( $response, $searchstr ) ) === false )
	{
		echo "<strong>Parse error</strong> in weather parser: No air pressure found.";
		return;
	}

	/* Find the end position of the pressure. */
	$searchstr = "</span>";
	if ( ( $pos1 = strpos( $response, $searchstr, $pos0 ) ) === false )
	{
		echo "<strong>Parse error</strong> in weather parser: No air pressure found.";
		return;
	}

	/* Get a start position and a length for the substr call. */
	$pos = $pos0 + $searchstrlen;
	$len = $pos1 - $pos;

	/* Get the gust variables. */
	$pressure = round( substr( $response, $pos, $len ) );	

	/* Find the start position of the dew point. */
	$searchstr = "<span id=\"divDewPoint\">";
	$searchstrlen = strlen( $searchstr );
	if ( ( $pos0 = strpos( $response, $searchstr ) ) === false )
	{
		echo "<strong>Parse error</strong> in weather parser: No dew point found.";
		return;
	}

	/* Find the end position of the pressure. */
	$searchstr = "&deg;";
	if ( ( $pos1 = strpos( $response, $searchstr, $pos0 ) ) === false )
	{
		echo "<strong>Parse error</strong> in weather parser: No dew point found.";
		return;
	}

	/* Get a start position and a length for the substr call. */
	$pos = $pos0 + $searchstrlen;
	$len = $pos1 - $pos;

	/* Get the dewpoint variables. */
	$dewpoint = substr( $response, $pos, $len );	

	/* Find the start position of the windchill. */
	$searchstr = "<span id=\"divFeelsLike\">";
	$searchstrlen = strlen( $searchstr );
	if ( ( $pos0 = strpos( $response, $searchstr ) ) === false )
	{
		echo "<strong>Parse error</strong> in weather parser: No windchill information found.";
		return;
	}

	/* Find the end position of the windchill. */
	$searchstr = "&deg;";
	if ( ( $pos1 = strpos( $response, $searchstr, $pos0 ) ) === false )
	{
		echo "<strong>Parse error</strong> in weather parser: No windchill information found.";
		return;
	}

	/* Get a start position and a length for the substr call. */
	$pos = $pos0 + $searchstrlen;
	$len = $pos1 - $pos;

	/* Get the gust variables. */
	$windchill = substr( $response, $pos, $len );	

	/* Find the start position of the humidity. */
	$searchstr = "<span id=\"divHumidity\">";
	$searchstrlen = strlen( $searchstr );
	if ( ( $pos0 = strpos( $response, $searchstr ) ) === false )
	{
		echo "<strong>Parse error</strong> in weather parser: No humidity information found.";
		return;
	}

	/* Find the end position of the humidity. */
	$searchstr = "%</span>";
	if ( ( $pos1 = strpos( $response, $searchstr, $pos0 ) ) === false )
	{
		echo "<strong>Parse error</strong> in weather parser: No humidity information found.";
		return;
	}

	/* Get a start position and a length for the substr call. */
	$pos = $pos0 + $searchstrlen;
	$len = $pos1 - $pos;

	/* Get the humidity variables. */
	$humidity = substr( $response, $pos, $len );

	/* Find the start position of the sunrise time. */
	$searchstr = "<li>Sunrise: <span>";
	$searchstrlen = strlen( $searchstr );
	if ( ( $pos0 = strpos( $response, $searchstr ) ) === false )
	{
		echo "<strong>Parse error</strong> in weather parser: No sunrise information found.";
		return;
	}

	/* Find the end position of the sunrise time. */
	$searchstr = "</span>";
	if ( ( $pos1 = strpos( $response, $searchstr, $pos0 ) ) === false )
	{
		echo "<strong>Parse error</strong> in weather parser: No sunrise information found.";
		return;
	}

	/* Get a start position and a length for the substr call. */
	$pos = $pos0 + $searchstrlen;
	$len = $pos1 - $pos;

	/* Get the sunrise variables. */
	$sunrise = date( "H:i:s", strtotime( substr( $response, $pos, $len ) ) );

	/* Find the start position of the sunset time. */
	$searchstr = "<li>Sunset: <span>";
	$searchstrlen = strlen( $searchstr );
	if ( ( $pos0 = strpos( $response, $searchstr ) ) === false )
	{
		echo "<strong>Parse error</strong> in weather parser: No sunset information found.";
		return;
	}

	/* Find the end position of the sunset time. */
	$searchstr = "</span>";
	if ( ( $pos1 = strpos( $response, $searchstr, $pos0 ) ) === false )
	{
		echo "<strong>Parse error</strong> in weather parser: No sunset information found.";
		return;
	}

	/* Get a start position and a length for the substr call. */
	$pos = $pos0 + $searchstrlen;
	$len = $pos1 - $pos;

	/* Get the sunset variables. */
	$sunset = date( "H:i:s", strtotime( substr( $response, $pos, $len ) ) );

	
	/*****************************************************************************************\
	\*****************************************************************************************/

	/* Put the gathered information in the database. */
	$db->query( "UPDATE locations SET min_temp=$low_temp, max_temp=$high_temp, avg_wind_dir='$avg_wind_dir', avg_wind_speed=$avg_wind, pressure=$pressure, dewpoint=$dewpoint, windchill=$windchill, humidity=". ( $humidity * 10 ) .", sunrise='$sunrise', sunset='$sunset' WHERE id=$loc_id" );
}
?>