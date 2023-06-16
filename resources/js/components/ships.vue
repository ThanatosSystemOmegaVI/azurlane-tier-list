<template>
	<section class="p-4">
		<div class="row">
			<!-- Title -->
			<div class="col-12">
				<h1 class="text-center text-primary">Tier list edit</h1>
			</div>

			<!-- List of unsorted ships + add ship button -->
			<div class="col-12">
				<div class="row">
					<!-- list of ships -->
					<div class="col-xl-11">
						<div id="shiplist" class="shiplist border border-primary rounded-1 overflow-auto">
							<div class="ship" v-for="ship in ships">
								<img v-bind:src="'../../data/'+ship.image">
								<p>{{ ship }}</p>
							</div>
						</div>
					</div>
					<!-- add ship button -->
					<div class="col-xl-1">
						<div class="addbutton border border-primary rounded-1 bg-primary text-warning" @click="addship = !addship">
							<font-awesome-icon icon="fa-solid fa-plus" />
						</div>
					</div>
				</div>
			</div>

			<!-- tier list with draggable items -->
			<div class="col-12"></div>

			<!-- ship adding popup -->
			<div class="col-12">
				<div class="popup" id="addship" v-if="addship">
					<!-- title -->
					<div class="row">
						<div class="col-12">
							<h2 class="text-center text-primary">Add ship</h2>
							<font-awesome-icon icon="fa-solid fa-xmark" class="position-absolute closepopup text-primary fs-1" @click="addship = !addship"/>
						</div>
					</div>
					<!-- inputs -->
					<div class="row">
						<div class="col-6">
							<input type="file" id="image" class="form-control mb-2" @change="onFileChanged($event)"
								accept="image/*" placeholder="shipimage">
						</div>
						<div class="col-6">
							<input type="text" id="name" class="form-control mb-2" v-model="newShip.name"
								placeholder="shipname">
						</div>
					</div>
					<div class="row">
						<div class="col-6">
							<select id="type" class="form-control mb-2" v-model="newShip.type">
								<option value="">Select a ship type</option>
								<option v-for="sType in shipType">{{ sType }}</option>
							</select>
						</div>
						<div class="col-6">
							<select id="rarity" class="form-control mb-2" v-model="newShip.rarity">
								<option value="">Select a ship rarity</option>
								<option v-for="sRarity in shipRarity">{{ sRarity }}</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-6">
							<select id="faction" class="form-control mb-4" v-model="newShip.faction">
								<option value="">Select a ship faction</option>
								<option v-for="sFaction in shipFaction">{{ sFaction }}</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-6">
							<div class="row">
								<div class="col-6">
									<input type="text" id="FP" v-model="newShip.stats.FP" class="form-control mb-1"
										placeholder="FP">
									<input type="text" id="HP" v-model="newShip.stats.HP" class="form-control mb-1"
										placeholder="HP">
									<input type="text" id="AA" v-model="newShip.stats.AA" class="form-control"
										placeholder="AA">
								</div>
								<div class="col-6">
									<input type="text" id="SP" v-model="newShip.stats.SP" class="form-control mb-1"
										placeholder="SP">
									<input type="text" id="AVI" v-model="newShip.stats.AVI" class="form-control mb-1"
										placeholder="AVI">
									<input type="text" id="TRP" v-model="newShip.stats.TRP" class="form-control"
										placeholder="TRP">
								</div>
							</div>
						</div>
						<div class="col-6">
							<textarea id="note" class="form-control h-100" v-model="newShip.note"
								placeholder="Note"></textarea>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<button class="btn button btn-primary w-100 mt-2" @click="addShip()">Add ship</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</template>

<script>
import '../../css/ships.css';
export default {
	name: "ships",
	data() {
		return {
			// ships list
			ships:{},
			// toggle add ship popup 
			addship: false,
			// add ship select data
			shipRarity: ['Common', 'Rare', 'Elite', 'Super rare', 'Ultra'],
			shipType: ['DD', 'CL', 'CA', 'BB', 'CV', 'AR', 'SS', 'Misc'],
			shipFaction: ['Iris Libre', 'Vichya Dominion'],
			// new ship data 
			newShip: {
				image: "",
				name: "",
				type: "",
				rarity: "",
				faction: "",
				note: "",
				stats: {
					FP: null,
					HP: null,
					AA: null,
					SP: null,
					AVI: null,
					TRP: null,
				},
			}
		}
	},
	created() {
		// get all ships from the database
		let _this = this;
		this.axios.post("/getships", {}).then(response => {
			if (response['data']['ships'].length > 0) {
				_this.ships = response['data']['ships'];
			} else {
				_this.ships = response['data']['ships'];
			}
		});
	},
	methods: {
		// add ship to database
		addShip: function () {
			if (this.newShip.image !== "" && this.newShip.name !== "" && this.newShip.type !== "" && this.newShip.rarity !== "" && this.newShip.faction !== "") {
				//ajax
				this.axios.post("/addship", this.newShip).then(response => {
					if (response['data']['bool'] == "true") {
						this.$notify({ text: response['data']['message'], type: 'success', duration: 3000 });
						location.reload();
					} else {
						this.$notify({ text: response['data']['message'], type: 'warn', duration: 3000 });
					}
				});
			} else {
				let messages = [];
				this.newShip.faction === "" ? messages.push('Ship "Faction" is mandatory') : null;
				this.newShip.rarity === "" ? messages.push('Ship "Rarity" is mandatory') : null;
				this.newShip.type === "" ? messages.push('Ship "Type" is mandatory') : null;
				this.newShip.name === "" ? messages.push('Ship "Name" is mandatory') : null;
				this.newShip.image === "" ? messages.push('Ship "Image" is mandatory') : null;

				messages.forEach(element => {
					this.$notify({ text: element, type: 'warn', duration: 3000 });
				});
			}
		},

		// read ship image as base64 data 
		onFileChanged: function (e) {
			let _this = this;
			let file = e.target.files[0];
			if (file !== undefined && file !== null) {
				let reader = new FileReader();
				reader.readAsDataURL(file);
				reader.onload = function () {
					_this.newShip.image = reader.result;
				};
			}
		}
	}
}
</script>