const {JSDOM} = require("jsdom");
let redirectUrl;
const fetch = require("node-fetch");
const vis =  require('vis-network');
var testHtml = fetch("http://localhost:1000/network_sample.html")
    .then(res => res.text())
    .then(text => console.log(text))
const jsdom = new JSDOM(testHtml)
const jQuery = require('jquery')(jsdom.createWindow);


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

let gephiJSON = fetch("http://localhost:1000/sample_data.json")
    .then(res => res.json())
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
        var container = jQuery("#mynetwork");
        var network = new vis.Network(container, data, options);
    })
    .catch(err => {
        console.log(err);
    });

function redirect(values, id, selected, hovering) {
    var nodeId = jQuery("#node-id");
    nodeId.value = id
    let redirect = confirm("Would you like to redirect to " + id);
    if(redirect) {
        document.getElementById("get-nodes").submit();
    }
}