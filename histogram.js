var xhr = new XMLHttpRequest();
xhr.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        var responseData = JSON.parse(this.responseText);

        var playgroundsContainer = document.getElementById('playgroundsContainer');
        for (var i = 0; i < responseData.playgrounds.length; i++) {
            var playgroundInfo = responseData.playgroundInfo[i];
            var playgroundParagraph = document.createElement('p');
            playgroundParagraph.innerHTML = playgroundInfo;
            playgroundsContainer.appendChild(playgroundParagraph);
        }

        var id = responseData.id;
        var playgrounds = responseData.playgrounds;
        var favoritesCount = responseData.favoritesCount;

        var colors = ['rgba(220, 50, 50, 0.4)', 'rgba(172, 61, 200, 0.4)', 'rgba(54, 162, 235, 0.4)', 'rgba(255, 206, 86, 0.4)', 'rgba(176, 229, 158, 0.4)'];
        var borderСolors = ['rgba(220, 50, 50, 1)', 'rgba(172, 61, 200, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(176, 229, 158, 1)'];

        var ctx = document.getElementById('playgroundsChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: playgrounds,
                datasets: [{
                    label: 'Количество пользователей, которые были заинтересованы данной площадкой',
                    data: favoritesCount,
                    backgroundColor: colors,
                    borderColor: borderСolors,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        beginAtZero: true,
                    },
                    y: {
                        beginAtZero: true,
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }

        });
    }
};


xhr.open("GET", "histogram.php", true);
xhr.send();
