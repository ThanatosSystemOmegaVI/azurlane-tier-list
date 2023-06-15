<template>
	<div class="container py-5 h-100">
		<div class="row d-flex align-items-center justify-content-center h-100">
			<div class="col-md-8 col-lg-7 col-xl-6">
				<img src="../../img/draw2.svg" class="img-fluid" alt="Phone image">
			</div>
			<div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
				<!-- Email -->
				<div class="form-outline mb-4">
					<div class="input-group mb-3">
						<span class="input-group-text" id="basic-addon1"><font-awesome-icon icon="fa-solid fa-at" /></span>
						<input type="email" id="email" class="form-control form-control-lg" placeholder="Email" v-model="email">
					</div>
				</div>
				<!-- Password -->
				<div class="form-outline mb-4">
					<div class="input-group mb-3">
						<span class="input-group-text" id="basic-addon1"><font-awesome-icon icon="fa-solid fa-lock" /></span>
						<input type="password" id="password" class="form-control form-control-lg" placeholder="Password" v-model="password">
					</div>
				</div>
				<!-- Submit -->
				<button type="submit" class="btn btn-primary btn-lg btn-block w-100" @click="login()">Login <font-awesome-icon icon="fa-solid fa-arrow-right-to-bracket" /> </button>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	name: "login",
	data() {
		return {
			email: '',
			password: '',
			error: null,
			success: false,
		}
	},
	created() {
	},
	methods: {
		login: async function () {
			const logindata = { username: this.email, password: this.password };
			this.axios.post("/loginuser", logindata)
				.then(response => {
					if (response['data']['bool'] == "true") {
						this.$notify({
							// title: "Important message",
							text: response['data']['message'],
							type: 'success',
							duration: 3000,
						});
						location.href = "/";
					}else{
						this.$notify({
							// title: "Important message",
							text: response['data']['message'],
							type: 'warn',
							duration: 3000,
						});
					}
				});
		}
	}
}
</script>