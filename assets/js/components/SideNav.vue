<script>
    export default {
        data() {
            return {
                isActive: ''
            }
        },
        computed: {
            showSublist() {
                return [
                    this.isActive ? 'active open' : ''
                ];
            }
        },
        methods: {
            toggleNav() {
                if(this.isActive == true) {
                    this.isActive = false;
                } else {
                    this.isActive = true;
                }
            }
        }
    }
</script>