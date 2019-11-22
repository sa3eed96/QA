<template>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <form v-if="editing" class="card-body" @submit.prevent="update">
                    <div class="card-title">
                        <input type="text" class="form-control form-control-lg" v-model="title">
                    </div>
                    <hr>

                    <div class="media">
                         <div class="media-body">
                            <div class="form-group">
                            <textarea v-model="body" class="form-control" rows="10" required></textarea>
                        </div>
                        <button class="btn btn-primary" @click="editing=false">Update</button>
                        <button class="btn btn-outline-secondary" type="button" @click="editing=false">Cancel</button>
                        </div>
                    </div>
                </form>
                <div v-else class="card-body">
                    <div class="card-title">
                        <div class="d-flex align-items-center">
                            <h1> {{ title }} </h1>
                            <div class="ml-auto">
                                <a class="btn btn-outline-secondary" href="/question">Back</a>
                            </div>
                        </div>
                        <div class="d-flex">
                                <div class="ml-1 rounded p-1 bg-info" v-for="tag in this.tags" v-bind:key="tag.id">
                                    {{tag.tag}}
                                </div>
                        </div>
                    </div>
                    <hr>

                    <div class="media">
                        <div class="d-felx flex-column vote-controls">
                            <vote :model="question" name="questions"></vote>
                            <favourite :question="question"></favourite>
                        </div>
                        <div class="media-body">
                            <div v-html="bodyHtml"></div>
                            <div class="ml-auto">
                                <a v-if="authorize('modify', question)" @click.prevent="editing=true" class="btn btn-sm btn-outline-info">Edit</a>
                                <button v-if="authorize('modify', question)" @click="destroy" class="btn btn-outline-danger btn-sm">Delete</button>
                            </div>
                            <div class="float-right">
                                <user-info :model="question" label="Asked"></user-info>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['question'],
    data(){
        return{
            title: this.question.title,
            body: this.question.body,
            tags: this.question.tags,
            bodyHtml: this.question.body_html,
            editing: false,
            id: this.question.id,
            beforeEdit: {}
        };
    },
    methods:{
        edit(){
            this.beforeEdit = {
                title: this.title,
                body: this.body
            },
            this.editing=true;
        },
        cancel(){
            this.body = this.beforeEdit.body;
            this.editing = false;
        },
        update(){
            axios.put(`/question/${this.id}`,{
                title: this.title,
                body: this.body
            }).then(({data}) =>{
                this.bodyHtml = data.body_html;
                this.$toast.success(data.message,'Success',{timeout: 3000});
                this.editing=false;
            }).catch(({response})=>{
                this.$toast.error(response.data.message,'Error',{timeout: 3000});
            });
        },
        destroy(){
            if(confirm('Are You Sure?')){
                axios.delete(`/question/${this.id}`).then(({data})=>{
                    this.$toast.success(data.message, 'Success',{timeout: 2000});
                    setTimeout(()=>window.location.href = "/question",3000); 
                }).catch(err=>{
                this.$toast.error(err.response.data.message, 'Error',{timeout: 3000}); 
                })  ;
            }
        }
    }
}
</script>