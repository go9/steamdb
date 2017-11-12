<template>
        <div class="composer-container">

            <input style="float:left;width:75%;border:none;background-color:transparent;" type="text" class="form-control" placeholder="Type your message" v-model="composerText" @keydown.enter="sendMessage">
            <button style="float:left;width:25%;" class="btn btn-secondary btn" @click="sendMessage">SEND</button>


            <!--<input class="form-control form-control-sm" style="width:80%;">-->
            <!--<button class="btn btn-primary btn-sm" style="width:20%;">SEND</button>-->
        </div>

</template>

<script>
    import {bus} from "../bus.js";

    export default {
        props: ['conversation'],
        data(){
            return {
                composerText: ""
            }
        },
        methods: {
          sendMessage(){
              bus.$emit("messageComposed", {
                  conversation : this.conversation,
                  text: this.composerText
              });

              this.composerText = "";
          }
        }
    }
</script>
