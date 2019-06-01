<script>
    export default {

        props: ['id'],

        data()  {
            return {
                msg: {},
                currency: ' (EUR)',
                title: null,
                shopCountry: 'DE',
                description: null,
                stock: null,
                sellPrice: '',
                productId: this.id
            }
        },

        mounted () {
            axios.get('/aliexpress/data?id=' + this.productId)
                .then(response => (
                    this.title = response.data.title,
                    this.stock = response.data.stock,
                    this.description = response.data.description
                ));
        },

        watch: {
            shopCountry: function () {
                this.shopCountry === 'GB' ? this.currency = ' (GBP)' : this.currency = ' (EUR)';
            }
        },

        methods: {
            checkForm: function (e)
            {
                this.msg = {};
                this.checkTitle();
                this.checkSellPrice();
                this.checkShopCountry();
                this.checkStock();
                this.checkDescription();

                if (Object.keys(this.msg).length === 0) {
                    return true;
                }
                e.preventDefault();
            },

            checkTitle: function ()
            {
                if (!this.title || !(this.title.trim())) {
                    this.msg.title = 'Please, provide product name';
                } else if (this.title.trim().length > 80) {
                    this.msg.title = 'Oops, product\'s name shouldn\'t exceed 80 characters!';
                }

                return !this.msg.hasOwnProperty('title');
            },

            checkSellPrice: function ()
            {
                if (!this.sellPrice) {
                    this.msg.sellPrice = 'Please, enter product\'s sales price';
                } else if (this.isNotFloat(this.sellPrice)) {
                    this.msg.sellPrice = 'Please, enter number';
                } else if (this.countDecimals() > 2) {
                    this.msg.sellPrice = 'Please, enter price with two decimal places';
                } else if (this.sellPrice < 0) {
                    this.msg.sellPrice = 'Price can\'t be negative';
                }

                return !this.msg.hasOwnProperty('sellPrice');
            },

            checkShopCountry: function ()
            {
                if (!this.shopCountry || !(this.shopCountry.trim())) {
                    this.msg.shopCountry = 'Please, select Germany or United Kingdom';
                } else if (this.shopCountry !== 'DE' && this.shopCountry !== 'GB') {
                    this.msg.shopCountry = 'Please, select Germany or United Kingdom';
                }

                return !this.msg.hasOwnProperty('shopCountry');
            },

            checkStock: function ()
            {
                let number = this.stock;
                if (!number) {
                    this.msg.stock = 'Please, enter product\'s stock value';
                } else if (this.isNotInteger(number)) {
                    this.msg.stock = 'Please, enter number';
                } else if (number < 0) {
                    this.msg.stock = 'Stock can\'t be negative';
                }

                if (!this.msg.hasOwnProperty('stock')) {
                    this.stock = parseInt(number);
                }

                return !this.msg.hasOwnProperty('stock');
            },

            checkDescription: function ()
            {
                if (!this.description || !(this.description.trim())) {
                    this.msg.description = 'Please, provide product\'s description';
                }

                return !this.msg.hasOwnProperty('description');
            },

            isNotInteger: function(value)
            {
                var number = parseInt(value);
                return Number.isNaN(number);
            },

            isNotFloat: function (value)
            {
                var number = parseFloat(value);
                return Number.isNaN(number);
            },

            countDecimals: function() {
                try {
                    this.sellPrice = parseFloat(this.sellPrice);
                } catch (e) {
                    return 0;
                }
                if (Math.floor(this.sellPrice) !== this.sellPrice)
                    return this.sellPrice.toString().split(".")[1].length || 0;
                return 0;
            }
        }
    }
</script>