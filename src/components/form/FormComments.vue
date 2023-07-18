<template>
    <div id="form-comments">
        <div class="timeline">
            <div class="timeline-head">
                <div class="comment-input-wrapper">
                    <div class="comment-input-header">
                        <span class="small text-muted">Add a comment</span>
                        <button class="btn btn-small btn-outline-primary btn-comment pull-right" @click="postComment">
                            Comment
                        </button>
                    </div>
                    <div class="comment-input-container">
                        <textarea v-model="commentInput" rows="5" cols="50"></textarea>
                        <!-- <button clas="btn btn-primary" @click="postComment">Post</button> -->
                    </div>
                </div>
            </div>
            <div id="timeline-items">
                <div v-for="comment in comments" :key="comment.name" class="comment">{{ comment.text }}</div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'FormComments',
    inject: ['comments', 'postComment']
}
</script>

<style scoped>
.timeline {
    position: relative;
}

.timeline-head {
    background-color: #fff;
    position: relative;
    z-index: 1;
}

.timeline-head .comment-input-header {
    background-color: #fafbfc;
    padding: 5px 15px;
    /* border-bottom: 1px solid #ebeff2; */
    border: 1px solid #d1d8dd;
    border-radius: 3px 3px 0 0;
}

.comment-input-container textarea{
    width: 100%;
    border: 1px solid #ebeff2;
    padding: 15px;
}

.comment {
    margin-bottom: 10px;
    padding: 5px;
    border: 1px solid #ccc;
}

.btn-comment {
    padding: 1px 5px;
    font-size: 12px;
    line-height: 1.5;
    border-radius: 3px;
}
</style>