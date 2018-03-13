function routeToProject(id) {
	var url = window.location.href += 'project-summary/' + id;
	url = url.replace('//', '/');

	window.location.href.replace(url);
}

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
