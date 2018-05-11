"use strict";
var WooFieldsEditor;
(function (WooFieldsEditor) {
    (function (PlacementField) {
        PlacementField[PlacementField["Stretch"] = 0] = "Stretch";
        PlacementField[PlacementField["Left"] = 1] = "Left";
        PlacementField[PlacementField["Right"] = 2] = "Right";
    })(WooFieldsEditor.PlacementField || (WooFieldsEditor.PlacementField = {}));
    var PlacementField = WooFieldsEditor.PlacementField;
    (function (TypeField) {
        TypeField[TypeField["Text"] = 0] = "Text";
        TypeField[TypeField["TextArea"] = 1] = "TextArea";
        TypeField[TypeField["Password"] = 2] = "Password";
        TypeField[TypeField["CheckBox"] = 3] = "CheckBox";
        TypeField[TypeField["Select"] = 4] = "Select";
        TypeField[TypeField["MultiSelect"] = 5] = "MultiSelect";
        TypeField[TypeField["DatePicker"] = 6] = "DatePicker";
    })(WooFieldsEditor.TypeField || (WooFieldsEditor.TypeField = {}));
    var TypeField = WooFieldsEditor.TypeField;
    (function (ServerFilterMode) {
        ServerFilterMode[ServerFilterMode["None"] = 0] = "None";
        ServerFilterMode[ServerFilterMode["ProductFilter"] = 1] = "ProductFilter";
        ServerFilterMode[ServerFilterMode["CategoryFilter"] = 2] = "CategoryFilter";
    })(WooFieldsEditor.ServerFilterMode || (WooFieldsEditor.ServerFilterMode = {}));
    var ServerFilterMode = WooFieldsEditor.ServerFilterMode;
    var DependenceFieldDescription = (function () {
        function DependenceFieldDescription() {
        }
        return DependenceFieldDescription;
    }());
    WooFieldsEditor.DependenceFieldDescription = DependenceFieldDescription;
    var ServerFilterDescription = (function () {
        function ServerFilterDescription() {
            this.products = new Array();
            this.categories = new Array();
        }
        return ServerFilterDescription;
    }());
    WooFieldsEditor.ServerFilterDescription = ServerFilterDescription;
    var FieldDescription = (function () {
        function FieldDescription() {
            this.serverFilterMode = ServerFilterMode.None;
            this.serverFilterDescription = new ServerFilterDescription();
            this.useClientFilter = false;
            this.arrayClientConditions = new Array();
            //
            this.showForAdmin = true;
            this.showForEmail = false;
            //</CHECKBOX>
            //<CALENDAR>
            this.showWeek = false;
            this.firstDay = 0;
            this.dateFormat = "mm/dd/yy";
            this.numberOfMonths = 1;
            this.changeYear = false;
            this.changeMonth = false;
        }
        return FieldDescription;
    }());
    WooFieldsEditor.FieldDescription = FieldDescription;
})(WooFieldsEditor || (WooFieldsEditor = {}));
var WooFieldsEditor;
(function (WooFieldsEditor) {
    var Storage = (function () {
        function Storage() {
        }
        Storage.generateBillingFields = function () {
            var result = new Array();
            var billing_first_name = new WooFieldsEditor.FieldDescription();
            billing_first_name.name = "billing_first_name";
            billing_first_name.isVisible = true;
            billing_first_name.placement = WooFieldsEditor.PlacementField.Left;
            billing_first_name.label = "";
            billing_first_name.typeField = WooFieldsEditor.TypeField.Text;
            billing_first_name.required = true;
            billing_first_name.placeholder = "";
            billing_first_name.standartField = true;
            billing_first_name.clear = false;
            var billing_last_name = new WooFieldsEditor.FieldDescription();
            billing_last_name.name = "billing_last_name";
            billing_last_name.isVisible = true;
            billing_last_name.placement = WooFieldsEditor.PlacementField.Right;
            billing_last_name.label = "";
            billing_last_name.typeField = WooFieldsEditor.TypeField.Text;
            billing_last_name.required = true;
            billing_last_name.placeholder = "";
            billing_last_name.standartField = true;
            billing_last_name.clear = true;
            var billing_company = new WooFieldsEditor.FieldDescription();
            billing_company.name = "billing_company";
            billing_company.isVisible = true;
            billing_company.placement = WooFieldsEditor.PlacementField.Stretch;
            billing_company.label = "";
            billing_company.typeField = WooFieldsEditor.TypeField.Text;
            billing_company.required = false;
            billing_company.placeholder = "";
            billing_company.standartField = true;
            billing_company.clear = false;
            var billing_email = new WooFieldsEditor.FieldDescription();
            billing_email.name = "billing_email";
            billing_email.isVisible = true;
            billing_email.placement = WooFieldsEditor.PlacementField.Left;
            billing_email.label = "";
            billing_email.typeField = WooFieldsEditor.TypeField.Text;
            billing_email.required = true;
            billing_email.placeholder = "";
            billing_email.standartField = true;
            billing_email.clear = false;
            var billing_phone = new WooFieldsEditor.FieldDescription();
            billing_phone.name = "billing_phone";
            billing_phone.isVisible = true;
            billing_phone.placement = WooFieldsEditor.PlacementField.Right;
            billing_phone.label = "";
            billing_phone.typeField = WooFieldsEditor.TypeField.Text;
            billing_phone.required = true;
            billing_phone.placeholder = "";
            billing_phone.standartField = true;
            billing_phone.clear = true;
            var billing_country = new WooFieldsEditor.FieldDescription();
            billing_country.name = "billing_country";
            billing_country.isVisible = true;
            billing_country.placement = WooFieldsEditor.PlacementField.Stretch;
            billing_country.label = "";
            billing_country.typeField = WooFieldsEditor.TypeField.Select;
            billing_country.required = true;
            billing_country.placeholder = "";
            billing_country.standartField = true;
            billing_country.clear = false;
            var billing_address_1 = new WooFieldsEditor.FieldDescription();
            billing_address_1.name = "billing_address_1";
            billing_address_1.isVisible = true;
            billing_address_1.placement = WooFieldsEditor.PlacementField.Stretch;
            billing_address_1.label = "";
            billing_address_1.typeField = WooFieldsEditor.TypeField.Text;
            billing_address_1.required = true;
            billing_address_1.placeholder = "";
            billing_address_1.standartField = true;
            billing_address_1.clear = false;
            var billing_address_2 = new WooFieldsEditor.FieldDescription();
            billing_address_2.name = "billing_address_2";
            billing_address_2.isVisible = true;
            billing_address_2.placement = WooFieldsEditor.PlacementField.Stretch;
            billing_address_2.label = "";
            billing_address_2.typeField = WooFieldsEditor.TypeField.Text;
            billing_address_2.required = false;
            billing_address_2.placeholder = "";
            billing_address_2.standartField = true;
            billing_address_2.clear = false;
            var billing_city = new WooFieldsEditor.FieldDescription();
            billing_city.name = "billing_city";
            billing_city.isVisible = true;
            billing_city.placement = WooFieldsEditor.PlacementField.Stretch;
            billing_city.label = "";
            billing_city.typeField = WooFieldsEditor.TypeField.Text;
            billing_city.required = true;
            billing_city.placeholder = "";
            billing_city.standartField = true;
            billing_city.clear = false;
            var billing_state = new WooFieldsEditor.FieldDescription();
            billing_state.name = "billing_state";
            billing_state.isVisible = true;
            billing_state.placement = WooFieldsEditor.PlacementField.Left;
            billing_state.label = "";
            billing_state.typeField = WooFieldsEditor.TypeField.Text;
            billing_state.required = true;
            billing_state.placeholder = "";
            billing_state.standartField = true;
            billing_state.clear = false;
            var billing_postcode = new WooFieldsEditor.FieldDescription();
            billing_postcode.name = "billing_postcode";
            billing_postcode.isVisible = true;
            billing_postcode.placement = WooFieldsEditor.PlacementField.Right;
            billing_postcode.label = "";
            billing_postcode.typeField = WooFieldsEditor.TypeField.Text;
            billing_postcode.required = true;
            billing_postcode.placeholder = "";
            billing_postcode.standartField = true;
            billing_postcode.clear = true;
            result.push(billing_first_name, billing_last_name, billing_company, billing_email, billing_phone, billing_country, billing_address_1, billing_address_2, billing_city, billing_state, billing_postcode);
            return result;
        };
        Storage.generateShippingFields = function () {
            var result = new Array();
            var shipping_first_name = new WooFieldsEditor.FieldDescription();
            shipping_first_name.name = "shipping_first_name";
            shipping_first_name.isVisible = true;
            shipping_first_name.placement = WooFieldsEditor.PlacementField.Left;
            shipping_first_name.label = "";
            shipping_first_name.typeField = WooFieldsEditor.TypeField.Text;
            shipping_first_name.required = true;
            shipping_first_name.placeholder = "";
            shipping_first_name.standartField = true;
            shipping_first_name.clear = false;
            var shipping_last_name = new WooFieldsEditor.FieldDescription();
            shipping_last_name.name = "shipping_last_name";
            shipping_last_name.isVisible = true;
            shipping_last_name.placement = WooFieldsEditor.PlacementField.Right;
            shipping_last_name.label = "";
            shipping_last_name.typeField = WooFieldsEditor.TypeField.Text;
            shipping_last_name.required = true;
            shipping_last_name.placeholder = "";
            shipping_last_name.standartField = true;
            shipping_last_name.clear = true;
            var shipping_company = new WooFieldsEditor.FieldDescription();
            shipping_company.name = "shipping_company";
            shipping_company.isVisible = true;
            shipping_company.placement = WooFieldsEditor.PlacementField.Stretch;
            shipping_company.label = "";
            shipping_company.typeField = WooFieldsEditor.TypeField.Text;
            shipping_company.required = false;
            shipping_company.placeholder = "";
            shipping_company.standartField = true;
            shipping_company.clear = false;
            var shipping_country = new WooFieldsEditor.FieldDescription();
            shipping_country.name = "shipping_country";
            shipping_country.isVisible = true;
            shipping_country.placement = WooFieldsEditor.PlacementField.Stretch;
            shipping_country.label = "";
            shipping_country.typeField = WooFieldsEditor.TypeField.Select;
            shipping_country.required = true;
            shipping_country.placeholder = "";
            shipping_country.standartField = true;
            shipping_country.clear = false;
            var shipping_address_1 = new WooFieldsEditor.FieldDescription();
            shipping_address_1.name = "shipping_address_1";
            shipping_address_1.isVisible = true;
            shipping_address_1.placement = WooFieldsEditor.PlacementField.Stretch;
            shipping_address_1.label = "";
            shipping_address_1.typeField = WooFieldsEditor.TypeField.Text;
            shipping_address_1.required = true;
            shipping_address_1.placeholder = "";
            shipping_address_1.standartField = true;
            shipping_address_1.clear = false;
            var shipping_address_2 = new WooFieldsEditor.FieldDescription();
            shipping_address_2.name = "shipping_address_2";
            shipping_address_2.isVisible = true;
            shipping_address_2.placement = WooFieldsEditor.PlacementField.Stretch;
            shipping_address_2.label = "";
            shipping_address_2.typeField = WooFieldsEditor.TypeField.Text;
            shipping_address_2.required = false;
            shipping_address_2.placeholder = "";
            shipping_address_2.standartField = true;
            shipping_address_2.clear = false;
            var shipping_city = new WooFieldsEditor.FieldDescription();
            shipping_city.name = "shipping_city";
            shipping_city.isVisible = true;
            shipping_city.placement = WooFieldsEditor.PlacementField.Stretch;
            shipping_city.label = "";
            shipping_city.typeField = WooFieldsEditor.TypeField.Text;
            shipping_city.required = true;
            shipping_city.placeholder = "";
            shipping_city.standartField = true;
            shipping_city.clear = false;
            var shipping_state = new WooFieldsEditor.FieldDescription();
            shipping_state.name = "shipping_state";
            shipping_state.isVisible = true;
            shipping_state.placement = WooFieldsEditor.PlacementField.Left;
            shipping_state.label = "";
            shipping_state.typeField = WooFieldsEditor.TypeField.Text;
            shipping_state.required = true;
            shipping_state.placeholder = "";
            shipping_state.standartField = true;
            shipping_state.clear = false;
            var shipping_postcode = new WooFieldsEditor.FieldDescription();
            shipping_postcode.name = "shipping_postcode";
            shipping_postcode.isVisible = true;
            shipping_postcode.placement = WooFieldsEditor.PlacementField.Right;
            shipping_postcode.label = "";
            shipping_postcode.typeField = WooFieldsEditor.TypeField.Text;
            shipping_postcode.required = true;
            shipping_postcode.placeholder = "";
            shipping_postcode.standartField = true;
            shipping_postcode.clear = true;
            result.push(shipping_first_name, shipping_last_name, shipping_company, shipping_country, shipping_address_1, shipping_address_2, shipping_city, shipping_state, shipping_postcode);
            return result;
        };
        Storage.generateAccountFields = function () {
            var result = new Array();
            var account_password = new WooFieldsEditor.FieldDescription();
            account_password.name = "account_password";
            account_password.isVisible = true;
            account_password.placement = WooFieldsEditor.PlacementField.Stretch;
            account_password.label = "";
            account_password.typeField = WooFieldsEditor.TypeField.Password;
            account_password.required = true;
            account_password.placeholder = "";
            account_password.standartField = true;
            account_password.clear = false;
            result.push(account_password);
            return result;
        };
        Storage.generateOrderFields = function () {
            var result = new Array();
            var order_comments = new WooFieldsEditor.FieldDescription();
            order_comments.name = "order_comments";
            order_comments.isVisible = true;
            order_comments.placement = WooFieldsEditor.PlacementField.Stretch;
            order_comments.label = "";
            order_comments.typeField = WooFieldsEditor.TypeField.TextArea;
            order_comments.required = false;
            order_comments.placeholder = "";
            order_comments.standartField = true;
            order_comments.clear = false;
            result.push(order_comments);
            return result;
        };
        Storage.getNewCustomField = function (name) {
            var result = new WooFieldsEditor.FieldDescription();
            result.name = name;
            result.isVisible = true;
            result.placement = WooFieldsEditor.PlacementField.Stretch;
            result.label = "Custom field";
            result.typeField = WooFieldsEditor.TypeField.Text;
            result.required = false;
            result.placeholder = "";
            result.standartField = false;
            result.clear = false;
            return result;
        };
        return Storage;
    }());
    WooFieldsEditor.Storage = Storage;
})(WooFieldsEditor || (WooFieldsEditor = {}));
"use strict";
if ("undefined" == typeof angular) {
}
else
    (function (angular) {
        angular.module('woofieldseditor', [])
            .directive('woofieldseditorlist', function () {
            return {
                link: function ($scope, element, attrs) {
                    var scopeOptions = $scope.woofieldseditorlistOptions;
                    var list = new WooFieldsEditor.ListControl(element, scopeOptions);
                    $scope.$watch("woofieldseditorlistDatasource", function (value) {
                        list.dataSource = value;
                    }, true);
                    if ($scope.woofieldseditorlistOninitialized)
                        $scope.woofieldseditorlistOninitialized(list);
                },
                scope: {
                    "woofieldseditorlistDatasource": "=",
                    "woofieldseditorlistOptions": "=",
                    "woofieldseditorlistOninitialized": "="
                }
            };
        });
    })(angular);
/// <reference path="../definitelytyped/jquery.d.ts" />
var WooFieldsEditor;
(function (WooFieldsEditor) {
    var ListControl = (function () {
        function ListControl(panel, options) {
            this._$panel = $(panel);
            this._options = options;
        }
        Object.defineProperty(ListControl.prototype, "dataSource", {
            get: function () {
                return this._dataSource;
            },
            set: function (newValue) {
                this._dataSource = newValue;
                this.reDraw();
            },
            enumerable: true,
            configurable: true
        });
        ListControl.prototype.reDraw = function () {
            this._$panel.empty();
            var $rootLayout = $("<ul/>");
            $rootLayout.addClass("list-group");
            this.initSortableAction($rootLayout);
            for (var index = 0; index < this._dataSource.length; index++) {
                var $rowItem = this.getItem(this._dataSource[index]);
                $rootLayout.append($rowItem);
            }
            this._$panel.append($rootLayout);
        };
        ListControl.prototype.update = function () {
            this._editedData = void 0;
            this.reDraw();
        };
        ListControl.prototype.getItem = function (data) {
            var $rowItem = $("<div/>");
            $rowItem.addClass("list-group-item");
            if (data.standartField) {
                $rowItem.css("border-left", "2px solid #337ab7");
            }
            else {
                $rowItem.css("border-left", "2px solid #abab08");
            }
            $rowItem.append(this.getItemContent(data));
            return $rowItem;
        };
        ListControl.prototype.getTypeIcon = function (typeField) {
            switch (typeField) {
                case WooFieldsEditor.TypeField.Text:
                case WooFieldsEditor.TypeField.TextArea:
                    {
                        var title;
                        if (typeField === WooFieldsEditor.TypeField.Text)
                            title = "Text";
                        else if (typeField === WooFieldsEditor.TypeField.TextArea)
                            title = "TextArea";
                        return '<i class="fa fa-file-text-o" title="' + title + '" aria-hidden="true"></i> ';
                    }
                case WooFieldsEditor.TypeField.Password: {
                    return '<i class="fa fa-lock" title="Password" style="margin-right: 4px;" aria-hidden="true"></i> ';
                }
                case WooFieldsEditor.TypeField.CheckBox: {
                    return '<i class="fa fa-check-square-o" title="CheckBox" aria-hidden="true"></i> ';
                }
                case WooFieldsEditor.TypeField.DatePicker: {
                    return '<i class="fa fa-calendar" title="DatePicker" aria-hidden="true"></i> ';
                }
                case WooFieldsEditor.TypeField.Select:
                    {
                        return '<i class="fa fa-list" title="Select" aria-hidden="true"></i> ';
                    }
                case WooFieldsEditor.TypeField.MultiSelect:
                    {
                        return '<i class="fa fa-list-alt" title="Multiselect" aria-hidden="true"></i> ';
                    }
            }
        };
        ListControl.prototype.getPlacementIcon = function (place) {
            switch (place) {
                case WooFieldsEditor.PlacementField.Left: {
                    return '<i class="fa fa-align-left" title="Left" aria-hidden="true"></i> ';
                }
                case WooFieldsEditor.PlacementField.Right: {
                    return '<i class="fa fa-align-right" title="Right"  aria-hidden="true"></i> ';
                }
                case WooFieldsEditor.PlacementField.Stretch: {
                    return '<i class="fa fa-align-justify" title="Stretch" aria-hidden="true"></i> ';
                }
            }
        };
        ListControl.prototype.getVisibleIcon = function (data) {
            if (data.isVisible) {
                if (data.serverFilterMode === WooFieldsEditor.ServerFilterMode.None && data.useClientFilter === false)
                    return '<i class="fa fa-eye" title="Visible" aria-hidden="true"></i> ';
                else
                    return '<i class="fa fa-low-vision" title="Visibility rules" aria-hidden="true"></i> ';
            }
            else
                return '<i class="fa fa-eye" title="Not visible" style="opacity:0.3" aria-hidden="true"></i> ';
        };
        ListControl.prototype.getItemContent = function (data) {
            var table = $("<div/>");
            var self = this;
            var btnDeleteItem = void 0;
            table.append(this.getTypeIcon(data.typeField));
            table.append(this.getPlacementIcon(data.placement));
            var requiredIcon = $('<i class="fa fa-asterisk" title="Required" aria-hidden="true"></i> ');
            requiredIcon.css("margin-right", "3px");
            if (!data.required) {
                requiredIcon.css("opacity", 0.3);
                requiredIcon.attr("title", "Not required");
            }
            table.append(requiredIcon);
            table.append(this.getVisibleIcon(data));
            var text = $("<span style='cursor:pointer'/>");
            var caption = data.name;
            if (data.label) {
                caption += '::"' + data.label + '"';
            }
            if (self._editedData === data) {
                caption = "<strong>" + caption + "*<strong>";
            }
            text.append(caption);
            //table.mouseenter(() => {
            //    var content = data.label;
            //    if (!data.label) 
            //        content = data.name;
            //    text.empty();              
            //    text.append("<strong>" + content + "</strong>");
            //    if (self._editedData === data) {
            //        text.append("*");
            //        text.css("font-weight", "bold");
            //    }
            //});
            //table.mouseleave(() => {
            //    text.empty();
            //    text.append(data.name);
            //    if (self._editedData === data) {
            //        text.append("*");
            //        text.css("font-weight", "bold");
            //    }
            //});
            if (!this._editedData) {
                if (!data.standartField) {
                    btnDeleteItem = $("<button style='margin-left:3px;'/>");
                    btnDeleteItem.addClass("btn btn-default btn-xs pull-right");
                    btnDeleteItem.append("Delete");
                    btnDeleteItem.on("click", data, function (e) {
                        e.preventDefault();
                        self.dataSource.splice(self.dataSource.indexOf(e.data), 1);
                        if (self._options.onItemDeleted) {
                            self._options.onItemDeleted(e.data);
                        }
                        self.reDraw();
                    });
                    table.append(btnDeleteItem);
                }
                var btnEditItem = $("<button/>");
                btnEditItem.addClass("btn btn-default btn-xs pull-right");
                btnEditItem.append("Edit");
                //var onEditItem = function (data: FieldDescription) {
                //}
                //this._options.onEditItemSelected(data);
                btnEditItem.on("click", { data: data, onEditFunc: this.onEditItemSelected.bind(this) }, function (e) {
                    e.preventDefault();
                    self._editedData = e.data.data;
                    e.data.onEditFunc(e.data.data);
                    self.reDraw();
                });
   var btnVisibilityEditItem = $("<button style='margin-right:3px;'/>");
                btnVisibilityEditItem.addClass("btn btn-default btn-xs pull-right");
                btnVisibilityEditItem.append('<i class="fa fa-eye" title="Visibility Rules" aria-hidden="true"></i>');
                btnVisibilityEditItem.on("click", { data: data, onEditFunc: this.onVisibleRulesEdited.bind(this) }, function (e) {
                    e.preventDefault();
                    self._editedData = e.data.data;
                    e.data.onEditFunc(e.data.data);
                    self.reDraw();
                });
                table.append(btnEditItem);
table.append(btnVisibilityEditItem);
            }
            table.append(text);
            return table;
        };
        ListControl.prototype.onEditItemSelected = function (data) {
            //  console.log('man');
            if (this._options.onBasePropsEdited) {
                this._options.onBasePropsEdited(data);
            }
        };
        ListControl.prototype.onVisibleRulesEdited = function (data) {
            if (this._options.onVisibleRulesEdited) {
                this._options.onVisibleRulesEdited(data);
            }
        };
        ListControl.prototype.initSortableAction = function (panel) {
            var startIndex;
            var self = this;
            panel.sortable({
                placeholder: "ui-state-highlight",
                axis: "y",
                cursor: "move",
                opacity: 0.8,
                update: function (event, ui) {
                    var indexItem = ui.item.index();
                    var dataCurrentItem = self.dataSource[startIndex];
                    self.dataSource.splice(startIndex, 1);
                    self.dataSource.splice(indexItem, 0, dataCurrentItem);
                },
                start: function (event, ui) {
                    startIndex = ui.item.index();
                }
            });
            panel.disableSelection();
        };
        return ListControl;
    }());
    WooFieldsEditor.ListControl = ListControl;
})(WooFieldsEditor || (WooFieldsEditor = {}));
var WooFieldsEditor;
(function (WooFieldsEditor) {
    var ListControlOptions = (function () {
        function ListControlOptions(onBasePropsEdited, onItemDeleted, onVisibleRulesEdited) {
            this.onBasePropsEdited = onBasePropsEdited;
            this.onItemDeleted = onItemDeleted;
            this.onVisibleRulesEdited = onVisibleRulesEdited;
        }
        return ListControlOptions;
    }());
    WooFieldsEditor.ListControlOptions = ListControlOptions;
})(WooFieldsEditor || (WooFieldsEditor = {}));
//# sourceMappingURL=wooFieldsEditor.js.map