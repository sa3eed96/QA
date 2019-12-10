<template>
<div class="col-sm-12 col-md-10">
  <div class="row">
    <div class="col-sm-12 col-md-4">
      <div v-if="editing" class="card">
        <div class="card-header"> Edit Info </div>
        <form @submit.prevent="update()" class="card-body">
          <div class="form-group">
            <input class="form-control" type="text" v-model="name" placeholder="Name">
          </div>
          <div class="form-group">
            <input class="form-control" type="text" v-model="age" placeholder="Age">
          </div>
          <div class="form-group">
            <input class="form-control" type="text" v-model="country" placeholder="Country">
          </div>
          <button type="submit" class="btn btn-primary">Update</button>
          <button type="button" class="btn btn-secondary" @click="cancel()">Cancel</button>
        </form>
      </div>
      <div v-else class="card">
        <div class="card-header">Personal Info </div>
        <div class="card-body">
          <img :src="user.avatar">
          <div>
            Name: {{name}}
          </div>
          <div>
            Age: {{age ? age : 'not specified'}}
          </div>
          <div>
            Country: {{country ? country : 'not specified'}}
          </div>
          <button class="btn btn-primary" @click="edit()">Edit</button>
        </div>
      </div>
    </div>
    <div class="col-sm-12 col-md-6">
      <div class="card">
        <div class="card-header">{{user.name}}'s Questions</div>
        <div class="card-body">
          <div class="media post" v-for="question in user.questions" v-bind:key="question.id">
            <div class="d-flex felx-column counters">
              <div class="vote">
                <strong>{{question.votes_count}}</strong>
                votes
              </div>
              <div class="status" :class="question.status">
                <strong>{{question.answers_count}}</strong>
                answers
              </div>
              <div class="view">{{question.views}}views</div>
            </div>
            <div class="media-body">
              <div class="d-flex align-items-center">
                <h3 class="mt-0">
                  <a :href="getQuestionUrl(question.slug)">{{ question.title }}</a>
                </h3>
              </div>
              <p class="lead">
                <small class="text-muted">{{question.created_date}}</small>
              </p>
              {{ question.excerpt }}
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
  props: ["user"],
  data(){
    return {
      editing: false,
      name: this.user.name,
      age: this.user.age ? user.age : null,
      country: this.user.country ? user.country : null,
      beforeEdit: {}
    };
  },
  methods: {
    getQuestionUrl(slug){
      return `/question/${slug}`;
    },
    update(){
      axios.put(`/user/${this.user.id}`,{
        name: this.name
      }).then(({data}) =>{
        this.editing = false;          
        this.$toast.success('Info Updated', 'Success',{timeout: 3000});

      }).catch(err=>{
          this.$toast.error(err.response.data.message, 'Error',{timeout: 3000});
      });
    },
    edit(){
      this.beforeEdit = {
        name: this.name,
        country: this.country,
        age: this.age
      };
      this.editing = true;
    },
    cancel(){
      this.name = this.beforeEdit.name;
      this.age = this.beforeEdit.age;
      this.country = this.beforeEdit.country;
      this.editing = false;
    }
  },
};
</script>