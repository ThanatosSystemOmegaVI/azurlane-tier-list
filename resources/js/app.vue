<template>
	<!-- header -->
	<div class="row">
		<div class="col-12">
			<div id="header" class="header shadow-sm p-2 position-relative">
				<div class="row">
					<div class="col-8 col-md-8 col-lg-10 col-xl-10">
						<div class="headeritem">
							<img src="../img/logo banner clear.png" class="headerimg pointer" @click="toPage('/')" alt="logo">
						</div>
					</div>
					<div class="col-4 col-md-4 col-lg-2 col-xl-2">
						<font-awesome-icon icon="fa-regular fa-circle-user" v-if="loggedin" class="headericon" />
					</div>
				</div>
			</div>
		</div>
		<div class="col-12">
			<router-view></router-view>
			<notifications width="350"/>
		</div>
	</div>
</template>

<script>
export default {
	name: "login",
	data() {
		return {
			loggedin: false,
		}
	},
	created() {
		this.axios.post("/checkuser", {})
			.then(response => {
				if (response['data']['bool'] == "true") {
					this.loggedin = true;
				} else {
					this.loggedin = false;
				}
			})
	},
	methods: {
		toPage: function (link) {
			location.href = link;
		}
	}
}
</script>