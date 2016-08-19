import Vue from "vue";
Vue.use(require('vue-resource'));

var vm = new Vue({

	el: 'html',
	data:
	{
		'token': $('meta[name="csrf-token"]').attr('content'),
		'bus': null,
		'dispatch': null
	},
	methods:
	{
		initialize()
		{
			this.$http.post('/api/seats', { bus: this.bus, dispatch: this.dispatch, _token: this.token} ).then(res => {
			  console.log(res);
			}).catch(err => {
			  console.log(err);
			}); //ajax request for retrieving the seats
		}
	},
	ready()
	{
		alert('connect');
		initialize();
		setInterval(function (){
			initialize();
		}, 1000); // calls initialize every 1 second.
	},


});