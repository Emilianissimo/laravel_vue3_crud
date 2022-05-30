<template>
    <div :class="{'loading-container': true, loading: isLoading, visible: isVisible}">
        <div class="loader" :style="{ width: progress + '%' }">
            <div class="light"></div>
        </div>
        <div class="glow"></div>
    </div>
</template>

<script>
    export default {}
</script>
