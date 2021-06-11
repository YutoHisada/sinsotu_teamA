<template>
<div>
    <transition name="view" mode="out-in">
        <!--SPA使うのに超重要 -->
        <router-view :key="$route.fullPath"/>
    </transition>
</div>
</template>

<script>
export default {
    created () {
        this.$store.dispatch('getUser')
    },
    methods: {
        //
    }
}
</script>

<style scope>
</style>