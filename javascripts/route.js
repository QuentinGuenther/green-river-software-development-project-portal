function routeTo(id) {
	var url = window.location.href += 'project-summary/' + id;
	url = url.replace('//', '/');

	window.location.href.replace(url);
}
