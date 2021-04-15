<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>
    <title>Document</title>
    <style type="text/css">
        #mynetwork {
            width: 100%;
            height: 100%;
            border: 1px solid lightgray;
        }

        body,html {
            height: 100%;
        }
    </style>
</head>
<body>
    <div id="mynetwork"></div>
    <form id="get-nodes" method="get" action="network_sample.php" style="display:none">
        <input name="nodeId" id="node-id"/>
        <input type="submit">
    </form>
</body>
</html>