$('.clickable').click(function(){

	var row = $(this).closest('tr');
	var id = row.find('td:eq(0)').text(); // get the first td value, which should be the project id

	var url = window.location.href += 'project-summary/' + id; // add project-summary and the id to the url
	url = url.replace('//', '/'); // remove extraneous slashes

	window.location.href.replace(url); // go to the url
});

function routeToCourse(id) {
	var url = window.location.href;

	url = url.replace("/project-summary/", "");
	url = url.replace(/[_0-9]+$/, ''); // https://stackoverflow.com/questions/13563895/removing-the-last-digits-in-string
	url = url + '/course-summary/' + id;

	// window.location.href doesn't work here
	// because webdev is 99.999999% random and full of hacks
	// that don't make any sense
	location.replace(url);
}

function confirmDelete(id, title) {

	var result = confirm('Are you sure you want to delete the project \"' + title + '\"');

	if(result == true) {

		var url = window.location.href += 'delete-project/' + id; // add project-summary and the id to the url
		url = url.replace('//', '/'); // remove extraneous slashes

		window.location.href.replace(url); // go to the url
	}
}