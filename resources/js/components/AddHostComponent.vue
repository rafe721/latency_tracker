<template>
    <div class='ui basic content center aligned segment'>
        <button class='ui basic button icon' v-on:click="openForm" v-show="!isCreating">
            <i class='plus icon'></i>
        </button>
        <div class='ui centered card' v-show="isCreating">
            <div class="content">
                <div class="ui form">
                    <div class="field">
                        <label>Host Name</label>
                        <input v-model="host_name_input" type="text" ref="host name" defaultValue="">
                    </div>
                    <div class='ui two button attached buttons'>
                        <button class='ui basic blue button' v-on:click="sendForm()">
                            Create
                        </button>
                        <button class='ui basic red button' v-on:click="closeForm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AddHostComponent",
        data() {
            return {
                host_name_input : '',
                isCreating : false,
            };
        },
        methods : {
            openForm() {
                this.isCreating = true;
            },
            sendForm() {
                if (this.host_name_input.length > 0) {
                    const host_name = this.host_name_input;
                    this.$emit('add-host', host_name);
                    this.clearHostNameInput();
                    this.isCreating = false;
                }
            },
            closeForm() {
                this.isCreating = false;
                this.clearHostNameInput();
            },
            clearHostNameInput() {
                this.host_name_input = "";
            }
        }
    }
</script>

<style scoped>

</style>