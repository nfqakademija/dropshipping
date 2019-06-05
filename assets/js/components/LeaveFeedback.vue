<template>
    <div>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle btn-block" style="border-radius: 4px;" type="button" id="dropdownFeedback" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Leave Feedback
            </button>
            <div class="dropdown-menu p-2" style="background: #fbf8ff;" aria-labelledby="dropdownFeedback">
                <form>
                    <textarea class="form-control" placeholder="Enter feedback message..." v-model="feedbackMessage"></textarea>
                    <button type="button" class="btn btn-sm btn-success mt-1"
                            @click="submitFeedback(transactionID, orderline, itemID, orderID)"
                            :disabled="isDisabled">Submit</button>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['transactionID', 'orderline', 'itemID', 'orderID'],

        data() {
            return {
                isActive: false,
                disabled: 0,
                feedbackMessage: ''
            }
        },

        computed: {
            isDisabled() {
                return !this.feedbackMessage;
            }
        },

        methods: {
            submitFeedback(transactionID, orderline, itemID, orderID) {

                if(this.feedbackMessage.length < 3) {
                    alert('Before submitting you must enter feedback message.');
                } else {
                    axios.post('/api/ebay/leavefeedback/'
                        + transactionID + '/'
                        + orderline + '/'
                        + itemID + '/'
                        + orderID + '/'
                        + this.feedbackMessage
                        )
                        .then(function (response) {
                            console.log(response);
                        });
                    alert('Feedback Send successful. Thanks!');
                }
            }
        }
    }
</script>