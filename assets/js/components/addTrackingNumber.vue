<template>
    <div>
        <div class="dropdown">
            <button class="btn btn-warning dropdown-toggle btn-block" type="button" id="dropdownTracking" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Add Tracking
            </button>
            <div class="dropdown-menu p-2" style="background: #fbf8ff;" aria-labelledby="dropdownTracking">
                <form>
                    <div class="form-group">
                    <label for="tracking_carrier">Carrier:</label>
                    <input class="form-control" type="text" name="tracking_carrier" placeholder="Enter Carrier..." v-model="trackingCarrier" required>
                    </div>
                    <div class="form-group">
                    <label for="tracking_number">Number:</label>
                    <input class="form-control" type="text" name="tracking_number" placeholder="Enter Trackin number..." v-model="trackingNumber" required>
                    </div>
                    <button class="btn btn-success mt-1" type="button" :disabled="isDisabled" @click="addTracking">Submit</button>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['orderID'],

        data() {
            return {
                trackingCarrier: '',
                trackingNumber: '',
                disabled: 0
            }
        },

        computed: {
            isDisabled() {
                return !this.trackingCarrier || !this.trackingNumber;
            }
        },

        methods: {
            addTracking() {
                alert('Labas');
            }
        }
    }
</script>