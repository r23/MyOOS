<html>
<head>
<title>SmartJax Addnum (Form Submit) Example</title>
<script type="text/javascript" src="/smartjax/smartjax.js"></script>
<script type="text/javascript">
  var sjObj = new SMARTJAX("/smartjax/smartjax_server.php");
  function smartjax_addnum() {
    sjObj.loadForm("numberform");
    return sjObj.call("addnum", cbAddNum);
  }
  function cbAddNum(resp) {
    var myform = document.getElementById("numberform");
    myform.num3.value = resp;
  }
</script>
</head>
<body>
<p>
<b>Compute the sum via xmlhttprequest, passing form variables.</b><br />
Note this example works with or without javascript enabled!<br />

<br />
<form id="numberform" method="POST" action="">
<input type="text" name="num1" size="3" value="<?php echo isset($_POST['num1']) ? $_POST['num1'] : 3; ?>"> +
<input type="text" name="num2" size="3" value="<?php echo isset($_POST['num1']) ? $_POST['num1'] : 5; ?>"> =
<input type="text" name="num3" size="3" value="<?php echo !empty($_POST) ? (int) $_POST['num1'] + (int) $_POST['num2'] : ''; ?>">
<input type="submit" name="submit" value="calculate" onclick="return(!smartjax_addnum());">
</form>
<p>
Other examples:<br />
<a href="helloworld.php">Helloworld (text update)</a></br />
<a href="addnum.php">Add Numbers (form submit)</a><br />
<a href="random.php">Random Numbers (three values updated)</a><br />
</p>
<p>
<a href="http://www.phpinsider.com/php/code/SmartJax/">SmartJax Home Page</a>
</p>
</body>
</html>
