<template>
    <div>
        <a class="nav-link dropdown-toggle d-flex" @blur="view" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div v-if="notifications.length > 0 && !notifications[0].read" class="mr-1">
                <i id="notice" class="fas fa-exclamation-circle fa-sm"></i>
            </div>
            <div>
                Notifications
            </div>
        </a>
        <div @scroll="scroll()" class="dropdown-menu" id="menu" aria-labelledby="navbarDropdownMenuLink">
          <a v-for="(notification, index) in notifications" v-bind:key="index" class="list dropdown-item border-bottom border-secondary" :href="getUrl(notification.question_slug)">
              <div class="d-flex">
                <div v-if="!notification.read" class="mr-1">
                    <i id="circle" class="fas fa-circle fa-xs"></i>
                </div>
                <div class="mr-1">
                    {{ notification.body }}
                </div>
                <div class="ml-auto">
                    <span>{{ notification.created_at | moment("ddd, hA") }}</span>
                </div>
              </div>
              <div class="d-inline-block text-truncate font-weight-bold" style="max-width: 150px;">
                  {{notification.question_slug}}
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
        axios.get(`/notifications?user_id=${window.Auth.user.id}`).then(({data})=>{
           if(data.notifications.data.length > 0){
                this.notifications = data.notifications.data;
                this.nextUrl = data.notifications.next_page_url;
           }
           else
            this.noMore = true;
        });
        
        // Echo.private(`acceptChannel.${window.Auth.user.id}`)
        //     .listen('AcceptEvent', (e)=>{
        //         this.notifications.unshift({
        //             'body': 'Your answer was accepted',
        //             'read': false,
        //             'created_at': new Date().toISOString(),
        //             'question_slug': e.slug
        //         });
        //     });
        // Echo.private(`replyChannel.${window.Auth.user.id}`)
        //     .listen('ReplyEvent',(e) =>{
        //         this.notifications.unshift({
        //             'body': 'Someone replied to your question',
        //             'read': false,
        //             'created_at': new Date().toISOString(),
        //             'question_slug': e.question.slug
        //         });            
        //     });
    },
    methods: {
        getUrl(slug){
            return `/question/${slug}`;
        },
        view(){
            if(this.notifications.length > 0 && !this.notifications[0].read){
                axios.patch('/notifications',{user_id: window.Auth.user.id});
                let i=0;
                while(i < this.notifications.length && !this.notifications[i].read){
                    this.notifications[i].read = true;
                    i++;
                }
            }
        },
        scroll(){
            if(this.nextUrl){
                if(this.notifications.length*75-document.getElementById('menu').scrollTop < 200){
                    axios.get(`${this.nextUrl}&user_id=${window.Auth.user.id}`).then(({data})=>{
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