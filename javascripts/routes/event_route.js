App.EventRoute = Ember.Route.extend({
    model: function(params) {
        var uid = params.uid,
            searchParams = {
                query: 'detailed',
                match: 'all',
                uid: uid
            };

        return Ember.$.getJSON('api/search.php?' + $.param(searchParams)).then(function(results) {
            if (results.data.length) {
                return results.data[0];
            } else {
                return [];
            }
        });
    }
});