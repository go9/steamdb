<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


use App\Game;
use App\Genre;
use App\Category;
use App\Language;
use App\Metacritic;
use App\Screenshot;
use App\Movie;
use App\Developer;
use App\Publisher;
use App\Image;
use App\ImageTheme;
use App\PackageContent;
use App\Store;
use App\Role;

use App\Purchase;

class GameSearch
{
    public $query = null;
    public $results = null;

    // Options
    public $perPage = 10;

    public function __construct($query = null)
    {
        // Check if we are searching/filtering from a list of games
        if (isset($query) && $query == null) {
            // If a list was provided, it will be filtered.
            // If that list is empty, it will act as all()
            // Set it to empty
            $this->query = (new Game)->newQuery();
            $this->query->where("public", 10); // doesn't exist.
        } else if (isset($query)) {
            // The query isn't empty, so set it
            $this->query = $query;
        } else {
            // The query is not set.
            $this->query = (new Game)->newQuery();
        }

        // Only show public games
        $this->query->where("public", 1);
    }

    public function searchBy($categories)
    {

        // Exceptions
        if (isset($categories["page"])) {
            unset($categories["page"]);
        }

        if (isset($categories["sorting"])) {
            $sorting = $categories["sorting"];
            unset($categories["sorting"]);
        }

        if (isset($categories["display"])) {
            if (in_array($categories["display"], [12, 24, 36])) {
                $this->perPage = $categories["display"];
            }
            unset($categories["display"]);
        }

        foreach ($categories as $key => $items) {
            if ($key == "keywords" && $key != "") {

                // Check if string contains anything
                foreach (explode(" ", trim($items)) as $keyword) {
                    $this->query->where('name', 'LIKE', "%$keyword%");
                }
                continue;
            }

            // Make sure it's an array
            // The only parameter that isn't expecting an array is keywords, so it's been skipped

            if (!is_array($items)) {
                $items = [$items];
            }

            if ($key == "types") {
                $this->query->where(function ($query) use ($items) {
                    foreach ($items as $type) {
                        // Check if first
                        $query->where("type", $type);
                    }
                });
                continue;
            }

            foreach ($items as $item) {
                $this->query->whereHas("$key", function ($query) use ($item, $key) {
                    $query->where("$key.id", '=', $item);
                });
            }
        }

        // Sort
        if (isset($sorting) && $sorting != null) {
            $params = explode("-", $sorting);
            $this->query->orderBy($params[0], $params[1]);
        }

        return $this;
    }

    public function results()
    {
        $this->results = $this->query->get();

        if (isset($_GET)) {
            $this->results->input = $_GET;
        }

        return $this->results;
    }

    public function paginatedResults()
    {
        $this->results = $this->query->paginate($this->perPage);
        if (isset($_GET)) {
            $this->results->input = $_GET;
        }

        return $this->results;
    }
}

function GameSearch($data = null)
{
    return new GameSearch($data);
}

class GameController extends Controller
{


    // Views
    public function test()
    {
        /*
        $steam = resolve('App\External\Steam');
        return \redirect(
           $steam->urlBuilder()->isPlayingSharedGame(Auth::user()->steamid, 10)->get()
        );
        //return view("test");
        */

        $id = 321;


    }

    public function home()
    {

        return view("home");
    }

    public function showLibrary()
    {
        if (!Auth::check()) {
            Session::flash("message-info", "Log in or create an account to add games to your library!");
            return Redirect::route("login");
        }

        return view("games.index")->withGames(GameSearch(Auth::user()->library())->searchBy($_GET)->paginatedResults());
    }

    public function showInventory()
    {
        if (!Auth::check()) {
            Session::flash("message-info", "Log in or create an account to add games to your Inventory!");
            return Redirect::route("login");
        }

        return view("games.index")->withGames(GameSearch(Auth::user()->inventory())->searchBy($_GET)->paginatedResults());
    }

    public function show($id)
    {
        return view("games.show")->withGame(Game::find($id));
    }

    public function index()
    {
        return view("games.index")->withGames(GameSearch()->searchBy($_GET)->paginatedResults());
    }

    public function updateGames()
    {
        // Sync with steam
        $this->syncApplist();
        return view("settings.updatedatabase")->withGames(
            Game::where("public", "-1")
                ->where("type", "unknown")
                ->pluck("steam_id")
                ->toArray()
        );
    }

    public function updatePackages()
    {
        return view("settings.updatedatabasepackages")->withGames(
            Game::where("public", "-1")
                ->where("type", "package")
                ->pluck("steam_id")
                ->toArray()
        );
    }


    // Functions
    public function search()
    {
        return GameSearch()->searchBy($_GET)->paginatedResults();
    }

    public function syncApplist()
    {
        // We're going to need more than 30 seconds
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes


        // Get all appids that are in the list and subtract all the appids that are in the database
        $steam = resolve('App\External\Steam');
        $appids = array_diff(
            array_column($steam->getApplist(), "appid"),
            Game::where("type", "!=", "package")->pluck("steam_id")->toArray()
        );

        $games = [];
        foreach ($appids as $key => $appid) {
            // Get the game info from the json file
            $game = $steam->getApplist()[$key];

            // Change key name
            $game["steam_id"] = $game["appid"];
            unset($game["appid"]);

            // Add game to list
            $games[] = array_merge(
                $game,
                [
                    "type" => "unknown",
                    "public" => "-1"
                ]
            );
        }

        // Add the games to the database. Split into chunks to avoid errors
        foreach (array_chunk($games, 1000) as $chunk) {
            Game::insert($chunk);
        }

        return $games;
    }

    public function g2aAutoMatcher(){

        //ini_set('memory_limit', '-1');

        $data = [
            "matched" => [],
            "unmatched" => [],
            "failure" => []
        ];

        $games = Game::whereNull("g2a_id")->where("public",1)->get();
        foreach($games as $game){
            console($game);

            if ($game == null) {
                $data["error"][] = $game->id;
                continue;
             }

            // Search G2a For any matches
            $results = resolve("App\External\G2aApi")->searchByPhrase($game->name);
            if (isset($results["perfect_match"]) && $results["perfect_match"] != false) {
                $game->g2a_id = $results["perfect_match"]["id"];
                $game->save();
                $data["matched"][] = $game->id;
            }
            else{
                $data["unmatched"][] = $game->id;
            }
        }

        return view("settings.g2a_auto_matcher")->withData($data);
    }

    public function g2aUpdatePrice(Request $request)
    {
        $this->validate($request, [
            "game_id" => "required|numeric",
            "hours" => "nullable|numeric"
        ]);


        $game = Game::find($request->game_id);
        if ($game == null) {
            return ["success" => false, "code" => 0, "message" => "Game was not found.", "data" => ["id" => $request->game_id]];
        }

        $g2a = resolve("App\External\G2aApi");

        // Check if the request contains hours flag. If it's greater than 0, check if we have the prices already
        if (isset($request->hours) && $request->hours != 0 && DB::table("game_store")
                ->where("game_id", $game->id)
                ->where("store_id", $g2a->getStore()->id)
                ->where("created_at", '>=', Carbon::now()->subHours($request->hours))
                ->get() != null
        ) {
            return [
                "success" => false,
                "code" => 1,
                "message" => "Already have new enough price ({$request->hours} old)",
                "data" => $game
            ];

        } else {
            // Save price
            $price = $g2a->fetchPrice($game->g2a_id);
            $game->prices()->attach($g2a->getStore(), ["price" => $price]);
            return [
                "success" => true,
                "data" => $game,
                "price" => $price
            ];
        }
    }

    // Crud

    public function storeGame($id)
    {
        // Get the data
        $steam = resolve("App\External\Steam");

        $json = $steam->parseAppData($id);
        $json["data"]["steam_id"] = $id;
        $content = $json["data"];

        // Check if we have the game
        $packages = null;
        $game = Game::where("steam_id", $id)->where("type", "!=", "package")->first();
        // If the game is not in the db, create it.

        // Check if the data is good
        if ($json["success"] == false) {
            // Check what kind of error it was
            if ($json["code"] == 4) {
                // Steam didn't have anything for us. If we aleady have it, let's update it.
                if ($game != null) {
                    $game->public = 0;
                    $game->save();
                }
            }
            return $json;
        }

        // Make sure we have the game, if not, let's make it.
        if ($game == null) {
            $game = new Game;
            $game->steam_id = $id;
        }
        $game->name = $content['name'];
        $game->type = $content['type'];

        if ($game->type == "game" || $game->type == "dlc") {
            $game->public = 1;

            $game->description = isset($content['detailed_description']) ? $content['detailed_description'] : null;
            $game->about_the_game = isset($content['about_the_game']) ? $content['about_the_game'] : null;
            $game->short_description = isset($content['short_description']) ? $content['short_description'] : null;

            $game->platform_windows = isset($content['platforms']["windows"]) ? $content['platforms']["windows"] : null;
            $game->platform_mac = isset($content['platforms']["mac"]) ? $content['platforms']["mac"] : null;
            $game->platform_linux = isset($content['platforms']["linux"]) ? $content['platforms']["linux"] : null;

            $game->req_win_min = isset($content['pc_requirements']['minimum']) ? $content['pc_requirements']['minimum'] : null;
            $game->req_win_rec = isset($content['pc_requirements']['recommended']) ? $content['pc_requirements']['recommended'] : null;
            $game->req_mac_min = isset($content['mac_requirements']['minimum']) ? $content['mac_requirements']['minimum'] : null;
            $game->req_mac_rec = isset($content['mac_requirements']['recommended']) ? $content['mac_requirements']['recommended'] : null;
            $game->req_lin_min = isset($content['linux_requirements']['minimum']) ? $content['linux_requirements']['minimum'] : null;
            $game->req_lin_rec = isset($content['linux_requirements']['recommended']) ? $content['linux_requirements']['recommended'] : null;

            $game->website = isset($content['website']) ? $content['website'] : null;
            $game->legal_notice = isset($content['legal_notice']) ? $content['legal_notice'] : null;

            $game->is_free = isset($content['is_free']) ? $content['is_free'] : null;
            $game->full_game = isset($content['full_game']) ? $content['full_game'] : null;
            $game->required_age = isset($content['required_age']) ? $content['required_age'] : null;
            $game->support_url = isset($content['support_info']["url"]) ? $content['support_info']["url"] : null;
            $game->support_email = isset($content['support_info']["email"]) ? $content['support_info']["email"] : null;
            $game->external_account = isset($content['ext_user_account_notice']) ? $content['ext_user_account_notice'] : null;
            $game->controller_support = isset($content['controller_support']) && $content['controller_support'] == "full" ? true : false;

            if (isset($content['release_date']["date"])) {
                try {
                    $game->release_date = new Carbon($content['release_date']["date"]);
                } catch (\Exception $e) {
                    $game->release_date = $content['release_date']["date"];
                }

            }

            $game->save();

            // Add images
            if (isset($content['header_image']) && !Image::where('game_id', $game->id)->where("url", $content['header_image'])->exists()) {
                $image = new Image;
                $image->type = "header_image";
                $image->url = $content['header_image'];
                $image->image_themes_id = 1;
                $game->images()->save($image);
            }
            if (isset($content['background']) && !Image::where('game_id', $game->id)->where("url", $content['background'])->exists()) {
                $image = new Image;
                $image->type = "background";
                $image->url = $content['background'];
                $image->image_themes_id = 1;
                $game->images()->save($image);
            }

            if (!Metacritic::where("game_id", $game->id)->exists() && isset($content['metacritic']['score']) && isset($content['metacritic']['url'])) {

                $metacritic = new Metacritic;
                $metacritic->score = $content['metacritic']['score'];
                $metacritic->url = $content['metacritic']['url'];
                $game->metacritics()->save($metacritic);
            }

            if (isset($content['supported_languages'])) {
                foreach ($content['supported_languages'] as $item) {

                    // Get the language ID
                    $language = Language::firstOrCreate(["name" => $item]);

                    // Let's see if this game already has this language
                    $game->languages()->syncWithoutDetaching([$language->id]);
                }
            }
            if (isset($content['publishers'])) {
                foreach ($content['publishers'] as $item) {
                    // Check if the publisher is already in the database.
                    $publisher = Publisher::firstOrCreate(["name" => $item]);

                    // Let's see if this game already has this category
                    $game->publishers()->syncWithoutDetaching([$publisher->id]);
                }
            }
            if (isset($content['developers'])) {
                foreach ($content['developers'] as $item) {
                    // Check if the developer is already in the database.
                    $developer = Developer::firstOrCreate(["name" => $item]);

                    $game->developers()->syncWithoutDetaching([$developer->id]);

                }
            }
            if (isset($content['categories'])) {
                foreach ($content['categories'] as $item) {
                    // Check if the category is already in the database.
                    $category = Category::firstOrCreate(
                        [
                            "name" => $item["description"],
                            "id" => $item["id"]
                        ]
                    );


                    // Let's see if this game already has this category
                    $game->categories()->syncWithoutDetaching([$category->id]);
                }
            }
            if (isset($content['genres'])) {
                foreach ($content['genres'] as $item) {
                    // Check if the genre is already in the database.
                    $genre = Genre::firstOrCreate(
                        [
                            "name" => $item["description"],
                            "id" => $item["id"]
                        ]
                    );

                    // Let's see if this game already has this genre
                    $game->genres()->syncWithoutDetaching([$genre->id]);
                }
            }
            if (isset($content['screenshots'])) {
                foreach ($content['screenshots'] as $item) {
                    // Get the screenshot
                    $screenshot = Screenshot::where("url", "=", $item['path_full'])->first();
                    if ($screenshot == null) {
                        // The screenshot isn't in the DB. Let's add it.
                        $screenshot = new Screenshot;
                        $screenshot->thumbnail = $item['path_thumbnail'];
                        $screenshot->url = $item['path_full'];
                        $game->screenshots()->save($screenshot);
                    }
                }
            }
            if (isset($content['movies'])) {
                foreach ($content['movies'] as $item) {
                    // Get the movie ID
                    $movie = Movie::where("movie_id", "=", $item['id'])->first();
                    if ($movie == null) {
                        // The screenshot isn't in the DB. Let's add it.
                        $movie = new Movie;
                        $movie->movie_id = $item['id'];
                        $movie->name = $item['name'];
                        $movie->thumbnail = $item['thumbnail'];
                        $movie->webm_sd = $item['webm']['480'];
                        $movie->webm_hd = $item['webm']['max'];
                        $movie->highlight = $item['highlight'];

                        $game->movies()->save($movie);
                    }
                }
            }

            if (isset($content['packages'])) {
                foreach ($content['packages'] as $pid) {
                    // Check if the package is already in the database.
                    $package = Game::where("steam_id", $pid)->where("type", "package")->first();

                    if ($package != null) {
                        continue;
                    }

                    // Save package, get info later
                    $package = new Game();
                    $package->steam_id = $pid;
                    $package->public = -1;
                    $package->name = "";
                    $package->type = "package";
                    $package->save();

                    $packages[] = $package;

                }
            }
        } else {
            $game->public = 0;
            $game->save();
        }

        return [
            "success" => true,
            "data" => $game,
            "packages" => $packages
        ];
    }

    public function storePackage($id)
    {
// Check if we have the package.
        $package = Game::where("steam_id", $id)->where("type", "package")->first();
        if ($package == null) {
            // If not, make it. (don't save it though)
            $package = new Game;
            $package->type = "package";
            $package->public = -1;
            $package->steam_id = $id;
        }


        // Get the data from steam
        $json = resolve("App\External\Steam")->parsePackageData($id);

        // Verify the data is good
        if ($json["success"] == false) {
            // Check what kind of error it was
            if ($json["code"] == 4) {
                // Steam didn't have anything for us. If we aleady have it, let's update it.
                if ($package != null) {
                    $package->public = 0;
                    $package->save();
                }
            }
            return $json;
        }

        // Store steam id
        $json["data"]["steam_id"] = $id;
        $content = $json["data"];


        $package->name = $content['name'];
        $package->description = isset($content['page_content']) ? $content['page_content'] : null;
        $package->controller_support = isset($content["controller"]["full_gamepad"]) ? $content["controller"]["full_gamepad"] : null;
        $package->platform_windows = isset($content['platforms']["windows"]) ? $content['platforms']["windows"] : null;
        $package->platform_mac = isset($content['platforms']["mac"]) ? $content['platforms']["mac"] : null;
        $package->platform_linux = isset($content['platforms']["linux"]) ? $content['platforms']["linux"] : null;
        $package->save();


        // Add images
        if (isset($content['header_image']) && !Image::where('game_id', $package->id)->where("url", $content['header_image'])->exists()) {
            $image = new Image;
            $image->type = "header_image";
            $image->url = $content['header_image'];
            $image->image_themes_id = 1;
            $package->images()->save($image);
        }

        if (isset($content['page_image']) && !Image::where('game_id', $package->id)->where("url", $content['page_image'])->exists()) {
            $image = new Image;
            $image->type = "package_image_large";
            $image->url = $content['page_image'];
            $image->image_themes_id = 1;
            $package->images()->save($image);
        }

        if (isset($content['small_logo']) && !Image::where('game_id', $package->id)->where("url", $content['page_image'])->exists()) {
            $image = new Image;
            $image->type = "package_image_small";
            $image->url = $content['small_logo'];
            $image->image_themes_id = 1;
            $package->images()->save($image);
        }


        // Get the package contents
        $gamesInPackage = null;

        // Verify that there are some apps
        if(!isset($content['apps'])){

            $package->public = false;
            $package->save();

            return [
                "success" => false,
                "data" => $package,
                "message" => "There were no games in this package"
            ];
        }

        foreach ($content['apps'] as $appid) {
            // Check if the game is in the database.
            $game = Game::where("steam_id", $appid['id'])->where("type", "!=", "package")->first();

            if ($game == null) {
                // Don't have it. Have to get it first!
                $game = $this->storeApp($appid['id']);
            }

            // Add to pivot table.
            $packageContent = PackageContent::firstOrCreate([
                "package_id" => $package->id,
                "content_id" => $game->id
            ]);

            $gamesInPackage[] = $game;
        }

        // Check if only one in package- if so, set public to false
        $package->public = count($gamesInPackage) > 1 ? true : false;
        $package->save();


        return [
            "success" => true,
            "data" => $package,
            "contents" => $gamesInPackage
        ];
    }

    public function create()
    {
        return view("games.create");
    }

    public function store()
    {
        return back()->withInput();
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        // There is no deleting games.
        // Deleting a game means setting public to false.

        return back();
    }
}
