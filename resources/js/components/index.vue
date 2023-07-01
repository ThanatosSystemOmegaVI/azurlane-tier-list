<template>
	<section class="p-4">
		<div class="row">
			<!-- ------------------------------------- TITLE ------------------------------------- -->
			<div class="col-12 col-md-12 offset-lg-1 col-lg-10 offset-xl-1 col-xl-10">
				<div class="row align-items-center">
					<div class="col-12 col-md-12 col-lg-6 col-xl-6"><img src="../../img/azurlanelogo.webp" class="img-fluid" alt="azurlanelogo"></div>
					<div class="col-12 col-md-12 col-lg-6 col-xl-6"><img src="../../img/tierlisttext.webp" class="img-fluid" alt="tierlisttext"></div>
				</div>
			</div>

			<div class="col-12 col-md-12 offset-lg-1 col-lg-10 offset-xl-1 col-xl-10">
				<div id="addbutton" class="col-12 col-md-12 col-lg-12 col-xl-12 addbutton position-relative">
					<div class="button-default bg-secondary text-white fw-bold pointer d-flex justify-content-between w-100 m-0">
						<font-awesome-icon class="my-auto" icon="fa-solid fa-angles-left" @click="switchFaction('prev')" />
						<span> {{ currentFaction }}</span>
						<font-awesome-icon class="my-auto" icon="fa-solid fa-angles-right" @click="switchFaction('next')" />
					</div>
				</div>
			</div>
			<!-- ------------------------------------- TIERLIST ------------------------------------- -->
			<div class="col-12 col-md-12 offset-lg-1 col-lg-10 offset-xl-1 col-xl-10">
				<div class="tiers bg-dark shadow-sm">
					<div class="d-flex flex-row w-100 my-1" v-for="tier in tieritems"
						v-bind:style="'background-color:' + tier.color">
						<!-- tier name -->
						<div class="tiername text-white position-relative">
							<p class="position-absolute">{{ tier.name }}</p>
						</div>
						<!-- tier data -->
						<div class="w-100 flex-wrap tierdata drop-zone d-flex gap-1">
							<div class="ship shiphomepage" v-for="ship in tier.items" @click="showShipData(ship)">
								<div v-bind:class="'d-flex flex-column position-relative shipborder ' + ship.rarity">
									<img v-bind:src="'/getimagedata/ships/' + ship.image" class="shipimage">
									<span class="shipname">{{ ship.name }}</span>
									<img v-bind:src="'/getimagedata/shiptypes/' + ship.type + '.png'"
										class="position-absolute shipicon">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- ------------------------------------- POPUP ------------------------------------- -->
			<div class="col-12">
				<div class="popup" id="showship" v-if="showShip" v-bind:class="'bg '+this.shipData.rarity">
					<div class="row">
						<div class="col-12 col-md-12 col-lg-12 col-xl-12 position-relative">
							<h1 class="w-100 bg-secondary text-white text-center">{{ this.shipData.name }}</h1>
							<font-awesome-icon icon="fa-solid fa-xmark" class="position-absolute closepopup fs-1 text-white" @click="this.showShip = false" />
						</div>
					</div>
					<div class="row">
						<div class="col-12 col-md-12 col-lg-12 col-xl-12">
							<div class="d-flex flex-row gap-2 justify-content-between">
								<img v-bind:src="'/getimagedata/ships/' + this.shipData.image" class="showshipimage rounded-3">
								<p class="shipnote m-0 mt-2 rounded-1 bg-white">{{ this.shipData.note }}</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</template>

<script>
import '../../css/ships.css';
import '../../css/index.css';
export default {
	name: "index",
	data() {
		return {
			tieritems: {
				"S": { items: [], name: "S", color: "#c89afd " },
				"A": { items: [], name: "A", color: "#0085b5 " },
				"B": { items: [], name: "B", color: "#60dbe8 " },
				"C": { items: [], name: "C", color: "#8bd346 " },
				"D": { items: [], name: "D", color: "#f9a52c " },
				"E": { items: [], name: "E", color: "#b85042 " },
				"F": { items: [], name: "F", color: "#671b11 " },
			},
			shipData: {},
			showShip: false,
			factions: ["Iris Orthodoxy", "Eagle Union", "Royal Navy", "Sakura Empire", "Iron Blood", "Dragon Empery", "Northern Parliament", "Sardegna Empire"],
			currentFaction: "Iris Orthodoxy",
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

		showShipData: function (ship) {
			this.shipData = Object.assign({}, ship);
			this.shipData.Performace = JSON.parse(this.shipData.Performace);
			this.showShip = true;
		},
	}
}

</script>