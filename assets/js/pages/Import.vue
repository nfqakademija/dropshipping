<script>
    export default {
        data()  {
            return {
                msg: {},
                importLink: null,
                importSource: 1
            }
        },

        methods: {
            checkForm: function (e)
            {
                if ((this.hasAliExpressProductId() && this.importSource === 1) || (this.hasAmazonProductId() && this.importSource === 2)) {
                    return true;
                }

                e.preventDefault();

                this.msg = {};

                this.checkLink();
            },

            checkLink: function ()
            {
                if (!this.importLink || !(this.importLink.trim())) {
                    this.msg.importLink = 'Oops, link to a product is missing!';
                } else if (Number(this.importSource) === 1 && !this.hasAliExpressProductId()) {
                    this.msg.importLink = 'Please provide full AliExpress link.';
                } else if (Number(this.importSource) === 2 && !this.hasAmazonProductId()) {
                    this.msg.importLink = 'Please provide full Amazon link.';
                }
            },

            /**
             *
             * @returns {boolean}
             */
            hasAliExpressProductId: function()
            {
                let reg = /[\/|_](\d+)\.html/;
                return reg.test(this.importLink);
            },

            /**
             *
             * @returns {boolean}
             */
            hasAmazonProductId: function()
            {
                var thisRegex = /dp\/([A-Z|0-9]{10})/;
                return thisRegex.test(this.importLink);
            }
        }
    }
</script>