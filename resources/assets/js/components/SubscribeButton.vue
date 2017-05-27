<template>
        <button :class="classes" @click="subscribe">Subscribe</button>
</template>

<script>
    export default {
        props: [ 'active' ],

        data() {
            return {
                isSubscribed: this.active
            }
        },

        computed: {
            classes() {
                return [ 'btn', this.isSubscribed ? 'btn-primary' : 'btn-default' ];
            }
        },

        methods: {
            subscribe() {
                axios[
                    (this.isSubscribed ? 'delete' : 'post')
                ](location.pathname + '/subscriptions').then(() => {
                    this.isSubscribed = ! this.isSubscribed;
                });

            }
        }
    }
</script>