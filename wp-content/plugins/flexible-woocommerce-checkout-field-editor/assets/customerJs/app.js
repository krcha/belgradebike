"use strict";
(function ($, editorsDescription) {
    function initTextBox(textBoxDescription) {
        if (textBoxDescription.defaultValue)
            $("#" + textBoxDescription.name).val(textBoxDescription.defaultValue);
    }

    function initTextArea(textAreaDescription) {
        if (textAreaDescription.defaultValue)
            $("#" + textAreaDescription.name).val(textAreaDescription.defaultValue);
    }

    function initPassword(passwordDescription) {
        if (passwordDescription.defaultValue)
            $("#" + passwordDescription.name).val(passwordDescription.defaultValue);
    }

    function initCheckBox(checkBoxDescription) {
        if (checkBoxDescription.defaultValue)
            $("#" + checkBoxDescription.name).prop('checked', checkBoxDescription.defaultValue);
    }

    function initSelect(selectDescription) {
        $("#" + selectDescription.name).select2();
        $("#" + selectDescription.name).select2().select2("val", null);

        if (selectDescription.defaultValue)
            $("#" + selectDescription.name).select2().select2("val", selectDescription.defaultValue);
        else
            $("#" + selectDescription.name + "_field").find(".select2-choice .select2-chosen").html('&nbsp;');
    }

    function initMultiSelect(multiselectDescription) {
        $("#" + multiselectDescription.name).attr("multiple", "multiple");
        $("#" + multiselectDescription.name).select2();
        $("#" + multiselectDescription.name).select2().select2("val", null);

        if (multiselectDescription.defaultValue) {
            var arrayDefaultValues = JSON.parse(multiselectDescription.defaultValue);
            $("#" + multiselectDescription.name).select2().select2("val", arrayDefaultValues);
        }

        var newId = multiselectDescription.name + "[]";
        $("#" + multiselectDescription.name).attr("name", newId);
    }

    function initDatePicker(datePickerDescription) {
        $("#" + datePickerDescription.name).datepicker({
            showWeek: datePickerDescription.showWeek,
            firstDay: datePickerDescription.firstDay,
            dateFormat: datePickerDescription.dateFormat,
            numberOfMonths: datePickerDescription.numberOfMonths,
            changeYear: datePickerDescription.changeYear,
            changeMonth: datePickerDescription.changeMonth
        });

        if (datePickerDescription.maxDate)
            $("#" + datePickerDescription.name).datepicker("option", "maxDate", datePickerDescription.maxDate);

        if (datePickerDescription.minDate)
            $("#" + datePickerDescription.name).datepicker("option", "minDate", datePickerDescription.minDate);

        if (datePickerDescription.defaultSelectedDate)
            $("#" + datePickerDescription.name).datepicker("setDate", datePickerDescription.defaultSelectedDate);
    }

    function prepareEditors(fieldDescriptions) {
        for (var indexField = 0; indexField < fieldDescriptions.length; indexField++) {
            var currentField = fieldDescriptions[indexField];
            if (currentField.standartField === true)
                continue;
            switch (currentField.typeField) {
                case 0:
                {
                    initTextBox(currentField);
                    break;
                }
                case 1:
                {
                    initTextArea(currentField);
                    break;
                }
                case 2:
                {
                    initPassword(currentField);
                    break;
                }
                case 3:
                {
                    initCheckBox(currentField);
                    break;
                }
                case 4:
                {
                    initSelect(currentField);
                    break;
                }
                case 5:
                {
                    initMultiSelect(currentField);
                    break;
                }
                case 6:
                {
                    initDatePicker(currentField);
                    break;
                }
            }

        }
    }

    function compareTwoStringArrays(firstArray, secondArray) {

        if (firstArray.length !== secondArray.length)
            return false;

        for (var indexArray = 0; indexArray < firstArray.length; indexArray++) {
            var valueFromFirstArray = firstArray[indexArray];
            if (secondArray.indexOf(valueFromFirstArray) === -1)
                return false;
        }

        return true;
    }


    function isVisibleTextBox(nameOwner, value) {
        if ($("#" + nameOwner).val() === value)
            return true;
        else
            return false;
    }

    function isVisibleCheckBox(nameOwner, value) {


        if ($("#" + nameOwner).is(":checked"))
            if (value)
                return true;
            else
                return false;
        else if (!value)
            return true;
        else
            return false;
    }

    function isVisibleSelect(nameOwner, value) {
        if ($("#" + nameOwner).val() === value)
            return true;
        else
            return false;
    }

    function isVisibleMultiSelect(nameOwner, value) {

        var jsArrayConditions = [];

        if (value)
            jsArrayConditions = JSON.parse(value);

        var valueOfMultiSelect = $("#" + nameOwner).val();
        if (valueOfMultiSelect === null)
            valueOfMultiSelect = [];


        if (compareTwoStringArrays(valueOfMultiSelect, jsArrayConditions))
            return true;
        else
            return false;
    }

    function isVisibleDatePicker(nameOwner, value) {
        if ($('#' + nameOwner).val() === value)
            return true;
        else
            return false;
    }

    function getStatus(rules) {

        for (var indexRule = 0; indexRule < rules.length; indexRule++) {
            var currentRule = rules[indexRule];

            switch (currentRule.typeField) {
                case 0:
                {
                    if (!isVisibleTextBox(currentRule.name, currentRule.value))
                        return false;
                    break;
                }
                case 1:
                {
                    if (!isVisibleTextBox(currentRule.name, currentRule.value))
                        return false;
                    break;
                }
                case 2:
                {
                    if (!isVisibleTextBox(currentRule.name, currentRule.value))
                        return false;

                    break;
                }
                case 3:
                {
                    if (!isVisibleCheckBox(currentRule.name, currentRule.value))
                        return false;

                    break;
                }
                case 4:
                {
                    if (!isVisibleSelect(currentRule.name, currentRule.value))
                        return false;

                    break;
                }
                case 5:
                {
                    if (!isVisibleMultiSelect(currentRule.name, currentRule.value))
                        return false;

                    break;
                }
                case 6:
                {
                    if (!isVisibleDatePicker(currentRule.name, currentRule.value))
                        return false;

                    break;
                }
            }
        }
        return true;

    }

    function dependOfCheckBox(name, data, callback) {
        $("#" + name).change(function () {
            callback(data);
        });
    }

    function dependOfSelect(name, data, callback) {
        $("#" + name).on("change", function () {
            callback(data);
        });
    }

    function dependOfTextBox(name, data, callback) {
        $("#" + name).on("change keyup paste", function () {
            callback(data);
        });
    }


    function dependOfMultiSelect(name, data, callback) {
        $("#" + name).on("change", function (e) {
            callback(data);
        });
    }

    function dependOfDatePicker(name, data, callback) {
        $("#" + name).datepicker("option", "onSelect", function (dateText) {
            callback(data);
        });
    }


    function t(nameOwner, fieldDescriptions) {
        for (var indexField = 0; indexField < fieldDescriptions.length; indexField++) {
            var currentField = fieldDescriptions[indexField];

            if (currentField.useClientFilter === false)
                continue;

            var arrayClientConditions = currentField.arrayClientConditions;

            if (arrayClientConditions.filter(function (item) {
                return item.name === nameOwner;
            }).length === 0)
                continue;

            var isVisibleNow = getStatus(arrayClientConditions);
            if (isVisibleNow)
                $('#' + currentField.name).parent().show();
            else
                $('#' + currentField.name).parent().hide();
        }
    }


    function setDependsOfVisibility(fieldDescriptions) {

        for (var indexField = 0; indexField < fieldDescriptions.length; indexField++) {
            var currentField = fieldDescriptions[indexField];

            if (currentField.useClientFilter === false)
                continue;

            var arrayClientConditions = currentField.arrayClientConditions;

            var isVisibleNow = getStatus(arrayClientConditions);
            if (isVisibleNow)
                $('#' + currentField.name).parent().show();
            else
                $('#' + currentField.name).parent().hide();


            for (var indexRule = 0; indexRule < currentField.arrayClientConditions.length; indexRule++) {

                var currentRule = currentField.arrayClientConditions[indexRule];
                switch (currentRule.typeField) {
                    case 3:
                    {
                        dependOfCheckBox(currentRule.name, {name: currentRule.name, fieldDescriptions: fieldDescriptions}, function (e) {
                            t(e.name, e.fieldDescriptions);
                        });
                        break;
                    }
                    case 0:
                    case 1:
                    case 2:
                    {
                        dependOfTextBox(currentRule.name, {name: currentRule.name, fieldDescriptions: fieldDescriptions}, function (e) {
                            t(e.name, e.fieldDescriptions);
                        });
                        break;
                    }
                    case 4:
                    {
                        dependOfSelect(currentRule.name, {name: currentRule.name, fieldDescriptions: fieldDescriptions}, function (e) {
                            t(e.name, e.fieldDescriptions);
                        });
                        break;
                    }
                    case 5:
                    {
                        dependOfMultiSelect(currentRule.name, {name: currentRule.name, fieldDescriptions: fieldDescriptions}, function (e) {
                            t(e.name, e.fieldDescriptions);
                        });
                        break;
                    }
                    case 6:
                    {
                        dependOfDatePicker(currentRule.name, {name: currentRule.name, fieldDescriptions: fieldDescriptions}, function (e) {
                            t(e.name, e.fieldDescriptions);
                        });
                        break;
                    }
                }


            }



        }
    }

    $().ready(function () {
        if (window.checkoutFieldsEditor.billingEditorsDescription) {
            var billingFieldDescriptions = JSON.parse(window.checkoutFieldsEditor.billingEditorsDescription);
            if (billingFieldDescriptions) {

                prepareEditors(billingFieldDescriptions);


                setDependsOfVisibility(billingFieldDescriptions);
            }
        }

        if (window.checkoutFieldsEditor.shippingEditorsDescription) {
            var shippingFieldDescriptions = JSON.parse(window.checkoutFieldsEditor.shippingEditorsDescription);
            if (shippingFieldDescriptions) {
                prepareEditors(shippingFieldDescriptions);
                setDependsOfVisibility(shippingFieldDescriptions);
            }
        }

        if (window.checkoutFieldsEditor.accountEditorsDescription) {
            var accountFieldDescriptions = JSON.parse(window.checkoutFieldsEditor.accountEditorsDescription);
            if (accountFieldDescriptions) {
                prepareEditors(accountFieldDescriptions);
                setDependsOfVisibility(accountFieldDescriptions);
            }
        }
        if (window.checkoutFieldsEditor.orderEditorsDescription) {
            var orderFieldDescriptions = JSON.parse(window.checkoutFieldsEditor.orderEditorsDescription);
            if (orderFieldDescriptions) {
                prepareEditors(orderFieldDescriptions);
                setDependsOfVisibility(orderFieldDescriptions);
            }
        }

    });


})(jQuery, checkoutFieldsEditor.editorsDescription);
