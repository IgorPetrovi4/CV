var ctxLight = document.getElementById('myChartLight')
var dataLight = document.getElementById('myChartLight').getAttribute('data-light')

var myChartLight = new Chart( ctxLight, {
    type: 'bar',
    data: {
        labels: ['Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень', 'Серпень', 'Вересень', 'Жовтень', 'Литопад', 'Грудень'],
        datasets: [{
            label: 'Витрачено грн.',
            data: JSON.parse(dataLight),
            backgroundColor:
                'rgba(14,197,243)',
            borderColor:
                'rgba(7,127,208)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});


var ctxWater = document.getElementById('myChartWater');
var dataWater = document.getElementById('myChartWater').getAttribute('data-water')

var myChartWater = new Chart(ctxWater, {
    type: 'bar',
    data: {
        labels: ['Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень', 'Серпень', 'Вересень', 'Жовтень', 'Литопад', 'Грудень'],
        datasets: [{
            label: 'Витрачено грн.',
            data: JSON.parse(dataWater),
backgroundColor:
    'rgba(15,241,151)',
        borderColor:
'rgba(5,134,74)',
    borderWidth: 1
}]
},
options: {
    scales: {
        yAxes: [{
            ticks: {
                beginAtZero: true
            }
        }]
    }
}
});

var ctxHeat = document.getElementById('myChartHeat');
var dataHeat = document.getElementById('myChartHeat').getAttribute('data-heat')
var myChartHeat = new Chart(ctxHeat, {
    type: 'bar',
    data: {
        labels: ['Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень', 'Серпень', 'Вересень', 'Жовтень', 'Литопад', 'Грудень'],
        datasets: [{
            label: 'Витрачено грн.',
            data: JSON.parse(dataHeat),
            backgroundColor:
                'rgba(236,39,118)',
            borderColor:
                'rgba(125,14,46)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});

var ctxSdpt = document.getElementById('myChartSdpt');
var dataSdpt = document.getElementById('myChartSdpt').getAttribute('data-sdpt')
var myChartSdpt = new Chart(ctxSdpt, {
    type: 'bar',
    data: {
        labels: ['Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень', 'Серпень', 'Вересень', 'Жовтень', 'Литопад', 'Грудень'],
        datasets: [{
            label: 'Витрачено грн.',
            data: JSON.parse(dataSdpt),
            backgroundColor:
                'rgba(236,226,39)',
            borderColor:
                'rgba(125,71,14)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});


