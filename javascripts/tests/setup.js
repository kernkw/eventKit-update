/* ==========================================================================
 * SETUP.JS
 * ==========================================================================
 * This file sets up QUnit for use with the Ember application.
 */

CW.rootElement = '#ember-application';
CW.setupForTesting();
CW.injectTestHelpers();

// Run before each test case.
QUnit.testStart(function() {
    Ember.run(function() {
        CW.reset();
    });
    Ember.testing = true;
});

// Run after each test case.
QUnit.testDone(function() {
    Ember.testing = false;
});

// Clean Up
QUnit.done(function() {
    Ember.run(function() {
        CW.reset();
    });
});