@extends('layouts.app')

@section('style')


    <style>
        .chat-container {
            display: flex;
            flex-direction: column;
        }

        .chat {
            border: 1px solid gray;
            border-radius: 3px;
            width: 50%;
            padding: 0.5rem;
        }

        .chat-left {
            background-color: white;
            align-self: flex-start;
        }


        .chat-right {
            background-color: #adff2f7f;
            align-self: flex-end;

        }

        .message-input-container {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: white;
            border: 1px solid gray;
            padding: 1rem;
        }
    </style>
@endsection
@section('content')


    <div class="container" style="margin-bottom: 230px;" >
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">

                        @if($chats->count() > 0)
                            <div class="chat-container">
                                @foreach($chats as $chat)

                                    @if($chat->sender_id === Auth()->user()->id)
                                        <p class="chat chat-right">
                                            <b>Sender :{{ $chat->sender_name}}</b><br>
                                            {{$chat->message}}
                                        </p>
                                    @else
                                        <p class="chat chat-left">
                                            <b>Sender :{{ $chat->sender_name}}</b><br>

                                            {{$chat->message}}
                                        </p>

                                    @endif

                                @endforeach

                            </div>
                        @else
                            <p>sorry no chats found</p>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="message-input-container">
        <form action="{{route('chat.create')}}" method="post">
            @csrf
            <div class="form-group">
                <label for="message">Message</label>
                <input name="message" type="text" class="form-control" id="message">
            </div>
            <div class="form-group">
                <select name="user_id" id="usre_id">
                    @foreach($users as $user)
                        <option value="{{$user->id}}">{{$user->name}}</option>
                        @endforeach
                </select>
                <button type="submit" class="btn btn-primary">SEND MESSAGE</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>

        // Retrieve Firebase Messaging object.
        const messaging = firebase.messaging();
        // Add the public key generated from the console here.
        messaging.usePublicVapidKey("BPKnvmxumokwFLOtSrh0kU6GDzqjFQrRVJ42oaFAbSTOo63gevdy_cLS4RJZD5TIH7avj2TWDRWVY5V8JcYqpjw");

        function retriveToken() {

            // Get Instance ID token. Initially this makes a network call, once retrieved
            // subsequent calls to getToken will return from cache.
            messaging.getToken().then((currentToken) => {
                if (currentToken) {
                    console.log('Token received :' + currentToken)
                    sendTokenToServer(currentToken);
                    // updateUIForPushEnabled(currentToken);
                } else {
                    // // Show permission request.
                    alert('You should allow Notification');
                }
            }).catch((err) => {
                // console.log('An error occurred while retrieving token. ', err);
                console.log(err.message)
                // showToken('Error retrieving Instance ID token. ', err);
                // setTokenSentToServer(false);
            });


        }

        retriveToken();

        function sendTokenToServer(fcm_token) {
            const user_id = {{Auth()->user()->id}}
            axios.post('/api/save-token', {
                fcm_token, user_id
            })
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });
        }

        messaging.onTokenRefresh(() => {
            retriveToken();
        });


        messaging.onMessage((payload) => {
            console.log('Message received.');
            console.log(payload);

            location.reload();
        });

    </script>

@endsection
