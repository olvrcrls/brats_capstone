$(document).ready(() => {
   let minimumDays;
     $.ajax({
         type: 'get',
         url: '/api/schedule/days/fetch',
         data: 'json',
         success: (response => {
            minimumDays = response[0].DaysSpanToReserve_Days
            $('.datepicker').pickadate({
                selectMonths: true, // Creates a dropdown to control month
                selectYears: 15, // Creates a dropdown of 15 years to control year
                min: minimumDays + 1,
                onOpen: function () {
                  this.clear();
                },
                onSet: function () {
                  var x,y,year,date,month;
                  x = $('.datepicker').pickadate().val().toString();
                  y = x.split(/[ ,]+/);
                  date = y[0];
                  month = y[1];
                  year = y[2];
                  //console.log(y[0]+" "+ y[1]+ " "+ y[2]);
                  if(date && month && year){
                    this.close();
                
                  }
                }
              });
          }),
         error: (error => {
            console.log(error)
            $('.datepicker').pickadate({
                selectMonths: true, // Creates a dropdown to control month
                selectYears: 15, // Creates a dropdown of 15 years to control year
                min: null,
                onOpen: function () {
                  this.clear();
                },
                onSet: function () {
                  var x,y,year,date,month;
                  x = $('.datepicker').pickadate().val().toString();
                  y = x.split(/[ ,]+/);
                  date = y[0];
                  month = y[1];
                  year = y[2];
                  console.log(y[0]+" "+ y[1]+ " "+ y[2]);
                  if(date && month && year){
                    this.close();
                  }
                }
              });
          })
    });
});

   
