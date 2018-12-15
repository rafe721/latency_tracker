<template>
    <div>
        <p> Check latency of host names or IP addresses (IPv4)</p>
        <p class="centered" v-if="refresh_in != 0">{{refresh_in}}</p>
        <div v-if="refresh_in == 0">
            refreshing...
        </div>
        <host-record v-for="(host_name, index) in host_list"
                     v-bind:key="index"
                     v-bind:latency="host_latency_list.hasOwnProperty(host_name) ? host_latency_list[host_name] : 'TBA' "
                     v-bind:host_name="host_name"
        v-on:delete-host="deleteHost"></host-record>
        <add-host v-on:add-host="addHost"></add-host>

    </div>
</template>

<script>
    export default {
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
            this.getLatencyOfAvailableHosts();
        },
        data() {
            return {
                host_latency_list :{

                },
                host_list : [],
                refresh_in : 0
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
            defaultCall() {
                console.log('Working');
            },
            deleteHost(host_name) {
                var host_index = this.host_list.indexOf(host_name);
                if (host_index > -1) {
                    this.host_list.splice(host_index , 1);
                }
                this.host_latency_list = _.omit(this.host_latency_list, host_name);
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
            getLatencyOfAvailableHosts() {
                console.log('pinging');
                let hosts_param = this.host_list.join(',');
                axios.get('/ping?hosts='+hosts_param)
                    .then( (response) => {
                        console.log('Ping Response');
                        console.log(response.data);
                        this.host_latency_list = response.data;
                        this.host_list = Array.from(new Set(this.host_list.concat(Object.keys(this.host_latency_list))));
                        // this.host_list = Object.keys(this.host_latency_list);
                        // setTimeout(this.getLatencyOfAvailableHosts, 10000);
                        this.refresh_in = 11;
                        setTimeout(this.updateCounter(), 1000);
                    })
                    .catch(() => {

                    });
            }
        },
    }
</script>
<style scoped>

</style>