<html>
<head>
<title>SmartJax Helloworld Example</title>
<script type="text/javascript" src="/smartjax/smartjax.js"></script>
<script type="text/javascript">
  var sjObj = new SMARTJAX("/smartjax/smartjax_server.php");
  function smartjax_hello() {
    return sjObj.call("helloworld", cbHello);
  }
  function cbHello(resp) {
    document.getElementById("content").innerHTML = resp;
  }
</script>
</head>
<body>
<p>
<b>Text and time via xmlhttprequest.</b><br />
Click the link to get new information.<br />
Note this example works with or without javascript enabled!
</p>
<a href="" onclick="return(!smartjax_hello());">click me!</a>
<br />
<div id="content"><?php echo "Hello World! time is" . strftime('%H:%M:%S %Z'); ?></div>
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
