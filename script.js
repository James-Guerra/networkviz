$(document).ready(function() {
    var rawJSON = $("#interactive-graph").attr("data-graph");
    rawJSON = "lib/plugins/networkviz/" + rawJSON;

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
            id = properties.nodes[0]
            if(id != undefined) {
                label = network.nodesHandler.body.nodes[id].options.label
                redirectPopup(label);
            }
        });

        network.once("stabilizationIterationsDone", function() {
            $(".spinner").css('display', 'none');
            $(".network-searcher").css("display", "block");
        }); 

        // console.log(network)
        mygraph = {
            nodes: network.body.nodes,
            edges: network.body.edges
        }
        // console.log(mygraph)
        for(let key in mygraph.nodes) {
            $(".results-container").append(`<div>${key}</div>`)
        }
        $(".network-searcher input").keyup(e => {
            var input, filter, ul, li, a, i, txtValue;
            input = document.querySelector(".network-searcher input");
            filter = input.value.toUpperCase();
            allDivs = document.querySelectorAll(".results-container div");
            allDivs.forEach(div => {
                // console.log(div)
                txtValue = div.innerText;
                if(txtValue.toUpperCase().indexOf(filter) > -1) {
                    div.style.display = "block";
                } else {
                    div.style.display = "none";
                }
            })
        })
        
        let resultsContainer = document.querySelectorAll(".results-container div");
        resultsContainer.forEach(div => {
            div.addEventListener("click", () => {
                nodeId = div.innerText;
                // console.log(nodeId)
                focusOnNode(network, nodeId)
            })
        })
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

    $("body").keydown(e => {
        let input = $(".network-searcher, .network-searcher > input");
        if(e.which == 13) {
            input.css("display", "block");
            input.focus();
            input.attr("placeholder", "")
            $(".results-container").css("height", "400px");
            $(".results-container").css("display", "block");
        }
        if(e.which == 27) {
            input.blur()
            input.attr("placeholder", "Press " + "\u23CE to start searching this network")
            $(".results-container").css("height", "0px");
        }
    })

    $(".network-searcher input").on("focus", () => {
        $(".results-container").css("height", "400px");
        $(".results-container").css("display", "block");
        $(".network-searcher input").attr("placeholder", "");
    })

    $(".network-searcher").click(() => {
        $(".network-searcher input").focus();
    })
    
    function focusOnNode(network, nodeId) {
        network.focus(nodeId, {scale: 0.7, animation:true});
        network.selectNodes([nodeId]);
    }
    
    
})