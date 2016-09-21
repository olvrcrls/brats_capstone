<template>
	<div>
		<div class="col s6 col m6">
			<div class="input-field col s5 col m5">
				<b class="flow-text">SEARCH A ROUTE <i class="fa fa-search"></i></b> 
				<input type="text" name="search" name="search" v-model="search" placeholder="Search here..." />
			</div>
		</div>
		<table class="highlight bordered">
			<tbody>
				<tr v-for="route in routes | filterBy search in 'Route_Name'" v-if="routes.length > 0">
					<td>
						<a class="flow-text teal-text" href="/route/{{ route.Route_Id }}">
							<p>
								<i class="fa fa-btn fa-map-marker"></i> {{ route.Route_Name }} 
							</p>	
						</a>
					</td>
				</tr>
				<tr v-if="routes.length <= 0">
					<td>
						<h4 class="blue-grey-text">No Routes Available, yet. <i class="fa fa-remove"></i></h4>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</template>
<script type="text/javascript">
	export default {
		data()
		{
			return {
				'routes': [],
				'search': ''
			}
		},
		methods:
		{
			retrieveRoutes()
			{
				this.$http.get('/api/routes/fetch').then(response => {
					console.log("Routes are loaded.")
					this.routes = response.data
				}).catch(error => {
					console.log(error)
				})
			}
		},
		ready()
		{
			this.retrieveRoutes()
		}
	}
</script>