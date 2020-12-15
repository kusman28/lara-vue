<template>
    <div>
        <h1>
            The number is: {{ views }}
        </h1>
        <button @click="updateCounter(1)">Increase</button>
        <button @click="updateCounter(-1)">Decrease</button>
        <br>
        <hr>

        <div v-for="(blog, i) in blogs" :key="i">
            <h1>{{blog.title}}</h1>
            <p>{{blog.post}}</p>
        </div>

        <div>
            <h1 v-if="showItem">true</h1>
            <p v-else>false</p>
        </div>

        <button @click="showItem = !showItem">Change</button>
    </div>
</template>
<script>
export default {
    data() {
        return {
            views: 0,
            blogs: [],
            showItem: false
        }
    },
    methods: {
        updateCounter(number) {
            this.views += number
        },
        changeShowItem() {
            this.showItem = !this.showItem
        }
    },
    created() {
        this.views = 100

        let posts = [
                {
                    id: '1',
                    title: 'Wew',
                    post: 'Wew123'
                },
                {
                    id: '2',
                    title: 'Wew',
                    post: 'Wew123'
                },
                {
                    id: '3',
                    title: 'Wew',
                    post: 'Wew123'
                },
                ]

                this.blogs = posts
    }
}
</script>