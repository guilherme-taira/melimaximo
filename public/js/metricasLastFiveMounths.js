$(document).ready(function () {

    function formatDate(date) {
        const day = date.getDate().toString().padStart(2, '0'),
            month = (date.getMonth() + 1).toString().padStart(2, '0'),
            year = date.getFullYear();

        return `${day}/${month}/${year}`;
    }


    // FUNCAO QUE PEGA O VALORES DA URL
    var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
        return false;
    };

    // URL PARAMETER
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
        beforeSend: function () {
            $("#loaderDiv").show();
        },
        success: function (response) {
            // RESULT OF REQUEST
            const {
                results
            } = response;
            console.log(response);
            const days = [];
            const visitas = [];
            results.forEach(function (i, index) {
                var {
                    date,
                    total
                } = i;
                visitas[index] = total;
                days[index] = formatDate(new Date(date));
            });


        },
        error: function (error) {
            console.log(error);
        }
    });

    $.ajax({
        url: `http://127.0.0.1:8000/api/v1/getMetricsMercadoLivre150days?id=${idMl}`,
        method: 'GET',
        headers: {
            Accept: 'aplication/json',
            'Content-Type': 'aplication/json',
            'Access-Control-Allow-Origin': '*',
            'Access-Control-Allow-Methods': 'GET',
        },
        beforeSend: function () {
            $("#loaderDiv").show();
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

            let total = 0;
            let totalVisits = [];
            let mes = [];

            for (let i = 0; i < days.length; i++) {

                for (let i = 0; i < days.length; i++) {
                    if(mes.includes(days[i].getMonth()) === false){
                        mes.push(days[i].getMonth());
                    }
                }

                let firstMounth = days[1].getMonth();

                try {
                    if(mes.includes(days[i].getMonth())) {

                        if(days[i].getMonth() != firstMounth){
                            total += visitas[i];
                            totalVisits[days[i].getMonth()] = total;
                        }else{
                            total += visitas[i];
                            totalVisits[days[i].getMonth()] = total;
                        }

                    }
                } catch (error) {
                    console.log(error);
                }


            }

            const ctx = document.getElementById('myChart2').getContext('2d');
            const myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['janeiro','fevereiro','março','abril','maio','junho','julho','agosto','setembro','outubro','novembro','dezembro'],
                    datasets: [{
                        label: '',
                        data: totalVisits,
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // hide loading
            $("#loaderDiv").hide();
        },
        error: function (error) {
            console.log(error);
        }
    });

    function sumAllVisits(visits){
        for (let index = 0; index < visits.length; index++) {
            const element = array[index];

        }
    }
});
