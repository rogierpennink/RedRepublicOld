<?
/**
 * User level defines.
 */
$user_rights = array();
define( 'USER_SUPERADMIN', 10 );		$user_rights[10] = "Super Administrator";
define( 'USER_ADMIN', 9 );				$user_rights[9] = "Administrator";
define( 'USER_MODERATOR', 8 );			$user_rights[8] = "Moderator";
										$user_rights[7] = "";
										$user_rights[6] = "";
define( 'USER_SUPPORT', 5 );			$user_rights[5] = "Support Administrator";
										$user_rights[4] = "";
										$user_rights[3] = "";
										$user_rights[2] = "";
										$user_rights[1] = "";
define( 'USER_DEFAULTMEMBER', 0 );		$user_rights[0] = "Member";

/**
 * Wind direction defines.
 */
define( "WIND_NORTH", "N" );
define( "WIND_NORTH_NORTHEAST", "NNE" );
define( "WIND_NORTHEAST", "NE" );
define( "WIND_EAST_NORTHEAST", "ENE" );
define( "WIND_EAST", "E" );
define( "WIND_EAST_SOUTHEAST", "ESE" );
define( "WIND_SOUTHEAST", "SE" );
define( "WIND_SOUTH_SOUTHEAST", "SSE" );
define( "WIND_SOUTH", "S" );
define( "WIND_SOUTH_SOUTHWEST", "SSW" );
define( "WIND_SOUTHWEST", "SW" );
define( "WIND_WEST_SOUTHWEST", "WSW" );
define( "WIND_WEST", "W" );
define( "WIND_WEST_NORTHWEST", "WNW" );
define( "WIND_NORTHWEST", "NW" );
define( "WIND_NORTH_NORTHWEST", "NNW" );

/**
 * The weather_wind associative array for output.
 */
$weather_wind = array();
$weather_wind[WIND_NORTH] = "Northern";
$weather_wind[WIND_NORTH_NORTHEAST] = "North-Northeastern";
$weather_wind[WIND_NORTHEAST] = "Northeastern";
$weather_wind[WIND_EAST_NORTHEAST] = "East-Northeastern";
$weather_wind[WIND_EAST] = "Eastern";
$weather_wind[WIND_EAST_SOUTHEAST] = "East-Southeastern";
$weather_wind[WIND_SOUTHEAST] = "Southeastern";
$weather_wind[WIND_SOUTH_SOUTHEAST] = "South-Southeastern";
$weather_wind[WIND_SOUTH] = "Southern";
$weather_wind[WIND_SOUTH_SOUTHWEST] = "South-Southwestern";
$weather_wind[WIND_SOUTHWEST] = "Southwestern";
$weather_wind[WIND_WEST_SOUTHWEST] = "West-Southwestern";
$weather_wind[WIND_WEST] = "Western";
$weather_wind[WIND_WEST_NORTHWEST] = "West-Northwestern";
$weather_wind[WIND_NORTHWEST] = "Northwestern";
$weather_wind[WIND_NORTH_NORTHWEST] = "North-Northwestern";

/**
 * Tavern messages
 */
define( "TAVERN_BAR_MESSAGE", 0 );
define( "TAVERN_BAR_ACTION", 1 );