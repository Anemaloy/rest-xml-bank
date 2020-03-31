<template>
    <div>
        <header>
            <h2>REST APP</h2>
        </header>
            <router-view></router-view>
    </div>
</template>
<script>
    export default {
        data(){
            return {

            }
        },
        mounted(){
            document.title = this.$route.meta.title;
        },
    }
</script>

<style>

</style>