<?php
    // $url = $_SERVER['HTTP_HOST'];
    $url.=$_SERVER['REQUEST_URI'];
    $url=substr($url, 1, -1);
    // echo $url;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    <div class="spinner">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
        <div class="rect4"></div>
        <div class="rect5"></div>
    </div>
    <div id="interactive-graph" data-graph="sample_data.json"></div>
    <div id="overlay">
        <div class="redirect-container">
            <p class="redirect-prompt"></p>
            <form action="sample.php" method="get" id="confirm-redirect">
                <button class="button" type="submit" id="confirm-button">Confirm</button>
            </form>
            <button class="button" id="cancel-button">Cancel</button>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            var network;
            var globalGraph;
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
            node.color = "#205771"
        });
        graph.edges.forEach(edge => {
            edge.color = "#205771"
        });
        var parsed = vis.parseGephiNetwork(graph, parserOptions);
        globalGraph = graph

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
                    node: handleChosenState
                },
                font: {
                    color: "black",
                    size: 28,
                    face: "arial",
                },
                fixed: {
                    x: true,
                },
                scaling: {
                    label: {
                        min: 15,
                        max: 100,
                        maxVisible: 100,
                        drawThreshold: 2
                    }
                },
                size: 32,
                // title: htmlTitle("some text")
            },
            edges: {
                color: {
                    // highlight: "blue"
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
        network = new vis.Network(container, parsedGraph, options);
        
        network.on("click", properties => {
            network.renderer.renderingActive = false
            console.log(network.renderer)
            id = properties.nodes[0]
            if(id != undefined) {
                label = network.nodesHandler.body.nodes[id].options.label
                redirectPopup(label);
            }
        });

        network.once("stabilizationIterationsDone", function() {
            $(".spinner").css('display', 'none');
        }); 
    })

    function handleChosenState(values, id) {
        nodeTitle = network.nodesHandler.body.nodes[id].options.attributes.Title            
        network.nodesHandler.body.nodes[id].options.title = htmlTitle(nodeTitle)
        values.size = 15
    }

    function htmlTitle(html) {
        const container = document.createElement("div");
        container.className = "html-title"
        container.innerHTML = html;
        return container;
    }   

    function redirectPopup(id) {
        $("#confirm-button")[0].value = id
        let redirectContainer = $(".redirect-container");
        let redirectPrompt = $(".redirect-container p")
        redirectContainer[0].style.visibility = "visible";
        displayId = id.bold();
        displayId = displayId.italics();
        redirectPrompt[0].innerHTML = "Would you like to redirect to " + displayId
        $(".redirect-container").slideFadeToggle('3%')
        $("#overlay").fadeIn(300)
        
    }

    $("#cancel-button").click(() => {
        $("#overlay").fadeOut(300)
    })

    $.fn.slideFadeToggle  = function(position) {
        return this.animate({opacity: '1', top: position});
    }; 
})

    </script>
</body>
</html>