<template>
    <div class="container">
        <div class="row">
            <div class="col-md-2 bg-light" style="min-height:100vh">
                <div class="sidebar-menu">
                    <b class="all-posts" v-on:click="getAllPosts">News</b>
                    <hr>
                    <ul>
                        <SidebarCategory inline-template
                        v-for="category in categories"
                        v-bind:category="category"
                        v-bind:key="category.id"
                        v-on:click.native="getPostsByCategory(category.id)"
                        v-bind:class="(isSelected(category, chosen_category_id))?'selected':''"
                        ></SidebarCategory>
                    </ul>
                </div>
            </div>
            <div class="col-md-10">
                <div class="row align-items-center">
                    <div class="p-4">
                        <div class="row justify-content-around">
                            <div class="col-md-4">
                                <h3 class="text-white">{{category_title}}</h3>
                            </div>
                            <div class="col-md-8 row text-center">
                                <div class="col-md-4 col-12 new-post-btn">
                                    <button v-on:click="modal.title='Add post'; setMethod('POST')" type="button" class="btn btn-outline-brand" data-bs-toggle="modal" data-bs-target="#modal">Add new post</button>
                                </div>
                                <div class="col-6 col-md-4">
                                    <input type="number" min="1" placeholder="Limit posts" class="form-control" v-model="limiterGlobal">
                                </div>
                                <div class="col-6 col-md-4">
                                    <input type="text" placeholder="Search posts" class="form-control" v-model="searcherGlobal">
                                </div>
                            </div>
                        </div>
                        <hr class="text-white">
                        <div class="row justify-content-around align-items-center">
                            <div class="col-md-12 justify-content-between row">
                                <div class="col-6">
                                    <input v-if="category_is_choosen" min="1" type="number" placeholder="Limit posts" class="form-control" v-model="limiter">
                                </div>
                                <div class="col-6">
                                    <input v-if="category_is_choosen" type="text" placeholder="Search posts" class="form-control" v-model="searcher">
                                </div>
                            </div>
                            <TransitionGroup>
                                <Post
                                    v-for="post in filteredPosts"
                                    v-bind:post="post"
                                    v-bind:key="post.id"
                                    @call_edit_post_modal="onPostEditModalCalled"
                                    @delete_post="onPostDeleted"
                                    @call_details_modal="openPostDetailsModal"
                                >   
                                </Post>
                            </TransitionGroup>
                            <div class="item error text-white" v-if="(searcherGlobal&&!filteredPosts.length) || (searcher&&!filteredByCategoryPosts.length)">
                                <p>No results found!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" ref="vuemodal" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{modal.title}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-around align-items-center">
                        <div class="col-md-6">
                            <div v-if="error.seen" class="alert alert-danger">
                                Fill all blank fields
                            </div>
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" v-model="post.title" class="form-control" id="input-title">
                            </div>
                            <div class="form-group">
                                <label>Body</label>
                                <textarea rows="10" v-model="post.body" class="form-control" id="input-body"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Category</label>
                                <select class="form-control" id="select-category" v-model="post.category_id">
                                    <FormCategory inline-template
                                    v-for="category in categories"
                                    v-bind:category="category"
                                    v-bind:key="category.id"
                                    ></FormCategory>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <p class="text-center">
                                    <label for="image-modal-form">
                                        <img :src="modal.image.url" class="w-100 h-300-px image-form">
                                    </label>
                                </p>
                                <label for="image-modal-form">Choose Image</label>
                                <input type="file" ref="fileupload" @change="onFileChange" id="image-modal-form" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button v-if="methods.post.value" type="button" v-on:click="addPost()" class="btn btn-brand">Save</button>
                    <button v-else-if="methods.put.value" type="button" v-on:click="editPost()" class="btn btn-brand">Edit</button>
                </div>
                </div>
            </div>
        </div>
        <PostDetailsModal
        bind:key="post_details_modal"
        v-bind:post="post"
        @clear_modal="clearModalData()"
        ></PostDetailsModal>
    </div>
</template>

<script>
    import axios from 'axios';
    import Post from './PostComponent.vue'
    import FormCategory from './FormCategoryComponent.vue'
    import SidebarCategory from './SidebarCategoryComponent.vue'
    import PostDetailsModal from './PostDetailsModalComponent.vue';

    const CURRENT_HOST = location.protocol + '//' + location.host;
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const today  = new Date();
    const NO_IMAGE = "https://us.123rf.com/450wm/koblizeek/koblizeek1902/koblizeek190200055/125337077-no-image-vector-symbol-missing-available-icon-no-gallery-for-this-moment-.jpg"
    const CONFIG = {
        headers:{ 'Content-Type': 'multipart/form-data'}
    }

    export default {
        components: {
            Post: Post,
            FormCategory: FormCategory,
            SidebarCategory: SidebarCategory,
            PostDetailsModal: PostDetailsModal
        },
        data: function() {
            return {
                category_title: 'Choose category',
                limiter: '',
                searcher: '',
                limiterGlobal: '',
                searcherGlobal: '',
                posts: [],
                filteredPosts: [],
                filteredByCategoryPosts: [],
                categories: [],
                category_is_choosen: false,
                chosen_category_id: null,
                methods: {
                    post: {
                        name: "POST",
                        value: false
                    },
                    put: {
                        name: "PUT",
                        value: false
                    }
                },
                modal: {
                    title: '',
                    image:{
                        url: NO_IMAGE
                    }
                },
                post: {},
                postPaths: [],
                categoryPaths: [],
                error: {
                    seen: false
                }
            };
        },
        watch: {
            limiter: function (limit){
                this.getLimitedPosts(limit)
            },
            searcher: function (query){
                this.getFilteredPosts(query)
            },
            limiterGlobal: function (limit){
                this.getLimitedPostsGlobal(limit)
            },
            searcherGlobal: function (query){
                this.getFilteredPostsGlobal(query)
            }
        },
        created(){
            axios.get(CURRENT_HOST + "/api/categories")
            .then(response => response.data)
            .then(data => {
                data.data.forEach(category => {
                    this.categories.push(category)
                })
                this.categoryPaths = data.paths
            })
            this.fetchAllPosts()
        },
        mounted(){
            this.$refs.vuemodal.addEventListener('hidden.bs.modal', event => {
                this.clearModalData()
            })
        },
        methods: {
            clearModalData(){
                this.post = {}
                this.$refs.fileupload.value=null;
                this.modal.image.url = NO_IMAGE
                this.error.seen = false
            },
            fetchAllPosts(){
                this.chosen_category_id = null
                this.category_is_choosen = false
                this.postPaths = {}
                this.posts = []
                this.filteredPosts = []
                this.filteredByCategoryPosts = []
                axios.get(CURRENT_HOST + "/api/posts")
                    .then(response => response.data)
                    .then(data => {
                        data.data.forEach(post => {
                            this.posts.push(post)
                            this.filteredPosts = this.posts
                        })
                        this.postPaths = data.paths
                    })
            },
            getAllPosts(){
                this.category_is_choosen = false
                this.chosen_category_id = null
                this.limiterGlobal = ''
                this.searcherGlobal = ''
                this.filteredPosts = this.posts
            },
            async getPostsByCategory(categoryId){
                this.chosen_category_id = categoryId
                this.limiterGlobal = ''
                this.searcherGlobal = ''
                let result = await axios.get(CURRENT_HOST + "/api/categories/"+categoryId)
                .then(response => response.data)
                .then(data => {
                    return data
                })
                this.category_is_choosen = true
                this.category_title = result.category.title
                this.filteredByCategoryPosts = result.posts
                this.filteredPosts = this.filteredByCategoryPosts
            },
            getLimitedPosts(limit) {
                if(!limit){
                    this.filteredPosts = this.filteredByCategoryPosts
                    return;
                }
                this.filteredPosts = this.filteredByCategoryPosts.slice(0, limit)
            },
            getLimitedPostsGlobal(limit) {
                this.chosen_category_id = null
                this.category_is_choosen = false
                if(!limit){
                    this.filteredPosts = this.posts
                    return;
                }
                this.filteredPosts = this.posts.slice(0, limit)
            },
            getFilteredPosts(query){
                if(!query){
                    this.filteredPosts = this.filteredByCategoryPosts
                }
                this.filteredPosts = this.filteredByCategoryPosts.filter(post => {
                    if(post.title.includes(query) || post.body.includes(query)){
                        return post
                    }
                })
            },
            getFilteredPostsGlobal(query){
                this.chosen_category_id = null
                this.category_is_choosen = false
                if(!query){
                    this.filteredPosts = this.posts
                }
                this.filteredPosts = this.posts.filter(post => {
                    if(post.title.includes(query) || post.body.includes(query)){
                        return post
                    }
                })
            },
            appendNewPost(){
                this.post.real_image = URL.createObjectURL(this.post.real_image)
                this.post.created_at = today.toLocaleDateString("en-US", options)
                this.posts = [this.post].concat(this.posts)
                if(this.chosen_category_id == this.post.category_id || this.chosen_category_id == null){
                    this.filteredPosts = [this.post].concat(this.filteredPosts)
                }
                this.post = {}
            },
            setMethod(methodName){
                Object.keys(this.methods).forEach(key => {
                    this.methods[key].value = false
                    if (this.methods[key].name == methodName){
                        this.methods[key].value = true
                    }
                })
            },
            isSelected(category, chosen_category_id){
                return category.id == chosen_category_id
            },
            onFileChange(e){
                let file = e.target.files[0]
                if(!file){
                    delete this.post.real_image
                    this.modal.image.url = NO_IMAGE
                    return
                }
                this.post.real_image = file
                this.modal.image.url = URL.createObjectURL(file)
            },
            addPost(){
                if(!this.post.title || !this.post.body){return this.error.seen = true}

                let formData = new FormData()
                formData.append('title', this.post.title)
                formData.append('body', this.post.body)
                formData.append('category_id', this.post.category_id)
                formData.append('image', this.post.real_image)

                axios.post(this.postPaths.POST, formData, CONFIG)
                .then(response => {
                    this.post.id = response.data.id
                    this.appendNewPost()
                    this.hideModal('modal')
                })
                .catch(err => console.log(err))
            },
            editPost(){
                if(!this.post.title || !this.post.body){return this.error.seen = true}

                let formData = new FormData()
                formData.append('_method', 'PUT')
                formData.append('title', this.post.title)
                formData.append('body', this.post.body)
                formData.append('category_id', this.post.category_id)
                if(this.$refs.fileupload.value){
                    formData.append('image', this.post.real_image)
                }

                axios.post(this.postPaths.POST + '/' + this.post.id, formData, CONFIG)
                .then(response => {
                    let viewPost = this.filteredPosts.find(p => p.id == this.post.id)
                    this.post.real_image = URL.createObjectURL(this.post.real_image)
                    viewPost = this.post
                    this.hideModal('modal')
                })
                .catch(err => console.log(err))
            },
            onPostEditModalCalled(post){
                this.post = post
                findID('select-category').childNodes.forEach(option => {
                    if (option.value == post.category_id){
                        option.setAttribute('selected', 'selected')
                    }
                })
                this.modal.title = 'Edit post'
                this.modal.image.url = this.post.real_image
                this.setMethod('PUT')
                this.openModal('modal')
            },
            onPostDeleted(post){
                axios.delete(this.postPaths.POST + '/' + post.id)
                .then(resonse => {
                    // let postIndex = this.filteredPosts.map(data => data.id).indexOf(post.id) //  hindi style
                    let postIndex = this.filteredPosts.findIndex(p => p.id == post.id) // normal style
                    this.filteredPosts.splice(postIndex, 1)
                })
                .catch(err => console.log(err))
            },
            hideModal(modalName){
                bootstrap.Modal.getInstance(findID(modalName)).hide()
                document.querySelector('.modal-backdrop').remove()
            },
            openModal(modalName){
                let modal = new bootstrap.Modal(findID(modalName))
                modal.show()
            },
            openPostDetailsModal(post){
                this.post = post
                this.openModal('post_detail_modal')
            },
        }

    }
</script>