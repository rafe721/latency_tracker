<template>
    <div class="latency-container">
        <div class="title-area center-text" style="margin: 40px auto 20px;">
            <H1>Check network latency</H1>
            <h3 class="subtitle">Manage a list of Host names or IPv4 addresses and select ones to check for network latency.</h3>
            <div class="centered center-text">
                <h2>{{ status_text }}</h2>
                <countdown v-bind:countdown="refresh_in"></countdown>
            </div>
        </div>
        <table class="table table-striped table-dark centered host-table">
            <thead>
                <tr>
                    <th scope="col">
                        <button class='ui basic green button'
                                title="Ping now"
                                style="float: left;"
                                v-on:click="pingNow()">
                            <i class="fa fa-refresh"></i>
                        </button>
                    </th>
                    <th scope="col">
                        Host name or IP (v4)
                    </th>
                    <th scope="col">
                        Observed Latency (in milli-sec)
                    </th>
                    <th scope="col">
                        <button class='ui basic blue button' title="Remove Selected Hosts" v-on:click="deleteSelectedHosts()" style="float: right;">
                            <i class="fa fa-trash"></i>
                        </button>
                    </th>
                </tr>
            </thead>
            <tbody>
                <host-table-record v-for="(host_name, index) in host_list"
                                   v-bind:key="index"
                                   v-bind:is_selected="selected_host_list.indexOf(host_name) != -1 ? true : false "
                                   v-bind:host_name="host_name"
                                   v-bind:latency="host_latency_map.hasOwnProperty(host_name) ? host_latency_map[host_name] : 'TBA' "
                                   v-on:delete-host="deleteHost"
                                   v-on:select-host="selectHost"
                                   v-on:unselect-host="unselectHost">
                </host-table-record>
                <add-host-record v-on:add-host="addHost"></add-host-record>
            </tbody>
        </table>
    </div>
</template>

<script>
    import HostTableRecordComponent from "./HostTableRecordComponent";
    export default {
        components: {HostTableRecordComponent},
        props : {
            'host_map' : {
                type: Object,
                default : () => {
                    return {};
                },
            },
            'is_edit' : {
                type: Boolean,
                default: false,
            }
        },
        data() {
            return {
                host_latency_map :{}, // @type object hosts hostname => latency pairs from the server
                host_list : [], // @type array list of available hosts
                selected_host_list : [], // @type list of selected hosts...
                // Refresh status & messages
                refreshing_msg : "Refreshing...",
                next_refresh_msg : "Next ping in...",
                error_retry_msg : "Next refresh in...",
                status_text : 'Refreshing...',
                // Refresh tracking
                refresh_rate : 10, //
                refresh_in : 0, // to start off...
                refresh_timer : null, // holds the current timer
            }
        },
        mounted() {
            this.host_latency_map = this.host_map;
            this.host_list = Object.keys(this.host_map);
            this.selected_host_list = this.host_list.slice();
            this.getLatencyOfAvailableHosts();
        },
        methods : {
            // cancels the counter and pings the server
            pingNow() {
                this.status_text = this.refreshing_msg;
                this.refresh_in = 0;
                if (this.refresh_timer !== null) {
                    clearInterval(this.refresh_timer);
                }
                this.getLatencyOfAvailableHosts();
            },
            // updates the counter (minus 1). If the counter reaches 0 (Zero), a ping is sent to the server.
            updateCounter () {
                this.refresh_in--;
                if (this.refresh_in > 0) {
                    this.refresh_timer = setTimeout(this.updateCounter, 1000);
                } else {
                    // to make the system seem a little laid back..
                    setTimeout( () => { this.status_text = this.refreshing_msg; }, 200);
                    this.getLatencyOfAvailableHosts();
                }
            },
            // Pings server with list of selected hosts
            getLatencyOfAvailableHosts() {
                // console.log('pinging');
                let hosts_param = this.selected_host_list.join(',');
                // console.log(hosts_param);
                // As async is not possible with core PHP (without any plugins), one way of having
                // concurrent execution would be to create promisses for every parameter and
                axios.get('/ping?hosts='+hosts_param)
                    .then( (response) => {
                        // console.log('Ping Response');
                        // console.log(response.data);
                        this.host_latency_map = response.data;
                        this.status_text = this.next_refresh_msg;
                    })
                    .catch(( err ) => {
                        this.status_text = this.error_retry_msg;
                    }).then(() => { // finally, reset the timer...
                        this.refresh_in = this.refresh_rate + 1;
                        this.refresh_timer = setTimeout(this.updateCounter(), 1000);
                    });
            },
            // Manages addition of host into this.host_list and this.selected_host_list
            addHost(new_host_name) {
                if (this.$stringHelper.isValidHostName(new_host_name)) {
                    if (this.host_list.includes(new_host_name)) {
                        toast({
                            type: 'success',
                            title: "'" + new_host_name + "' already exists and was not added to list."
                        });
                    } else {
                        let new_host = {};
                        this.host_list.push(new_host_name);
                        new_host[new_host_name] = 'TBA';
                        this.host_latency_map = $.extend({}, new_host, this.host_latency_map);
                        this.selectHost(new_host_name);
                        toast({
                            type: 'success',
                            title: "Added '" + new_host_name + "' to list."
                        });
                    }
                } else {
                    toast({
                        type: 'error',
                        title: "'" + new_host_name + "' is not a valid host name."
                    });
                }
            },
            // Add host to this.selected_host_list
            selectHost(host_name) {
                // if not already selected
                if (this.selected_host_list.indexOf(host_name) == -1) {
                    this.selected_host_list.push(host_name);
                }
            },
            // Remove
            unselectHost(host_name) {
                let selected_host_index = this.selected_host_list.indexOf(host_name);
                // if definitely selected
                if (selected_host_index !== -1) {
                    this.selected_host_list.splice(selected_host_index, 1);
                }
            },
            /* Removes contents of this.host_list that are present in $this.selected_host_list and
             * clears this.selected_host_list
             */
            deleteSelectedHosts() {
                this.selected_host_list.forEach((item, index) => {
                    this.deleteHost(item, false);
                });
                this.selected_host_list.splice(0, this.selected_host_list.length);
                toast({
                    type: 'success',
                    title: 'Removed all selected Host names/IP addresses.'
                });
            },
            /* Removes given hostname from this.host_list
             */
            deleteHost(host_name, showText = true) {
                // unselect the host
                // this.unselectHost(host_name);
                // and remove from host_list
                let host_index = this.host_list.indexOf(host_name);
                if (host_index > -1) {
                    this.host_list.splice(host_index , 1);
                    if (showText == true) {
                        toast({
                            type: 'success',
                            title: 'Removed ' + host_name
                        });
                    }
                }
            },
        },
    }
</script>
<style scoped>
    .latency-container {
        width: 100%;
        margin: 0;
        padding: 0;
    }
    .center-text {
        text-align: center;
    }
    .centered {
        margin-right: auto;
        margin-left: auto;
    }
    .title-area {
        margin: 40px auto 20px;
    }
    .subtitle {
        color: gray;
    }
    .host-table {
        width: 60%;
        font-size: 1.5em;
    }
</style>