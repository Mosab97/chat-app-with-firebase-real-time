<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Notification;
use App\User;
use Illuminate\Http\Request;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;


class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $chats = Chat::all();
        return view('home',
            [
                'chats' => $chats,
                'users' => User::all()
            ]);
    }

    public function createChat(Request $request)
    {
//        return $request->user_id;
        $input = $request->all();
        $message = $input['message'];
        $chat = Chat::create([
            'sender_id' => auth()->user()->id,
            'sender_name' => auth()->user()->name,
            'message' => $message
        ]);

        $chat = Notification::create([
            'name' => $message,
            'user_id' => $request->user_id,
            'sender_id' => auth()->user()->id
        ]);



//        $this->broadcastMessage(auth()->user()->name, $message);
        $this->sendToUserMessage(auth()->user()->name, $message,$request->user_id);
        return redirect()->back();
    }

    private function broadcastMessage($senderName, $message)
    {

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder('New Message from ' . $senderName);
        $notificationBuilder->setBody($message)
            ->setSound('default')
            ->setClickAction('http://localhost:8000/home');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([
            'sender_name' => $senderName,
            'message' => $message
        ]);


        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();


        $tokens = User::all()->pluck('fcm_token')->toArray();

        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);

        return $downstreamResponse->numberSuccess();


    }


    private function sendToUserMessage($senderName, $message,$user_id)
    {

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder('New Message from ' . $senderName);
        $notificationBuilder->setBody($message)
            ->setSound('default')
            ->setClickAction('http://localhost:8000/home');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([
            'sender_name' => $senderName,
            'message' => $message,
            'user_id' => $user_id
        ]);


        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();


//        $tokens = User::all()->pluck('fcm_token')->toArray();
        $tokens = User::find($user_id)->fcm_token;

        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);

        return $downstreamResponse->numberSuccess();


    }

}
