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
            initZoom()

            this.$refs.vue_post_detail_modal.addEventListener('hidden.bs.modal', event => {
                this.$emit('clear_modal')
            })
        },  
        methods: {

        }
    }

    function initZoom(){
        // Zoom scripts

        var scale = 1,
        panning = false,
        pointX = 0,
        pointY = 0,
        start = { x: 0, y: 0 },
        zoom = document.getElementById("zoom");

        document.addEventListener('hidden.bs.modal', event => {
            scale = 1
            setTransform()
        })

        function setTransform() {
            zoom.style.transform = "translate(" + pointX + "px, " + pointY + "px) scale(" + scale + ")";
        }

        zoom.onmousedown = function (e) {
            e.preventDefault();
            start = { x: e.clientX - pointX, y: e.clientY - pointY };
            panning = true;
            zoom.style.cursor = 'grabbing'
        }

        zoom.onmouseup = function (e) {
            panning = false;
            zoom.style.cursor = 'grabbing'
        }

        zoom.onmousemove = function (e) {
            zoom.style.cursor = 'grabbing'
            e.preventDefault();
            if (!panning) {
                return;
            }
            pointX = (e.clientX - start.x);
            pointY = (e.clientY - start.y);
            setTransform();
        }

        zoom.onwheel = function (e) {
            e.preventDefault();
            var delta = (e.wheelDelta ? e.wheelDelta : -e.deltaY);
            if(delta > 0){
                scale *= 1.2
                zoom.style.cursor = 'zoom-in'
            }else{
                if(scale >= 1){
                    scale /= 1.2
                    zoom.style.cursor = 'zoom-out'
                }
            }

            setTransform();
        }
    }

</script>
