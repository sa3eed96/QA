<template>
    <div class="form-group">
        <input class="d-none" type="file" ref="images" @change="handleImages()" multiple>
        <button class="btn btn-secondary mb-1" type="button"  @click="openSelector()">Upload Image</button>
        <div v-if="error" class="alert alert-danger mb-1">
            {{status}}
        </div>
        <div class="thumbnails">
            <p v-if="urls.length > 0"> Click an Image to Remove</p>
            <img v-for="(url,index) in urls" v-bind:key="index" :src="url" @click="removeImage(index)" class="rounded float-left img-thumbnail mr-1" title="Remove">
        </div>
        <div class="d-block">
            <br>
            <br>
        </div>
    </div>
</template>

<script>
export default {
    data(){
  	return {
    	status:'',
        error: false,
        files: [],
        urls: []
    };
  },
    methods:{
        removeImage(index){
            this.urls.splice(index,1);
            this.files.splice(index,1);
        },
        openSelector(){
            this.$refs.images.click();
        },
        invalid(file){
            const mime = file.type;
            const kbSize = file.size/1024; 
            return (kbSize > 1024) || (mime != "image/jpeg" && mime != "image/png");
        },
        handleImages(){
            this.error=false;
            let removed = '';
            for (let i = 0, numFiles = this.$refs.images.files.length; i < numFiles; ++i) {
                if(this.invalid(this.$refs.images.files[i])){
                    this.error = true;
                    removed +=' '+this.$refs.images.files[i].name;
                }
                else{
                    this.files.push(this.$refs.images.files[i]);
                    this.urls.push(URL.createObjectURL(this.$refs.images.files[i]));
                }
            }
            this.$emit('imagesAdded', this.files);
            if(this.error){
                this.status = `error uploading ${removed}, size must not exceed 1 MB and only jpeg nd png allowed \n`;
            }
        }
    }
}
</script>

<style scoped>

img{
  height:75px;  
  width:75px;
}

img:hover{
  height:85px;  
  width:85px;
  cursor:pointer;
}

</style>