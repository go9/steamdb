<style>
    .message-container {
        clear: both;
        width: 100%;
        height: auto;
    }

    .message {
        font-size: .85em;
        /*height: auto;*/
        padding: 5px;
        color: black;
        margin: 5px 5px 0px 5px;
        max-width: 80%;
    }

    .message-to {
        float: left;
        background-color: #F1F0F0;
        width: auto;
        border-radius: 15px 15px 15px 0px;

    }

    .message-to-continued {
        border-radius: 0px 15px 15px 0px;
    }

    .message-from-continued {
        border-radius: 15px 0px 0px 15px;
    }

    .message-from {
        float: right;
        width: auto;

        background-color: #4080FF;
        color: white;
        border-radius: 15px 15px 0px 15px;
    }
</style>

<template>
    <div class="message-container">
        <div class="message" v-bind:class="message.fromUser ? 'message-from' : 'message-to'">
            {{ message.message}}
        </div>
    </div>

</template>

<script>
    export default {
        props: ['message', 'index'],
        mounted(){
            $("#messages-content").scrollTop($("#messages-content")[0].scrollHeight);

            timeago.cancel();
            timeago().render(document.querySelectorAll('.need_to_be_rendered'));
        }
    }
</script>
