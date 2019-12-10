<template>
    <div class="media post">
        <div class="d-felx flex-column vote-controls">
            <vote :model="answer" name="answers"></vote>
            <accept :answer="answer"></accept>
        </div>
        <div class="media-body">
            <form v-if="editing" @submit.prevent="update">
                <div class="form-group">
                    <textarea v-model="body" class="form-control" rows="10" required></textarea>
                </div>
                <div class="thumbnails">
                    <p v-if="images.length > 0"> Click an Image to Remove</p>
                    <img v-for="(img,index) in images" v-bind:key="index" :src="img.image" @click="removeImage(index,img)" class="img-edit rounded float-left img-thumbnail mr-1" title="Remove">
                </div>
                <button class="btn btn-primary" type="submit">Update</button>
                <button class="btn btn-outline-secondary" type="button" @click="cancelEdit()">Cancel</button>
            </form>
            <div v-else>
                <div v-html="bodyHtml"></div>
                <div class="text-center mb-4">
                    <img v-for="(img,index) in images" v-bind:key="index" :src="img.image" class="img-fluid mb-4">
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="ml-auto">
                            <a v-if="authorize('modify', answer)" @click.prevent="editing=true" class="btn btn-sm btn-outline-info">Edit</a>
                            <button v-if="authorize('modify', answer)" @click="destroy" class="btn btn-outline-danger btn-sm">Delete</button>
                        </div>
                    </div>
                    <div class="col-4"></div>
                    <div class="col-4">
                        <div class="float-right">
                            <user-info :model="answer" label="Answerd"></user-info>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['answer'],
    data(){
        return {
            editing: false,
            body: this.answer.body,
            bodyHtml: this.answer.body_html,
            id: this.answer.id,
            questionId: this.answer.question_id,
            images: [],
            removedImages: []
        };
    },
    created(){
        axios.get(`/answer/${this.answer.id}/images`).then(({data}) => {
            data.images.forEach(img =>{
                this.images.push({id:img.id,image:`data:image\jpeg;base64, ${img.image}`});
            });
        });
    },
    methods: {
        update(){
            axios.patch(`${this.questionId}/answer/${this.id}`,{
                body: this.body,
                removedImages: this.removedImages.map(img => img.id)
            }).then(res =>{
                this.editing=false;
                this.bodyHtml = res.data.body_html;
                this.removedImages = [];
                this.$toast.success(res.data.message, 'Success',{timeout: 3000});
            }).catch(err => {
                this.$toast.error(err.response.data.message, 'Error',{timeout: 3000});
            });
        },
        destroy(){
            if(confirm('Are You Sure?')){
                axios.delete(`${this.questionId}/answer/${this.id}`).then(res=>{
                    this.$emit('deleted');
                }).catch(err=>{
                this.$toast.error(err.response.data.message, 'Error',{timeout: 3000}); 
                });
            }
        },
        removeImage(index, image){
            this.removedImages.push(image);
            this.images.splice(index, 1);
        },
        cancelEdit(){
            this.editing = false;
            this.removedImages.forEach(img => this.images.push(img));
            this.removedImages = [];
        }
    }
}
</script>


<style scoped>

.img-edit{
  height:75px;  
  width:75px;
}

.img-edit:hover{
  height:85px;  
  width:85px;
  cursor:pointer;
}

</style>