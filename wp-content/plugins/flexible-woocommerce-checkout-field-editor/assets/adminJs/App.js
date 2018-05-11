"use strict";
var app = angular.module("checkoutFieldsEditor", ["ngRoute", "woofieldseditor", "flexWhaleLookup"]);

app.config(function ($routeProvider, $windowProvider) {
    $ = jQuery.noConflict();

    var $window = $windowProvider.$get();
    $routeProvider.when('/billingFields',
            {
                templateUrl: $window.checkoutFieldsEditor.pluginUrl + 'templates/fieldsEditorTemplate.html',
                controller: 'fieldsEditorCtrl'
            });
    $routeProvider.when('/shippingFields',
            {
                templateUrl: $window.checkoutFieldsEditor.pluginUrl + 'templates/fieldsEditorTemplate.html',
                controller: 'fieldsEditorCtrl'
            });
    $routeProvider.when('/accountFields',
            {
                templateUrl: $window.checkoutFieldsEditor.pluginUrl + 'templates/fieldsEditorTemplate.html',
                controller: 'fieldsEditorCtrl'
            });
    $routeProvider.when('/orderFields',
            {
                templateUrl: $window.checkoutFieldsEditor.pluginUrl + 'templates/fieldsEditorTemplate.html',
                controller: 'fieldsEditorCtrl'
            });

    $routeProvider.otherwise({redirectTo: '/billingFields'});
});
