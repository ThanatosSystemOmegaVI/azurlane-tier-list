<template>
	<section class="p-4">
		<div class="row">
			<!-- ------------------------------------- List of unsorted ships + add ship button ------------------------------------- -->
			<div class="col-12 col-md-12 offset-lg-1 col-lg-10 offset-xl-1 col-xl-10">
				<!-- ------------------------------------- Add ship button ------------------------------------- -->
				<div class="row">
					<div id="addbutton" class="col-12 col-md-12 col-lg-6 col-xl-6 addbutton position-relative">
						<div class="button-default bg-secondary text-white fw-bold pointer d-flex justify-content-between">
							<font-awesome-icon class="my-auto" icon="fa-solid fa-angles-left" @click="switchFaction('prev')" />
							<span> {{ currentFaction }}</span>
							<font-awesome-icon class="my-auto" icon="fa-solid fa-angles-right" @click="switchFaction('next')" />
						</div>
					</div>
					<div id="addbutton" class="col-12 col-md-12 offset-lg-2 col-lg-4 offset-xl-2 col-xl-4 addbutton position-relative" @click="addShip()">
						<div class="button-default button-slanted bg-secondary text-white fw-bold pointer">
							<span class="button-slanted-content"><font-awesome-icon icon="fa-solid fa-plus" /> Add Ship</span>
						</div>
					</div>
				</div>
				<!-- -------------------------------------  List of unsorted ships ------------------------------------- -->
				<div class="row">
					<div class="col-12 col-md-12 col-lg-12 col-xl-12">
						<div id="shiplist" class="shiplist d-flex gap-2">
							<div class="ship" v-for="ship in ships" draggable @dragstart="startDrag($event, ship, 'ships')"
								v-bind:id="ship.id">
								<font-awesome-icon icon="fa-solid fa-pen" class="editShip" @click="editShip(ship)" />
								<font-awesome-icon icon="fa-solid fa-trash" class="deleteShip" @click="deleteShip(ship)" />
								<div v-bind:class="'d-flex flex-column position-relative shipborder ' + ship.rarity">
									<img v-bind:src="'/getimagedata/ships/' + ship.image" class="shipimage">
									<span class="shipname">{{ ship.name }}</span>
									<img v-bind:src="'/getimagedata/shiptypes/' + ship.type + '.png'" class="position-absolute shipicon">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- ------------------------------------- tier list with draggable items  ------------------------------------- -->
			<div class="col-12 col-md-12 offset-lg-1 col-lg-10 offset-xl-1 col-xl-10">
				<div class="tiers bg-dark">
					<div class="d-flex flex-row w-100 my-1" v-for="tier in tieritems"
						v-bind:style="'background-color:' + tier.color">
						<!-- tier name -->
						<div class="tiername text-white position-relative">
							<p class="position-absolute">{{ tier.name }}</p>
						</div>
						<!-- tier data -->
						<div class="w-100 flex-wrap tierdata drop-zone d-flex gap-1" @drop="onDrop($event, tier.name)"
							@dragover.prevent @dragenter.prevent>
							<div class="ship" v-for="ship in tier.items" draggable
								@dragstart="startDrag($event, ship, tier.name)" v-bind:id="ship.id">
								<div v-bind:class="'d-flex flex-column position-relative shipborder ' + ship.rarity">
									<font-awesome-icon icon="fa-solid fa-pen" class="editShip" @click="editShip(ship)" />
									<font-awesome-icon icon="fa-solid fa-trash" class="deleteShip" @click="deleteShip(ship)" />
									<img v-bind:src="'/getimagedata/ships/' + ship.image" class="shipimage">
									<span class="shipname">{{ ship.name }}</span>
									<img v-bind:src="'/getimagedata/shiptypes/' + ship.type + '.png'" class="position-absolute shipicon">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- submit -->
			<div class="col-12 col-md-12 offset-lg-1 col-lg-10 offset-xl-1 col-xl-10">
				<button class="btn button btn-primary w-100" @click="saveTierList()"><font-awesome-icon icon="fa-solid fa-floppy-disk" /> Save order</button>
			</div>
			<!-- ------------------------------------- ship adding popup ------------------------------------- -->
			<div class="col-12">
				<div class="popup" id="addship" v-if="showAddship">
					<!-- title -->
					<div class="row">
						<div class="col-12">
							<h2 class="text-center text-primary" v-if="this.editShipID == 0">Add ship</h2>
							<h2 class="text-center text-primary" v-else>Edit ship</h2>
							<font-awesome-icon icon="fa-solid fa-xmark" class="position-absolute closepopup text-primary fs-1" @click="this.showAddship = false" />
						</div>
					</div>
					<!-- inputs -->
					<img v-if="this.newShip.image.includes('base64')" v-bind:src="this.newShip.image" class="newshipimage">
					<img v-else v-bind:src="'/getimagedata/ships/' + this.newShip.image" class="newshipimage">
					<div class="row">
						<div class="col-6">
							<input type="file" id="image" class="form-control mb-2" @change="onFileChanged($event)" accept="image/*" placeholder="shipimage">
						</div>
						<div class="col-6">
							<input type="text" id="name" class="form-control mb-2" v-model="newShip.name" placeholder="Ship name">
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
						<div class="col-12">
							<textarea id="note" class="form-control h-100" v-model="newShip.note" placeholder="Note"></textarea>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<button class="btn button btn-primary w-100 mt-2" v-if="this.editShipID == 0" @click="addShipSubmit()"><font-awesome-icon icon="fa-solid fa-plus" /> Add ship</button>
							<button class="btn button btn-primary w-100 mt-2" v-else @click="editShipSubmit()"><font-awesome-icon icon="fa-solid fa-pen" /> Edit ship</button>
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
			// tierlist 
			drag: false,
			tieritems: {
				"S": { items: [], name: "S", color: "#c89afd" },
				"A": { items: [], name: "A", color: "#0085b5" },
				"B": { items: [], name: "B", color: "#60dbe8" },
				"C": { items: [], name: "C", color: "#8bd346" },
				"D": { items: [], name: "D", color: "#f9a52c" },
				"E": { items: [], name: "E", color: "#b85042" },
				"F": { items: [], name: "F", color: "#671b11" },
			},
			// ships list
			ships: {},
			// toggle add ship popup 
			showAddship: false,
			editShipID: 0,
			// add ship select data
			shipRarity: ['Common', 'Rare', 'Elite', 'Super rare', 'Ultra'],
			shipType: ['DD', 'CL', 'CA', 'BB', 'CB', 'CV', 'AR', 'SS', 'Misc'],
			shipFaction: ["Iris Libre", "Vichya Dominion","Eagle Union", "Royal Navy", "Sakura Empire", "Iron Blood", "Dragon Empery", "Northern Parliament", "Sardegna Empire"],
			factions: ["Iris Orthodoxy", "Eagle Union", "Royal Navy", "Sakura Empire", "Iron Blood", "Dragon Empery", "Northern Parliament", "Sardegna Empire"],
			currentFaction: "Iris Orthodoxy",
			// new ship data 
			newShip: {
				image: "",
				name: "",
				type: "",
				rarity: "",
				faction: "",
				note: "",
			},
		}
	},

	created() {
		// get all ships from the database
		this.getShips();
	},

	methods: {
		switchFaction(direction) {
			let CurrentIndex = this.factions.indexOf(this.currentFaction);
			let newIndex;
			if (direction == "next") {
				newIndex = CurrentIndex + 1;
				newIndex = newIndex > (this.factions.length - 1) ? 0 : newIndex;
			} else { //prev
				newIndex = CurrentIndex - 1
				newIndex = newIndex < 0 ? (this.factions.length - 1) : newIndex;
			}
			this.currentFaction = this.factions[newIndex];
			this.getShips();
		},

		startDrag(evt, item, originList) {
			evt.dataTransfer.dropEffect = 'move';
			evt.dataTransfer.effectAllowed = 'move';
			evt.dataTransfer.setData('shipID', item.id);
			evt.dataTransfer.setData('origin', originList);
		},

		onDrop(evt, targetList) {
			const shipID = evt.dataTransfer.getData('shipID');
			const originList = evt.dataTransfer.getData('origin');
			// Find correct ship
			if (originList == "ships") {
				// move ship from top row to ranked row
				const shipIndex = this.ships.findIndex((x) => x.id == shipID);
				this.tieritems[targetList].items.push(this.ships[shipIndex]);
				// delete ship from top row
				this.ships.splice(shipIndex, 1);
			} else {
				// move ship from ranked row to an other ranked row
				const originIndex = this.tieritems[originList].items.findIndex((x) => x.id == shipID);
				this.tieritems[targetList].items.push(this.tieritems[originList].items[originIndex]);
				// delete ship from original ranked row
				this.tieritems[originList].items.splice(originIndex, 1);
			}
		},

		addShip: function () {
			this.refreshInputs();
			this.showAddship = true;
		},

		addShipSubmit: function () {
			if (this.newShip.image !== "" && this.newShip.name !== "" && this.newShip.type !== "" && this.newShip.rarity !== "" && this.newShip.faction !== "") {
				//ajax
				this.axios.post("/addship", this.newShip).then(response => {
					if (response['data']['bool'] == "true") {
						this.$notify({ text: response['data']['message'], type: 'success', duration: 3000 });
						this.getShips();
						this.showAddship = false;
					} else {
						this.$notify({ text: response['data']['message'], type: 'error', duration: 3000 });
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

		// Delete ship from database
		deleteShip: function (ship) {
			if (ship !== undefined && ship !== null && ship.id != "0") {
				this.$swal({
					title: "Deletion",
					text: "Are you sure You want to delete: " + ship.name + "?",
					type: "warning",
					showCancelButton: true,
					cancelButtonText: "No, wait!",
					confirmButtonColor: "#a10000",
					confirmButtonText: "Yes, Delete it!"
				}).then((result) => {
					if (result.value) {
						this.axios.post("/deleteship", ship).then(response => {
							if (response['data']['bool'] == "true") {
								this.$notify({ text: response['data']['message'], type: 'success', duration: 3000 });
								this.getShips();
							} else {
								this.$notify({ text: response['data']['message'], type: 'error', duration: 3000 });
							}
						});
					}
				});
			}
		},

		saveTierList: function () {
			this.axios.post("/saveshiptierlist", this.tieritems).then(response => {
				if (response['data']['bool'] == "true") {
					this.$notify({ text: response['data']['message'], type: 'success', duration: 3000 });
				} else {
					this.$notify({ text: response['data']['message'], type: 'error', duration: 3000 });
				}
			});
		},

		// Edit ship in database
		editShip: function (ship) {
			if (ship !== undefined && ship !== null && ship.id != "0") {
				this.refreshInputs();
				this.showAddship = true;
				this.editShipID = ship.id;
				ship.Performace = JSON.parse(ship.Performace);
				this.newShip = Object.assign({}, ship);
			}
		},

		editShipSubmit: function () {
			if (this.newShip.image !== "" && this.newShip.name !== "" && this.newShip.type !== "" && this.newShip.rarity !== "" && this.newShip.faction !== "") {
				//ajax
				this.axios.post("/editship", this.newShip).then(response => {
					if (response['data']['bool'] == "true") {
						this.$notify({ text: response['data']['message'], type: 'success', duration: 3000 });
						this.getShips();
						this.showAddship = false;
					} else {
						this.$notify({ text: response['data']['message'], type: 'error', duration: 3000 });
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
		},

		getShips: function () {
			this.axios.post("/getships", { "faction": this.currentFaction }).then(response => {
				// fill all tiers with the correct ships
				this.ships = response['data']['ships']['notier'];
				this.tieritems["S"]['items'] = response['data']['ships']["S"] !== undefined ? response['data']['ships']["S"] : [];
				this.tieritems["A"]['items'] = response['data']['ships']["A"] !== undefined ? response['data']['ships']["A"] : [];
				this.tieritems["B"]['items'] = response['data']['ships']["B"] !== undefined ? response['data']['ships']["B"] : [];
				this.tieritems["C"]['items'] = response['data']['ships']["C"] !== undefined ? response['data']['ships']["C"] : [];
				this.tieritems["D"]['items'] = response['data']['ships']["D"] !== undefined ? response['data']['ships']["D"] : [];
				this.tieritems["E"]['items'] = response['data']['ships']["E"] !== undefined ? response['data']['ships']["E"] : [];
				this.tieritems["F"]['items'] = response['data']['ships']["F"] !== undefined ? response['data']['ships']["F"] : [];
			});
		},

		refreshInputs: function () {
			this.editShipID = 0;
			this.newShip = {
				image: "",
				name: "",
				type: "",
				rarity: this.newShip.rarity,
				faction: this.newShip.faction,
				note: "",
			}
		}
	}
}
</script>