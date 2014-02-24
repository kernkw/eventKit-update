/* ==========================================================================
 * EMAIL.JS
 * ==========================================================================
 *
 * SUMMARY
 * This file contains the main Ember JS data for viewing info on a specific
 * email address.
 *
 */

App.EmailRoute = Ember.Route.extend({
    model: function(params) {
        var searchParams = {
            query: "email_stats",
            email: params['email_id']
        };
        return Ember.$.getJSON('api/search.php?' + $.param(searchParams)).then(function(results) {
            results['email'] = params['email_id'];
            return results;
        });
    }
});