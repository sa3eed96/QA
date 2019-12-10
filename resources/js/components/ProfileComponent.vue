<template>
<div class="col-sm-12 col-md-10">
  <div class="row">
    <div class="col-sm-12 col-md-4">
      <div v-if="editing" class="card">
        <div class="card-header"> Edit Info </div>
        <form @submit.prevent="update()" class="card-body">
          <div class="form-group">
            <input class="form-control" type="text" v-model="name" placeholder="Name">
            <div v-if="errors.name" class="alert alert-danger mb-1">
              {{errors.name[0]}}
            </div>
          </div>
          <div class="form-group">
            <input class="form-control" type="text" v-model="age" placeholder="Age">
            <div v-if="errors.age" class="alert alert-danger mb-1">
              {{errors.age[0]}}
            </div>
          </div>
          <div class="form-group">
            <input class="form-control" type="text" v-model="country" placeholder="Country">
            <div v-if="errors.country" class="alert alert-danger mb-1">
              {{errors.country[0]}}
            </div>
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
          <button v-if="authorize('modifyProfile', user)" class="btn btn-primary" @click="edit()">Edit</button>
        </div>
      </div>
      <div class="my-4 p-4 border">
        <h4>Reputation: <span class="bg-success rounded py-2 px-3 font-weight-bold text-white">{{user.reputation}}</span></h4>
      </div>
      <div>
        <button v-if="authorize('modifyProfile', user)" @click="deleteAccount()" class="btn btn-danger" >Delete Account</button>
      </div>
    </div>
    <div class="col-sm-12 col-md-6">
      <div class="card mb-4">
        <div class="card-header">{{user.name}}'s Questions</div>
        <div class="card-body">
          <div v-if="questions.data.length > 0">
            <div class="media post" v-for="question in questions.data" v-bind:key="question.id">
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
            <div class="d-flex mt-4 justify-content-center">
              <button @click="fetch(questions.first_page_url,'questions')" :class="questions.current_page !== 1 ? '':'disabled'" class="mr-1 btn btn-secondary btn-sm"> &lt;&lt; </button>
              <button @click="fetch(questions.prev_page_url,'questions')" :class="questions.prev_page_url !== null ? '':'disabled'" class="btn btn-secondary btn-sm"> &lt; </button>
              <div class="text-dark mx-2 font-weight-bold">
                {{questions.current_page}} - {{questions.last_page}}
              </div>
              <button @click="fetch(questions.next_page_url,'questions')" :class="questions.next_page_url !== null ? '': 'disabled'" class="btn btn-secondary btn-sm">&gt;</button>
              <button @click="fetch(questions.last_page_url,'questions')" :class="questions.current_page !== questions.last_page ? '': 'disabled'" class="ml-1 btn btn-secondary btn-sm">&gt;&gt;</button>
            </div>
          </div>
          <div v-else>
            There is no questions to show
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header">{{user.name}}'s Answers</div>
        <div class="card-body">
          <div v-if="answers.data.length > 0">
            <div class="post" v-for="answer in answers.data" v-bind:key="answer.id">
              <h3 class="mt-0">
                    <a :href="getQuestionUrl(answer.question_slug)">{{ answer.question_title }}</a>
              </h3>
              <p class="lead">
                  <small class="text-muted">{{answer.created_date}}</small>
              </p>
              <div v-html="answer.excerpt"></div>
            </div>
            <div class="d-flex mt-4 justify-content-center">
              <button @click="fetch(answers.first_page_url,'answers')" :class="answers.current_page !== 1 ? '':'disabled'" class="mr-1 btn btn-secondary btn-sm"> &lt;&lt; </button>
              <button @click="fetch(answers.prev_page_url,'answers')" :class="answers.prev_page_url !== null ? '':'disabled'" class="btn btn-secondary btn-sm"> &lt; </button>
              <div class="text-dark mx-2 font-weight-bold">
                {{answers.current_page}} - {{answers.last_page}}
              </div>
              <button @click="fetch(answers.next_page_url,'answers')" :class="answers.next_page_url !== null ? '': 'disabled'" class="btn btn-secondary btn-sm">&gt;</button>
              <button @click="fetch(answers.last_page_url,'answers')" :class="answers.current_page !== answers.last_page ? '': 'disabled'" class="ml-1 btn btn-secondary btn-sm">&gt;&gt;</button>
            </div>
          </div>
          <div v-else>
            There is no answers to show
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
      age: this.user.age,
      country: this.user.country,
      beforeEdit: {},
      errors: {},
      questions: {},
      answers: {}
    };
  },
  created(){
    axios.get(`/user/${this.user.id}/questions`).then(({data})=>{
      this.questions = data.questions;
    });

    axios.get(`/user/${this.user.id}/answers`).then(({data})=>{
      this.answers = data.answers;
    });
  },
  methods: {
    getQuestionUrl(slug){
      return `/question/${slug}`;
    },
    update(){
      axios.put(`/user/${this.user.id}`,{
        name: this.name,
        age: this.age,
        country: this.country
      }).then(({data}) =>{
        this.editing = false;          
        this.$toast.success('Info Updated', 'Success',{timeout: 3000});
        this.errors = {};
      }).catch(err=>{
          this.$toast.error(err.response.data.message, 'Error',{timeout: 3000});
          this.errors = err.response.data.errors;
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
      this.errors = {};
      this.editing = false;
    },
    deleteAccount(){
      if(confirm('Are you sure you want to delete your account?'))
        axios.delete(`/user/${this.user.id}`).then(res=>{
          window.location.href = "/";
        });
    },
    fetch(url, model){
      axios.get(url).then(({data})=>{
        if(model === 'questions')
          this.questions = data.questions;
        else
          this.answers = data.answers;
      });
    }
  },
};
</script>