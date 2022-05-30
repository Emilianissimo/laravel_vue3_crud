<template>
    <div class="modal fade" ref="vue_post_detail_modal" id="post_detail_modal" tabindex="-1" aria-labelledby="post_detail_modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="post_detail_modalTitle">{{post.title}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-around align-items-center">
                    <div class="col-md-12 image-zoom-box">
                        <div id="zoom">
                            <img :src="post.real_image" class="w-100" style="max-height: 500px;object-fit: contain;" alt="">
                        </div>
                    </div>
                    <div class="col-md-12 p-3">
                        {{post.body}}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <p class="text-right">
                    {{post.created_at}}
                </p>
            </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'PostDetailsModal',
        props: {
            post: {
                type: Object
            }
        },
        mounted(){
            this.$refs.vue_post_detail_modal.addEventListener('hidden.bs.modal', event => {
                this.$emit('clear_modal')
            })
        },
        methods: {

        }
    }
</script>
