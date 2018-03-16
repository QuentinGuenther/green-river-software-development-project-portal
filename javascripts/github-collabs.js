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

    $.ajax({
        type: "GET",
        url: url,
        success: function(result) {
            $.each(result, function(index, author) {
                getUserData(author.author.url);
            });
        },
        statusCode: {
            403: function() {
                $("#collabs").append("<tr><td colspan='3'><strong class='text-center'>Too many requests to GitHub. Will be back later!</strong></td></tr>");
            },
            404: function() {
                $("#collabs").append("<tr><td colspan='3'><strong class='text-center'>Could not find GitHub repository.</strong></td></tr>");
            }
        }
    });
}

function getUserData(url) {
    $.ajax({
        type: "GET",
        url: url,
        success: function(result) {
            let userData = {
                html_url: result.html_url,
                login: result.login,
                email: result.email
            };
            userData.email = (userData.email == null) ? "" : userData.email;
            $("#collabs").append("<tr><td>" + userData.login + "</td><td><a href='mailto:" + userData.email + "'>" +  userData.email + "</a></td><td><a href='" + userData.html_url + "'>" + userData.html_url + "</a></td></tr>");
        },
        statusCode: {
            403: function() {
                $("#collabs").append("<tr><td colspan='3'><strong class='text-center'>Too many requests to GitHub. Will be back later!</strong></td></tr>");
            },
            404: function() {
                $("#collabs").append("<tr><td colspan='3'><strong class='text-center'>Could not find GitHub repository.</strong></td></tr>");
            }
        }
    });
}

$(function() {
    let repo = getRepoUrl();

    getContributors(repo.owner, repo.repo_name);
});