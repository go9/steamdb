

<template>
    <div id="messenger-users">
        <input class="form-control" style="width:100%;">
        <messenger-user
                v-for="(conversation, index) in conversations"
                :conversation="conversation"
                :index="index"></messenger-user>
    </div>
</template>

<script>
    import {bus} from "../bus.js";

    export default {
        props: ['conversations'],
        data(){
            return {
                index: 0,
            }
        },
        mounted(){
            var component = this;
            bus.$on('messageComposed', function (text, that = component) {

            });
        }
    }
</script>
