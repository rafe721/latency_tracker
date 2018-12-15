<template>
    <div>
        <p> Check latency of host names or IP addresses (IPv4)</p>
        <p class="centered" v-if="refresh_in != 0">{{refresh_in}}</p>
        <div v-if="refresh_in == 0">
            refreshing...
        </div>
        <table class="table table-striped table-dark">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Host name or IP (v4)</th>
                    <th scope="col">Observed Latency (in milli-sec)</th>
                    <th scope="col">Remove Selected</th>
                </tr>
            </thead>
            <tbody>
                <host-table-record v-for="(host_name, index) in host_list"
                                   v-bind:key="index"
                                   v-bind:is_selected="selected_host_list.indexOf(host_name) != -1 ? true : false "
                                   v-bind:host_name="host_name"
                                   v-bind:latency="host_latency_list.hasOwnProperty(host_name) ? host_latency_list[host_name] : 'TBA' "
                                   v-on:delete-host="deleteHost"
                                   v-on:select-host="selectHost"
                                   v-on:unselect-host="unselectHost">
                    >
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
        mounted() {
            this.host_latency_list = this.host_map;
            this.host_list = Object.keys(this.host_map);
            this.selected_host_list = this.host_list.slice();
            this.getLatencyOfAvailableHosts();
        },
        data() {
            return {
                host_latency_list :{},
                host_list : [],
                selected_host_list : [],
                refresh_rate : 10, //
                refresh_in : 0 // to start off...
            }
        },
        methods : {
            updateCounter () {
                this.refresh_in--;
                if (this.refresh_in > 0) {
                    setTimeout(this.updateCounter, 1000);
                } else {
                    this.refresh = "refreshing...";
                    this.getLatencyOfAvailableHosts();
                }
            },
            getLatencyOfAvailableHosts() {
                console.log('pinging');
                let hosts_param = this.selected_host_list.join(',');
                console.log(hosts_param);
                axios.get('/ping?hosts='+hosts_param)
                    .then( (response) => {
                        console.log('Ping Response');
                        console.log(response.data);
                        this.host_latency_list = response.data;
                        this.host_list = Array.from(
                            new Set(
                                this.host_list.concat(
                                    Object.keys(this.host_latency_list)
                                )
                            )
                        );
                        // this.host_list = Object.keys(this.host_latency_list);
                        // setTimeout(this.getLatencyOfAvailableHosts, 10000);
                        this.refresh_in = this.refresh_rate + 1;
                        setTimeout(this.updateCounter(), 1000);
                    })
                    .catch(() => {
                        this.refresh_in = this.refresh_rate + 1;
                        setTimeout(this.updateCounter(), 1000);
                    });
            },
            defaultCall() {
                console.log('Working');
            },
            addHost(new_host_name) {
                if (this.host_list.includes(new_host_name)) {
                    console.log(new_host_name + " exists..");
                } else {
                    let new_host = {};
                    this.host_list.push(new_host_name);
                    new_host[new_host_name] = null;
                    this.host_latency_list = $.extend({}, new_host, this.host_latency_list);
                }
            },
            selectHost(host_name) {
                console.log("Selecting " + host_name);
                if (this.selected_host_list.indexOf(host_name) !== -1) {
                    this.selected_host_list.push(host_name);
                }
            },
            unselectHost(host_name) {
                console.log("Unselecting " + host_name);
                let selected_host_index = this.selected_host_list.indexOf(host_name);
                if (selected_host_index !== -1) {
                    this.selected_host_list.splice(selected_host_index, 1);
                }
            },
            deleteHost(host_name) {
                var host_index = this.host_list.indexOf(host_name);
                if (host_index > -1) {
                    this.host_list.splice(host_index , 1);
                }
                this.host_latency_list = _.omit(this.host_latency_list, host_name);
            },
        },
    }
</script>
<style scoped>

</style>