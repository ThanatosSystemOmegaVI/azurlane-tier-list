<?php

namespace App\Http\Controllers;

use App\Models\Ships;
use Illuminate\Http\Request;

$path = getcwd() . "/../azurlanetierlist/data";

class ShipsController extends Controller
{
    public function getImage(Request $request, $type, $name)
    {
        $file = file_get_contents("$path/$type/$name");
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
                    $ships[$ship['tier']][] = $ship;
                } else {
                    $ships['notier'][] = $ship;
                }
            }
        }

        return response()->json([
            'ships' => $ships,
        ]);

    }

    public function addShip(Request $request)
    {
        $name = $request->filled('name') ? $request->get('name') : "";
        $type = $request->filled('type') ? $request->get('type') : "";
        $rarity = $request->filled('rarity') ? $request->get('rarity') : "";
        $faction = $request->filled('faction') ? $request->get('faction') : "";
        $performace = $request->filled('Performace') ? $request->get('Performace') : [];
        $note = $request->filled('note') ? $request->get('note') : "";
        $image = $request->filled('image') ? $request->get('image') : "";

        if (!empty($name) && !empty($type) && !empty($rarity) && !empty($faction)) {
            if (!Ships::where('name', $name)->exists()) {
                // save image
                $filename = date("ymd") . rand(1111, 9999);
                $extension = str_replace("data:image/", "", explode(";base64,", $image)[0]);
                $file = base64_decode(explode(";base64,", $image)[1]);
                if (!is_dir($$path)) {
                    mkdir($path);
                }
                if (!is_dir("$path/ships/")) {
                    mkdir("$path/ships");
                }
                file_put_contents("$path/ships/$filename.$extension", $file);

                // insert ship
                $insert = Ships::insert([
                    "image" => "$filename.$extension", "name" => $name, "type" => $type, "rarity" => $rarity,
                    "faction" => $faction, "Performace" => json_encode($performace), "note" => $note,
                ]);
                if ($insert) {
                    return response()->json([
                        'bool' => "true",
                        'message' => "Ship $name Successfully added",
                    ]);
                } else {
                    return response()->json([
                        'bool' => "false",
                        'message' => "something went wriong when adding $name",
                    ]);
                }
            } else {
                // already exists
                return response()->json([
                    'bool' => "false",
                    'message' => "There already exists a ship with the name $name",
                ]);
            }
        } else {
            // missing fields
            return response()->json([
                'bool' => "false",
                'message' => "Name, Type, Rartity & Faction fields are mandatory",
            ]);
        }
    }

    public function editShip(Request $request)
    {
        $id = $request->filled('id') ? $request->get('id') : 0;
        $name = $request->filled('name') ? $request->get('name') : "";
        $type = $request->filled('type') ? $request->get('type') : "";
        $rarity = $request->filled('rarity') ? $request->get('rarity') : "";
        $faction = $request->filled('faction') ? $request->get('faction') : "";
        $performace = $request->filled('Performace') ? $request->get('Performace') : [];
        $note = $request->filled('note') ? $request->get('note') : "";
        $image = $request->filled('image') ? $request->get('image') : "";

        if (!empty($id)) {
            if (!empty($name) && !empty($type) && !empty($rarity) && !empty($faction)) {
                // check if a new image has been uploaded
                if (strpos($image, ";base64,") !== false) {
                    // save new image
                    $filename = date("ymd") . rand(1111, 9999);
                    $extension = str_replace("data:image/", "", explode(";base64,", $image)[0]);
                    $file = base64_decode(explode(";base64,", $image)[1]);

                    if (!is_dir($path)) {
                        mkdir($path);
                    }
                    if (!is_dir("$path/ships/")) {
                        mkdir("$path/ships");
                    }

                    file_put_contents("$path/ships/$filename.$extension", $file);
                    $filename .= "." . $extension;

                    // remove old image
                    $currentShip = Ships::where("id", $id)->first()->toArray();
                    if (file_exists("$path/ships/" . $currentShip['image'])) {
                        unlink("$path/ships/" . $currentShip['image']);
                    }
                } else {
                    $filename = $image;
                }

                // update ship data
                $update = Ships::where("id", $id)->update([
                    "image" => $filename, "name" => $name, "type" => $type, "rarity" => $rarity,
                    "faction" => $faction, "Performace" => json_encode($performace), "note" => $note,
                ]);

                if ($update) {
                    return response()->json([
                        'bool' => "true",
                        'message' => "Ship $name Successfully updated",
                    ]);
                } else {
                    return response()->json([
                        'bool' => "false",
                        'message' => "something went wrong when updating: $name",
                    ]);
                }

            } else {
                return response()->json([
                    'bool' => "false",
                    'message' => "Name, Type, Rartity & Faction fields are mandatory",
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
        $id = $request->filled('id') ? $request->get('id') : 0;
        $name = $request->filled('name') ? $request->get('name') : "";

        if (!empty($id)) {
            // remove old image
            $currentShip = Ships::where("id", $id)->first();
            if (!empty($currentShip)) {
                $currentShip = $currentShip->toArray();
                if (file_exists("$path/ships/" . $currentShip['image'])) {
                    unlink("$path/ships/" . $currentShip['image']);
                }
            }

            $delete = Ships::where("id", $id)->delete();
            if ($delete) {
                return response()->json([
                    'bool' => "true",
                    'message' => "Ship $name Successfully deleted",
                ]);
            } else {
                return response()->json([
                    'bool' => "false",
                    'message' => "something went wrong when deleting: $name",
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
