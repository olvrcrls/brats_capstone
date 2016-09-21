import Vue from 'vue';
import Route from '../vue/routes.vue';
Vue.use(require('vue-resource'));


let vm = new Vue({
	el: 'body',
	data: {
		'routes': [],
		'search': ''
	},
	components: { 'Routes': Route },
	ready()
	{
		console.log('Routes vm ready.')
	}

});