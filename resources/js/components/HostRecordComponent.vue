<template>
    <div class="ui centered card">
        <div class="content" v-show="!isEditing">
            <div class="meta">
                {{ host_name }}
            </div>
            <div class="meta">
                {{ getLatency() }}
            </div>
            <div class='extra content'>
                <span class='right floated edit icon' v-on:click="showForm">
                    <i class='edit icon'></i>
                </span>
            </div>
            <span class='right floated trash icon' v-on:click="deleteHost">
                <i class='trash icon'></i>
            </span>
        </div>
        <div class="content" v-show="isEditing">
            <div class='ui form'>
                <div class='field'>
                    <label>Host Name</label>
                    <input type='text' v-model="host_name" >
                </div>
                <div class='field'>
                    <label>Latency</label>
                    {{ getLatency() }}
                </div>
                <div class='ui two button attached buttons'>
                    <button class='ui basic blue button' v-on:click="hideForm">
                        Close X
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name : "HostRecordComponent",
        props : [
            'host_name',
            'latency'
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
            }
        },
    }
</script>

<style scoped>

</style>