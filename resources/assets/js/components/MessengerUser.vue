<style>
    .convos-user {
        padding: 10px;
        display: flex;

    }

    .convos-user:hover {
        background-color: rgba(1, 1, 1, .2);
    }

    .convos-user-avatar-container {
        width: 10%;
        height: 100%;
        border-radius: 50%;
        background-color: white;
    }

    .convos-user-avatar {
        width:100%;
        max-width: 50px;
        height: 100%;
        border-radius: 50%;
        background-color: white;
    }

    .convos-user-info {
        width: 90%;
    }

    .convos-user-quick-message{
        font-size:.7em;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }



</style>

<template>
    <div class="convos-user" @click="changeMessages">

        <div class="convos-user-avatar-container">
            <img class="convos-user-avatar" v-bind:src="conversation.users.friend.avatar ? conversation.users.friend.avatar : 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png'">
        </div>

        <div class="convos-user-info">
            <div class="convos-user-name">
                {{ conversation.users.friend.name}}
            </div>

            <div class="convos-user-quick-message">
                <i v-bind:class="
                messages.length > 0 && messages[messages.length-1].fromUser ? icons.from : icons.to" aria-hidden='true'></i>
                {{messages.length > 0 ? messages[messages.length-1].message : ""}}
                <br>
                <time
                        v-bind:class="conversation.messages.length > 0 ? 'need_to_be_rendered' : ''"
                        v-bind:datetime="conversation.messages.length > 0 ? conversation.messages[conversation.messages.length -1].created_at.replace(' ', 'T')+ 'Z' : '0'">
                </time>
            </div>
        </div>
    </div>



</template>

<script>
    import {bus} from "../bus.js";

    export default {
        props: ['conversation', 'index'],
        data(){
            return {
                messages : this.conversation.messages,
                icons: {
                    from : "fa fa-share",
                    to : ''
                }
            }
        },
        methods: {
            changeMessages(){
                bus.$emit('changeMessages', this.conversation)
            }
        },
        created(){
            if(this.index === 0){
                this.changeMessages();
            }
        }
    }

</script>
