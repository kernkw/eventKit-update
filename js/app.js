App = Ember.Application.create();

App.Router.map(function() {
  	this.resource('dashboard', { path: "/" }, function() {
  		this.resource('recent', { path: "/" }, function() {
  			this.resource('totals', { path: "/" });
  		});
  	});
});

App.IndexRoute = Ember.Route.extend({
  	setupController: function(controller) {
  		//this.transitionTo('dashboard');
  	}
});

App.RecentRoute = Ember.Route.extend({
	model: function() {
		var call = Ember.$.getJSON('api/search.php?query=recent&limit=10');
		return call;
	}
});

App.TotalsRoute = Ember.Route.extend({
	model: function() {
		var call = Ember.$.getJSON('api/search.php?query=total&hours=24');
		return call;
	}
})

