<?
$AUTH_LEVEL = 0;
$REDIRECT = true;
include_once "../includes/utility.inc.php";

error_reporting( E_ALL );

function _mysqldump()
{
	global $db;
	$sql="show tables;";
	$result= $db->query( $sql );
	if( $result )
	{
		while ( $row = mysql_fetch_row( $result ) )
		{
			_mysqldump_table_structure($row[0]);

			_mysqldump_table_data($row[0]);
		}
	}
	else
	{
		echo "-- no tables in ". $db->db ."\n";
	}
	mysql_free_result($result);
}

function _mysqldump_table_structure($table)
{
	global $db;
	echo "-- Table structure for table `$table`\n";
	echo "DROP TABLE IF EXISTS `$table`;\n\n";
	
		$sql="show create table `$table`; ";
		$result=$db->query($sql);
		if( $result)
		{
			if ( $row= mysql_fetch_assoc($result))
			{
				echo $row['Create Table'].";\n\n";
			}
		}
		mysql_free_result($result);
}

function _mysqldump_table_data($table)
{
	global $db;

	$sql="select * from `$table`;";
	$result=$db->query($sql);
	if( $result)
	{
		$num_rows= mysql_num_rows($result);
		$num_fields= mysql_num_fields($result);

		if( $num_rows > 0)
		{
			echo "-- dumping data for table `$table`\n";

			$field_type=array();
			$i=0;
			while( $i < $num_fields)
			{
				$meta= mysql_fetch_field($result, $i);
				array_push($field_type, $meta->type);
				$i++;
			}

			//print_r( $field_type);
			echo "insert into `$table` values\n";
			$index=0;
			while( $row= mysql_fetch_row($result))
			{
				echo "(";
				for( $i=0; $i < $num_fields; $i++)
				{
					if( is_null( $row[$i]))
						echo "null";
					else
					{
						switch( $field_type[$i])
						{
							case 'int':
								echo $row[$i];
								break;
							case 'string':
							case 'blob' :
							default:
								echo "'".mysql_real_escape_string($row[$i])."'";

						}
					}
					if( $i < $num_fields-1)
						echo ",";
				}
				echo ")";

				if( $index < $num_rows-1)
					echo ",";
				else
					echo ";";
				echo "\n";

				$index++;
			}
		}
	}
	mysql_free_result($result);
	echo "\n";
}

_mysqldump();

echo "\n";
echo "UPDATE items SET item_id=0 WHERE name='Unknown';\n";
echo "UPDATE char_characters SET id=0 WHERE nickname='ADMINISTRATOR';\n";
echo "UPDATE icons SET icon_id=0 WHERE url='items/unknown.png';\n";
echo "UPDATE governments SET id=0 WHERE name='Anarchy';\n";
?>