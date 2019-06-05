<template>
    <div>
        <a href="#" class="">
            <div class="item-img rounded" v-bind:style="background"></div>
        </a>
    </div>
</template>
<script>
    export default {
        props: ['ebay', 'product'],

        data() {
            return {
                hasImage: '',
                noImg: 'https://s3.amazonaws.com/uifaces/faces/twitter/brad_frost/128.jpg',
                hasAliexpress: ''
            }
        },
        computed: {
            background () {
                return 'background: url('+this.hasImage+')'
            }
        },
        mounted() {
            if (this.product.aliexpress != null && this.product.aliexpress !== 0) {
                if(typeof this.product.aliexpress.image_link !== 'undefined') {
                    this.hasAliexpress = this.product.aliexpress.image_link;
                } else {
                    this.hasAliexpress = null;
                }
            }

            if(this.hasAliexpress === "" || this.hasAliexpress === 0) {
                if(typeof this.ebay.PictureDetails !== 'undefined' ) {
                    this.hasImage = this.ebay.PictureDetails.GalleryURL;
                } else {
                    this.hasImage = 'https://s3.amazonaws.com/uifaces/faces/twitter/brad_frost/128.jpg';
                }
            } else {
                this.hasImage = this.hasAliexpress;
            }
        }
    }
</script>
<style>
    .item-img {
        background-repeat: no-repeat !important;
        background-position: 50px 150px !important;
        background-size: cover !important;
        background-position: center !important;
    }
</style>