App.Router.map(function() {
    // DASHBOARD
    this.resource('dashboard', {
        path: "/"
    });

    // SEARCH
    this.resource('search', {
        path: "/search/:query/page/:page"
    });

    // DETAILED SEARCH ENTRY
    this.resource('detailedSearch', {
        path: "/detailed_search"
    });

    this.resource('detailedSearchResults', {
        path: "/detailed_search/:query/results/:page"
    });

    // EVENT INSPECTOR
    this.resource('event', {
        path: "/event/:uid"
    });

    //EMAIL INSPECTOR
    this.resource('email', {
        path: "/email/:email_id"
    });
});