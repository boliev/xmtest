const backendEndpoint = 'http://localhost:8882/v1/';

document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.datepicker');
    var instances = M.Datepicker.init(elems, {'format': 'yyyy-mm-dd'});
});

$(function() {
    $("#errors").hide();
    $("#progress").hide();
})