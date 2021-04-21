<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>
    <title>Document</title>
    <style type="text/css">
        #interactive-graph {
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
    <div id="interactive-graph" data-graph="sample_data.json"></div>
    <form id="get-nodes" method="get" action="network_sample.php" style="display:none">
        <input name="nodeId" type="text" id="node-id" value="Mexican"/>
        <input type="submit">
    </form>

    <script>
        $(document).ready(function() {
    var rawJSON = $("#interactive-graph").attr("data-graph");
    rawJSON = rawJSON;
    console.log(rawJSON)

    $.getJSON(rawJSON, function(graph) {
        // inherit all properties from gephi graph
    var parserOptions = {
        edges: {
            inheritColors: false
        },
        nodes: {
            fixed: true,
            parseColor: true
        }
    }

    graph.nodes.forEach(node => {
        node.group = "hi";
        console.log(node)
    })
    graph.nodes.forEach(node => {
        node.color = "rgb(142, 145, 145)"
    });
    graph.edges.forEach(edge => {
        edge.color = "rgb(142, 145, 145)"
    });
    // console.log(graph.nodes)
    var parsed = vis.parseGephiNetwork(graph, parserOptions);

    var parsedGraph = {
        nodes: parsed.nodes,
        edges: parsed.edges
    }

    //a few customary options to make graph look some what nice
    var options = {
        physics: {
            enabled: true,
        },
        nodes: {
            shape: "dot",
            chosen: {
                //on node click, call redirect function
                node: redirect
            },
            font: "40px arial black",
            fixed: {
                x: true,
            },
            scaling: {
                label: {
                    min: 33,
                    maxVisible: 55,
                    drawThreshold: 5
                }
            },
        },
        edges: {
            color: {
                highlight: "blue"

            },
            hoverWidth: 7,
            length: 400,
            scaling: {
                label: {
                    min: 33,
                    maxVisible: 55,
                    drawThreshold: 5
                }
            },
            selectionWidth: 7,
            smooth: {
                type: "continuous",
                roundness: 1
            },
        },

        interaction: {
            hover: true,
        },
        layout: {
            clusterThreshold: 0
        },
        physics: {
            barnesHut: {
                gravitationalConstant: -19000,
                springConstant: 0.01,
                damping: 0.1
            },
            stabilization: {
                iterations: 2000
            }
        },
    }
    //assign full screen div to `container`
    var container = document.querySelector("#interactive-graph");

    // `vis.Network()` does all the magic and should display the graph on html canvas
    var network = new vis.Network(container, parsedGraph, options);
    })
})
function redirect(values, id, selected, hovering) {
    console.log(values)
    values.size = 15
    // values.color = "blue"
    // var nodeId = jQuery("#node-id");
    // nodeId.value = id
    // let redirect = confirm("Would you like to redirect to " + id);
    // if(redirect) {
    //     document.getElementById("get-nodes").submit();
    // }
}
    </script>
</body>
</html>