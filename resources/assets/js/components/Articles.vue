<template>
    <div style="margin-top: 55px;">
        <h2>Articles</h2>
        <form class="ui form" @submit.prevent="addArticle">
            <div class="field">
                <label>Title</label>
                <input name="title" placeholder="Title" type="text" v-model="article.title" maxlength="20">
            </div>
            <div class="field">
                <label>Body</label>
                <textarea rows="2" name="body" v-model="article.body" maxlength="200"></textarea>
            </div>
            <button class="ui button" type="submit">Save</button>
        </form>
        <p>&nbsp</p>
        <p>
            {{ encodeArticle }}
        </p>
        <p>&nbsp</p>
        <div class="ui buttons">
            <button class="ui labeled icon button" role="link" :class="{disabled: !pagination.prev_page_url}" @click="fetchArticles(pagination.prev_page_url)">
                <i class="left chevron icon"></i>
                Prev
            </button>
            <button class="ui button">
                Page {{ pagination.current_page }} of {{ pagination.last_page }}
            </button>
            <button class="ui right labeled icon button" role="link" :class="{disabled: !pagination.next_page_url}"  @click="fetchArticles(pagination.next_page_url)">
                Next
                <i class="right chevron icon"></i>
            </button>
        </div>
        <div class="ui card lg" v-for="article in articles" :key="article.id">
            <div class="image">
                <img/>
            </div>
            <div class="content">
                <a class="header">{{ article.title }}</a>
                <div class="description">
                    {{ article.body }}
                </div>
            </div>
            <div class="extra content">
                {{ article.created_at['date'] }}
            </div>
            <hr>
            <div class="ui buttons">
                <button class="ui yellow button" @click="editArticle(article)">Edit</button>
                <div class="or"></div>
                <button class="ui red button" @click="deleteArticle(article.id)">Delete</button>
            </div>
        </div>
        <p>&nbsp</p>
    </div>
</template>

<script>
    export default{
        data(){
            return {
                articles:[],
                article:{
                    id:'',
                    title: '',
                    body:''
                },
                article_id: '',
                pagination: {},
                edit: false,
                vt: this
            }
        },
        created(){
            this.fetchArticles()
        },
        methods:{
            fetchArticles:function(page_url){
                let vm = this;
                page_url = page_url || 'api/articles'
                fetch(page_url)
                    .then(res => res.json())
                    .then(res => {
                        this.articles = res.data
                        vm.makePagination(res.meta,res.links)
                    })
                    .catch(err => console.log(err))
            },
            makePagination(meta, links){
                this.pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev
                }
            },
            deleteArticle(id){
                if(confirm('Are you sure?')){
                    fetch(`api/articles/${id}`,{
                        method: 'delete'
                    })
                        .then(res => res.json())
                        .then(data => {
                            this.fetchArticles();
                            alert('article removed')
                        })
                        .catch(err => console.log(err))
                }
            },
            addArticle: function(){
                if(this.edit === false){
                    fetch('api/articles',{
                        method: 'post',
                        body: JSON.stringify(this.article),
                        headers: {
                            'content-type':'application/json'
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            this.article.title = ''
                            this.article.body = ''
                            this.fetchArticles()
                            alert('Article Added')
                        })
                        .catch(err => {
                            console.log('add')
                            console.log(err)
                        })
                }else{
                    fetch('api/articles',{
                        method: 'put',
                        body: JSON.stringify(this.article),
                        headers: {
                            'content-type':'application/json'
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            this.edit = false
                            this.article.title = ''
                            this.article.body = ''
                            this.fetchArticles()
                            alert('Article Updated')
                        })
                        .catch(err =>{
                            console.log('update')
                            console.log(err)
                        })
                }
            },
            editArticle(article){
                this.edit = true
                this.article.id = article.id
                this.article.article_id = article.id
                this.article.title = article.title
                this.article.body = article.body
            }
        },
        computed:{
            encodeArticle(){
                return JSON.stringify(this.article)
            }
        }
    }
</script>

<style>
    div.ui.card.lg{
        width:100%;
    }
</style>