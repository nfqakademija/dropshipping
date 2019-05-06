<template>
    <div class="item-col fixed item-col-actions-dropdown">
        <div class="item-actions-dropdown" :class="classes" @click="toggle" :attribute="20">
            <a class="item-actions-toggle-btn">
                <span class="inactive">
                    <i class="fa fa-cog"></i>
                 </span>
                <span class="active">
                    <i class="fa fa-chevron-circle-right"></i>
                </span>
            </a>
            <div class="item-actions-block active">
                <ul class="item-actions-list">
                    <li>
                        <a class="remove" v-on:click="askForDelete()" href="#">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li>
                        <a class="edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['ebay-item'],

        data() {
            return {
                isActive: '',
                isItem: this.ebayItem
            }
        },

        computed: {
            classes() {
                return [
                    'item-actions-dropdown',
                    this.isActive ? 'active' : 'inactive'
                ];
            }
        },
        methods: {
            toggle() {
                if(this.isActive == true) {
                    this.isActive = false;
                } else {
                    this.isActive = true;
                }
            },
            askForDelete() {
                var item = confirm("Are you sure you want delete this item?");
                if(item == true) {
                    alert('Taip');
                } else {
                    alert('Ne');
                }
            }
        }
    }
</script>