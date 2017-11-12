/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('messenger-users', require('./components/MessengerUsers.vue'));
Vue.component('messenger-user', require('./components/MessengerUser.vue'));
Vue.component('messenger-messages', require('./components/MessengerMessages.vue'));
Vue.component('messenger-message', require('./components/MessengerMessage.vue'));
Vue.component('messenger-composer', require('./components/MessengerComposer.vue'));

const app = new Vue({
        el: '#app',
        data: {
            conversations: [],
            auth: null
        },
        mounted(){
            axios
                .post(
                    "/conversation/get/all", {
                        //user_id: this.auth.id
                    })
                .then(
                    response => {
                        console.log(response);

                        this.auth = response.data.data.user;

                        var data  = response.data.data.conversations;

                        // Go through convos
                        for (var conversation_id in data){
                            var conversation = data[conversation_id];

                            var users = {};
                            for(var i in conversation.users){
                                if(this.auth.id === conversation.users[i].id) {
                                    users.user = conversation.users[i];
                                }else{
                                    users.friend = conversation.users[i];
                                }
                            }
                            conversation.users = users;

                            //delete conversation.users;

                            // Go through messages
                            for(var message_id in conversation.messages){
                                conversation.messages[message_id].fromUser
                                    = this.auth.id === conversation.messages[message_id].user_id;
                            }
                        }

                        this.conversations = response.data.data.conversations;
                     }
                );
        },

        created(){
            Echo.join('chatroom')
                .here(function(){
                    console.log("here");
                })
                .joining(function(){
                    console.log("joining");
                })
                .leaving(function(){
                    console.log("leaving");
                })
                .listen('MessagePosted', (e) => {
                    for(var i in this.conversations){
                        if(this.conversations[i].id === e.message.conversation_id){

                            timeago.cancel();
                            timeago().render(document.querySelectorAll('.need_to_be_rendered'));


                            e.message.fromUser = e.message.user_id === this.auth.id;
                            this.conversations[i].messages.push(e.message);

                            var audio = new Audio("https://notificationsounds.com/soundfiles/68ce199ec2c5517597ce0a4d89620f55/file-sounds-954-all-eyes-on-me.mp3");
                            audio.play();
                            break;
                        }
                    }
                })

            Echo.join(`conversations-updated.19`)
                .listen('ConversationPosted', (e) => {
                    var data = e.user.conversations;

                    // Go through convos
                    for (var conversation_id in data){
                        var conversation = data[conversation_id];

                        var users = {};
                        for(var i in conversation.users){
                            if(this.auth.id === conversation.users[i].id) {
                                users.user = conversation.users[i];
                            }else{
                                users.friend = conversation.users[i];
                            }
                        }
                        conversation.users = users;

                        //delete conversation.users;

                        // Go through messages
                        for(var message_id in conversation.messages){
                            conversation.messages[message_id].fromUser
                                = this.auth.id === conversation.messages[message_id].user_id;
                        }
                    }

                    this.conversations = data;

                });
        }
    })
;
