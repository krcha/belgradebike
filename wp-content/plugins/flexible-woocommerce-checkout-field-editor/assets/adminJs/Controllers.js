"use strict";
var app = angular.module("checkoutFieldsEditor");
app.controller("menuCtrl",
        function ($scope, $location, $route) {
            $scope.$route = $route;
            $scope.$watch("$route.current.$$route.originalPath", function (originalPath) {
                if (!originalPath)
                    return;

                $scope.selectedBillingFields = false;
                $scope.selectedShippingFields = false;
                $scope.selectedAccountFields = false;
                $scope.selectedOrderFields = false;

                switch (originalPath) {
                    case '/billingFields':
                    {
                        $scope.selectedBillingFields = true;
                        break;
                    }
                    case '/shippingFields':
                    {
                        $scope.selectedShippingFields = true;
                        break;
                    }
                    case '/accountFields':
                    {
                        $scope.selectedAccountFields = true;
                        break;
                    }
                    case '/orderFields':
                    {
                        $scope.selectedOrderFields = true;
                        break;
                    }

                }
            });
            $scope.showBillingFields = function () {
                $location.path("billingFields");
            };
            $scope.showShippingFields = function () {
                $location.path("shippingFields");
            };
            $scope.showAccountFields = function () {
                $location.path("accountFields");
            };
            $scope.showOrderFields = function () {
                $location.path("orderFields");
            };
        });

app.controller("fieldsEditorCtrl", function ($scope, $settingsStorageService, $location) {
    var prefix = '';

    if ($location.$$path === '/billingFields') {
        prefix = 'billing';
    } else
    if ($location.$$path === '/shippingFields') {
        prefix = 'shipping';
    } else
    if ($location.$$path === '/accountFields') {
        prefix = 'account';
    } else
    if ($location.$$path === '/orderFields') {
        prefix = 'order';
    }

    var placementDataSource = [
        {type: 0, caption: "Full width"},
        {type: 1, caption: "First half"},
        {type: 2, caption: "Second half"}];

    var visibilityFieldConditions = [
        {type: 0, caption: "None"},
        {type: 1, caption: "Product filter"},
        {type: 2, caption: "Category filter"}
    ];

    var editorTypeDataSource = [
        {caption: "Text", value: 0},
        {caption: "TextArea", value: 1},
        {caption: "Password", value: 2},
        {caption: "Checkbox", value: 3},
        {caption: "Select", value: 4},
        {caption: "Multiselect", value: 5},
        {caption: "DatePicker", value: 6},
    ];

    var wooProducts = $settingsStorageService.getAllProducts();
    var wooCategories = $settingsStorageService.getAllCategories();

    function hideNotifications() {
        $scope.isVisibleMessageSavedBillingFields = false;
        $scope.isVisibleMessageResetBillingFields = false;
        $scope.isVisibleError = false;
    }


    function setStateButtons(disabled) {
        $scope.disabledSaveBillingFields = disabled;
        $scope.disabledResetBillingFields = disabled;
        $scope.disabledAddNewFields = disabled;
    }

    function loadDescriptionFields() {
        var fields = $settingsStorageService.getFieldsSettings(prefix);

        $scope.fieldsSource = fields;
    }

    function resetStateControls() {
        $scope.selectedPlacement = placementDataSource[0];
        $scope.selectedEditorType = editorTypeDataSource[0];

    
        $scope.currentServerFilter=null;
        $scope.selectedProducts = null;
        $scope.selectedCategories = null;

        $scope.selectedDependsField = null;
    }

    // использование Math.round() даст неравномерное распределение!
    function getRandomInt(min, max)
    {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }


    function getNameForNewCustomFiled() {
        var allNames = refFieldsListControl.dataSource.map(function (existItem) {
            return existItem.name;
        });

        var tempateName = "wc_" + prefix + "_field_";
        var index = getRandomInt(0, 10000);
        var maybeNewName;

        do {
            maybeNewName = tempateName + index;
            index = getRandomInt(0, 10000);
        } while (allNames.indexOf(maybeNewName) !== -1);

        return maybeNewName;
    }


    function initController() {
        loadDescriptionFields();
        setStateButtons(false);
        hideNotifications();
        $scope.isVisibleEditor = false;
        $scope.currentEdited = {};
        $scope.referenceFields = [];
        $scope.typeReferenceField = -1;
    }
    initController();

    var refFieldsListControl;


    function getDependencyFieldsList() {
        var result = [];

        for (var indexField = 0; indexField < refFieldsListControl.dataSource.length; indexField++) {
            var fieldDescription = refFieldsListControl.dataSource[indexField];
            if (fieldDescription.name !== $scope.currentEdited.name) {
                var captionField = fieldDescription.name;
                if (fieldDescription.label)
                    captionField += "::" + fieldDescription.label;
                result.push(
                        {
                            caption:captionField,
                            name: fieldDescription.name,
                            type: fieldDescription.typeField
                        });
            }
        }

        return result;
    }

    function setStateVisibilityRulesEditor() {

        $scope.currentServerFilter = visibilityFieldConditions.filter(function (item) {
            return $scope.currentEdited.serverFilterMode === item.type;
        });


        if ($scope.currentEdited.serverFilterMode === 1) {
            $scope.selectedProducts = wooProducts.filter(function (product) {
                return $scope.currentEdited.serverFilterDescription.products.indexOf(product.id) !== -1;
            });
        }

        if ($scope.currentEdited.serverFilterMode === 2) {
            $scope.selectedCategories = wooCategories.filter(function (category) {
                return $scope.currentEdited.serverFilterDescription.categories.indexOf(category.id) !== -1;
            });
        }

        // $scope.referenceFields = getDependencyFieldsList();



        $scope.dependentFieldsString = "";
        for (var indexField = 0; indexField < refFieldsListControl.dataSource.length; indexField++) {
            var fieldDescription = refFieldsListControl.dataSource[indexField];
            if (fieldDescription.useClientFilter) {
                for (var iDep = 0; iDep < fieldDescription.arrayClientConditions.length; iDep++) {
                    if (fieldDescription.arrayClientConditions[iDep].name === $scope.currentEdited.name) {
                        var captionField = fieldDescription.name;
                        if (fieldDescription.label)
                            captionField += "::" + fieldDescription.label;

                        $scope.dependentFieldsString += captionField + ";\n";
                    }
                }
            }
        }
    }

    function removeReference(nameOwner) {
        for (var indexField = 0; indexField < refFieldsListControl.dataSource.length; indexField++) {
            var fieldDescription = refFieldsListControl.dataSource[indexField];
            if (fieldDescription.useClientFilter) {

                var items = fieldDescription.arrayClientConditions.filter(function (item) {
                    return item.name === nameOwner
                });

                for (var index = 0; index < items.length; index++)
                    fieldDescription.arrayClientConditions.splice(fieldDescription.arrayClientConditions.indexOf(items[index]), 1);


                if (fieldDescription.arrayClientConditions.length === 0)
                    fieldDescription.useClientFilter = false;
            }
        }
    }

    var oldEditorType = void 0;

    function setNewEditEntry(newEditEntry) {
        $scope.currentEdited = $.extend({}, {}, newEditEntry);

        $scope.selectedPlacement = placementDataSource.filter(function (item) {
            return $scope.currentEdited.placement === item.type;
        });

        $scope.selectedEditorType = editorTypeDataSource.filter(function (item) {
            return $scope.currentEdited.typeField === item.value;
        });

        oldEditorType = $scope.currentEdited.typeField;

        setStateVisibilityRulesEditor();
    }


    $scope.listFieldsOptions = {
        onBasePropsEdited: function (data) {

            $scope.$apply(function () {
                setNewEditEntry(data);
                $scope.isVisibleBasePropsEditor = true;
                setStateButtons(true);
                hideNotifications();
            });
        },
        onVisibleRulesEdited: function (data) {
            $scope.$apply(function () {
                setNewEditEntry(data);
                $scope.isVisibleVisibilityRulesEditor = true;
                setStateButtons(true);
                hideNotifications();
            });
        },
        onItemDeleted: function (data) {

            removeReference(data.name);
            hideNotifications();
        }
    };

    $scope.onFieldsListInitialized = function (fieldsControl) {
        refFieldsListControl = fieldsControl;
    };

    $scope.saveCurrentItem = function ($event) {
        $event.preventDefault();
        resetStateControls();
        setStateButtons(false);

        if ($scope.currentEdited.useClientFilter === false)
            $scope.currentEdited.arrayClientConditions.length = 0;


        if (oldEditorType !== $scope.currentEdited.typeField) {
            removeReference($scope.currentEdited.name);
        }

        if ($scope.currentEdited.arrayClientConditions.length === 0)
            $scope.currentEdited.useClientFilter = false;

        $scope.isVisibleVisibilityRulesEditor = false;
        $scope.isVisibleBasePropsEditor = false;
        var editedItem = $scope.currentEdited;
        var listItem = $scope.fieldsSource.filter(function (item) {
            return item.name === editedItem.name;
        })[0];
        $scope.fieldsSource[$scope.fieldsSource.indexOf(listItem)] = editedItem;
        refFieldsListControl.update();
    };

    $scope.cancelChanges = function ($event) {
        $event.preventDefault();
        setStateButtons(false);
        resetStateControls();
        $scope.isVisibleVisibilityRulesEditor = false;
        $scope.isVisibleBasePropsEditor = false;
        refFieldsListControl.update();
    };

    $scope.addNewCustomFields = function ($event) {
        $event.preventDefault();
        hideNotifications();

        var newCustomField = WooFieldsEditor.Storage.getNewCustomField(getNameForNewCustomFiled());
        refFieldsListControl.dataSource.push(newCustomField);
    };

    $scope.saveFieldsSettings = function ($event) {
        $event.preventDefault();
        hideNotifications();

        $settingsStorageService.saveFieldsSettings(prefix, refFieldsListControl.dataSource).then(function () {
            $scope.isVisibleMessageSavedBillingFields = true;
        }, function () {
            $scope.isVisibleError = true;
        });
    };

    $scope.resetFieldsSettings = function ($event) {
        $event.preventDefault();
        hideNotifications();

        $settingsStorageService.resetFieldsSettings(prefix).then(function () {

            $scope.isVisibleMessageResetBillingFields = true;


            $scope.fieldsSource = refFieldsListControl.dataSource = $settingsStorageService.getFieldsSettings(prefix);

        }, function () {
            $scope.isVisibleError = true;
        });
    };


    $scope.selectClientRule = function (item) {
        $scope.currentRuleEdited = item;
    };

    $scope.deleteCurrentRule = function () {
        $scope.currentEdited.arrayClientConditions.splice($scope.currentEdited.arrayClientConditions.indexOf($scope.currentRuleEdited), 1);
        $scope.currentRuleEdited = void 0;
    }

    $scope.addNewClientRule = function () {
        $scope.isVisibleVisibilityRulesEditor = false;
        $scope.showClientRulesEditor = true;


        $scope.referenceFields = getDependencyFieldsList();
        $scope.selectedDependsField = [];

        $scope.currentRuleEdited = {};
    };

    $scope.editClientRule = function () {
        $scope.isVisibleVisibilityRulesEditor = false;
        $scope.showClientRulesEditor = true;

        $scope.referenceFields = getDependencyFieldsList();
        $scope.selectedDependsField = $scope.referenceFields.filter(function (field) {
            return $scope.currentRuleEdited.name === field.name;
        });


    };

    $scope.saveClientRule = function ($event) {
        $event.preventDefault();

        $scope.selectedDependsField = null;

        var existItems = $scope.currentEdited.arrayClientConditions.filter(function (item) {
            return item.name == $scope.currentRuleEdited.name;
        });

        if (existItems.length === 0)
            $scope.currentEdited.arrayClientConditions.push($scope.currentRuleEdited);
        else
        {
            existItems[0].typeField = $scope.currentRuleEdited.typeField;
            existItems[0].value = $scope.currentRuleEdited.value;
        }


        $scope.currentRuleEdited = void 0;
        $scope.showClientRulesEditor = false;
        $scope.isVisibleVisibilityRulesEditor = true;
    };

    $scope.cancelClientRule = function ($event) {
        $event.preventDefault();

        $scope.selectedDependsField = null;

        $scope.showClientRulesEditor = false;
        $scope.isVisibleVisibilityRulesEditor = true;

        $scope.currentRuleEdited = void 0;


    };

    $scope.getTextForRuleDescription = function (item) {
        return "Fields visibility depend on " + item.name;
    }

    $scope.placementOptions = {
        dataSource: placementDataSource,
        selectedItems: [],
        displayExpr: "caption",
        multiSelection: false,
        isVisibleSearchPanel: false,
        heightPopup: "96px",
        onSelectionChanged: function (data) {
            if (data.selectedItems.length > 0)
                $scope.currentEdited.placement = data.selectedItems[0].type;
        }
    };

    $scope.serverFilterOptions = {
        dataSource: visibilityFieldConditions,
        selectedItems: [],
        displayExpr: "caption",
        multiSelection: false,
        isVisibleSearchPanel: false,
        heightPopup: "125px",
        onSelectionChanged: function (data) {
            if (!$scope.$$phase)
                $scope.$apply(function () {
                    $scope.currentEdited.serverFilterDescription = new WooFieldsEditor.ServerFilterDescription();

                    if (data.selectedItems.length > 0)
                        $scope.currentEdited.serverFilterMode = data.selectedItems[0].type;
                });
        }
    };

    $scope.editorTypeOptions = {
        dataSource: editorTypeDataSource,
        selectedItems: [],
        displayExpr: "caption",
        multiSelection: false,
        isVisibleSearchPanel: false,
        heightPopup: "215px",
        onSelectionChanged: function (data) {
            if (!$scope.$$phase)
                $scope.$apply(function () {
                    if (data.selectedItems.length > 0)
                        $scope.currentEdited.typeField = data.selectedItems[0].value;

                    switch ($scope.currentEdited.typeField) {
                        case 0:
                        case 1:
                        case 2:
                        case 4:
                        case 5:
                        {
                            $scope.currentEdited.defaultValue = "";
                            break;
                        }
                        case 3:
                        {
                            $scope.currentEdited.defaultValue = false;
                            break;
                        }
                    }
                });
        }
    };

    $scope.productsSelectorOptions = {
        dataSource: wooProducts,
        selectedItems: [],
        displayExpr: "title",
        multiSelection: true,
        isVisibleSearchPanel: true,
        heightPopup: "215px",
        onSelectionChanged: function (data) {
            var selectedItems = data.selectedItems;

            if (!$scope.$$phase)
                $scope.$apply(function () {
                    if (selectedItems.length === 0) {
                        $scope.currentEdited.serverFilterDescription.products = [];
                        return;
                    }

                    var productIds = selectedItems.map(function (product) {
                        return product.id;
                    });
                    $scope.currentEdited.serverFilterDescription.products = productIds;
                });
        },
        messages: {
            noDataMessage: "No products",
            placeholderMessage: "Please select a product...",
            prefixMultiSelectionField: " products"
        }
    };

    $scope.categoriesSelectorOptions = {
        dataSource: wooCategories,
        selectedItems: [],
        displayExpr: "title",
        multiSelection: true,
        isVisibleSearchPanel: true,
        heightPopup: "215px",
        onSelectionChanged: function (data) {
            var selectedItems = data.selectedItems;

            if (!$scope.$$phase)
                $scope.$apply(function () {
                    if (selectedItems.length === 0)
                    {
                        $scope.currentEdited.serverFilterDescription.categories = [];
                        return;
                    }

                    var categoryIds = selectedItems.map(function (category) {
                        return category.id;
                    });
                    $scope.currentEdited.serverFilterDescription.categories = categoryIds;

                });

        },
        messages: {
            noDataMessage: "No categories",
            placeholderMessage: "Please select a category...",
            prefixMultiSelectionField: " categories"
        }
    };

    $scope.dependencyFieldsOptions = {
        dataSource: $scope.referenceFields,
        selectedItems: [],
        displayExpr: "caption",
        multiSelection: false,
        isVisibleSearchPanel: true,
        heightPopup: "215px",
        onSelectionChanged: function (data) {
            if (!$scope.$$phase)
                $scope.$apply(function () {
                    if (data.selectedItems.length > 0)
                    {


                        $scope.currentRuleEdited.typeField = data.selectedItems[0].type;
                        $scope.currentRuleEdited.name = data.selectedItems[0].name;

                        switch ($scope.currentRuleEdited.typeField) {
                            case 0:
                            case 1:
                            case 2:
                            case 4:
                            case 5:
                            {
                                $scope.currentRuleEdited.value = "";
                                break;
                            }
                            case 3:
                            {
                                $scope.currentRuleEdited.value = false;
                                break;
                            }
                        }
                    }
                });
        },
        messages: {
            noDataMessage: "No fields",
            placeholderMessage: "Please select field...",
            prefixMultiSelectionField: " field"
        }
    };
});

app.controller("shippingFieldsCtrl", function ($scope) {
    $scope.content = "shipping fields";
});

app.controller("additionalFieldsCtrl", function ($scope) {
    $scope.content = "additional fields";
});