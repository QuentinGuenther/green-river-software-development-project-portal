$(document).ready( function () {
    $('#project-table').DataTable( {
        paging: false,
        scrollY: 400,
        "language": {
            "emptyTable": "No Projects Currently Recorded"
        }
    });
} );