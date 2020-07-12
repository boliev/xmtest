$(function() {
    $("#main-form-submit").click(function () {
        let progressBar = $("#progress");
        progressBar.show();
        clearServerErrors();
        if(!validateForm()) {
            progressBar.hide();
            return false;
        }

        jQuery.get(
            backendEndpoint+'quotations',
            {
                'company': getParamValue('company'),
                'email': getParamValue('email'),
                'startDate': getParamValue('start_date'),
                'endDate': getParamValue('end_date')
            }
        ).done(function(data){
            if(data.quotations && data.quotations.length > 0) {
                createAndFillResults(data.quotations);
            }
            progressBar.hide();
        }).fail(function (handler) {
            showServerErrors(handler.responseJSON.errors)
            progressBar.hide();
        })
        return true;
    })
})

function createAndFillResults(quotations) {
    $("#result-table").remove();
    $("#result-chart").remove();
    createResultTable();
    quotations.forEach(function (quotation) {
        date = new Date(quotation.date);
        $("#result-table").find('tbody').append(
            '<tr>' +
            '<td>'+moment(quotation.date).format('Y-MM-DD')+'</td>\n' +
            '<td>'+quotation.open.toFixed(3)+'</td>\n' +
            '<td>'+quotation.high.toFixed(3)+'</td>\n' +
            '<td>'+quotation.low.toFixed(3)+'</td>\n' +
            '<td>'+quotation.close.toFixed(3)+'</td>\n' +
            '<td>'+quotation.volume+'</td>\n' +
            '</tr>');
    })

    createChart(quotations);
}

function createResultTable() {
    $("#results").append('<table class="striped" id="result-table"></table>');
    $("#result-table").append("<thead><tr>" +
        "<th>Date</th>" +
        "<th>Open</th>" +
        "<th>High</th>" +
        "<th>Low</th>" +
        "<th>Close</th>" +
        "<th>Volume</th>" +
     "</tr></thead><tbody></tbody>");

}

function getParamValue(id) {
    return $('#'+id).val().trim();
}

function createChart(quotation)
{
    $("#results").append('<canvas id="result-chart" width="400" height="400"></canvas>');
    var ctx = $('#result-chart');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: quotation.map((quotation) => moment(quotation.date).format('Y-MM-DD')).reverse(),
            datasets: [{
                type: 'line',
                label: 'Open',
                data: quotation.map((quotation) => quotation.open).reverse(),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                ],
                borderWidth: 1
            },
            {
                type: 'line',
                label: 'CLose',
                data: quotation.map((quotation) => quotation.close).reverse(),
                backgroundColor: [
                    'rgba(132, 99, 255, 0.2)',
                    'rgba(235, 162, 54, 0.2)',
                ],
                borderColor: [
                    'rgba(132, 99, 255, 1)',
                    'rgba(235, 162, 54, 1)',
                ],
                borderWidth: 1
            }
            ]
        },
        options: {}
    });
}

function clearServerErrors()
{
    $("#errors").hide();
    $("#errors").html('');
}

function showServerErrors(errors)
{
    errors.map(function(error) {
        $("#errors").append('<p class="m1 center" style="margin: 0px; color: #fff">'+error+'</p>');
    })
    $("#errors").show();
}