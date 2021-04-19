//allows client side to work with DOM
// const {JSDOM} = require("jsdom");
// //necessary for usinf Javascript Fetch function with node
// const fetch = require("node-fetch");
// //necessary for JS to use Vis-Network within node
// const vis =  require('vis-network');
/*
the `network_sample.html` file is assigned to testHtml and converted into text. 

I WAS GOING TO USE THIS TO USE THIS TO SAVE ME FROM HAVING THE ENTIRE HTML FILE INSIDE THIS FILE
BUT WASN'T SURE HOW TO MAKE IT WORK... SO TEMPORARILY ASSIGNING `testHtml` as below

TODO: fix this fetch call
*/
// var testHtml = fetch("http://localhost:1000/network_sample.html")
//     .then(res => res.text())
//     // .then(text => console.log(text))

var testHtml = `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <input name="nodeId" id="node-id" value="Mexican"/>
        <input type="submit">
    </form>
</body>
</html>`

//creates DOM with given html above
// const jsdom = new JSDOM(testHtml)
// const { window } = jsdom;
// const { document } = window;
// global.window = window;
// global.document = document;

// //decided to try and use jQuery as vis-network recommended it
// const $ = global.jQuery = require( 'jquery' );
// //test to see if jQuery is working
// console.log( `jQuery ${jQuery.fn.jquery} working! Yay!!!` );
// //another small test to see if the #node-id can be accessed
// const inputElement = $("#node-id");
// //print out #node-id value
// console.log( inputElement[0].value);

/////////////////////////
/////   IRRELEVANT  /////
/////////////////////////

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

/////////////////////////
/////   IRRELEVANT  /////
/////////////////////////

//fetch `sample_data.json` and unpack the graph
let gephiJSON = fetch("http://localhost:1000/sample_data.json")
    .then(res => res.json())
    .then(graphUnpacked => {
        //inherit all properties from gephi graph
        var parserOptions = {
            edges: {
                inheritColors: false
            },
            nodes: {
                fixed: true,
                parseColor: true
            }
        }

        //parse gephi graph through function to ensure correct format for visnetwork
        var parsed = vis.parseGephiNetwork(graphUnpacked, parserOptions);
        var data = {
            nodes: parsed.nodes,
            edges: parsed.edges
        };

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
        var container = $("#mynetwork");
        
        // `vis.Network()` does all the magic and should display the graph on html canvas
        var network = new vis.Network(container, data, options);
    })
    .catch(err => {
        console.log(err);
    });

// `redirect()` takes the NODE'S id when clicked and assign's it to an <input> tag (with id #node-id) 
// The submit button is then fired, to change the search query which should ultimately redirect the page.

// NOTE: THERE IS PROBABLY A BETTER WAY TO DO THIS. I THINK THIS METHOD IS A VERY 'WALK-AROUND' WAY OF ACHIEVING
// WHAT NEEDS TO BE DONE.
function redirect(values, id, selected, hovering) {
    var nodeId = jQuery("#node-id");
    nodeId.value = id
    let redirect = confirm("Would you like to redirect to " + id);
    if(redirect) {
        document.getElementById("get-nodes").submit();
    }
}