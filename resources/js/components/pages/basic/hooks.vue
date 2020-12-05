<template>
    <div>
        <h1 ref="myRef">Hooksss</h1>
        <p>{{name}}</p>
    </div>
</template>

<script>
export default {
    data() {
        return {
            name: 'K. Usman'
        }
    },
    created() {
        console.log('data and other function is availablle but not html', this.name)
    },
    mounted() {
        console.log(this.$refs.myRef)
    }
}
</script>