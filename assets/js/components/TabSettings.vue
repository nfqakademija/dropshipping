<template>
    <ul class="nav nav-tabs pull-right" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="#all-orders" role="tab" data-toggle="tab">All</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#not-shipped-orders" role="tab" data-toggle="tab">
                Not Shipped - <span style="color:red;">({{ this.not_shipped }}) </span>
            </a>
        </li>
    </ul>
</template>
<script>
    export default {
        props: ['not_shipped']
    }
</script>