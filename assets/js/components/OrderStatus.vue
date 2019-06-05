<template>
    <button class="d-block btn-block btn" style="border-radius: 4px;"
            :class="markAsSend"
            @click="markSendButton(order)"
            v-model="sendText">{{ sendText }}
    </button>
</template>
<script>
    export default {
        props: ['orderID', 'shippedTime'],

        data() {
            return {
                markSend: false,
                sendText: 'Mark As Dispatched',
                order: this.orderID,
                shipTime: this.shippedTime,
            }
        },

        mounted() {
            if (this.shipTime === undefined) {
                this.markSend = false;
                this.sendText = 'Mark As Dispatched';
            } else {
                this.markSend = true;
                this.sendText = 'Unmark';
            }
        },

        computed: {
            markAsSend() {
                return ['btn', this.markSend ? 'btn-danger' : 'btn-success'];
            }
        },

        methods: {
            markSendButton(id) {
                if(this.markSend === false) {
                    this.markSend = true;
                    this.markSendToEbay(id);
                    this.sendText = 'Unmark';
                } else {
                    this.markSend = false;
                    this.cancelMarkSend(id);
                    this.sendText = 'Mark As Dispatched';
                }
            },
            markSendToEbay(id) {
                alert('Dispatched.');
                axios.post('/api/ebay/markshipped/' + id)
                    .then(function (response) {
                        // handle success
                    });

            },
            cancelMarkSend(id) {
                alert('Dispatched was canceled.');
                axios.post('/api/ebay/unmarkshipped/' + id)
                    .then(function (response) {
                        console.log(response);
                    });
            }
        }
    }
</script>