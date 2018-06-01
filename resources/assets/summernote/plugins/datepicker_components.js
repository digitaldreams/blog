var datepickerTemplateContents={"pickers\/date":"\r\n<div class=\"form-group\">\r\n    <label for=\"datetimepicker-date\">Date<\/label>\r\n    <div class=\"input-group\">\r\n        <input type=\"text\" class=\"form-control datetimepicker-date\" id=\"datetimepicker-date\" placeholder=\"e.g. 06\/28\/2017\"\r\n               data-date=\"12-02-2012\" data-date-format=\"dd\/mm\/yyyy\"\r\n        >\r\n        <div class=\"input-group-addon\">\r\n            <label for=\"datetimepicker-date\" class=\"fa fa-calendar\"><\/label>\r\n        <\/div>\r\n    <\/div>\r\n<\/div>\r\n","pickers\/date_time":"\r\n<div class=\"form-group\">\r\n    <label for=\"datetimepicker-date-time\">Date Time<\/label>\r\n    <div class=\"input-group\">\r\n        <input type=\"text\" class=\"form-control datetimepicker-date-time\" id=\"datetimepicker-date-time\"\r\n               placeholder=\"e.g. 06\/28\/2017 12:00 am\" data-date-format=\"dd\/mm\/yyyy HH:ii p\"\r\n        >\r\n        <div class=\"input-group-addon\">\r\n            <label for=\"datetimepicker-date-time\" class=\"fa fa-calendar\"><\/label>\r\n        <\/div>\r\n    <\/div>\r\n<\/div>\r\n","pickers\/future_date":"\r\n<div class=\"form-group\">\r\n    <label for=\"datetimepicker-future-date\">Future Date<\/label>\r\n    <div class=\"input-group\">\r\n        <input type=\"text\" class=\"form-control datetimepicker-future-date\" id=\"datetimepicker-future-date\"\r\n               placeholder=\"e.g. 06\/28\/2017\" data-date-format=\"dd\/mm\/yyyy\"\r\n        >\r\n        <div class=\"input-group-addon\">\r\n            <label for=\"datetimepicker-future-date\" class=\"fa fa-calendar\"><\/label>\r\n        <\/div>\r\n    <\/div>\r\n<\/div>\r\n","pickers\/future_date_time":"\r\n<div class=\"form-group\">\r\n    <label for=\"datetimepicker-future-date-time\">Future Date Time<\/label>\r\n    <div class=\"input-group\">\r\n        <input type=\"text\" class=\"form-control datetimepicker-future-date-time\" id=\"datetimepicker-future-date-time\"\r\n               placeholder=\"e.g. 06\/28\/2017\" data-date-format=\"dd\/mm\/yyyy HH:ii p\"\r\n        >\r\n        <div class=\"input-group-addon\">\r\n            <label for=\"datetimepicker-future-date-time\" class=\"fa fa-calendar\"><\/label>\r\n        <\/div>\r\n    <\/div>\r\n<\/div>\r\n","pickers\/past_date":"\r\n<div class=\"form-group\">\r\n    <label for=\"datetimepicker-past-date\">Past Date<\/label>\r\n    <div class=\"input-group\">\r\n        <input type=\"text\" class=\"form-control datetimepicker-past-date\" id=\"datetimepicker-past-date\"\r\n               placeholder=\"e.g. 06\/28\/2017\" data-date-format=\"dd\/mm\/yyyy\"\r\n        >\r\n        <div class=\"input-group-addon\">\r\n            <label for=\"datetimepicker-past-date\" class=\"fa fa-calendar\"><\/label>\r\n        <\/div>\r\n    <\/div>\r\n<\/div>\r\n\r\n","pickers\/past_date_time":"\r\n<div class=\"form-group\">\r\n    <label for=\"datetimepicker-past-date-time\">Past Date Time<\/label>\r\n    <div class=\"input-group\">\r\n        <input type=\"text\" class=\"form-control datetimepicker-past-date-time\" id=\"datetimepicker-past-date-time\"\r\n               placeholder=\"e.g. 06\/28\/2017 12:00 am\" data-date-format=\"dd\/mm\/yyyy HH:ii p\"\r\n        >\r\n        <div class=\"input-group-addon\">\r\n            <label for=\"datetimepicker-past-date-time\" class=\"fa fa-calendar\"><\/label>\r\n        <\/div>\r\n    <\/div>\r\n<\/div>\r\n\r\n","pickers\/time":"\r\n<div class=\"form-group\">\r\n    <label for=\"datetimepicker-time\">Time<\/label>\r\n    <div class=\"input-group\">\r\n        <input type=\"text\" class=\"form-control datetimepicker-time\" id=\"datetimepicker-time\"\r\n               placeholder=\"e.g. 12:00 AM\" data-date-format=\"HH:ii p\"\r\n        >\r\n        <div class=\"input-group-addon\">\r\n            <label for=\"datetimepicker-time\" class=\"fa fa-calendar\"><\/label>\r\n        <\/div>\r\n    <\/div>\r\n<\/div>\r\n"};
var datepickerToolbars=['pickers'];
(function (factory) {
    /* global define */
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['jquery'], factory);
    } else if (typeof module === 'object' && module.exports) {
        // Node/CommonJS
        module.exports = factory(require('jquery'));
    } else {
        // Browser globals
        factory(window.jQuery);
    }
}(function ($) {

    // Extends plugins for adding hello.
    //  - plugin is external module for customizing.
    $.extend($.summernote.plugins, {
        /**
         * @param {Object} context - context object has status of editor.
         */
        'pickers': function (context) {

            return addDropdown(context, 'pickers', Object.values({"Date":"date","Date_time":"date_time","Future_date":"future_date","Future_date_time":"future_date_time","Past_date":"past_date","Past_date_time":"past_date_time","Time":"time"}),datepickerTemplateContents);

    },

    });
}));