<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\Message;
use App\User;

use App\Events\MessagePosted;
use App\Events\ConversationPosted;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;
use View;


class MessageController extends Controller
{

    public function __construct(){

    }

    public function index(){
        return view("messages.index");
    }

    public function createConversation(Request $request){
        $this->validate($request, [
            "user_ids.*" => "numeric"
        ]);

        // check if these two users already have a conversation

        $users = [];
        foreach($request->user_ids as $user_id){
            if(($user = User::find($user_id))){
                $users[] = $user;
            }
            else{
                return ["success" => false];
            }
        }

        $conversation_id = array_intersect(
            $users[0]->conversations->pluck("id")->toArray(),
            $users[1]->conversations->pluck("id")->toArray()
        );

        if(sizeof($conversation_id) > 0){
            $conversation = Conversation::find($conversation_id[0]);
        }
        else{
            $conversation = new Conversation();
            $conversation->save();
        }



        foreach($users as $user){
                $conversation->users()->syncWithoutDetaching($user);
        }


        event(new ConversationPosted($users[0]));
        event(new ConversationPosted($users[1]));

        return [
            "success" => true,
            "data" => [
                "conversation" => $conversation,
                "conversation_id" => $conversation_id
            ]
        ];
    }

    public function getUsersConversations(Request $request){

        if(Auth::check()){
            return [
                "success" => true,
                "data" => [
                    "user" => Auth::user(),
                    "test" => true,
                    "conversations" => Auth::user()->conversations->sortByDesc("most_recent_message_timestamp")->load("messages", "users")
                ]
            ];
        }

        return [
            "success" => false
        ];


    }

    public function insertUserIntoConversation(Request $request){
        $this->validate($request, [
            "user_id" => "required|numeric",
            "conversation_id" => "required|numeric"
        ]);

        if($conversation = Conversation::find($request->conversation_id)){
            if(($user = User::find($request->user_id))){
                $conversation->users()->syncWithoutDetaching($user);
            }

            return [
                "success" => true,
                "data" => [
                    "conversation" => $conversation,
                    "user" => $user
                ]
            ];
        }

        return [
            "success" => false,
            "data" => $request->only(["user_id", "conversation_id"])
        ];

    }

    public function createMessage(Request $request){
        $this->validate($request, [
            "user_id" => "required|numeric",
            "conversation_id" => "required|numeric",
            "message" => "required|string"
        ]);

        if( ($conversation = Conversation::find($request->conversation_id)) && ($user = User::find($request->user_id)) ){
                $message = Message::create(
                    [
                        "user_id" => $request->user_id,
                        "conversation_id" => $request->conversation_id,
                        "message" => $request->message
                    ]
                );


            event(new MessagePosted($message, $user));

            return [
                "success" => true,
                "data" => [
                    "conversation" => $conversation,
                    "user" => $user,
                    "message" => $message
                ]
            ];
        }

        return [
            "success" => false,
            "data" => $request->only(["user_id", "conversation_id", "message"])
        ];


    }




    // OLD

//    public function createConversationWithMessage(Request $request){
//        $this->validate($request, [
//            "from_user" => "required|numeric",
//            "to_user" => "required|numeric",
//            "message" => "required|string"
//        ]);
//
//        // Create conversation
//        $conversation = Conversation::where("user_id", $request->from_user);
//
//        // Add users to conversation
//        foreach([$request->from_user, $request->to_user] as $user_id){
//            if(($user = User::find($user_id))){
//                $conversation->users()->syncWithoutDetaching($user);
//            }
//            else{
//                return false;
//            }
//        }
//
//        dd($conversation);
//
//        // Create message
//        $message = Message::create(
//            [
//                "user_id" => $request->from_user,
//                "conversation_id" => $conversation->id,
//                "message" => $request->message
//            ]
//        );
//
//        event(new MessagePosted($message, $user));
//
//        // redirect to messages
//        return redirect(route("/messages"));
//
//    }

//    public function sendNewMessage(Request $request){
//        $this->validate($request, [
//            "message" => "required|string",
//            "user_id" => "required|numeric"
//        ]);
//
//        // Check if we already have a conversation
//
//        $convo = Conversation::where("user_one",Auth::user()->id)->where("user_two", $request->user_id)->first();
//        if($convo == null){
//            $convo = Conversation::where("user_one",$request->user_id)->where("user_two", Auth::user()->id)->first();
//        }
//        if($convo == null){
//            $convo = new Conversation();
//            $convo->user_one = Auth::user()->id;
//            $convo->user_two = $request->user_id;
//            $convo->save();
//        }
//
//
//
//
//        $message = new Message();
//        $message->message = $request->message;
//        $message->user_id = Auth::user()->id;
//        $message->conversation_id = $convo->id;
//        $message->save();
//
//        return redirect()->action("MessageController@index");
//
//
//
//    }
//
//    public function sendMessage(Request $request){
//        $this->validate($request, [
//            "conversation_id" => "required|numeric",
//            "message" => "required|string",
//            "user_id" => "required|numeric"
//        ]);
//
//        $conversation = Conversation::find($request->conversation_id);
//        if(!$conversation){
//            return ["success" => false];
//        }
//
//        $message = new Message();
//        $message->message = $request->message;
//        $message->user_id = $request->user_id;
//        $message->conversation_id = $request->conversation_id;
//        $message->save();
//
//        $conversation->messages()->save($message);
//
//        return [
//            "success" => true,
//            "data" => [
//                "conversation" => $conversation->load("messages", "user_one", "user_two"),
//                "message" => $message
//            ]
//        ];
//    }
//
//    public function getConversation(Request $request){
//        $this->validate($request, [
//            "conversation_id" => "required|numeric",
//        ]);
//
//        $conversation = Conversation::find($request->conversation_id);
//        if(!$conversation){
//            return ["success" => false];
//        }
//
//        return [
//            "success" => true,
//            "data" => [
//                "conversation" => $conversation->load("messages", "user_one", "user_two"),
//            ]
//        ];
//    }
//
//    public function getConversations(Request $request){
//        $this->validate($request, [
//            "user_id" => "numeric",
//        ]);
//
//        return [
//            "success" => true,
//            "data" => [
//                "conversations" =>
//                    Conversation::where("user_one", Auth::user()->id)
//                    ->orWhere("user_two", Auth::user()->id)
//                    ->get()
//                    ->load("messages", "user_one", "user_two"),
//                "user" => Auth::user()
//            ]
//        ];
//
//
//    }

}