// Useful to destroy previous one before creating new chart
var globalChartInstance;

// Pagination
$("body").on("click", "[action='paginatePrevChart']", function(e) {
    reputationCharts($(this).attr('startAt'));
});

$("body").on("click", "[action='paginateNextChart']", function(e) {
    reputationCharts($(this).attr('startAt'));
});

/**
 * Reputation charts
 * @param Date startAt
 */
function reputationCharts(startAt) { 
    // console.log('*** reputationCharts');
    // console.log(startAt);

    // get first day of current month
    if (typeof startAt === "undefined") {
        var startAt = new Date();
        var startAt = new Date(startAt.getFullYear(), startAt.getMonth(), 1);
    }

    var xhrPath = getXhrPath(
        ROUTE_MODAL_REPUTATION,
        'user',
        'reputationEvolution',
        RETURN_HTML
        );

    $.ajax({
        type: 'POST',
        url: xhrPath,
        data: { 'startAt': startAt },
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                // purge charts
                if(globalChartInstance) {
                    globalChartInstance.destroy();
                }

                // load charts
                var lineChartData = {
                    labels : data['labels'],
                    datasets : [
                        {
                            data : data['data'],
                            // @todo fill theses values not here:
                            label: "Reputation points",
                            fillColor : "rgba(229, 108, 64, 0.3)",
                            strokeColor : "#e56c40",
                            pointColor : "#e56c40",
                            pointStrokeColor : "#fff",
                            pointHighlightFill : "#fff",
                            pointHighlightStroke : "#e56c40"
                        }
                    ]               
                }
                globalChartInstance = loadReputationDataCharts(lineChartData);

                // update pagination offset
                // console.log(data['datePrev']);
                // console.log(data['dateNext']);

                $('.chartNavPrev').attr('startAt', data['datePrev']);
                $('.chartNavNext').attr('startAt', data['dateNext']);
                $('.chartNavPrev').show();
                $('.chartNavNext').show();
            }
            $('#ajaxGlobalLoader').hide();
        }
    });
}

/**
 * init charts with data
 * @param array
 * @return chart instance
 */
function loadReputationDataCharts(lineChartData) {
    // console.log('*** loadReputationDataCharts');
    // console.log(lineChartData);

    if (lineChartData === "undefined") {
        return false;
    }

    var ctx = document.getElementById("reputationLine").getContext("2d");
    var myChart = new Chart(ctx).Line(lineChartData, {
        responsive: true,
        animation: false,
        bezierCurve : false,
        scaleFontFamily: "'nerissemibold'",
        scaleFontSize: 8,
        scaleFontColor: "#a9a9a9",
        tooltipTemplate: "<%= value %> POINTS",
        tooltipFontFamily: "'nerisblack'",
        tooltipFontSize: 10,
        tooltipFillColor: "#2d2d2d",
        tooltipFontColor: "#fff",
        tooltipCornerRadius : 3,
        tooltipYPadding:10,
        tooltipXPadding:10,
        tooltipCaretSize:4,
        pointDotRadius : 4,
        pointDotStrokeWidth : 2,
        scaleShowGridLines : false,
        scaleGridLineColor : "#dbdcdd",
        scaleLineColor: "#dbdcdd",
        datasetStrokeWidth : 2,
        pointHitDetectionRadius : 5
    });

    return myChart;
}
