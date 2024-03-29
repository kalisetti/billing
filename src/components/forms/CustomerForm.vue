<template>
    <div class="form-page">
        <!-- Bread crumbs -->
        <div>
            <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/desk">Desk</a></li>
                <li class="breadcrumb-item"><a :href="'/desk/List/' + tableName">{{ tableName }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ recordId }}</li>
            </ol>
            </nav>
        </div>

        <!-- form-toolbar -->
        <div class="form-toolbar d-flex justify-content-end align-items-center">
            <div class="form-indicators">
                <div v-if="isNewRecord || hasUnsavedChanges">Not Saved</div>
            </div>
            <div class="form-actions">
                <button v-if="isNewRecord || hasUnsavedChanges" class="btn btn-sm btn-primary ms-2" @click="saveRecord">Save</button>
                <button v-if="isDraft && !hasUnsavedChanges && isSubmittable" class="btn btn-sm btn-primary ms-2" @click="submitRecord">Submit</button>
                <button v-if="isSubmitted && !hasUnsavedChanges && isSubmittable" class="btn btn-sm btn-primary ms-2" @click="cancelRecord">Cancel</button>
                <button v-if="isCancelled && !hasUnsavedChanges && isSubmittable" class="btn btn-sm btn-danger ms-2" @click="deleteRecord">Delete</button>
            </div>
        </div>
        
        <!-- form-section -->
        <div id="form-body">
            <div id="form-fields" class="row">
                <div class="col">
                    <div class="form-group">
                    <label for="customer_name" :class="{ 'required': isCustomerNameEmpty }">Customer Name</label>
                    <input type="text" id="customer_name" class="form-control" 
                        v-model="recordData['customer_name']" 
                        :class="{ 'error-border': isCustomerNameEmpty}"
                        required
                        >
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="email" :class="{ 'required': isEmailEmpty }">Email ID</label>
                        <input type="email" id="email" class="form-control"
                            v-model="recordData['email']"
                            :class="{ 'error-border': isEmailEmpty }"
                            required
                            >
                    </div>
                    <div class="form-group">
                        <label for="contact_no">Contact No</label>
                        <input type="text" id="contact_no" class="form-control" 
                            v-model="recordData['contact_no']"
                            >
                    </div>
                </div>
            </div>

            <hr>
            <h5>ADDRESS</h5>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="address1">Address Line 1</label>
                        <input type="text" id="address1" class="form-control" 
                            v-model="recordData['address1']"
                            >
                        </div>
                    <div class="form-group">
                        <label for="address2">Address Line 2</label>
                        <input type="text" id="address2" class="form-control" 
                            v-model="recordData['address2']"
                            >
                    </div>
                    <div class="form-group">
                        <label for="address3">Address Line 3</label>
                        <input type="text" id="address3" class="form-control" 
                            v-model="recordData['address3']"
                            >
                    </div>
                </div>
                <div class="col"></div>
            </div>
        </div>

        <!-- form-comments -->
        <form-comments></form-comments>
    </div>
</template>

<script>
import axios from 'axios';
import FormComments from '/src/components/forms/FormComments.vue';

export default {
    name: "CustomerForm",
    components: {
        'form-comments': FormComments,
    },
    data() {
        return {
            recordId: null,
            tableName: 'customers',
            recordData: {
                customer_name: '',
                email: '',
                docstatus: 0,
            },
            oldData: {},
            commentInput: '',
            comments: [],
            newRecord: true,
            isSubmittable: false,
            showErrorDialog: false
        };
    },
    computed: {
        isCustomerNameEmpty() {
            return this.recordData['customer_name'].trim() === '';
        },
        isEmailEmpty() {
            return this.recordData['email'].trim() === '';
        },
        tableColumns() {
            const commonColumns = ['name', 'created_by', 'created_on', 'modified_by', 'modified_on', 'docstatus'];
            const columns = Object.keys(this.recordData);
            return columns.filter((column) => !commonColumns.includes(column));
        },
        isNewRecord() {
            return this.newRecord;
        },
        hasUnsavedChanges() {
            // Check if there are unsaved changes in the form
            // Return true if there are unsaved changes, false otherwise
            const newRecordKeys = Object.keys(this.recordData);
            const oldRecordKeys = Object.keys(this.oldData);

            if (newRecordKeys.length !== oldRecordKeys.length) {
                return true;
            } else {
                for (let key of newRecordKeys) {
                    if (!oldRecordKeys.includes(key)) {
                        return true;
                    } else {
                        if (this.recordData[key] !== this.oldData[key]) {
                            return true;
                        }
                    }
                }
            }
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
        console.log('***SubscriptionPlan.created: ', this.recordData);
        this.recordId = this.$route.params.recordId;
        // this.tableName = this.$route.params.tableName;
        this.fetchRecordData();
        // this.fetchComments();
    },
    methods: {
        fetchRecordData() {
            console.log('***SubscriptionPlan.methods.fetchRecordData');
            // Make an API call to fetch record data based on recordId and tableName
            axios.get(`/api/formAPI.php?table=${this.tableName}&recordId=${this.recordId}`)
                .then((response) => {
                    const results = (response.data.rows ? response.data.rows[0] : []);
                    if (results) {
                        this.recordData = results;
                        this.newRecord = false;
                        this.oldData = { ...this.recordData };
                    }
                    console.log('>>>this.recordData', this.recordData);
                })
                .catch((error) => {
                    console.error('Error fetching record data: ', error);
                });
        },
        fetchComments() {
            // Make an API call to fetch comments for the current record
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
            console.log('***SubscriptionPlan.methods.saveRecord: ', this.recordData);
            // Make an API call to save the record data
            axios.post(`/api/formAPI.php?table=${this.tableName}&recordId=${this.recordId}`, this.recordData)
                .then((response) => {
                    // this.fetchRecordData(); // Fetch the updated record data
                    if (response.data.rows) {
                        this.$router.push(`/desk/Edit/${this.tableName}/${response.data.rows[0].name}`);
                    }
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
    provide() {
        return {
            postComment: this.postComment,
            comments: this.comments
        }
    },
    watch: {
        recordData(newData) {
            console.log('watch ===> recordData: ', newData);
        }
    }
}
</script>