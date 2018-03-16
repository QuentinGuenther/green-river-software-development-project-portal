function getRepoUrl() {
    // https://www.github.com/:owner/:repo
    // return {owner, repo}

    let repo = $("#GitHubUrl").attr("href");

    repo = repo.split("/");

    let result = {
        "owner": repo[3],
        "repo_name": repo[4]
    };

    return result;
}

function getContributors(owner, repo) {
    let url = "https://api.github.com/repos/" +
    owner + "/" + repo + "/stats/contributors";

    $.getJSON(url, function(result) {
        let users = [];
        $.each(result, function(index, author) {
            getUserData(author.author.url);
        });
    });
}

function getUserData(url) {
    $.getJSON(url, function(result) {
        let userData = {
            html_url: result.html_url,
            login: result.login,
            email: result.email
        };
        userData.email = (userData.email == null) ? "" : userData.email;
        $("#collabs").append("<tr><td>" + userData.login + "</td><td><a href='mailto:" + userData.email + "'>" +  userData.email + "</a></td><td><a href='" + userData.html_url + "'>" + userData.html_url + "</a></td></tr>");
    });
}

$(function() {
    let repo = getRepoUrl();

    getContributors(repo.owner, repo.repo_name);
});