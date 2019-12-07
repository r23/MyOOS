<?php
/* ----------------------------------------------------------------------

   MyOOS [Dumper]
   http://www.oos-shop.de/

   Copyright (c) 2016 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   MySqlDumper
   http://www.mysqldumper.de

   Copyright (C)2004-2011 Daniel Schlichtholz (admin@mysqldumper.de)
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

if (!defined('MSD_VERSION')) die('No direct access.');
$sysaction=(isset($_GET['dosys'])) ? $_GET['dosys'] : 0;
$msg="";
$res=@mysqli_query($config['dbconnection'], "SHOW VARIABLES LIKE 'datadir'");
if ($res)
{
	$row=mysqli_fetch_array($res);
	$data_dir=$row[1];
}
switch ($sysaction)
{
	case 1: //FLUSH PRIVILEGES
		$msg="&gt; operating FLUSH PRIVILEGES<br>";
		$res=@mysqli_query($config['dbconnection'], "FLUSH PRIVILEGES");
		$meldung=mysqli_error($config['dbconnection']);
		if ($meldung!="")
		{
			$msg.='&gt; MySQL-Error: '.$meldung;
		}
		else
		{
			$msg.="&gt; Privileges were reloaded.";
		}
		break;
	case 2: //FLUSH STATUS
		$msg="&gt; operating FLUSH STATUS<br>";
		$res=@mysqli_query($config['dbconnection'], "FLUSH STATUS");
		$meldung=mysqli_error($config['dbconnection']);
		if ($meldung!="")
		{
			$msg.='&gt; MySQL-Error: '.$meldung;
		}
		else
		{
			$msg.="&gt; Status was reset.";
		}
		break;
	case 3: //FLUSH HOSTS
		$msg="&gt; operating FLUSH HOSTS<br>";
		$res=@mysqli_query($config['dbconnection'], "FLUSH HOSTS");
		$meldung=mysqli_error($config['dbconnection']);
		if ($meldung!="")
		{
			$msg.='&gt; MySQL-Error: '.$meldung;
		}
		else
		{
			$msg.="&gt; Hosts were reloaded.";
			;
		}
		break;
	case 4: //SHOW MASTER LOGS
		$msg="> operating SHOW MASTER LOGS<br>";
		$res=@mysqli_query($config['dbconnection'],"SHOW MASTER LOGS");
		$meldung=mysqli_error($config['dbconnection']);
		if ($meldung!="")
		{
			$msg.='&gt; MySQL-Error: '.$meldung;
		}
		else
		{
			$numrows=mysqli_num_rows($res);
			if ($numrows==0||$numrows===false)
			{
				$msg.='&gt; there are no master log-files';
			}
			else
			{
				$msg.='&gt; there are '.$numrows.' logfiles<br>';
				for ($i=0; $i<$numrows; $i++)
				{
					$row=mysqli_fetch_row($res);
					$msg.='&gt; '.$row[0].'&nbsp;&nbsp;&nbsp;'.(($data_dir) ? byte_output(@filesize($data_dir.$row[0])) : '').'<br>';
				}
			}
		}
		break;
	case 5: //RESET MASTER
		$msg="&gt; operating RESET MASTER<br>";
		$res=@mysqli_query($config['dbconnection'], "RESET MASTER");
		$meldung=mysqli_error($config['dbconnection']);
		if ($meldung!="")
		{
			$msg.='&gt; MySQL-Error: '.$meldung;
		}
		else
		{
			$msg.="&gt; All Masterlogs were deleted.";
		}
		break;
}
echo '<h5>'.$lang['L_MYSQLSYS'].'</h5>';
echo '<div id="hormenu"><ul>
			<li><a href="main.php?action=sys&amp;dosys=1">Reload Privileges</a></li>
			<li><a href="main.php?action=sys&amp;dosys=2">Reset Status</a></li>
			<li><a href="main.php?action=sys&amp;dosys=3">Reload Hosts</a></li>
			<li><a href="main.php?action=sys&amp;dosys=4">Show Log-Files</a></li>
			<li><a href="main.php?action=sys&amp;dosys=5">Reset Master-Log</a></li>
			</ul></div>';
echo '<div align="center" class="MySQLbox">';
echo '&gt; MySQL Dumper v'.MSD_VERSION.' - Output Console<br><br>';
echo ($msg!="") ? $msg : '> waiting for operation ...<br>';
echo '</div>';
