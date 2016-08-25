// pre-compiled es6 codes by Oliver Carlos
import Vue from 'vue';
let vm = new Vue({
	el: 'body',
	data:
	{

	},
	methods: {
		checkout(event)
		{
			console.log('User is checking out.');
			let form = event.target.id
		}
	},
}) //vue instance
