<template>
    <tr>
        <td scope="row">
            <input type="checkbox"
                    v-bind:name="host_name+'_latency'"
                    v-bind:checked="is_selected"
                   @click="toggleHostSelection($event)"
                    @change="cancelChange()"
                >
        </td>
        <td>{{ host_name }}</td>
        <td>{{ getLatency() }}</td>
        <td>
            <button class='ui basic blue button' v-on:click="deleteHost">
                Remove
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
                isEditing: false,
            };
        },
        methods: {
            getLatency() {
                return (this.latency == null) ? 'No response' : this.latency;
            },
            showForm() {
                this.isEditing = true;
            },
            hideForm() {
                this.isEditing = false;
            },
            deleteHost() {
                this.$emit('delete-host', this.host_name);
            },
            toggleHostSelection(e) {
                if (this.is_selected) {
                    this.$emit('unselect-host', this.host_name);
                } else {
                    this.$emit('select-host', this.host_name);
                }
            },
            cancelChange() {
                return false;
            }
        },
    }
</script>

<style scoped>

</style>