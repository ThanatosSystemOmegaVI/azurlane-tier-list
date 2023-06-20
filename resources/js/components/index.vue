<template>
	<section class="p-4">
		<div class="row">
			<!-- TITLE -->
			<div class="col-12 col-md-12 offset-lg-1 col-lg-10 offset-xl-1 col-xl-10">
				<div class="row align-items-center">
					<div class="col-12 col-md-12 col-lg-6 col-xl-6"><img src="../../img/azurlanelogo.webp" class="img-fluid" alt="azurlanelogo"></div>
					<div class="col-12 col-md-12 col-lg-6 col-xl-6"><img src="../../img/tierlisttext.webp" class="img-fluid" alt="tierlisttext"></div>
				</div>
			</div>
			<div class="col-12 col-md-12 offset-lg-1 col-lg-10 offset-xl-1 col-xl-10">

			</div>
			<!-- TIERLIST -->
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
							<div class="ship shiphomepage" v-for="ship in tier.items">
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
		</div>
	</section>
</template>

<script>
import '../../css/ships.css';
import '../../css/index.css';
export default {
	name: "Home",
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
		}
	},
	created() {
		// get all ships from the database
		this.getShips();
	},
	methods: {
		getShips: function () {
			this.axios.post("/getships", {}).then(response => {
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
	}
}
</script>