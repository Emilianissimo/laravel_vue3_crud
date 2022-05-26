<template>
    <li>{{category.title}}</li>
</template>

<script>
    export default {
        name: 'SidebarCategory',
        props: {
            category: {
                type: Object
            }
        }
    }
</script>