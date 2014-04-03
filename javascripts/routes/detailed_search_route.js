App.DetailedSearchRoute = Ember.Route.extend({
    model: function() {
        return searchParams;
    },

    renderTemplate: function() {
        this.render();
        setTimeout(function() {
            var d = $(".init-date-picker");
            d.each(function(index) {
                $(d[index]).datepicker();
            });
        }, 500);
    }
});

var searchParams = [];