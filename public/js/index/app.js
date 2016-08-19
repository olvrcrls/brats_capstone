
			// function fetch_select(val)
			// {
			// 	$.ajax({
			// 	type: 'post',
			// 	url: 'get_terminal_destination.php',
			// 	data: {
			// 	from:val
			// 	},
			// 	success: function (response) {
				
			// 	document.getElementById("destination").innerHTML=response; 
			// 		$(document).ready(function() {
			// 		$('#destination').material_select();
			// 		})
			// 	}
			// 	});
			// }
		
			$( document ).ready(function(){
				$(".button-collapse").sideNav();
			});
			
			$(document).ready(function() {
				$('#from').material_select();
			}); // SELECT INPUT
			
			
			$(document).ready(function() {
				$('#destination').material_select();
			});
			
			


			function submit_by_id() {
				var traveldate = document.getElementById("travel_date").value;
				var from = document.getElementById("from").value;
				var destination = document.getElementById("destination").value;
				var bustype = document.getElementById("bus_type").value;
	
	
				document.getElementById("find_travel_form").submit();//form submission
				alert(" Travel Date : "+traveldate+" \n From : "+from+" \n Destination : "+destination+" \n Bus Type : "+bustype+" \n Form Id : "+document.getElementById("find_travel_form").getAttribute("id")+"\n\n Form Submitted Successfully......");
	
			}
			
			function format_date(){
				var traveldate = document.getElementById("travel_date").value;
				
				var arr = traveldate.split(" ");
				
				var arr1= arr[1].split(",");
				
				if(arr1[0] == "January"){
					arr1[0] = "01";
				}
				else if(arr1[0] == "February"){
					arr1[0] = "02";
				}
				
				else if(arr1[0] == "March"){
					arr1[0] = "03";
				}
				
				else if(arr1[0] == "April"){
					arr1[0] = "04";
				}
				else if(arr1[0] == "May"){
					arr1[0] = "05";
				}
				
				else if(arr1[0] == "June"){
					arr1[0] = "06";
				}
				
				else if(arr1[0] == "July"){
					arr1[0] = "07";
				}
				
				else if(arr1[0] == "August"){
					arr1[0] = "08";
				}
				
				else if(arr1[0] == "September"){
					arr1[0] = "09";
				}
				
				else if(arr1[0] == "October"){
					arr1[0] = "10";
				}
				
				else if(arr1[0] == "November"){
					arr1[0] = "11";
				}
				
				else if(arr1[0] == "December"){
					arr1[0] = "12";
				}
				
				var DateFormat = arr[2].concat("-",arr1[0],"-",arr[0]);
				
				document.getElementById("travel_date").value = DateFormat;
			}