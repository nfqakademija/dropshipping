<template>
    <li class="item" v-if="count > 1">
        <div class="item-row">
            <div class="item-col fixed item-col-img xs">
                <a href="" >
                    <div class="item-img xs rounded" style="background-image: url(https://s3.amazonaws.com/uifaces/faces/twitter/brad_frost/128.jpg)"></div>
                </a>
            </div>
            <div class="item-col item-col-title flex-column" style="flex-grow: 6;">
                <div class="align-content-center pb-2" v-for="items in this.transaction.Transaction">
                    <a href="" class="">
                        <h4 class="item-title">{{ items.Item.Title}}</h4>
                    </a>
                </div>
            </div>
            <div class="item-col item-col-sales flex-column text-center">
                <div class="item-heading">Buy</div>
                <div class="item-col item-col-sales text-center">
                    <div class="item-heading">Buy</div>
                    <div class="" v-for="price in this.transaction.Transaction">
                        <div class="btn-group">
                            <button class="btn btn-success-outline d-block" style="border-radius: 4px;" v-if="order.profit > 0"><i class="fa fa-arrow-up"></i> {{ order.profit }} &euro;</button>
                            <button class="btn btn-primary-outline d-block" style="border-radius: 4px;">{{ price.TransactionPrice.value }} &euro;</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item-col item-col-stats flex-column text-center">
                <div class="item-heading">Sold</div>
                <div class="no-overflow align-content-center" v-for="quantity in this.transaction.Transaction">
                    <div class="item-stats sparkline d-block" data-type="bar">
                        {{ quantity.QuantityPurchased }} Qty
                    </div>
                </div>
            </div>
            <div class="item-col item-col-date text-center">
                <div class="item-heading">Paid Time</div>
                <div v-text="ago"></div>
            </div>
            <div class="item-col item-col-actions">
                <div class="item-heading">Shipping Details</div>
                <div>
                    <button type="button" class="btn btn-purple" v-on:click="isActive = !isActive">
                        <span>Details</span>
                    </button>
                </div>
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
                    <h3>Product</h3>
                </div>
                <div class="product-details col-sm-4 col-xs-8 col-md-2 col-lg-2">
                    <button class="btn btn-primary" type="button" v-if="order.type">{{ order.type }}</button>
                    <button class="btn btn-primary" type="button" v-else>None</button>
                </div>
                <div class="pull-left text-center buyer-text">
                    <h3>Actions</h3>
                </div>
                <div class="order-actions col-sm-4 col-xs-8 col-md-4 col-lg-3 align-items-center">
                    <order-status :orderID="orderID" :shippedTime="shippedTime"></order-status>
                    <add-tracking :orderID="orderID"></add-tracking>
                    <div v-for="feed in this.transaction.Transaction">
                        <leave-feedback
                                :transactionID="feed.TransactionID"
                                :orderline="feed.OrderLineItemID"
                                :itemID="feed.Item.ItemID"
                                :orderID="orderID"></leave-feedback>
                    </div>
                </div>
            </div>
        </div>
    </li>
    <li class="item" v-else>
        <div class="item-row">
            <div class="item-col fixed item-col-img xs">
                <a href="" v-for="gallery in this.transaction.Transaction">
                    <div class="item-img xs rounded" v-bind:style="background" :style="changeBackground(order.defaultImage[gallery.Item.ItemID])"></div>
                </a>
            </div>
            <div class="item-col item-col-title" v-for="items in this.transaction.Transaction" style="flex-grow: 6;">
                <div class="">
                    <a href="" class="">
                        <h4 class="item-title">{{ items.Item.Title}}</h4>
                    </a>
                </div>
            </div>
            <div class="item-col item-col-sales text-center">
                <div class="item-heading">Buy</div>
                <div class="" v-for="price in this.transaction.Transaction">
                    <div class="btn-group">
                        <button class="btn btn-success-outline" style="border-top-left-radius: 4px; border-bottom-left-radius: 4px;" v-if="order.profit > 0"><i class="fa fa-arrow-up"></i> {{ Number.parseFloat(order.profit).toFixed(2) }} &euro;</button>
                        <button class="btn btn-primary-outline" style="border-top-right-radius: 4px; border-bottom-right-radius: 4px;">{{ price.TransactionPrice.value }} &euro;</button>
                    </div>
                </div>
            </div>
            <div class="item-col item-col-stats text-center">
                <div class="item-heading">Sold</div>
                <div class="no-overflow" v-for="quantity in this.transaction.Transaction">
                    <div class="item-stats sparkline" data-type="bar">
                        {{ quantity.QuantityPurchased }} Qty
                    </div>
                </div>
            </div>
            <div class="item-col item-col-date text-center">
                <div class="item-heading">Paid Tim</div>
                <div v-text="ago"></div>
            </div>
            <div class="item-col item-col-actions">
                <div class="item-heading">Shipping Details</div>
                <div> <button type="button" class="btn btn-purple" v-on:click="isActive = !isActive">Details</button></div>
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
                    <h3>Product</h3>
                </div>
                <div class="product-details col-sm-4 col-xs-8 col-md-2 col-lg-2">
                    <button class="btn btn-primary" type="button" v-if="order.type">{{ order.type }}</button>
                    <button class="btn btn-primary" type="button" v-else>None</button>
                </div>
                <div class="pull-left text-center buyer-text">
                    <h3>Actions</h3>
                </div>
                <div class="order-actions col-sm-4 col-xs-8 col-md-4 col-lg-3 align-items-center">
                    <order-status :orderID="orderID" :shippedTime="shippedTime"></order-status>
                    <add-tracking :orderID="orderID"></add-tracking>
                    <div v-for="feed in this.transaction.Transaction">
                        <leave-feedback
                                :transactionID="feed.TransactionID"
                                :orderline="feed.OrderLineItemID"
                                :itemID="feed.Item.ItemID"
                                :orderID="orderID"></leave-feedback>
                    </div>
                </div>
            </div>
        </div>
    </li>
</template>
<script>
    import moment from 'moment';
    import orderStatus from './OrderStatus.vue';
    import leaveFeedback from './LeaveFeedback';
    import addTracking from './addTrackingNumber';
    export default {
        props: [
            'ebayorder',
            'transaction',
            'shipping',
            'order'
        ],
        components: { orderStatus, leaveFeedback, addTracking },

        data() {
            return {
                orderID: this.ebayorder.OrderID,
                isActive: false,
                count: this.transaction.Transaction.length,
                shippedTime: this.ebayorder.ShippedTime,
                status: false,
                hasImage: '',
                hasAliexpress: ''
            }
        },
        computed: {
            background() {
                return 'background: url('+this.hasImage+')'
            },
            showInfo() {
                return ['order-details', this.isActive ? 'd-block' : 'd-none'];
            },
            ago() {
                let time = moment(this.ebayorder.CreatedTime).format('YYYY-MM-DD HH:MM');
                return time;
            }
        },
        mounted() {
            if (this.order.ali != null) {
                if(typeof this.order.ali !== 'undefined') {
                    this.hasAliexpress = this.order.ali;
                } else {
                    this.hasAliexpress = null;
                }
            }
        },
        methods: {
            changeBackground(defaultImage) {
                if (this.hasAliexpress === "" || this.hasAliexpress === 0) {
                    if (defaultImage != null) {
                        this.hasImage = defaultImage;
                    } else {
                        this.hasImage = 'https://s3.amazonaws.com/uifaces/faces/twitter/brad_frost/128.jpg';
                    }
                } else {
                    this.hasImage = this.hasAliexpress;
                }

                return this.hasImage;
            }
        }
    }
</script>