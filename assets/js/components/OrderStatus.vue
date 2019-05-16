<template>
    <button class="btn"
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
                sendText: 'Mark As Send',
                order: this.orderID,
                shipTime: this.shippedTime,
            }
        },

        mounted() {
            if (this.shipTime === undefined) {
                this.markSend = false;
                this.sendText = 'Mark As Send';
            } else {
                this.markSend = true;
                this.sendText = 'Cancel Send';
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
                    this.sendText = 'Cancel Send';
                } else {
                    this.markSend = false;
                    this.cancelMarkSend(id);
                    this.sendText = 'Mark As Send';
                }
            },
            markSendToEbay(id) {
                alert('Sended To Ebay');
                axios.post('/api/ebay/markshipped/' + id)
                    .then(function (response) {
                        // handle success
                    });

            },
            cancelMarkSend(id) {
                alert('Cancel mark as send.');
                axios.post('/api/ebay/unmarkshipped/' + id)
                    .then(function (response) {
                        console.log(response);
                    });
            }
        }
    }
</script>