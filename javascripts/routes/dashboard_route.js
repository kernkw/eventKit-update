/* ==========================================================================
 * DASHBOARD_ROUTE.JS
 * ==========================================================================
 * Provides the model for the main dashboard page.
 */

App.DashboardRoute = Ember.Route.extend({
    model: function() {
        return Ember.$.getJSON('api/search.php?query=dashboard');//recent&limit=10');
    }
});