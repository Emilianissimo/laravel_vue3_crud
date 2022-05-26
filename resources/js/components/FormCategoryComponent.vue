<template>
    <option :value="category.id">{{category.title}}</option>
</template>

<script>
    export default {
        name: 'FormCategory',
        props: {
            category: {
                type: Object
            }
        }
    }
</script>