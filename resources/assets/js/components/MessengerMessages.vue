<style>
    #messages-content {
        height: 90%;
        overflow-y:scroll;
        display: flex;
        flex-direction: column;
        align-content: flex-start;

    }
    #messages-composer {
        height: 10%;
    }
</style>

<template>
    <div id="messenger-messages">
        <div id="messages-content">
            <messenger-message v-for="message, index in conversation.messages" :message="message" :index="index"></messenger-message>
        </div>

        <div id="messages-composer">
            <messenger-composer :conversation="conversation"></messenger-composer>
        </div>
    </div>


</template>

<script>

    import {bus} from "../bus.js";

    export default {
        data(){
            return {
                conversation: []
            }
        },
        mounted(){

            var component = this;

            bus.$on('changeMessages', function (conversation, that = component) {
                that.conversation = conversation;

                $('#messenger-users').toggleClass('hidden');
            });

            bus.$on('messageComposed', function (convo, that = component) {

                axios
                    .post(
                        "/conversation/create/message",
                        {
                            user_id: that.conversation.users.user.id,
                            conversation_id: that.conversation.id,
                            message: convo.text
                        }
                    )
                    .then(response => {
//                        response.data.data.message.fromUser = true;
//                        that.conversation.messages.push(response.data.data.message);
                    })
                    .catch(e => {
                        alert("Error! Check console for info");
                        console.log(e);
                    });

                // play sound
                var audio = new Audio("https://notificationsounds.com/soundfiles/4f4adcbf8c6f66dcfc8a3282ac2bf10a/file-sounds-1065-just-like-that.mp3");
                audio.play();

            })

        }
    }
</script>
