<?php
/* ----------------------------------------------------------------------

   MyOOS [Dumper]
   http://www.oos-shop.de/

   Copyright (c) 2019 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   MySqlDumper
   http://www.mysqldumper.de

   Copyright (C)2004-2011 Daniel Schlichtholz (admin@mysqldumper.de)
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/**
 * Set the error reporting level. Unless you have a special need, E_ALL is a
 * good level for error reporting.
 */
error_reporting(E_ALL);
// error_reporting(0);


define('OOS_VALID_MOD', true);

if (!@ob_start("ob_gzhandler")) @ob_start();

define('DUMPER_INCLUDE_PATH', dirname(__FILE__)=='/'?'':dirname(__FILE__));

include DUMPER_INCLUDE_PATH . '/includes/functions.php';

$page=(isset($_GET['page'])) ? $_GET['page'] : 'main.php';

if (!file_exists("./work/config/myoosdumper.php"))
{
	header("location: install.php");
	ob_end_flush();
	die();
}

?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8">
		<title>MyOOS [Dumper]</title>

        <meta name="viewport" content="width=device-width, maximum-scale=5, initial-scale=1, user-scalable=0">
        <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->

		<link href="css/dumper.min.css" rel="stylesheet">

		<meta name="robots" content="noindex,nofollow" />
	</head>

		<div id="wrapper" class="d-flex">

			<!-- 
				HEADER 
			-->
			<header id="header" class="d-flex">


				<!-- NAVBAR -->

				<!-- /NAVBAR -->

			</header>
			<!-- /HEADER -->


			<div id="wrapper_content" class="d-flex flex-fill">

				<!-- SIDEBAR -->
				<aside id="aside-main" class="d-flex flex-column">

					<!-- 
						LOGO 
					-->
					<div class="align-self-baseline w-100">
						<div class="clearfix d-flex">

							<!-- Logo : height: 60px max -->
							<a class="navbar-brand " href="index.php">
								<img src="images/logo_light.svg" width="110" height="60" alt="MyOOS [Dumper]">
							</a>

						</div>
					</div>
					<!-- /LOGO -->


			
					<div class="aside-inner">
						<?php # require 'menu.php'; ?>
					</div>
				
				</aside>
				<!-- /SIDEBAR -->

				<!-- MIDDLE -->
				<div id="middle" class="flex-fill">

					<?php # require $page; ?>

				</div>
				<!-- /MIDDLE -->


			</div>



			<!-- FOOTER -->
			<footer id="footer">

			</footer>
			<!-- /FOOTER -->


		</div><!-- /#wrapper -->

	</body>
</html>
<?php
ob_end_flush();
