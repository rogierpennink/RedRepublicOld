<?
/* The Debug Event
 * An event to introduce developers to the new system.
 */

/* Check if this is being answered */
if ( isset( $_SESSION["event"] ) )
{
	/* The following $_POST fields are ALWAYS there in any event request
	 * (hidden) id - ID of the event being answered.
	 * (hidden) handler - This filename.
	 **
	 * Besides these, you probably have your own form input fields.
	 */

	if ( isset( $_POST["myinput"] ) )
	{
		if ( $_POST["myinput"] == "no" )
			$_SESSION["notify"] = "Your input said \"no\".";
		elseif ( $_POST["myinput"] == "yes" )
			$_SESSION["notify"] = "Your input said \"yes\".";

		/* We have answered the event, it can go away now. */
		$char->answerEvent( $_POST["id"] );
	}
	else 
	{
		$_SESSION["error"] = "You did not select an input option, therefor the handler says its not done with this event.";
	}
}
