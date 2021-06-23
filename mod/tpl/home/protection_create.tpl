<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="robots" content="noindex,nofollow" />
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="expires" content="0">
	<meta http-equiv="cache-control" content="must-revalidate">
	<title>MyOOS [Dumper]</title>
	<link rel="stylesheet" type="text/css" href="css/{THEME}/style.css">
	<script>
		

			function checkPasswords()
			{
				if (document.getElementById('userpass1').value!=document.getElementById('userpass2').value)
				{
					alert('{PASSWORDS_UNEQUAL}');
					return false;
				}
				else return confirm('{HTACC_CONFIRM_CREATE}');
			}	
		</script>
		<style type="text/css">
			#myinput {
				border:1px solid #000000;	
				float:left;			
			}
			
		</style>	
</head>
<body class="content" onload="if (document.forms[0]) document.forms[0].username.focus();">
{HEADLINE}
<!-- BEGIN MSG -->
{MSG.TEXT}<br><br>
<!-- END MSG -->

<!-- BEGIN INPUT -->
<form method="post" action="main.php?action=schutz" onSubmit="return checkPasswords();">
<table style="width:700px;" border="0">
<tr>
	<td>{L_USERNAME}:</td>
	<td colspan="2"><input type="text" name="username" id="username" size="50" value="{INPUT.USERNAME}" class="Formtext"></td>
</tr>
<tr>
	<td>{L_PASSWORD}:</td>
	<td>
		<input type="password" name="userpass1" id="userpass1" value="{USERPASS2}" size="50" class="Formtext">
	</td>
</tr>
<tr>
	<td>{L_PASSWORD_REPEAT}:</td>
	<td>
		<input type="password" name="userpass2" id="userpass2" value="{USERPASS2}" size="50" class="Formtext">
	</td>
</tr>
<!--
<tr>
	<td>{L_PASSWORD_STRENGTH}:</td>
	<td>

	</td>
</tr>
-->
<tr><td>&nbsp;</td><td></td></tr>
<tr>
	<td>{L_ENCRYPTION_TYPE}:</td>
	<td>
		<table>
			<tr>
				<td>
					<input class="radio" type="radio" name="type" id="type4" value="4"{INPUT.TYPE4_CHECKED}>
				</td>
				<td>
					<label for="type4">{L_HTACC_BCRYPT}</label>
				</td>
			</tr>
			<tr>
				<td>
					<input class="radio" type="radio" name="type" id="type1" value="1"{INPUT.TYPE1_CHECKED}>
				</td>
				<td>
					<label for="type1">{L_HTACC_MD5}</label>
				</td>
			</tr>
			<tr>
				<td>
					<input class="radio" type="radio" name="type" id="type3" value="3"{INPUT.TYPE3_CHECKED}>
				</td>
				<td>
					<label for="type3">{L_HTACC_SHA1}</label>
				</td>
			</tr>
			<tr>
				<td>
					<input class="radio" type="radio" name="type" id="type0" value="0"{INPUT.TYPE0_CHECKED}>
				</td>
				<td>
					<label for="type0">{L_HTACC_CRYPT}</label>
				</td>
			</tr>
			<tr>
				<td>
					<input class="radio" type="radio" name="type" id="type2" value="2"{INPUT.TYPE2_CHECKED}>
				</td>
				<td>
					<label for="type2">{L_HTACC_NO_ENCRYPTION}</label>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td class="center" colspan="2">
	<br>
		<input type="submit" class="Formbutton" name="htaccess" value="{L_HTACC_CREATE}">
		<br><br>
	</td>
</tr>
</table>
</form>
<!-- END INPUT -->

<!-- BEGIN CREATE_SUCCESS -->
	<strong>{L_HTACC_CONTENT} .htaccess:</strong><br><br>
	<pre>{CREATE_SUCCESS.HTACCESS}</pre>

	<br><br><strong>{L_HTACC_CONTENT} .htpasswd:</strong><br><br>
	<pre>{CREATE_SUCCESS.HTPASSWD}</pre>
	<br><br>
	<a href="main.php" class="Formbutton">{L_HOME}</a>
<!-- END CREATE_SUCCESS -->

<!-- BEGIN CREATE_ERROR -->
<p class="error"><STRONG>{L_HTACC_CREATE_ERROR}:</strong></p>

	<strong>{L_HTACC_CONTENT} .htaccess:</strong><br><br>
	<textarea cols="80" rows="5">{CREATE_ERROR.HTACCESS}</textarea>

	<br><strong>{L_HTACC_CONTENT} .htpasswd:</strong><br><br>
	<textarea cols="80" rows="2">{CREATE_ERROR.HTPASSWD}</textarea>
	
	<br><br>
	<a href="main.php" class="Formbutton">{L_HOME}</a>
<!-- END CREATE_ERROR -->
