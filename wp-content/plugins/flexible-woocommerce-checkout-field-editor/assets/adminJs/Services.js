"use strict";
var app = angular.module("checkoutFieldsEditor");

app.service("$settingsStorageService", ["$http", function ($http) {
        var service = {};

        service.getGeneratedFields = function (prefix) {
            if (prefix == "billing")
                return WooFieldsEditor.Storage.generateBillingFields();
            else if (prefix == "shipping")
                return WooFieldsEditor.Storage.generateShippingFields();
            else if (prefix == "account")
                return WooFieldsEditor.Storage.generateAccountFields();
            else if (prefix == "order")
                return WooFieldsEditor.Storage.generateOrderFields();
        };

        service.getFieldsSettings = function (prefix) {
            var strJson;

            if (prefix == "billing")
                strJson = window.checkoutFieldsEditor.billingFieldsOptions;
            else if (prefix == "shipping")
                strJson = window.checkoutFieldsEditor.shippingFieldsOptions;
            else if (prefix == "account")
                strJson = window.checkoutFieldsEditor.accountFieldsOptions;
            else if (prefix == "order")
                strJson = window.checkoutFieldsEditor.orderFieldsOptions;

            if (strJson)
                return JSON.parse(strJson);
            else
                return service.getGeneratedFields(prefix);
        };

        service.resetStorageWindow = function (prefix) {
            if (prefix == "billing")
                window.checkoutFieldsEditor.billingFieldsOptions = void 0;
            else if (prefix == "shipping")
                window.checkoutFieldsEditor.shippingFieldsOptions = void 0;
            else if (prefix == "account")
                window.checkoutFieldsEditor.accountFieldsOptions = void 0;
            else if (prefix == "order")
                window.checkoutFieldsEditor.orderFieldsOptions = void 0;
        };

        service.setStorageWindow = function (prefix, json) {
            if (prefix == "billing")
                window.checkoutFieldsEditor.billingFieldsOptions = json;
            else if (prefix == "shipping")
                window.checkoutFieldsEditor.shippingFieldsOptions = json;
            else if (prefix == "account")
                window.checkoutFieldsEditor.accountFieldsOptions = json;
            else if (prefix == "order")
                window.checkoutFieldsEditor.orderFieldsOptions = json;
        }

        service.getAllProducts = function () {
            var strProducts = window.checkoutFieldsEditor.allProducts;
            if (strProducts)
                return JSON.parse(strProducts);
            else
                return null;
        };

        service.getAllCategories = function () {
            var strCategories = window.checkoutFieldsEditor.allCategories;
            if (strCategories)
                return JSON.parse(strCategories);
            else
                return null;
        };

        service.saveFieldsSettings = function (prefix, data) {
            return $http({
                url: window.checkoutFieldsEditor.ajaxurl,
                data: $.param({
                    action: "save" + prefix + "fields",
                    fieldsOptions: JSON.stringify(data)
                }),
                method: 'POST',
                responseType: 'arraybuffer',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
                    .then(function () {
                        service.setStorageWindow(prefix, JSON.stringify(data));
                    });
            ;
        };

        service.resetFieldsSettings = function (prefix) {
            return $http({
                url: window.checkoutFieldsEditor.ajaxurl,
                data: $.param({
                    action: "reset" + prefix + "fields"
                }),
                method: 'POST',
                responseType: 'arraybuffer',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function () {
                service.resetStorageWindow(prefix);
            });
            ;
        };

        return service;

    }]);



