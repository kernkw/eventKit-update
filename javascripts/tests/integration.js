/* ==========================================================================
 * INTEGRATION.JS
 * ==========================================================================
 * Integration tests.
 */


module("Integration Test");

test("Base elements present", function() {
    visit("/");
    var elementIDs = ["navbar", "sidebar", "main"];
    elementIDs.forEach(function(id, i) {
        ok(find("#" + id).length, id + " element present");
    });
});