<html>
<head>
<title>SmartJax Random Number Example</title>
<script type="text/javascript" src="/smartjax/smartjax.js"></script>
<script type="text/javascript">
  var sjObj = new SMARTJAX("/smartjax/smartjax_server.php");
  function smartjax_getrand() {
    return sjObj.call("getrand", cbGetrand);
  }
  function cbGetrand(resp) {
    document.getElementById("content").innerHTML = resp.rand;
    document.getElementById("content2").innerHTML = resp.rand2;
    document.getElementById("content3").innerHTML = resp.rand3;
  }
</script>
</head>
<body>
<p>
<b>Three random numbers via xmlhttprequest.</b><br />
Click the link to get new random numbers.<br />
Note this example works with or without javascript enabled!
</p>
<a href="" onclick="return(!smartjax_getrand());">click me!</a>
<br />
<div id="content"><?php echo rand(1,1000); ?></div>
<div id="content2"><?php echo rand(1,1000); ?></div>
<div id="content3"><?php echo rand(1,1000); ?></div>

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
