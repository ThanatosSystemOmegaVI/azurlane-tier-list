<?php

namespace App\Http\Controllers;

use App\Models\Ships;
use Illuminate\Http\Request;

class ShipsController extends Controller
{
    public function getImage(Request $request, $type, $name)
    {
        $file = file_get_contents("../data/$type/$name");
        $filetype = explode(".", $name)[1];
        header('Pragma: public');
        header('Cache-Control: max-age=86400');
        header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 86400));
        header("Content-Type: image/$filetype");
        header("Content-type: image/$filetype");
        echo $file;
        die;
    }

    public function getShips(Request $request)
    {
        $AllShips = Ships::orderBy('rarity')->orderBy('name')->get();
        $ships = [];
        if (!empty($AllShips)) {
            $AllShips = $AllShips->toArray();
            foreach ($AllShips as $ship) {
                if (!empty($ship['tier']) && $ship['tier'] !== "null") {
					$ships[$ship['tier']][]=$ship;
                }else{
					$ships['notier'][]=$ship;
				}
            }
        }

        return response()->json([
            'ships' => $ships,
        ]);

    }

    public function addShip(Request $request)
    {
        $_POST = $request->all();
        if (!Ships::where('name', $_POST['name'])->exists()) {
            // vars
            $note = !empty($_POST['note']) ? $_POST['note'] : "";

            // save image
            $filename = date("ymd") . rand(1111, 9999);
            $extension = str_replace("data:image/", "", explode(";base64,", $_POST['image'])[0]);
            $file = base64_decode(explode(";base64,", $_POST['image'])[1]);
            if (!is_dir("../data/")) {
                mkdir("../data");
            }
            if (!is_dir("../data/ships/")) {
                mkdir("../data/ships");
            }
            file_put_contents("../data/ships/$filename.$extension", $file);

            // insert ship
            $insert = Ships::insert([
                "image" => $filename . "." . $extension, "name" => $_POST['name'], "type" => $_POST['type'], "rarity" => $_POST['rarity'],
                "faction" => $_POST['faction'], "Performace" => json_encode($_POST['Performace']), "note" => $note,
            ]);
            if ($insert) {
                return response()->json([
                    'bool' => "true",
                    'message' => "Ship " . $_POST['name'] . " Successfully added",
                ]);
            } else {
                return response()->json([
                    'bool' => "false",
                    'message' => "something went wriong when adding " . $_POST['name'],
                ]);
            }
        } else {
            // already exists
            return response()->json([
                'bool' => "false",
                'message' => "There already exists a ship with the name " . $_POST['name'],
            ]);
        }
    }

    public function editShip(Request $request)
    {
        $_POST = $request->all();

        $note = !empty($_POST['note']) ? $_POST['note'] : "";

        if (!empty($_POST['id'])) {
            // check if a new image has been uploaded
            if (strpos($_POST['image'], ";base64,") !== false) {
                // save new image
                $filename = date("ymd") . rand(1111, 9999);
                $extension = str_replace("data:image/", "", explode(";base64,", $_POST['image'])[0]);
                $file = base64_decode(explode(";base64,", $_POST['image'])[1]);

                if (!is_dir("../data/")) {
                    mkdir("../data");
                }
                if (!is_dir("../data/ships/")) {
                    mkdir("../data/ships");
                }

                file_put_contents("../data/ships/$filename.$extension", $file);
                $filename .= "." . $extension;

                // remove old image
                $currentShip = Ships::where("id", $_POST['id'])->first()->toArray();
                if (file_exists("../data/ships/" . $currentShip['image'])) {
                    unlink("../data/ships/" . $currentShip['image']);
                }
            } else {
                $filename = $_POST['image'];
            }

            // update ship data
            $update = Ships::where("id", $_POST['id'])->update([
                "image" => $filename, "name" => $_POST['name'], "type" => $_POST['type'], "rarity" => $_POST['rarity'],
                "faction" => $_POST['faction'], "Performace" => json_encode($_POST['Performace']), "note" => $note,
            ]);

            if ($update) {
                return response()->json([
                    'bool' => "true",
                    'message' => "Ship " . $_POST['name'] . " Successfully updated",
                ]);
            } else {
                return response()->json([
                    'bool' => "false",
                    'message' => "something went wrong when updating: " . $_POST['name'],
                ]);
            }

        } else {
            return response()->json([
                'bool' => "false",
                'message' => "No ship found",
            ]);
        }
    }

    public function saveShipTierList(Request $request)
    {
        $tierlist = $request->all();

        $update = true;
        foreach ($tierlist as $rank) {
            foreach ($rank['items'] as $ship) {
                // update ship ranking
                $update &= Ships::where("id", $ship['id'])->update(["tier" => $rank['name']]);
            }
        }

        if ($update) {
            return response()->json([
                'bool' => "true",
                'message' => "Tier list succesfully updated",
            ]);
        } else {
            return response()->json([
                'bool' => "false",
                'message' => "Something went wrong when updating the tierlist",
            ]);
        }
    }

    public function deleteShip(Request $request)
    {
        $_POST = $request->all();
        if (!empty($_POST['id'])) {
            // remove old image
            $currentShip = Ships::where("id", $_POST['id'])->first()->toArray();
            if (file_exists("../data/ships/" . $currentShip['image'])) {
                unlink("../data/ships/" . $currentShip['image']);
            }

            $delete = Ships::where("id", $_POST['id'])->delete();
            if ($delete) {
                return response()->json([
                    'bool' => "true",
                    'message' => "Ship " . $_POST['name'] . " Successfully deleted",
                ]);
            } else {
                return response()->json([
                    'bool' => "false",
                    'message' => "something went wrong when deleting: " . $_POST['name'],
                ]);
            }
        } else {
            return response()->json([
                'bool' => "false",
                'message' => "No ship found",
            ]);
        }
    }

}
