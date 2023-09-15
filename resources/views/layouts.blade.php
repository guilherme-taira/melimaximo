<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard - Dark Style</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>

<body>


    <div id="chart"></div>
    <div id="wrapper">
        <div class="content-area">
            <div class="container-fluid">
                <div class="main">
                    <div class="row mt-4">
                        <div class="col-md-5">
                            <div class="box columnbox mt-4">
                                <div id="columnchart"></div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="box  mt-4">
                                <div id="linechart"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5">
                            <div class="box radialbox mt-4">
                                <div id="circlechart"></div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="box mt-4">
                                <div class="mt-4">
                                    <div id="progress1"></div>
                                </div>
                                <div class="mt-4">
                                    <div id="progress2">
                                        <button onclick="gerarGtin()" class="btn btn-warning">Gerar GTIN</button>
                                        <ul id="gtinList"></ul>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div id="progress3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script>

        var mesesDiv = document.querySelector("#wrapper > div > div > div > div.row.mt-4 > div.col-md-5 > div");

        mesesDiv.insertAdjacentHTML('afterbegin',
        `
        <div class='mlprice-Container-width-small' id='mlLoading'>
            <img src="https://usagif.com/wp-content/uploads/loading-23.gif" class="loadingPicture">
        </div>
        `);

        var diasDiv = document.querySelector("#wrapper > div > div > div > div.row.mt-4 > div.col-md-7 > div");

        diasDiv.insertAdjacentHTML('afterbegin',
        `
        <div class='mlprice-Container-width-small' id='mlLoadingDias'>
            <img src="https://usagif.com/wp-content/uploads/loading-23.gif" class="loadingPicture">
        </div>
        `);

        var conversaoTaxa = document.querySelector("#wrapper > div > div > div > div:nth-child(2) > div.col-md-5 > div.box.radialbox.mt-4");

        conversaoTaxa.insertAdjacentHTML('afterbegin',
        `
        <div class='mlprice-Container-width-small' id='mlLoadingConversao'>
            <img src="https://usagif.com/wp-content/uploads/loading-23.gif" class="loadingPicture">
        </div>
        `);


        var conversaoVisitasMes = document.querySelector("#wrapper > div > div > div > div:nth-child(2) > div.col-md-7 > div");

        conversaoVisitasMes.insertAdjacentHTML('afterbegin',
        `
        <div class='mlprice-Container-width-small' id='mlLoadingConversaoTaxa'>
            <img src="https://usagif.com/wp-content/uploads/loading-23.gif" class="loadingPicture">
        </div>
        `);

        function calcularDigitoVerificador(numero) {
            const digitos = String(numero).split('').map(Number);

            let somaImpares = 0;
            let somaPares = 0;

            for (let i = 0; i < digitos.length; i++) {
                if (i % 2 === 0) {
                    somaImpares += digitos[i];
                } else {
                    somaPares += digitos[i] * 3;
                }
            }

            const total = somaImpares + somaPares;
            const digitoVerificador = (10 - (total % 10)) % 10;

            return digitoVerificador;
        }

        function copy(text, target) {
            setTimeout(function() {
                $('#copied_tip').remove();
            }, 800);
            $(target).append("<div class='tip' id='copied_tip'>Copied!</div>");
            var input = document.createElement('input');
            input.setAttribute('value', text);
            document.body.appendChild(input);
            input.select();
            var result = document.execCommand('copy');
            document.body.removeChild(input)
            return result;
        }

        function gerarGtinComPrefixo(prefixo) {
            const numeroBase = Math.floor(Math.random() * 9000000000) + 1000000000;
            const gtinSemDV = Number(String(prefixo) + String(numeroBase));
            const digitoVerificador = calcularDigitoVerificador(gtinSemDV);
            const gtin = Number(String(gtinSemDV) + String(digitoVerificador));
            return gtin;
        }

        function gerarGtin() {
            const prefixo = 789;
            const quantidadeGtinGerados = 5; // Defina aqui a quantidade de GTINs que deseja gerar
            const gtinList = [];

            for (let i = 0; i < quantidadeGtinGerados; i++) {
                const gtinGerado = gerarGtinComPrefixo(prefixo);
                gtinList.push(gtinGerado);
            }

            // Exibe os GTINs gerados na lista na página
            const gtinListElement = document.getElementById('gtinList');
            gtinListElement.innerHTML = ''; // Limpa a lista antes de exibir os novos GTINs

            for (const gtin of gtinList) {
                const li = document.createElement('li');
                li.textContent = gtin;
                $("#gtinList").append(
                    `<li><span class="tamanho-gtin">${gtin}</span> <i class='bi bi-clipboard'></i></li>`
                    );
            }

        }

        window.Apex = {
            chart: {
                foreColor: '#fff',
                toolbar: {
                    show: false
                },
            },
            colors: ['#fccf31', '#17ead9', '#48b617'],
            stroke: {
                width: 3
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

        };

        //URL PARAMETER
        const idMl = getUrlParameter('id');

        $.ajax({
            url: `http://3.135.237.155/api/v1/getMetricsMercadoLivre150days?id=${idMl}`,
            method: 'GET',
            headers: {
                Accept: 'aplication/json',
                'Content-Type': 'aplication/json',
                'Access-Control-Allow-Origin': '*',
                'Access-Control-Allow-Methods': 'GET',
            },
            success: function(response) {
                // RESULT OF REQUEST
                const {
                    results
                } = response;
                const days = [];
                var total = 0;
                const visitas = [];
                results.forEach(function(i, index) {
                    var {
                        date,
                        total
                    } = i;
                    visitas[index] = total;
                    days[index] = formatDate(new Date(date));
                });

                var maior = 0;
                for (let e = 0; e < visitas.length; e++) {

                    if (maior < visitas[e]) {
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
                    success: function(response) {

                        // REMOVE O LOADING
                        $("#mlLoadingDias").remove();

                        var CalculoConversao = total;

                        // RESULT OF REQUEST
                        var ProjecaoVenda = [];
                        var itemPrice = 0;
                        for (let index = 0; index < visitas.length; index++) {
                            itemPrice = parseInt(visitas[index] / ((total / response.sold_quantity)
                                .toFixed(2)));
                            ProjecaoVenda.push(itemPrice);
                        }

                        var optionsLine = {
                            series: [{
                                name: 'Visitas',
                                data: visitas
                            }, {
                                name: 'Projeção de Vendas',
                                data: ProjecaoVenda
                            }],
                            chart: {
                                height: 350,
                                type: 'area'
                            },
                            title: {
                                floating: true,
                                offsetX: 0,
                                offsetY: -5,
                                text: `Visitas dos Últimos 150 dias`
                            },
                            dataLabels: {
                                enabled: false
                            },
                            stroke: {
                                curve: 'smooth'
                            },
                            xaxis: {
                                type: 'string',
                                categories: days
                            },
                            tooltip: {
                                x: {
                                    format: 'dd/MM/yy HH:mm'
                                },
                            },
                        };

                        var chartLine = new ApexCharts(
                            document.querySelector("#linechart"),
                            optionsLine
                        );
                        chartLine.render();

                        // REMOVE LOADING
                        $("#mlLoadingConversao").remove();

                        var optionsCircle = {
                            series: [{
                                name: 'Métricas',
                                group: 'Valor',
                                data: [total, response.sold_quantity, ((response
                                        .sold_quantity / CalculoConversao) *
                                    100).toFixed(0)]
                            }],
                            chart: {
                                type: 'bar',
                                height: 300,
                                stacked: true,
                            },
                            title: {
                                floating: true,
                                offsetX: 0,
                                offsetY: -5,
                                text: `Taxa de Conversão: ${((response.sold_quantity / CalculoConversao) * 100).toFixed(2)} %`
                            },
                            subtitle: {
                                align: 'left',
                                offsetY: 20,
                                text: `A cada ${(total / response.sold_quantity).toFixed(2)} Visitas teve 1 Conversão`,
                                style: {
                                    fontSize: '16px'
                                }
                            },
                            stroke: {
                                width: 1,
                                colors: ['#fff']
                            },
                            dataLabels: {
                                formatter: (val) => {
                                    return val
                                }
                            },
                            plotOptions: {
                                bar: {
                                    horizontal: false
                                }
                            },
                            xaxis: {
                                categories: [
                                    'Visitas',
                                    'Vendas',
                                    'Convesão'
                                ],
                                labels: {
                                    formatter: (val) => {
                                        return val;
                                    }
                                }
                            },
                            fill: {
                                opacity: 1,
                            },
                            colors: ['#80c7fd', '#008FFB', '#80f1cb', '#00E396'],
                            legend: {
                                position: 'top',
                                horizontalAlign: 'left'
                            }
                        };

                        var chartCircle = new ApexCharts(document.querySelector('#circlechart'),
                            optionsCircle);
                        chartCircle.render();

                    },
                    error: function(error) {
                        console.log(error);
                    }
                });


            },
            error: function(error) {
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

        function getMonthName(monthNumber) {
            switch (monthNumber) {
                case 1:
                    return "Janeiro";
                case 2:
                    return "Fevereiro";
                case 3:
                    return "Março";
                case 4:
                    return "Abril";
                case 5:
                    return "Maio";
                case 6:
                    return "Junho";
                case 7:
                    return "Julho";
                case 8:
                    return "Agosto";
                case 9:
                    return "Setembro";
                case 10:
                    return "Outubro";
                case 11:
                    return "Novembro";
                case 12:
                    return "Dezembro";
                default:
                    return "Número de mês inválido";
            }
        }

        // CODIGO QUE PEGA POR MES AS VISITAS

        $.ajax({
            url: `http://3.135.237.155/api/v1/getMetricsMercadoLivre150days?id=${idMl}`,
            method: 'GET',
            headers: {
                Accept: 'aplication/json',
                'Content-Type': 'aplication/json',
                'Access-Control-Allow-Origin': '*',
                'Access-Control-Allow-Methods': 'GET',
            },
            success: function(response) {
                // REMOVE DIV
                $("#mlLoadingConversaoTaxa").remove();

                // RESULT OF REQUEST
                const {
                    results
                } = response;
                let days = [];
                let visitas = [];
                results.forEach(function(i, index) {
                    var {
                        date,
                        total
                    } = i;
                    visitas[index] = total;
                    days[index] = new Date(date);

                });

                var ArrayMes = [];
                var ArrayMesNumber = [];
                for (let index = 0; index < days.length; index++) {
                    if (ArrayMes.includes(getMonthName(days[index].getMonth() + 1)) == false) {
                        ArrayMes.push(getMonthName(days[index].getMonth() + 1));
                    }
                }


                const mesesSeparados = separarEmMeses(days, visitas);
                var numeroPorcentagem = 0;
                var valor = 0;
                if (mesesSeparados[2] > mesesSeparados[3]) {
                    numeroPorcentagem = "Caiu: -" + ((mesesSeparados[2] / mesesSeparados[3]) * 100).toFixed(2);
                    valor = ((mesesSeparados[2] / mesesSeparados[3]) * 100).toFixed(2);
                } else {
                    numeroPorcentagem = "Subiu: +" + ((mesesSeparados[3] / mesesSeparados[2]) * 100).toFixed(2);
                    valor = ((mesesSeparados[3] / mesesSeparados[2]) * 100).toFixed(2);
                }

                var optionsProgress1 = {
                    chart: {
                        height: 70,
                        type: 'bar',
                        stacked: false,
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
                        success: function(response) {
                            // RESULT OF REQUEST
                            const {
                                sold_quantity
                            } = response;

                            let total = 0;

                            for (let i = 0; i < visitas.length; i++) {
                                total += visitas[i];
                            }

                            var ProjecaoVendaMes = [];
                            var itemPrice = 0;
                            for (let index = 0; index < mesesSeparados.length; index++) {
                                itemPrice = parseInt(mesesSeparados[index] / ((total / response
                                        .sold_quantity)
                                    .toFixed(2)));
                                ProjecaoVendaMes.push(itemPrice);
                            }

                            // REMOVE LOADING ON DIV
                            $("#mlLoading").remove();

                            var optionsColumn = {
                                series: [{
                                    name: 'Visitas',
                                    type: 'column',
                                    data: mesesSeparados
                                }, {
                                    name: 'Projeção de Vendas (Mês)',
                                    type: 'column',
                                    data: ProjecaoVendaMes
                                }],
                                chart: {
                                    height: 350,
                                    type: 'line',
                                },
                                stroke: {
                                    width: [0, 4, 4]
                                },
                                title: {
                                    text: 'Visitas dos Últimos Meses'
                                },
                                dataLabels: {
                                    enabled: true,
                                    enabledOnSeries: [1]
                                },
                                labels: ArrayMes,
                                xaxis: {
                                    type: 'string'
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
                        error: function(error) {
                            console.log(error);
                        }
                    });
                }




            },
            error: function(error) {
                console.log(error);
            }
        });


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
            url: `http://3.135.237.155/api/v1/getMetricsMercadoLivre150days?id=${idMl}`,
            method: 'GET',
            headers: {
                Accept: 'aplication/json',
                'Content-Type': 'aplication/json',
                'Access-Control-Allow-Origin': '*',
                'Access-Control-Allow-Methods': 'GET',
            },
            success: function(response) {
                // RESULT OF REQUEST
                const {
                    results
                } = response;
                let days = [];
                let visitas = [];
                results.forEach(function(i, index) {
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
            error: function(error) {
                console.log(error);
            }
        });


        window.setInterval(function() {

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
    </script>
</body>

</html>
