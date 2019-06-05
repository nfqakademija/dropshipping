<template>
    <div class="row">
            <div class="col-sm-12 col-md-12 col-xl-6 ml-auto d-flex align-items-center" v-if="countProfit != 0">
                <button class="btn btn-success-outline" style="border-radius: 4px; white-space: initial; word-wrap: break-word" :profit="profitCounter" v-if="countProfit != 0">
                    <span class="d-block">Total:<br> {{ countProfit }} &euro;</span>
                </button>
            </div>
            <div class="col-sm-12 col-md-12 col-xl-6">
                <button class="btn btn-sm btn-primary-outline btn-block" style="border-radius: 4px;" @click="togglePriceChange">
                    <span v-if="isChangedPrice == false">Ebay:<br> {{ this.ebayPrice }} &euro;</span>
                    <div class="" :display="showEditor" v-else>
                        <button class="btn btn-sm btn-link" style="color: #000;" type="button" @click="cancelPriceChange">Discard</button>
                        <input class="form-control" type="number" style="-webkit-appearance: none; " v-model="changePrice">
                    </div>
                </button>
                <button class="btn btn-sm btn-primary-outline btn-block" style="border-radius: 4px;" v-if="this.isAliexpress === true">
                    Ali:<br> {{ this.product.aliexpress.price }} &euro;
                </button>
                <div v-else>
                </div>
            </div>
    </div>
</template>
<script>
    export default {
        props: ['ebay', 'product'],

        data() {
            return {
                isAliexpress: false,
                isAmazon: false,
                isChangedPrice: false,
                isPrice: true,
                changePrice: '',
                ebayPrice: this.ebay.BuyItNowPrice.value,
                sellPrice: '',
                priceCounter: '',
                countProfit: '',
                profit: ''
            }
        },

        computed: {
            showEditor() {
                return [
                    this.isChangedPrice ? '' : 'd-none'
                ];
            },
            profitCounter() {
                if (this.isChangedPrice === true) {
                    this.countProfit =  this.changePrice - this.sellPrice;
                    this.countProfit = Number.parseFloat(this.countProfit).toFixed(2);
                }
            },
            showProfit() {
                if(this.isAliexpress === true) {
                    this.countProfit =  this.ebayPrice - this.sellPrice;
                    this.countProfit = Number.parseFloat(this.countProfit).toFixed(2);
                } else {
                    this.countProfit = 0;
                }
                return this.countProfit;
            }
        },

        mounted() {
            if(this.product != null) {
                if(this.product.aliexpress) {
                    this.isAliexpress = true;
                    this.sellPrice = this.product.aliexpress.price;
                    this.countProfit =  this.ebayPrice - this.sellPrice;
                    this.countProfit = Number.parseFloat(this.countProfit).toFixed(2);
                } else {
                    this.isAliexpress = false;
                }
            } else {
                return null;
            }
        },

        methods: {
            togglePriceChange() {
                this.isChangedPrice = true;
                this.isPrice = false;
                this.changePrice = this.ebayPrice;
            },
            cancelPriceChange() {
                this.isChangedPrice = false;
                if(this.isChangedPrice == true) {
                    this.isChangedPrice = false;
                } else {
                    this.isChangedPrice = true;
                }
            },
            openPriceChange() {
                this.isChangedPrice = true;
            },
            formatPrice(value) {
                let val = (value/1).toFixed(2).replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            }
        }
    }
</script>
<style>
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>