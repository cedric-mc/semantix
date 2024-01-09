<!DOCTYPE html>
<html>
<head>
    <title>Partie solo</title>
    <link rel="stylesheet" href="style2.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/networkgraph.js"></script>
</head>
<body>
<main>
    <br><br>
    <div class="wrapper">
        <form id="addNodeForm">
            <input type="text" id="newNodeName" placeholder="Enter node">
            <button type="submit">Add Node</button>
        </form>
    <div id="networkGraph" style="width: 600px; height: 400px; "></div>
    </div>
</main>

<script>
    var chart;
    var animalNames = ['Lion', 'Tiger', 'Bear', 'Elephant', 'Giraffe', 'Zebra', 'Panda', 'Kangaroo', 'Wolf', 'Fox'];
    var isFirstAddition = true; // Global flag to check if it's the first node being added
    function getRandomAnimals(animalArray) {
        var randomAnimals = [];
        while(randomAnimals.length < 2){
            var r = Math.floor(Math.random() * animalArray.length);
            if(randomAnimals.indexOf(animalArray[r]) === -1) randomAnimals.push(animalArray[r]);
        }
        return randomAnimals;
    }

    var selectedAnimals = getRandomAnimals(animalNames);
    document.addEventListener('DOMContentLoaded', function () {
        chart = Highcharts.chart('networkGraph', {
            chart: {
                type: 'networkgraph',
                height: '100%'
            },
            title: {
                text: 'Animal Network Graph'
            },
            series: [{
                dataLabels: {
                    enabled: true,
                    linkFormat: '',
                    linkTextPath: {
                        enabled: false // Ensure that text is not displayed on links
                    }
                },
                nodes: [{
                    id: selectedAnimals[0],
                    name: selectedAnimals[0]
                }, {
                    id: selectedAnimals[1],
                    name: selectedAnimals[1]
                }],
                data: [{
                    from: selectedAnimals[0],
                    to: selectedAnimals[1]
                }],
                marker: {
                    radius: 30,
                    symbol: 'circle'
                }
            }]

        });
        document.getElementById('addNodeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var newNodeName = document.getElementById('newNodeName').value;

            if(newNodeName && !chart.get(newNodeName)) { // Check if node doesn't already exist
                chart.series[0].addPoint({ // Add the new node
                    id: newNodeName,
                    name: newNodeName
                });
                if (isFirstAddition) {
                    // For the first addition, link the new node between the initial nodes
                    chart.series[0].addPoint({ from: newNodeName, to: selectedAnimals[0] });
                    chart.series[0].addPoint({ from: newNodeName, to: selectedAnimals[1] });
                    isFirstAddition = false; // Update the flag
                } else {
                    // For subsequent additions, link to a random existing node
                    var existingNodes = chart.series[0].nodes;
                    var randomNode = existingNodes[Math.floor(Math.random() * existingNodes.length)];
                    if (randomNode) {
                        chart.series[0].addPoint({ from: newNodeName, to: randomNode.id });
                    }
                }

                document.getElementById('newNodeName').value = ''; // Clear the input field
            }
        });
    });



</script>
</body>
</html>
