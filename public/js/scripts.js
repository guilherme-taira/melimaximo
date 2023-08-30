window.Apex = {
    chart: {
        foreColor: '#fff',
        toolbar: {
            show: false
        },
    },
    colors: ['#FCCF31', '#17ead9', '#f02fc2'],
    stroke: {
        width: 3
    },
    dataLabels: {
        enabled: false
    },
    grid: {
        borderColor: "#40475D",
    },
    xaxis: {
        axisTicks: {
            color: '#333'
        },
        axisBorder: {
            color: "#333"
        }
    },
    fill: {
        type: 'gradient',
        gradient: {
            gradientToColors: ['#F55555', '#6078ea', '#6094ea']
        },
    },
    tooltip: {
        theme: 'dark',
        x: {
            formatter: function (val) {
                return moment(new Date(val)).format("HH:mm:ss")
            }
        }
    },
    yaxis: {
        decimalsInFloat: 2,
        opposite: true,
        labels: {
            offsetX: -10
        }
    }
};

var trigoStrength = 3
var iteration = 11



function getRandom() {
    var i = iteration;
    return (Math.sin(i / trigoStrength) * (i / trigoStrength) + i / trigoStrength + 1) * (trigoStrength * 2)
}

function getRangeRandom(yrange) {
    return Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min
}

function generateMinuteWiseTimeSeries(baseval, count, yrange) {
    var i = 0;
    var series = [];
    while (i < count) {
        var x = baseval;
        var y = ((Math.sin(i / trigoStrength) * (i / trigoStrength) + i / trigoStrength + 1) * (trigoStrength * 2))

        series.push([x, y]);
        baseval += 300000;
        i++;
    }
    return series;
}

function getNewData(baseval, yrange) {
    var newTime = baseval + 300000;
    return {
        x: newTime,
        y: Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min
    }
}

//URL PARAMETER
const idMl = getUrlParameter('id');

$.ajax({
    url: `http://127.0.0.1:8000/api/v1/getMetricsMercadoLivre150days?id=${idMl}`,
    method: 'GET',
    headers: {
        Accept: 'aplication/json',
        'Content-Type': 'aplication/json',
        'Access-Control-Allow-Origin': '*',
        'Access-Control-Allow-Methods': 'GET',
    },
    success: function (response) {
        // RESULT OF REQUEST
        const {
            results
        } = response;
        const days = [];
        var total = 0;
        const visitas = [];
        const sold_quantity = 0;
        results.forEach(function (i, index) {
            var {
                date,
                total
            } = i;
            visitas[index] = total;
            days[index] = formatDate(new Date(date));
        });

        var maior = 0;
        for (let e = 0; e < visitas.length; e++) {

            if(maior < visitas[e]){
                maior = visitas[e];
            }
            total += visitas[e];
        }

        $.ajax({
            url: `https://api.mercadolibre.com/items/${idMl}`,
            method: 'GET',
            headers: {
                Accept: 'aplication/json',
                'Content-Type': 'aplication/json',
                'Access-Control-Allow-Origin': '*',
                'Access-Control-Allow-Methods': 'GET',
            },
            success: function (response) {
                var CalculoConversao = total * 100;

                // RESULT OF REQUEST
                var optionsCircle = {
                    series: [{
                        data: [total, response.sold_quantity]
                    }],
                    chart: {
                        type: 'bar',
                        height: 300
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 2,
                            horizontal: true,
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    title: {
                        text: 'Taxa de Conversão: ' + ((response.sold_quantity / total) * 100).toFixed(4) + "%",
                        align: 'left'
                    },
                    subtitle: {
                        text: `A cada ${(total / response.sold_quantity).toFixed(2)} Visitas, Tiveram 1 Conversão.`,
                        align: 'left',
                        margin: 30,
                        offsetX: 10,
                        offsetY: 30,
                        floating: false,
                        style: {
                            fontSize: '12px',
                            fontWeight: 'normal',
                            fontFamily: undefined,
                            color: '#9699a2'
                        },
                    },
                    dataLabels: {
                        enabled: true,
                        enabledOnSeries: [1]
                    },
                    xaxis: {
                        categories: ['Visitas', 'Vendas'],
                    }
                };

                var chartCircle = new ApexCharts(document.querySelector('#circlechart'), optionsCircle);
                chartCircle.render();

            },
            error: function (error) {
                console.log(error);
            }
        });


        var optionsLine = {
            series: [{
                name: "High - 2013",
                data: visitas
            }, ],
            chart: {
                zoom: {
                    enabled: true,
                    type: 'x',
                    autoScaleYaxis: true,
                    zoomedArea: {
                        fill: {
                            color: '#90CAF9',
                            opacity: 0.4
                        },
                        stroke: {
                            color: '#0D47A1',
                            opacity: 0.4,
                            width: 1
                        }
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                },
                height: 350,
                type: 'line',
                dropShadow: {
                    enabled: true,
                    color: '#000',
                    top: 18,
                    left: 7,
                    blur: 10,
                    opacity: 0.2
                },
                toolbar: {
                    show: true,
                    offsetX: 0,
                    offsetY: 0,
                    tools: {
                        download: true,
                        selection: true,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: true,
                        reset: true | '<img src="/static/icons/reset.png" width="20">',
                        customIcons: []
                    },
                    export: {
                        csv: {
                            filename: undefined,
                            columnDelimiter: ',',
                            headerCategory: 'category',
                            headerValue: 'value',
                            dateFormatter(timestamp) {
                                return new Date(timestamp).toDateString()
                            }
                        },
                        svg: {
                            filename: undefined,
                        },
                        png: {
                            filename: undefined,
                        }
                    },
                    autoSelected: 'zoom'
                },
            },
            colors: ['#77B6EA', '#545454'],
            dataLabels: {
                enabled: true,
            },
            stroke: {
                curve: 'smooth'
            },
            title: {
                text: 'Visitas Últimos 150 Dias',
                align: 'left'
            },
            markers: {
                size: 2
            },
            xaxis: {
                categories: days,
                title: {
                    text: 'Dias'
                }
            },
            yaxis: {
                title: {
                    text: 'Temperature'
                },
                min: 0,
                max: (maior + 100)
            },
            legend: {
                show: true,
                showForSingleSeries: false,
                showForNullSeries: true,
                showForZeroSeries: true,
                position: 'bottom',
                horizontalAlign: 'center',
                floating: false,
                fontSize: '14px',
                fontFamily: 'Helvetica, Arial',
                fontWeight: 400,
                formatter: undefined,
                inverseOrder: false,
                width: undefined,
                height: undefined,
                tooltipHoverFormatter: undefined,
                customLegendItems: [],
                offsetX: 0,
                offsetY: 0,
                labels: {
                    colors: undefined,
                    useSeriesColors: false
                },
                markers: {
                    width: 12,
                    height: 12,
                    strokeWidth: 0,
                    strokeColor: '#fff',
                    fillColors: undefined,
                    radius: 12,
                    customHTML: undefined,
                    onClick: undefined,
                    offsetX: 0,
                    offsetY: 0
                },
                itemMargin: {
                    horizontal: 5,
                    vertical: 0
                },
                onItemClick: {
                    toggleDataSeries: true
                },
                onItemHover: {
                    highlightDataSeries: true
                },
            },
            tooltip: {
                enabled: true,
                formatter: undefined,
                offsetY: 0,
                style: {
                    fontSize: 0,
                    fontFamily: 0,
                },
            },

        };

        var chartLine = new ApexCharts(
            document.querySelector("#linechart"),
            optionsLine
        );
        chartLine.render();

    },
    error: function (error) {
        console.log(error);
    }
});


function formatDate(date) {
    const day = date.getDate().toString().padStart(2, '0'),
        month = (date.getMonth() + 1).toString().padStart(2, '0'),
        year = date.getFullYear();

    return `${day}/${month}/${year}`;
}


// FUNCAO QUE PEGA O VALORES DA URL
function getUrlParameter(name) {
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)');
    var results = regex.exec(window.location.href);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}



// CODIGO QUE PEGA POR MES AS VISITAS

$.ajax({
    url: `http://127.0.0.1:8000/api/v1/getMetricsMercadoLivre150days?id=${idMl}`,
    method: 'GET',
    headers: {
        Accept: 'aplication/json',
        'Content-Type': 'aplication/json',
        'Access-Control-Allow-Origin': '*',
        'Access-Control-Allow-Methods': 'GET',
    },
    success: function (response) {
        // RESULT OF REQUEST
        const {
            results
        } = response;
        let days = [];
        let visitas = [];
        results.forEach(function (i, index) {
            var {
                date,
                total
            } = i;
            visitas[index] = total;
            days[index] = new Date(date);

        });

        const mesesSeparados = separarEmMeses(days, visitas);
        var numeroPorcentagem = 0;
        var valor = 0;
        if(mesesSeparados[3] > mesesSeparados[4]){
            numeroPorcentagem = "Caiu: -" + ((mesesSeparados[3] / mesesSeparados[4]) * 100).toFixed(2);
            valor = ((mesesSeparados[3] / mesesSeparados[4]) * 100).toFixed(2);
        }else{
            numeroPorcentagem = "Subiu: +" + ((mesesSeparados[4] / mesesSeparados[3]) * 100).toFixed(2);
            valor = ((mesesSeparados[4] / mesesSeparados[3]) * 100).toFixed(2);
        }

        console.log(valor);

        var optionsProgress1 = {
            chart: {
                height: 70,
                type: 'bar',
                stacked: true,
                sparkline: {
                    enabled: true
                }
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '20%',
                    colors: {
                        backgroundBarColors: ['#40475D']
                    }
                },
            },
            stroke: {
                width: 0,
            },
            series: [{
                name: 'Visitas em Relação ao Mês Passado',
                data: [valor]
            }],
            title: {
                floating: true,
                offsetX: -10,
                offsetY: 5,
                text: 'Visitas em Relação ao Mês Passado'
            },
            subtitle: {
                floating: true,
                align: 'right',
                offsetY: 0,
                text: `${numeroPorcentagem}%`,
                style: {
                    fontSize: '20px'
                }
            },
            tooltip: {
                enabled: false
            },
            xaxis: {
                categories: ['Process 1'],
            },
            yaxis: {
                max: 100
            },
            fill: {
                opacity: 1
            }
        }

        var chartProgress1 = new ApexCharts(document.querySelector('#progress1'), optionsProgress1);
        chartProgress1.render();


        var optionsProgress2 = {
            chart: {
                height: 70,
                type: 'bar',
                stacked: true,
                sparkline: {
                    enabled: true
                }
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '20%',
                    colors: {
                        backgroundBarColors: ['#40475D']
                    }
                },
            },
            colors: ['#17ead9'],
            stroke: {
                width: 0,
            },
            series: [{
                name: 'Process 2',
                data: [80]
            }],
            title: {
                floating: true,
                offsetX: -10,
                offsetY: 5,
                text: 'Process 2'
            },
            subtitle: {
                floating: true,
                align: 'right',
                offsetY: 0,
                text: '80%',
                style: {
                    fontSize: '20px'
                }
            },
            tooltip: {
                enabled: false
            },
            xaxis: {
                categories: ['Process 2'],
            },
            yaxis: {
                max: 100
            },
            fill: {
                type: 'gradient',
                gradient: {
                    inverseColors: false,
                    gradientToColors: ['#6078ea']
                }
            },
        }

        var chartProgress2 = new ApexCharts(document.querySelector('#progress2'), optionsProgress2);
        chartProgress2.render();


        var optionsProgress3 = {
            chart: {
                height: 70,
                type: 'bar',
                stacked: true,
                sparkline: {
                    enabled: true
                }
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '20%',
                    colors: {
                        backgroundBarColors: ['#40475D']
                    }
                },
            },
            colors: ['#f02fc2'],
            stroke: {
                width: 0,
            },
            series: [{
                name: 'Process 3',
                data: [74]
            }],
            fill: {
                type: 'gradient',
                gradient: {
                    gradientToColors: ['#6094ea']
                }
            },
            title: {
                floating: true,
                offsetX: -10,
                offsetY: 5,
                text: 'Process 3'
            },
            subtitle: {
                floating: true,
                align: 'right',
                offsetY: 0,
                text: '74%',
                style: {
                    fontSize: '20px'
                }
            },
            tooltip: {
                enabled: false
            },
            xaxis: {
                categories: ['Process 3'],
            },
            yaxis: {
                max: 100
            },
        }

        var chartProgress3 = new ApexCharts(document.querySelector('#progress3'), optionsProgress3);
        chartProgress3.render();


        var optionsColumn = {
            series: [{
                name: 'Mês',
                type: 'column',
                data: mesesSeparados
            }, {
                name: 'Visitas',
                type: 'line',
                data: mesesSeparados
            }],
            chart: {
                height: 350,
                type: 'line',
            },
            stroke: {
                width: [0, 4]
            },
            title: {
                text: 'Visitas dos Últimos 5 Meses'
            },
            dataLabels: {
                enabled: true,
                enabledOnSeries: [1]
            },
            labels: ['01 Jan 2001', '02 Jan 2001', '03 Jan 2001', '04 Jan 2001', '05 Jan 2001'],
            xaxis: {
                type: 'datetime'
            },
            yaxis: [{
                title: {
                    text: 'Quantidade de Visitas (Mês)',
                },

            }, {
                opposite: true,
                title: {
                    text: 'Quantidade de Visitas (Mês)',
                }
            }]
        };

        var chartColumn = new ApexCharts(
            document.querySelector("#columnchart"),
            optionsColumn
        );
        chartColumn.render();
    },
    error: function (error) {
        console.log(error);
    }
});

getDataProduct(idMl);

function getDataProduct(id) {
    $.ajax({
        url: `https://api.mercadolibre.com/items/${idMl}`,
        method: 'GET',
        headers: {
            Accept: 'aplication/json',
            'Content-Type': 'aplication/json',
            'Access-Control-Allow-Origin': '*',
            'Access-Control-Allow-Methods': 'GET',
        },
        success: function (response) {
            // RESULT OF REQUEST
            const {
                sold_quantity
            } = response;
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function separarEmMeses(arrayCompleto, visitasCompletas) {
    const meses31Dias = [];
    let mesAtual = [];
    let total = 0;
    let totalVisits = [];
    let mes = [];
    try {
        for (let i = 0; i < arrayCompleto.length; i++) {
            mesAtual.push(arrayCompleto[i].getDate());
            let diaAtual = arrayCompleto[i].getDate();
            let diaPos = arrayCompleto[i + 1].getDate();
            total = total + visitasCompletas[i];
            // visitas[i] PEGA O TOTAL ACUMULA E GRAVA NO MES E ZERA O TOTAL DENTRO DO IF
            if (parseInt(diaAtual) > parseInt(diaPos)) {
                meses31Dias.push(total);
                mesAtual = [];
                total = 0;
            }
        }
    } catch (error) {
        return meses31Dias;
    }

    return meses31Dias;
}



$.ajax({
    url: `http://127.0.0.1:8000/api/v1/getMetricsMercadoLivre150days?id=${idMl}`,
    method: 'GET',
    headers: {
        Accept: 'aplication/json',
        'Content-Type': 'aplication/json',
        'Access-Control-Allow-Origin': '*',
        'Access-Control-Allow-Methods': 'GET',
    },
    success: function (response) {
        // RESULT OF REQUEST
        const {
            results
        } = response;
        let days = [];
        let visitas = [];
        results.forEach(function (i, index) {
            var {
                date,
                total
            } = i;
            visitas[index] = total;
            days[index] = new Date(date);

        });

        const mesesSeparados = separarEmMeses(days, visitas);
        let total = 0;

        for (let i = 0; i < visitas.length; i++) {
            total += visitas[i];
        }


    },
    error: function (error) {
        console.log(error);
    }
});


window.setInterval(function () {

    iteration++;

    chartColumn.updateSeries([{
        data: [...chartColumn.w.config.series[0].data,
            [
                chartColumn.w.globals.maxX + 300000,
                getRandom()
            ]
        ]
    }])

    chartLine.updateSeries([{
        data: [...chartLine.w.config.series[0].data,
            [
                chartLine.w.globals.maxX + 300000,
                getRandom()
            ]
        ]
    }, {
        data: [...chartLine.w.config.series[1].data,
            [
                chartLine.w.globals.maxX + 300000,
                getRandom()
            ]
        ]
    }])

    chartCircle.updateSeries([getRangeRandom({
        min: 10,
        max: 100
    }), getRangeRandom({
        min: 10,
        max: 100
    })])

    var p1Data = getRangeRandom({
        min: 10,
        max: 100
    });
    chartProgress1.updateOptions({
        series: [{
            data: [p1Data]
        }],
        subtitle: {
            text: p1Data + "%"
        }
    })

    var p2Data = getRangeRandom({
        min: 10,
        max: 100
    });
    chartProgress2.updateOptions({
        series: [{
            data: [p2Data]
        }],
        subtitle: {
            text: p2Data + "%"
        }
    })

    var p3Data = getRangeRandom({
        min: 10,
        max: 100
    });
    chartProgress3.updateOptions({
        series: [{
            data: [p3Data]
        }],
        subtitle: {
            text: p3Data + "%"
        }
    })



}, 3000);

var options = {
    chart: {
      type: 'line'
    },
    series: [{
      name: 'sales',
      data: [30, 40, 35, 50, 49, 60, 70, 91, 125]
    }],
    xaxis: {
      categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998, 1999]
    }
  }

  var chart = new ApexCharts(document.querySelector("#chartDiv"), options);

  chart.render();
