
<script>
export default {
    props:['answer'],
    data(){
        return {
            editing:false,
            body:this.answer.body,
            bodyHtml : this.answer.body_html,
            id:this.answer.id,
            questionId:this.answer.question_id,
            beforeEditCache:null,
        }
    },
    methods:{
        edit()
        {
            this.beforeEditCache=this.body;
            this.editing=true;
        },
        cancel(){
            this.body=this.beforeEditCache;
            this.editing=false;
        }
        ,
        update () {
            axios.patch(`/questions/${this.questionId}/answers/${this.id} `, {
                body: this.body
            }).then(res => {                
               alert(res.data.message);
                this.editing=false;
                this.bodyHtml = res.data.body_html;
              
            }) .catch(err => {

                alert(err.response.data.message);          
            });
           
        },        
    },
    computed:{
        isInvalid(){
            if(this.body.length<10){
                return true;
            }
        }
    }
}
</script>