<template>
    <div class="col-md-5 my-4 p-3 bg-white post-block">
        <div class="d-flex border-bottom pb-2 justify-content-between">
            <div>
                {{post.created_at}}
            </div>
            <div class="d-flex justify-content-end text-right">
                <div class="pl-3" v-on:click="callEditModal(post)">
                    <i class="fa fa-edit action-btn"></i>
                </div>
                <div class="pl-3" v-on:click="deletePost(post)">
                    <i class="fa fa-trash action-btn"></i>
                </div>
            </div>
        </div>
        <br>
        <div class="post_preview" @click="callDetailsModal(post)">
            <img :src="post.real_image" class="w-100 h-250-px" alt="">
            <h3 class="post_preview_title">{{post.title}}</h3>
        </div>
    </div>
</template>

<script>

export default {
    name: 'Post',
    props: {
        post:{
            type: Object,
            required: true
        }
    },
    methods: {
        callEditModal(post){
            this.$emit('call_edit_post_modal', post)
        },
        deletePost(post){
            this.$emit('delete_post', post)
        },
        callDetailsModal(post){
            this.$emit('call_details_modal', post)
        }
    }
}
</script>