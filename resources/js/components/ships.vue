<template>
    <div class="row">
		<!-- Title -->
        <div class="col-12">
			<h1 class="text-center bg-primary text-white">tier list ships</h1>
		</div>

		<!-- List of unsorted ships + add ship button -->
		<div class="col-12">
			<div class="row">
				 <!-- list of ships -->
				<div class="col-xl-11">
					<div class="shiplist border border-primary rounded-1"></div>
				</div>
				 <!-- add ship button -->
				<div class="col-xl-1">
					<div class="addbutton border border-primary rounded-1 bg-primary text-warning">
						<font-awesome-icon icon="fa-solid fa-plus" />
					</div>
				</div>
			</div>
		</div>

		<!-- tier list with draggable items -->
		<div class="col-12"></div>

		<!-- ship adding popup -->
		<div class="col-12">
			<div class="popup" id="addship">
				<!-- title -->
				<div class="row">
					<div class="col-12">
						<h2 class="text-center bg-primary text-white">Add ship</h2>
					</div>
				</div>
				<!-- inputs -->
				<div class="row">
					<div class="col-6">
						<input type="file" id="image" class="form-control mb-2" placeholder="shipname">
					</div>
					<div class="col-6">
						<input type="text" id="name" class="form-control mb-2" placeholder="shipname">
					</div>
				</div>
				<div class="row">
					<div class="col-6">
						<select id="type" class="form-control mb-2">
							<option value="">Select a ship type</option>
						</select>
					</div>
					<div class="col-6">
						<select id="rarity" class="form-control mb-2">
							<option value="">Select a ship rarity</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<select id="faction" class="form-control mb-2">
							<option value="">Select a ship faction</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="row">
							<div class="col-6">
								<input type="text" id="FP" class="form-control mb-1" placeholder="FP">
								<input type="text" id="HP" class="form-control mb-1" placeholder="HP">
								<input type="text" id="AA" class="form-control" placeholder="AA">
							</div>
							<div class="col-6">
								<input type="text" id="SP" class="form-control mb-1" placeholder="SP">
								<input type="text" id="AVI" class="form-control mb-1" placeholder="AVI">
								<input type="text" id="TRP" class="form-control" placeholder="TRP">
							</div>
						</div>
					</div>
					<div class="col-6">
						<textarea id="note" class="form-control h-100" placeholder="Note"></textarea>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<button class="btn button btn-primary w-100 mt-2">Add ship</button>
					</div>
				</div>
			</div>
		</div>
    </div>
</template>

<script>
import '../../css/ships.css';
export default {
    name: "ships",
    data() {
        return {
            //
        }
    },
    created() {
    },
    methods: {}
}
</script>