<!DOCTYPE HTML>
<html>
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Smartphone brand response analysis</title>
        <script src="jquery.min.js"></script>
        <script src="highcharts.js"></script>
	</head>
	<body>
        <div id="container-pie" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        <div id="container-bar" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        <div id="container-score" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        <script type="text/javascript">
            $.getJSON('get_data.php',function(json){
                var totalArray = json[0].map(function(i,j){
                    return parseInt(json[2][j][1]) + parseInt(json[3][j][1]) + parseInt(json[1][j][1]);
                })
                var series = [
                    {
                        name : 'Negetive',
                        data : json[2].map(function(i,j){return 100*parseInt(i[1])/totalArray[j]})
                    },
                    {
                        name : 'Neutral',
                        data : json[3].map(function(i,j){return 100*parseInt(i[1])/totalArray[j]})
                    },
                    {
                        name : 'Positive',
                        data : json[1].map(function(i,j){return 100*parseInt(i[1])/totalArray[j]})
                    }
                ];
                $('#container-bar').highcharts({
                    chart: {
                        type: 'bar'
                    },
                    title: {
                        text: 'Data volume'
                    },
                    plotOptions : {
                        series : {
                            stacking : 'normal'
                        }
                    },
                    xAxis: {
                        categories: json[0].map(function(i){return i[0]})
                    },
                    credits: {
                        enabled: false
                    },
                    series: series
                })
                $('#container-pie').highcharts({
                    title: {
                        text: 'Data volume'
                    },
                    credits: {
                        enabled: false
                    },
                    series: [{
                        type : 'pie',
                        name : 'Data volume distribution',
                        data : json[0].map(function(i){
                            return [i[0], parseInt(i[1])]
                        })
                    }]
                })
                $('#container-score').highcharts({
                    chart: {
                        type: 'bar'
                    },
                    title: {
                        text: 'Sentiment distribution'
                    },
                    plotOptions : {
                        series : {
                            stacking : 'normal'
                        }
                    },
                    xAxis: {
                        categories: json[0].map(function(i){return i[0]})
                    },
                    credits: {
                        enabled: false
                    },
                    series: json[0].map(function(i,j,k){
                        var dataArray = Array(k.length);
                        for(var l=0; l < dataArray.length; l++){
                            dataArray[l] = 0;
                        }
                        dataArray[j] = 1250*parseFloat(i[2])/totalArray[j];
                        return {
                            name : i[0],
                            data : dataArray
                        }
                    })
                })
            })
        </script>
	</body>