<template>
    <tr>
        <td scope="row">
            <label class="checkbox">
                <input type="checkbox"
                       v-bind:name="host_name+'_latency'"
                       v-bind:checked="is_selected"
                       @click="toggleHostSelection($event)"
                       @change="cancelChange()">
                <span class="checkmark"></span>
            </label>
        </td>
        <td>{{ host_name }}</td>
        <td>{{ getLatency() }}</td>
        <td>
            <button class='ui basic blue button'
                    title="Delete this host"
                    v-on:click="deleteHost"
                    style="float: right;">
                <i class="fa fa-times-circle-o"></i>
            </button>
        </td>
    </tr>
</template>

<script>
    export default {
        name: "HostTableRecordComponent",
        props : [
            'host_name',
            'latency',
            'is_selected'
        ],
        data() {
            return {
            };
        },
        methods: {
            // Ensures that latency is represented consistently on the component
            getLatency() {
                return (this.latency == null) ? 'No response' : this.latency;
            },
            // Emits delete event
            deleteHost() {
                this.$emit('delete-host', this.host_name);
            },
            // Emits events upstream depending on stat of this.is_selected
            toggleHostSelection(e) {
                if (this.is_selected) {
                    this.$emit('unselect-host', this.host_name);
                } else {
                    this.$emit('select-host', this.host_name);
                }
            },
            // Clears the input
            cancelChange() {
                return false;
            }
        },
    }
</script>

<style scoped>
    .checkbox {
        display: block;
        position: relative;
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 22px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* Hide the browser's default checkbox */
    .checkbox input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    /* Create a custom checkbox */
    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 25px;
        width: 25px;
        background-color: #eee;
    }

    /* On mouse-over, add a grey background color */
    .checkbox:hover input ~ .checkmark {
        background-color: #ccc;
    }

    /* When the checkbox is checked, add a blue background */
    .checkbox input:checked ~ .checkmark {
        background-color: #2196F3;
    }

    /* Create the checkmark/indicator (hidden when not checked) */
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    /* Show the checkmark when checked */
    .checkbox input:checked ~ .checkmark:after {
        display: block;
    }

    /* Style the checkmark/indicator */
    .checkbox .checkmark:after {
        left: 10px;
        top: 5px;
        width: 6px;
        height: 12px;
        border: solid white;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }
</style>