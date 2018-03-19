1. All database/business logic is located in the models directory, while 
	all the html pages are in the views folder. Summary pages are manipulated 
	using the fat-free templating engine.
2. Index folder has all the routes to each page. Uses the fat-free templating
	engine to manipulate the html pages.
3. Database is defined with classes for each object. Uses PDO and prepared statements.
	Located in the models directory.
4. We have summary pages that pull data from the database. Each summary page has an
	option to update the information, or delete them from the database.
5. Over 100 commits on github
6. Each form creates a new object. Some of the classes inherit from another class.
	Eg., Client class extends from the ClientCompany class.
7. All php files have docblocks
8. Client side validation using html and server side validation using php
9. jquery used for routing when a user clicks a table row. Also used for initalizeing
	a datatable. Ajax used for the Github api to check if repo could be found, or if
	there were too many requests.
10. Utilizes the Github API