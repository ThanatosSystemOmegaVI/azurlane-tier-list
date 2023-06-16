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
        header("Content-type: image/$filetype");
        echo $file;
        die;
    }

    public function getships(Request $request)
    {
        $ships = Ships::orderBy('rarity')->orderBy('name')->get();
        if (!empty($ships)) {
            $ships = $ships->toArray();
        } else {
            $ships = [];
        }

        return response()->json([
            'ships' => $ships,
        ]);

    }

    public function addship(Request $request)
    {
        $_POST = $request->all();
        if (!Ships::where('name', $_POST['name'])->exists()) {
            // vars
            $note = !empty($_POST['note']) ? $_POST['note'] : "";

            // save image locally
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
                "faction" => $_POST['faction'], "Performace" => json_encode($_POST['stats']), "note" => $note,
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
}
