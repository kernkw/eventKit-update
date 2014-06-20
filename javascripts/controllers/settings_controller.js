/* ==========================================================================
 * SETTINGS_CONTROLLER.JS
 * ==========================================================================
 * Provides the actions for the settings page.
 */

App.SettingsController = Ember.Controller.extend({
    actions: {
        save: function() {
            var autodelete = this.get("model.selectedAutoDeleteValue") * 1,
                params = {
                    query: 'save_settings',
                    autodelete: autodelete
                };
            var resultsdisplayed = this.get("model.selectedEventsPerPage") * 1,
                params = {
                    query: 'save_settings',
                    eventsPerPage: eventsPerPage
                };
            $.get('api/search.php?' + $.param(params), function(data) {
                if (data) {
                    alert("Your changes have been saved!");
                    window.location.reload();
                } else {
                    alert("Uh oh! Something went wrong.");
                }
            });

        }
    }
});