<template>
    <tr class="this_row">
        <th scope="row">
            #
        </th>
        <td>
            <input type="text"
                   v-model="host_name_input"
                   ref="host name"
                   defaultValue=""
                   placeholder="Add a new Host or IPv4">
        </td>
        <td>
            <!-- No Latency for the new guy -->
        </td>
        <td>
            <div style="float: right;">
                <button class='ui basic red button'
                        title="Cancel"
                        maxlength="63"
                        v-on:click="clearHostNameInput">
                    <i class="fa fa-times"></i>
                </button>
                <button class='ui basic blue button'
                        title="Add new Host"
                        v-on:click="sendForm()">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {
        name: "AddHostRecordComponent",
        data() {
            return {
                host_name_input : '',
            };
        },
        methods : {
            sendForm() {
                if (this.host_name_input.length > 0) {
                    // Check if name is a valid host name.. No need to check for IP as if not an IP the string will pass as a host name..
                    const host_name = this.host_name_input;
                    this.$emit('add-host', host_name);
                    this.clearHostNameInput();
                    this.isCreating = false;
                }
            },
            clearHostNameInput() {
                this.host_name_input = "";
            }
        },
    }
</script>

<style scoped>
    .this_row input {
        padding: 0 0.5em;
        color: black;
    }
</style>