<template>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <form class="card-body" v-if="editing" @submit.prevent="update">
                    <div class="card-title">
                       <input type="text" class="form-control form-control-lg" v-model="title">
                    </div>

                    <hr>


                    <div class="media">
                         <div class="media-body">
                                        <div class="form-group">
                                <textarea v-model="body" class="form-control" rows="10" required></textarea>
                            </div>
                            <button class="btn btn-outline-primary" :disabled="isInvalid">Update</button>
                            <button class="btn btn-outline-secondary" @click="cancel" type="button" >Cancel</button>
                         </div>
                    </div>
                </form>
                <div class="card-body" v-else>
                    <div class="card-title">
                        <div class="d-flex align-items-center">
                            <h2>{{ title }}</h2>
                            <div class="ml-auto">
                            <a href="/questions" class="btn btn-outline-secondary">Back to all Questions</a>
                            </div>
                        </div>
                    </div>

                    <hr>


                    <div class="media">
                    <vote :model="question" name="question"></vote>
                         <div class="media-body">
                              <div v-html="bodyHtml"></div>
                            <div class="row">
                                <div class="col-4">
                                        <div class="ml-auto">
                                                <a v-if="authorize('modify',question)" @click.prevent="edit" class="btn btn-sm btn-outline-info">Edit</a>
                                                <button v-if="authorize('deleteQuestion',question)" @click="destroy" class="btn btn-sm btn-outline-danger" >Delete</button>    
                                        </div>
                                </div>
                                <div class="col-4"></div>
                                <div class="col-4">  
                                    <userinfo :model ="question" label="Asked"></userinfo>
                                </div>
                            </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Vote from './Vote.vue';
import Userinfo from './Userinfo.vue';
import modification from '../mixins/modfication.js';
export default {
    props:['question'],
    mixins:[modification],
    components:{Vote,Userinfo},
    data(){
        return {
            title:this.question.title,
            body:this.question.body,
            bodyHtml:this.question.body_html,
         
            id: this.question.id,
            beforEditCache:{}
        }
    },
    computed: {
            isInvalid(){
                return this.body.lenght<10 || this.title.lenght<10;
            },

            endpoint(){
                return `/questions/${this.id}`;
            }
    },
    methods :{
        setEditCache(){
            this.beforEditCache ={
                body:this.body,
                title:this.title
            };
          
        },
        restoreFromCache(){
            this.body =this.beforEditCache.body;
            this.title = this.beforEditCache.title;
           
        },
        payload()
        {
          return {
                body:this.body,
                title:this.title
            }
        },
            delete(){
            
                      axios.delete(this.endpoint).then(({data})=>{
                        this.$toast.success(data.message,"Success",{timeout:3000});
                });
                    setTimeout(()=>{
                        window.location.href="/questions";
                    },3000);
        }      

    }


}
</script>