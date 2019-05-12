<template>
    <li class="item d-block" v-if="count > 1">
        <div class="item-row">
            <div class="item-col fixed item-col-img xs">
                <a href="">
                    <div class="item-img xs rounded" style="background-image: url(https://s3.amazonaws.com/uifaces/faces/twitter/brad_frost/128.jpg)"></div>
                </a>
            </div>
            <div class="item-col item-col-title flex-column">
                <div class="align-content-center pb-2" v-for="items in this.transaction.Transaction">
                    <a href="" class="">
                        <h4 class="item-title">{{ items.Item.Title}}</h4>
                    </a>
                </div>
            </div>
            <div class="item-col item-col-sales flex-column justify-content-center">
                <div class="item-heading">Buy</div>
                <div v-for="price in this.transaction.Transaction">
                    <span class="d-block">{{ price.TransactionPrice.currencyID  }}</span>
                    <span class="d-block">{{ price.TransactionPrice.value  }}</span>
                </div>
            </div>
            <div class="item-col item-col-stats flex-column justify-content-center">
                <div class="item-heading">Sold</div>
                <div class="no-overflow align-content-center" v-for="quantity in this.transaction.Transaction">
                    <div class="item-stats sparkline d-block" data-type="bar">
                        {{ quantity.QuantityPurchased }} Qty
                    </div>
                </div>
            </div>
            <div class="item-col item-col-date">
                <div class="item-heading">Published</div>
                <div></div>
            </div>
            <div class="item-col item-col-actions">
                <div class="item-heading">Shipping Details</div>
                <div> <button type="button" class="btn btn-primary open" v-on:click="isActive = !isActive">Details</button></div>
            </div>
        </div>
        <div class="order-details p-2" :class="showInfo" v-if="isActive">
            <div class="row">
                <div class="pull-left text-center buyer-text">
                    <h3>Buyer</h3>
                </div>
                <div class="shipping-details col-sm-4 col-xs-8 col-md-4 col-lg-4">
                   <ul class="list-group">
                       <li><i class="fas fa-user"></i> Name: {{ this.shipping.Name }}</li>
                       <li><i class="fas fa-map-marker-alt"></i> Location: {{ this.shipping.Street1 + this.shipping.Street2}}</li>
                       <li><i class="fas fa-city"></i> City: {{ this.shipping.CityName }}</li>
                       <li><i class="fas fa-flag"></i> Country: {{ this.shipping.CountryName }}</li>
                       <li><i class="fas fa-truck"></i> Postal: {{ this.shipping.PostalCode }}</li>
                       <li><i class="fas fa-phone"></i> Phone: {{ this.shipping.Phone }}</li>
                   </ul>
                </div>
            </div>
        </div>
    </li>
    <li class="item" v-else>
        <div class="item-row">
            <div class="item-col fixed item-col-img xs">
                <a href="">
                    <div class="item-img xs rounded" style="background-image: url(https://s3.amazonaws.com/uifaces/faces/twitter/brad_frost/128.jpg)"></div>
                </a>
            </div>
            <div class="item-col item-col-title" v-for="items in this.transaction.Transaction">
                <div>
                    <a href="" class="">
                        <h4 class="item-title">{{ items.Item.Title}}</h4>
                    </a>
                </div>
            </div>
            <div class="item-col item-col-sales">
                <div class="item-heading">Buy</div>
                <div v-for="price in this.transaction.Transaction">
                    <span class="d-block">{{ price.TransactionPrice.currencyID  }}</span>
                    <span class="d-block">{{ price.TransactionPrice.value  }}</span>
                </div>
            </div>
            <div class="item-col item-col-stats">
                <div class="item-heading">Sold</div>
                <div class="no-overflow" v-for="quantity in this.transaction.Transaction">
                    <div class="item-stats sparkline" data-type="bar">
                        {{ quantity.QuantityPurchased }} Qty
                    </div>
                </div>
            </div>
            <div class="item-col item-col-date">
                <div class="item-heading">Published</div>
                <div></div>
            </div>
            <div class="item-col item-col-actions">
                <div class="item-heading">Shipping Details</div>
                <div> <button type="button" class="btn btn-primary" v-on:click="isActive = !isActive">Details</button></div>
            </div>
        </div>
        <div class="order-details p-2" :class="showInfo" v-if="isActive">
            <div class="row">
                <div class="pull-left text-center buyer-text">
                    <h3>Buyer</h3>
                </div>
                <div class="shipping-details col-sm-4 col-xs-8 col-md-4 col-lg-4">
                   <ul class="list-group">
                       <li><i class="fas fa-user"></i> Name: {{ this.shipping.Name }}</li>
                       <li><i class="fas fa-map-marker-alt"></i> Location: {{ this.shipping.Street1 + this.shipping.Street2}}</li>
                       <li><i class="fas fa-city"></i> City: {{ this.shipping.CityName }}</li>
                       <li><i class="fas fa-flag"></i> Country: {{ this.shipping.CountryName }}</li>
                       <li><i class="fas fa-truck"></i> Postal: {{ this.shipping.PostalCode }}</li>
                       <li><i class="fas fa-phone"></i> Phone: {{ this.shipping.Phone }}</li>
                   </ul>
                </div>
                <div class="pull-left text-center buyer-text">
                    <h3>Actions</h3>
                </div>
                <div class="order-actions col-sm-4 col-xs-8 col-md-4 col-lg-4">
                    <button class="btn" :class="markAsSend" @click="markSendButton" v-model="sendText">{{ sendText }}</button>
                </div>
            </div>
        </div>
    </li>
</template>
<script>
    import moment from 'moment';

    export default {
        props: ['transaction', 'shipping', 'paidtime'],

        data() {
            return {
                isActive: false,
                count: this.transaction.Transaction.length,
                markSend: false,
                sendText: 'Mark As Send'
            }
        },
        computed: {
            showInfo() {
                return ['order-details', this.isActive ? 'd-block' : 'd-none'];
            },
            markAsSend() {
                return ['btn', this.markSend ? 'btn-danger' : 'btn-success'];
            }
        },
        methods: {
            markSendButton() {
                if(this.markSend === false) {
                    this.markSend = true;
                    this.sendText = 'Cancel Send';
                    this.markSendToEbay();

                } else {
                    this.markSend = false;
                    this.sendText = 'Mark As Send';
                    this.cancelMarkSend();
                }
            },
            markSendToEbay() {
                alert('Sended To Ebay');
            },
            cancelMarkSend() {
                alert('Cancel mark as send.');
            }

        }
    }
</script>