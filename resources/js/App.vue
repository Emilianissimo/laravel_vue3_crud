<template>
    <div class="container p-0">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <router-link to="/" class="navbar-brand">
                    Laravel 9 + Vue.JS 3
                </router-link>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <router-link to="/news" class="nav-link">
                            News
                        </router-link>
                    </li>
                </ul>
                </div>
            </div>
        </nav>
    </div>
    <router-view v-slot="{ Component }">
        <Transition mode="out-in">
            <component :is="Component"></component>
        </Transition>
    </router-view>
</template>

<script>

    export default {}

</script>
