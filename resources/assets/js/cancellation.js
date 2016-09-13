import Vue from 'vue';

const vm = new Vue({
	el: 'body',
	data: {
		'other': false,
		'otherReason': null,
		'selectReason': '',
	},

	methods: 
	{
		selectMethod(event)
		{
			if (this.selectReason == 'other')
				this.showOther('show')
			else
				this.showOther('hide')
		},

		showOther(value)
		{
			if (value == 'show')
				this.other = true
			else if (value == 'hide')
				this.other = false
			else
				this.other = false
		}
	}

})