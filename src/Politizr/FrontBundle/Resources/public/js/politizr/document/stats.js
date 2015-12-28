// cf http://www.chartjs.org/docs/#line-chart

// Useful to destroy previous one before creating new chart
var notesChartInstance;

// Pagination
$("body").on("click", "[action='notesPaginatePrevChart']", function(e) {
    documentNotesCharts($('#modalMyPostsCharts').attr('uuid'), $('#modalMyPostsCharts').attr('type'), $(this).attr('startAt'));
});

$("body").on("click", "[action='notesPaginateNextChart']", function(e) {
    documentNotesCharts($('#modalMyPostsCharts').attr('uuid'), $('#modalMyPostsCharts').attr('type'), $(this).attr('startAt'));
});

/**
 * Document charts: load all widgets
 * @param string uuid
 * @param string type
 * @param Date startAt
 */
function documentCharts(uuid, type, startAt) { 
    // console.log('*** documentCharts');
    // console.log(uuid);
    // console.log(type);
    // console.log(startAt);

    documentNotesCharts(uuid, type, startAt);
}

/**
 * Notes charts
 * @param string uuid
 * @param string type
 * @param Date startAt
 */
function documentNotesCharts(uuid, type, startAt) { 
    // console.log('*** documentNotesCharts');
    // console.log(uuid);
    // console.log(type);
    // console.log(startAt);

    // get first day of current month
    if (typeof startAt === "undefined") {
        var startAt = new Date();
        var startAt = new Date(startAt.getFullYear(), startAt.getMonth(), 1);
        var startAt  = moment(startAt).format('YYYY-MM-DD');
        // // console.log('startAt = '+startAt);
    }

    var xhrPath = getXhrPath(
        ROUTE_MODAL_DOCUMENT_STATS_NOTES_EVOLUTION,
        'document',
        'statsNotesEvolution',
        RETURN_HTML
        );

    var localLoader = $('#myPostsNoteStats').find('.ajaxLoader').first();

    $.ajax({
        type: 'POST',
        url: xhrPath,
        data: { 'uuid': uuid, 'type': type, 'startAt': startAt },
        dataType: 'json',
        beforeSend: function ( xhr ) { xhrBeforeSend( xhr, localLoader ); },
        statusCode: { 404: function () { xhr404(localLoader); }, 500: function() { xhr500(localLoader); } },
        error: function ( jqXHR, textStatus, errorThrown ) { xhrError(jqXHR, textStatus, errorThrown, localLoader); },
        success: function(data) {
            localLoader.hide();
            if (data['error']) {
                $('#infoBoxHolder .boxError .notifBoxText').html(data['error']);
                $('#infoBoxHolder .boxError').show();
            } else {
                // purge previous charts
                if(notesChartInstance) {
                    notesChartInstance.destroy();
                }

                // load charts
                var lineChartData = {
                    labels : data['labels'],
                    datasets : [
                        {
                            data : data['data'],
                            // @todo fill theses values not here:
                            label: "Notes points",
                            fillColor : "rgba(229, 108, 64, 0.3)",
                            strokeColor : "#e56c40",
                            pointColor : "#e56c40",
                            pointStrokeColor : "#fff",
                            pointHighlightFill : "#fff",
                            pointHighlightStroke : "#e56c40"
                        }
                    ]               
                }
                notesChartInstance = loadDataCharts("myPostsNote", lineChartData);

                // update pagination offset
                // console.log(data['datePrev']);
                // console.log(data['dateNext']);

                $('#notesChartNavPrev').attr('startAt', data['datePrev']);
                $('#notesChartNavNext').attr('startAt', data['dateNext']);
                $('#notesChartNavPrev').show();
                $('#notesChartNavNext').show();
            }
        }
    });
}
