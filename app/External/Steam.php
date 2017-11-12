<?php
/**
 * Created by PhpStorm.
 * User: johnorlando
 * Date: 7/27/2017
 * Time: 10:06 PM
 */

namespace App\External;

use App\Store;

class Steam
{
    protected $key;
    protected $store = null;
    protected $applist = null;

    protected $urls = [
        "base" => "http://api.steampowered.com",
        "applist" => "http://api.steampowered.com/ISteamApps/GetAppList/v0002/",
        "app" => "http://store.steampowered.com/api/appdetails/?appids=",
        "appPriceFilters" => "&cc=us&filters=price_overview",
        "package" => "http://store.steampowered.com/api/packagedetails/?packageids="
    ];

    public function __construct($key)
    {
        // Get key
        $this->key = $key;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getStore()
    {
        // Check if we already have the store
        if ($this->store == null) {
            //Get store from DB
            if (!$this->store = Store::where("name", "Steam")->first()) {
                $store = new Store;
                $store->name = "Steam";
                $store->save();
                $this->store = $store;
            }
        }
        return $this->store;
    }

    public function getApplist()
    {
        // Check if we already have the applist
        if ($this->applist == null) {
            $this->applist = json_decode(file_get_contents($this->urls['applist']), true)["applist"]["apps"];
        }
        return $this->applist;
    }

    public function parseData($id, $type = "app")
    {

        // Validate the appid
        if (!is_numeric($id)) {
            return ["success" => false, "code" => 1, "message" => "Invalid appid"];
        }

        // Get the data from Steam
        if (!$content = file_get_contents($this->urls[$type] . $id)) {
            return ["success" => false, "code" => 2, "message" => "Steam blocked the call."];
        }
        $json = json_decode($content, true);

        // Remove the key from the data
        $json = reset($json);

        // Check if steam had the info for the appid we provided.
        if (!isset($json["success"])) {
            return ["success" => false, "code" => 3, "message" => "There was an error parsing the JSON from Steam."];
        }

        if ($json["success"] == false) {
            return ["success" => false, "code" => 4, "message" => "Steam didn't have any data for this appid"];
        }

        if ($json["success"] == true) {
            // Set the Steam id
            if (!isset($json["data"]["steam_id"])) {
                $json["data"]["steam_id"] = $id;
            }

            // Convert the languages to array
            if (isset($json["data"]["supported_languages"])) {
                $json["data"]["supported_languages"] = $this->getLanguagesAsArray($json["data"]["supported_languages"]);
            }
        }

        return $json;
    }

    public function parseAppData($id)
    {
        return $this->parseData($id);
    }

    public function parsePackageData($id)
    {
        return $this->parseData($id, "package");
    }

    public function getLanguagesAsArray($languages)
    {
        /**
         * Explode language string into array
         * Case: Normal
         * Input:
         *      English,Spanish,German
         * Resolution: Explode by ','
         *
         * Case:
         * Input:
         * Resolution:
         *
         * Case: System calls for new lines
         * Input:
         *      English\r\n
         *      Spanish\r\n
         *      German\r\n
         * Resolution: Replace all instances of '\r' and '\n' with a marker, '~!~', and explode by that marker.
         */

        $result = [];

        foreach (explode(",", preg_replace(array('/\n/', '/\r/'), '~!~', $languages)) as $lang) {
            foreach (explode('~!~', $lang) as $chunk) {
                foreach ($this->cleanLang($chunk) as $bit) {
                    if (in_array($bit, $result) || $bit == "") {
                        continue;
                    }
                    $result[] = $bit;
                }
            }
        }
        return $result;
    }

    public function cleanLang($chunk)
    {
        $result = [];

        $chunk = (explode("<", $chunk)[0]);
        $chunk = (explode('(', $chunk)[0]);
        $chunk = (explode('-', $chunk)[0]);
        $chunk = (explode(';', $chunk)[0]);
        $chunk = (explode('[', $chunk)[0]);
        $chunk = trim($chunk);

        // special cases
        if ($chunk == "English Dutch  English") {
            $result[] = "English";
            $result[] = "Dutch";
        } else if ($chunk == "#lang_français" || $chunk == "французский" || $chunk == "Francês") {
            $result[] = "French";
        } else if ($chunk == "Inglês" || $chunk == "английский") {
            $result[] = "English";
        } else if ($chunk == "русский" || $chunk == "Russo") {
            $result[] = "Russian";
        } else if ($chunk == "немецкий" || $chunk == "Alemão") {
            $result[] = "German";
        } else if ($chunk == "испанский" || $chunk == "Espanhol") {
            $result[] = "Spanish";
        } else if ($chunk == "чешский") {
            $result[] = "Czech";
        } else if ($chunk == "японский") {
            $result[] = "Japanese";
        } else if ($chunk == "бразильский португальский") {
            $result[] = "Brazilian Portuguese";
        } else if ($chunk == "турецкий") {
            $result[] = "Turkish";
        } else if ($chunk == "польский") {
            $result[] = "Polish";
        } else if ($chunk == "корейский") {
            $result[] = "Korean";
        } else if ($chunk == "Italiano") {
            $result[] = "Italian";
        } else {
            $result[] = $chunk;
        }
        return $result;
    }


    function buildUrl($api, $items){
        $base = "http://api.steampowered.com";
        $args = [];

        foreach($items as $key => $item){
            if($key == null || $item == null){
                continue;
            }
            $args[$key] = $item;
        }

        return $base . $api . "?" . http_build_query($args);
    }

    function decodeUrl($url){

        $json = file_get_contents($url);
        $content = json_decode($json, true);
        return $content;

    }

    // Api's
    function newsForApp($appid, $count = 999999, $length = 999999)
    {
        return $this->decodeUrl($this->buildUrl("/ISteamNews/GetNewsForApp/v0002/", [
            "count" => $count,
            "maxlength" =>$length,
            "appid" =>$appid
        ]));
    }

    function globalAchievementPercentagesForApp($appid)
    {
        return $this->buildUrl("/ISteamUserStats/GetGlobalAchievementPercentagesForApp/v0002/", [
            "gameid" => $appid,
            "format" =>"json"
        ]);
    }

    function globalStatsForGame($appid)
    {
        // ??

        return $this->buildUrl( "/ISteamUserStats/GetGlobalStatsForGame/v0001/", [
            "gameid" => $appid,
            "format" =>"json"
        ]);
    }

    function playerSummaries($steamids)
    {
        return $this->buildUrl("/ISteamUser/GetPlayerSummaries/v0002/", [
            "steamids" => $steamids,
            "key" => $this->key,
            "format" =>"json"
        ]);
    }

    function friendList($steamid)
    {
        return $this->buildUrl("/ISteamUser/GetFriendList/v0001", [
            "key" => $this->key,
            "steamids" => $steamid,
            "relationship" =>"friend",
            "format" =>"json"
        ]);
    }

    function playerAchievements($steamid, $appid)
    {
        return $this->buildUrl("/ISteamUserStats/GetPlayerAchievements/v0001/", [
            "key" => $this->key,
            "steamid" => $steamid,
            "appid" => $appid,
        ]);
    }

    function userStatsForGame($steamid, $appid)
    {
        return $this->buildUrl( "/ISteamUserStats/GetUserStatsForGame/v0002/", [
            "key" => $this->key,
            "steamid" => $steamid,
            "appid" => $appid,
        ]);
    }

    function ownedGames($steamid)
    {
        return $this->buildUrl( "/IPlayerService/GetOwnedGames/v0001/", [
            "key" => $this->key,
            "steamid" => $steamid,
            "format" => "json",
        ]);
    }

    function getOwnedGamesAsArray($steamid){
        $games = [];
        foreach($this->decodeUrl($this->ownedGames($steamid))["response"]["games"] as $game){
            $games[] = $game["appid"];
        }
        return $games;
    }

    function recentlyPlayedGames($steamid, $count = 999999)
    {
        return $this->buildUrl( "/IPlayerService/GetRecentlyPlayedGames/v0001/", [
            "key" => $this->key,
            "steamid" => $steamid,
            "count" => $count,
            "format" => "json"
        ]);
    }

    function IsPlayingSharedGame($steamid, $appid)
    {
        return $this->buildUrl( "/IPlayerService/IsPlayingSharedGame/v0001/", [
            "key" => $this->key,
            "steamid" => $steamid,
            "appid_playing" => $appid,
            "format" => "json"
        ]);
    }

    function schemaForGame($appid)
    {
        return $this->buildUrl( "/IPlayerService/IsPlayingSharedGame/v0001/", [
            "key" => $this->key,
            "appid" => $appid,
            "format" => "json"
        ]);
    }

    function playerBans($steamids){
        return $this->buildUrl( "/ISteamUserStats/GetSchemaForGame/v2/", [
            "key" => $this->key,
            "steamids" => $steamids
        ]);
    }

}

