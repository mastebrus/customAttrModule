define([
    'jquery',
    'mage/validation'
], function ($) {
    'use strict';

    $.validator.addMethod(
        'validate-custom-attribute',
        function (value) {
            return value !== ''; 
        },
        $.mage.__('Custom Attribute is required.')
    );

    return function () {
        $('#custom-attribute-input').rules('add', {
            'validate-custom-attribute': true
        });
    };
});
