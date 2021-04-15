let redirectUrl;

// var nodes = new vis.DataSet([
//         {id: 1, label: 'Node 1'},
//         {id: 2, label: 'Node 2'},
//         {id: 3, label: 'Node 3'},
//         {id: 4, label: 'Node 4'},
//         {id: 5, label: 'Node 5'}
//     ]);

//     // create an array with edges
//     var edges = new vis.DataSet([
//         {from: 1, to: 3},
//         {from: 1, to: 2},
//         {from: 2, to: 4},
//         {from: 2, to: 5}
//     ]);

//     var data = {
//         nodes: nodes,
//         edges: edges
//     };

//     var options = {
//         nodes: {
//             shape: "dot",
//             chosen: {
//                 label: redirect
//             }
//         },
//         edges: {
//             chosen: {
//                 label: redirect
//             }
//         }
//     }
//     container = document.getElementById("mynetwork")

//     var network = new vis.Network(container, data, options);

let gephiJSON = fetch("./sample_data.json")
    .then(results => results.json())
    .then(graphUnpacked => {
        var parserOptions = {
            edges: {
                inheritColors: false
            },
            nodes: {
                fixed: true,
                parseColor: true
            }
        }
        var parsed = vis.parseGephiNetwork(graphUnpacked, parserOptions);
        var data = {
            nodes: parsed.nodes,
            edges: parsed.edges
        };

        var options = {
            physics: {
                enabled: true,
            },
            nodes: {
                shape: "dot",
                chosen: {
                    node: redirect
                }
            },
            interaction: {
                hover: true,
            }
        }
        container = document.getElementById("mynetwork")
        var network = new vis.Network(container, data, options);
    });

function redirect(values, id, selected, hovering) {
    var nodeId = document.getElementById("node-id");
    nodeId.value = id
    let redirect = confirm("Would you like to redirect to " + id);
    if(redirect) {
        document.getElementById("get-nodes").submit();
    }
}