<template>
    <tr>
        <th scope="row">#</th>
        <td><input v-model="host_name_input" type="text" ref="host name" defaultValue=""></td>
        <td>
            <button class='ui basic red button' v-on:click="closeForm">
                Cancel
            </button>
        </td>
        <td>
            <button class='ui basic blue button' v-on:click="sendForm()">
                Create
            </button>
        </td>
    </tr>
</template>

<script>
    export default {
        name: "AddHostRecordComponent",
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
        },
    }
</script>

<style scoped>

</style>