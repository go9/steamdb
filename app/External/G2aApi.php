<?php
/**
 * Created by PhpStorm.
 * User: johnorlando
 * Date: 7/27/2017
 * Time: 10:06 PM
 */

namespace App\External;

use App\G2a;
use App\Store;

class G2aApi{

    public $store = null;

    protected $urls = [
        "search" => "http://www.g2a.com/lucene/search/quick?phrase=",
        "price" => "https://www.g2a.com/marketplace/product/auctions/?id="
    ];

    public function getStore(){
        if($this->store == null){
            $this->store = Store::firstOrCreate(["name" => "G2a"]);
        }
        return $this->store;
    }

    public function searchByPhrase($phrase){
        // Format the phrase

        // Remove special characters and make lowercase
        $searchTerm = trim(strtolower(preg_replace("/[^-a-zA-Z0-9_': ]/", '', $phrase)));

        // Make sure the phrase isn't empty
        if($searchTerm == null || $phrase == ""){
            return false;
        }

        // Get JSON
        $url = $this->urls["search"] . urlencode($searchTerm);
        $json = json_decode(file_get_contents($url), true);

        // Prepare result

        $results = ["results" => $json["docs"], "input" => $phrase];
        foreach ($results["results"] as $key => $result) {
            if (!isset($result["name"])) {
                continue;
            }
            if ($this->checkIfPerfectMatch($phrase, $result['name'])) {
                $results["perfect_match"] = $result;
            }

            // Always save all G2A's
            $this->saveG2a($result);
        }
        return $results;
    }

    public function fetchPrice($gid){
        // Get data from G2a
        $url = $this->urls["price"] . $gid;
        $content = file_get_contents($url);
        $json = json_decode($content, true);

        // Insert new price into database
        if(isset($json['a'])){
            // There is pricing data
            foreach ($json['a'] as $chunk) {
                // Just want the newest price.
                break;
            }
        }
        else{
            return false;
        }

        // Return the new price
        return $chunk["p"];
    }

    public function saveG2a($result){
        $g2a = G2a::find($result["id"]);
        if($g2a == null){
            $g2a = new G2a();
            $g2a->id = $result["id"];
            $g2a->name = $result['name'];
            $g2a->slug = $result['slug'];
            if (array_key_exists('thumbnail', $result)) {
                $g2a->thumbnail = $result['thumbnail'];
            }
            if (array_key_exists('smallImage', $result)) {
                $g2a->smallImage = $result['smallImage'];
            }
            if (array_key_exists('bigSearchImage', $result)) {
                $g2a->bigSearchImage = $result['bigSearchImage'];
            }
            $g2a->save();
        }
        return $g2a;
    }

    public function checkIfPerfectMatch($userPhrase, $g2aPhrase){
        // Make lowercase, trim, and get rid of all special chars
        $g2aPhrase = trim(strtolower(preg_replace("/[^a-zA-Z0-9 ]/", '', $g2aPhrase)));
        $userPhrase = trim(strtolower(preg_replace("/[^a-zA-Z0-9 ]/", '', $userPhrase)));

        //take the game's name out of it
        $remainder = trim(str_replace($userPhrase, "", $g2aPhrase));
        //take "STEAM CD-KEY GLOBAL: out. it's cdkey and not cd-key because we removed special characters
        $remainder = trim(str_replace("steam cdkey global", "", $remainder));
        //take "STEAM CD-KEY GLOBAL: out. it's cdkey and not cd-key because we removed special characters
        $remainder = trim(str_replace("steam key global", "", $remainder));
        // Remove "EARLY ACCESS"
        $remainder = trim(str_replace("early access", "", $remainder));

        // If there's nothing left, it's the right game.
        return  $remainder == "" ? true : false;
    }
}