(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
'use strict';

$(document).ready(function () {
  var minimumDays = void 0;
  $.ajax({
    type: 'get',
    url: '/api/schedule/days/fetch',
    data: 'json',
    success: function success(response) {
      minimumDays = response[0].DaysSpanToReserve_Days;
      $('.datepicker').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 15, // Creates a dropdown of 15 years to control year
        min: minimumDays + 1,
        onOpen: function onOpen() {
          this.clear();
        },
        onSet: function onSet() {
          var x, y, year, date, month;
          x = $('.datepicker').pickadate().val().toString();
          y = x.split(/[ ,]+/);
          date = y[0];
          month = y[1];
          year = y[2];
          //console.log(y[0]+" "+ y[1]+ " "+ y[2]);
          if (date && month && year) {
            this.close();
          }
        }
      });
    },
    error: function error(_error) {
      console.log(_error);
      $('.datepicker').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 15, // Creates a dropdown of 15 years to control year
        min: null,
        onOpen: function onOpen() {
          this.clear();
        },
        onSet: function onSet() {
          var x, y, year, date, month;
          x = $('.datepicker').pickadate().val().toString();
          y = x.split(/[ ,]+/);
          date = y[0];
          month = y[1];
          year = y[2];
          console.log(y[0] + " " + y[1] + " " + y[2]);
          if (date && month && year) {
            this.close();
          }
        }
      });
    }
  });
});

},{}]},{},[1]);

//# sourceMappingURL=site_es6.js.map
