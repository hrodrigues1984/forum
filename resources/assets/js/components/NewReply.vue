<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group">
                <textarea
                        v-model="body"
                        rows="5"
                        name="body"
                        id="body"
                        class="form-control"
                        required
                        placeholder="Have something to say"
                ></textarea>
            </div>
            <button type="submit" class="btn btn-default" @click="addReply">Post</button>
        </div>
        <p v-else class="text-center">Please <a href="/login">sign in</a> to participate in this discussion.</p>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                body: ''
            }
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            }
        },

        methods: {
            addReply()  {
                axios.post(`${location.pathname}/replies`, { body: this.body})
                    .then(({data}) => {
                        this.body = '';

                        flash('Your reply has been posted.');

                        this.$emit('created', data);
                    });
            }
        }
    }
</script>