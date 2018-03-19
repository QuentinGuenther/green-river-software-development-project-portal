// routes to a project summary page with an id
$('.clickableProject').click(function(){

	var row = $(this).closest('tr');
	var id = row.find('td:eq(0)').text(); // get the first td value, which should be the project id

	var url = window.location.href += 'project-summary/' + id; // add project-summary and the id to the url
	url = url.replace('//', '/'); // remove extraneous slashes

	window.location.href.replace(url); // go to the url
});

// routes to a course summary page with an id
$('.clickableCourse').click(function(){
	var url = window.location.href;

	var row = $(this).closest('tr');
	var id = row.find('td:eq(0)').text(); // get the first td value, which should be the project id

	url = url.replace("/project-summary/", "");
	url = url.replace(/[_0-9]+$/, ''); // https://stackoverflow.com/questions/13563895/removing-the-last-digits-in-string
	url = url + '/course-summary/' + id;

	location.replace(url); // go to the url
});

// routes to a course summary page given an id
function routeToCourse(id) {
	var url = window.location.href;

	url = url.replace("/project-summary/", "");
	url = url.replace(/[_0-9]+$/, ''); // https://stackoverflow.com/questions/13563895/removing-the-last-digits-in-string
	url = url + '/course-summary/' + id;

	location.replace(url);
}

// routes to a project deletion page
// asks if you are sure you want to delete
function confirmDeleteProject(id, title) {

	var result = confirm('Are you sure you want to delete the project \"' + title + '\"');

	if(result == true) {

		var url = window.location.href += 'delete-project/' + id; // add project-summary and the id to the url
		url = url.replace('//', '/'); // remove extraneous slashes

		window.location.href.replace(url);
	}
}

// routes to course deletion page
// asks if you are sure you want to delete
function confirmDeleteCourse(id, number) {

	var result = confirm("Are you sure you wish to delete this course: " + number);

	if(result == true) {
		var url = window.location.href;

		url = url.replace("/project-summary/", ""); 
		url = url.replace(/[_0-9]+$/, ''); // https://stackoverflow.com/questions/13563895/removing-the-last-digits-in-string
		url = url += '/delete-course/' + id;

		window.location = url;
	}
}