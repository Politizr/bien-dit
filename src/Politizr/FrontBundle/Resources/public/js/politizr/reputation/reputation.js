// cf http://www.chartjs.org/docs/#line-chart

// Useful to destroy previous one before creating new chart
var reputationChartInstance;

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
        var startAt  = moment(startAt).format('YYYY-MM-DD');
        // console.log('startAt = '+startAt);
    }

    var xhrPath = getXhrPath(
        ROUTE_MODAL_REPUTATION_EVOLUTION,
        'user',
        'reputationEvolution',
        RETURN_HTML
        );

    $.ajax({
        type: 'POST',
        url: xhrPath,
        data: { 'startAt': startAt },
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, 1 ); },
        statusCode: { 404: function () { xhr404(); }, 500: function() { xhr500(); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown); },
        success: function(data) {
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                // purge charts
                if(reputationChartInstance) {
                    reputationChartInstance.destroy();
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
                reputationChartInstance = loadDataCharts("reputationLine", lineChartData);

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
