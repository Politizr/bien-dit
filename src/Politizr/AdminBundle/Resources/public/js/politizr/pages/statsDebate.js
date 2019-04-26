var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: '# Sujets',
            data: keys,
            backgroundColor: [
                '#efa1ac',
                '#eaaace',
                '#d698e5',
                '#b69fe1',
                '#b0c2e1',
                '#9bd9db',
                '#9ed9b6',
                '#afd7a1',
                '#cad4a5',
                '#d0bfa2'
            ],
            borderColor: [
                '#ec0626',
                '#ed1991',
                '#bf1fe7',
                '#631fe1',
                '#266ce1',
                '#1bd4db',
                '#1ad966',
                '#4dd71d',
                '#acd520',
                '#d2901e'
            ],
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