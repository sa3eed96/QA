<template>
    <div>
        <a class="nav-link dropdown-toggle d-flex" @blur="view" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div v-if="notifications.length > 0 && !notifications[0].read_at" class="mr-1">
                <i id="notice" class="fas fa-exclamation-circle fa-sm"></i>
            </div>
            <div>
                Notifications
            </div>
        </a>
        <div @scroll="scroll()" class="dropdown-menu" id="menu" aria-labelledby="navbarDropdownMenuLink">
          <a v-for="(notification, index) in notifications" v-bind:key="index" class="list dropdown-item border-bottom border-secondary" :href="getUrl(notification.data.question_slug)">
              <div class="d-flex">
                <div v-if="!notification.read_at" class="mr-1">
                    <i id="circle" class="fas fa-circle fa-xs"></i>
                </div>
                <div class="mr-1">
                    {{ notification.data.body }}
                </div>
                <div class="ml-auto">
                    <span>{{ notification.created_at | moment("ddd, hA") }}</span>
                </div>
              </div>
              <div class="d-inline-block text-truncate font-weight-bold" style="max-width: 150px;">
                  {{notification.data.question_slug}}
              </div>
          </a>
          <div v-if="noMore" class="list text-center">
                No More Notifications to show
          </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            notifications: [],
            noMore: false,
            nextUrl: null
        }
    },
    mounted(){
        axios.get(`/user/${window.Auth.user.id}/notifications`).then(({data})=>{
           if(data.notifications.data.length > 0){
                this.notifications = data.notifications.data;
                this.nextUrl = data.notifications.next_page_url;
                if(data.notifications.data.length < 3)
                    this.noMore = true;
           }
           else
            this.noMore = true;
        });
        
        Echo.private('App.User.'+window.Auth.user.id)
            .notification((notification)=>{
                this.notifications.unshift({
                    'data':{
                        'body': notification.body,
                        'question_slug': notification.question_slug
                    },
                    'read_at': null,
                    'created_at': new Date().toISOString()
                });
             });
    },
    methods: {
        getUrl(slug){
            return `/question/${slug}`;
        },
        view(){
            if(this.notifications.length > 0 && !this.notifications[0].read_at){
                axios.patch(`/user/${window.Auth.user.id}/notifications`);
                let i=0;
                while(i < this.notifications.length && !this.notifications[i].read_at){
                    this.notifications[i].read_at = true;
                    i++;
                }
            }
        },
        scroll(){
            if(this.nextUrl){
                if(this.notifications.length*75-document.getElementById('menu').scrollTop < 200){
                    axios.get(`${this.nextUrl}`).then(({data})=>{
                        data.notifications.data.forEach(not => {
                            this.notifications.push(not);
                        });
                        this.nextUrl = data.notifications.next_page_url;
                    });
                }
            }
            else{
                this.noMore=true;
            }
        }
    }
}
</script>

<style scoped>
    #circle{
        color:lightgreen;
    }
    
    #notice{
        color:red;
    }

    #menu{
        height:187.5px;
        overflow-y:scroll;
    }

    .list{
        height:75px;
    }
</style>