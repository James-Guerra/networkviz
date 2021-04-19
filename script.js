$(document).ready(function() {
    var rawJSON = $("#interactive-graph").attr("data-graph");
    rawJSON = "lib/plugins/networkviz/" + rawJSON;
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

    var parsedGraph = {
        nodes: graph.nodes,
        edges: graph.edges
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
            }
        },
        interaction: {
            hover: true,
        }   
    }
    //assign full screen div to `container`
    var container = document.querySelector("#interactive-graph");

    // `vis.Network()` does all the magic and should display the graph on html canvas
    var network = new vis.Network(container, parsedGraph, options);
    })
})

// `redirect()` takes the NODE'S id when clicked and assign's it to an <input> tag (with id #node-id) 
// The submit button is then fired, to change the search query which should ultimately redirect the page.

// NOTE: THERE IS PROBABLY A BETTER WAY TO DO THIS. I THINK THIS METHOD IS A VERY 'WALK-AROUND' WAY OF ACHIEVING
// WHAT NEEDS TO BE DONE.
function redirect(values, id, selected, hovering) {
    // var nodeId = jQuery("#node-id");
    // nodeId.value = id
    // let redirect = confirm("Would you like to redirect to " + id);
    // if(redirect) {
    //     document.getElementById("get-nodes").submit();
    // }
}