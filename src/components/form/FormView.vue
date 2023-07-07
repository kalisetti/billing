<template>
    <div class="form-page">
        <!-- form-toolbar -->
        <div id="form-toolbar">
            <div id="form-indicators">
                <div v-if="isNewRecord || hasUnsavedChanges">Not Saved</div>
            </div>
            <div id="form-actions">
                <button v-if="isNewRecord || hasUnsavedChanges" class="btn btn-primary" @click="saveRecord">Save</button>
                <button v-if="isDraft" class="btn btn-primary" @click="submitRecord">Submit</button>
                <button v-if="isSubmitted" class="btn btn-primary" @click="cancelRecord">Cancel</button>
                <button v-if="isCancelled" class="btn btn-danger" @click="deleteRecord">Delete</button>
            </div>
        </div>
        
        <!-- form-section -->
        <div id="form-section">
            <!-- Input elements for all the columns except the common columns -->
            <template v-for="column in tableColumns" :key="column">
                <div class="form-group">
                    <label :for="column">{{ column }}</label>
                    <input :id="column" class="form-control" v-model="recordData[column]" type="text" :readonly="isSubmitted">
                </div>
            </template>
        </div>

        <!-- form-comments -->
        <div id="form-comments">
            <div id="post-comments">
                <textarea v-model="commentInput" rows="3" cols="50"></textarea>
                <button clas="btn btn-primary" @click="postComment">Post</button>
            </div>
            <div id="view-comments">
                <div v-for="comment in comments" :key="comment.name" class="comment">{{ comment.text }}</div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "FormView",
    data() {
        return {
            recordId: null,
            tableName: null,
            recordData: {},
            commentInput: '',
            comments: []
        };
    },
    computed: {
        tableColumns() {
            const commonColumns = ['name', 'created_by', 'created_on', 'modified_by', 'modified_on', 'docstatus'];
            const columns = Object.keys(this.recordData);
            return columns.filter((column) => !commonColumns.includes(column));
        },
        isNewRecord() {
            return this.recordId.startsWith('new-');
        },
        hasUnsavedChanges() {
            // Check if there are unsaved changes in the form
            // Return true if there are unsaved changes, false otherwise
            return false;
        },
        isDraft() {
            return this.recordData.docstatus === 0;
        },
        isSubmitted() {
            return this.recordData.docstatus === 1;
        },
        isCancelled() {
            return this.recordData.docstatus === 2;
        }
    },
    created() {
        console.log('***FormView.created');
        this.recordId = this.$route.params.recordId;
        this.tableName = this.$route.params.tableName;
        this.fetchRecordData();
        this.fetchComments();
    },
    methods: {
        fetchRecordData() {
            console.log('***FormView.methods.fetchRecordData');
            // Make an API call to fetch record data based on recordId and tableName
            // axios.get(`/api/fetchRecord.php?table=${this.tableName}&recordId=${this.recordId}`)
            axios.get(`/api/formAPI.php?table=${this.tableName}&recordId=${this.recordId}`)
                .then((response) => {
                    this.recordData = (response.data.rows ? response.data.rows[0] : []);
                    console.log('this.recordData',this.recordData);
                })
                .catch((error) => {
                    console.error('Error fetching record data: ', error);
                });
        },
        fetchComments() {
            // Make an API call to fetch comments for the current record
            // axios.get(`/api/fetchComments.php?table=${this.tableName}&recordId=${this.recordId}`)
            axios.get(`/api/formAPI.php?table=comments&recordId=${this.recordId}`)
                .then((response) => {
                    this.comments = response.data.rows;
                    console.log('this.comments',this.recordData);
                })
                .catch((error) => {
                    console.error('Error fetching comments: ', error);
                });
        },
        saveRecord() {
            // Make an API call to save the record data
            axios.post(`/api/saveRecord.php?table=${this.tableName}&recordId=${this.recordId}`, this.recordData)
                .then(() => {
                    this.fetchRecordData(); // Fetch the updated record data
                })
                .catch((error) => {
                    console.error('Error saving record: ', error);
                });
        },
        submitRecord() {
            // Make an API call to submit the record
            axios.post(`/api/submitRecord.php?table=${this.tableName}&recordId=${this.recordId}`)
                .then(() => {
                    this.fetchRecordData(); // Fetch the updated record data
                })
                .catch((error) => {
                    console.error('Error submitting record: ', error);
                });
        },
        cancelRecord() {
            // Make an API call to cancel the submitted record
            axios.post(`/api/cancelRecord.php?table=${this.tableName}&recordId=${this.recordId}`)
                .then(() => {
                    this.fetchRecordData(); // Fetch the updated record Data
                })
                .cancel((error) => {
                    console.error('Error cancelling record: ', error);
                });
        },
        deleteRecord() {
            // Make an API call to delete the record
            axios.post(`/api/deleteRecord.php?table=${this.tableName}&recordId=${this.recordId}`)
                .then(() => {
                    // Record deleted successfully, navigate back to ListView
                    this.$router.push(`/desk/List/${this.tableName}`);
                })
                .catch((error) => {
                    console.error('Error deleting record: ', error);
                });
        },
        postComment() {
            const commentData = {
                comment: this.commentInput,
                recordId: this.recordId,
                tableName: this.tableName,
            };

            // Make an API call to post the comment
            axios.post(`/api/formAPI.php?table=${this.tableName}`, commentData)
                .then(() => {
                    this.commentInput = ''; // Clear the comment input
                    this.fetchComments(); // Fetch the updated comments        
                })
                .catch((error) => {
                    console.error('Error posting comment: ', error);
                });
        }
    },
    // watch: {
    //   tableName(newId) {
    //     console.log('**FormView.watch.tableName: ', newId);
    //     this.recordId = this.$route.params.recordId;
    //     this.tableName = this.$route.params.tableName;
    //   },
    //   recordId(newId) {
    //     console.log('**FormView.watch.recordId: ', newId);
    //     this.recordId = this.$route.params.recordId;
    //     this.tableName = this.$route.params.tableName;
    //     this.fetchRecordData();
    //     this.fetchComments();
    //   }
    // }
}
</script>

<style scoped>
.comment {
    margin-bottom: 10px;
    padding: 5px;
    border: 1px solid #ccc;
}
</style>