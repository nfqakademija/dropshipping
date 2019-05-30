<script>
    export default {

        props: ['id'],

        data()  {
            return {
                msg: {},
                title: '',
                productId: this.id,
            }
        },

        methods: {
            checkForm: function (e)
            {
                e.preventDefault();

                this.checkTitle();

            },

            checkTitle: function ()
            {
                if (!this.title || !(this.title.trim())) {
                    this.msg.title = 'Please, provide product name';
                } else if (this.title.trim().length > 80) {
                    this.msg.title = 'Oops, product\'s name shouldn\'t exceed 80 characters!';
                }
            },

        }
    }
</script>