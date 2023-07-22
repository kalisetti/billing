<template>
    <div class="form-page">
        <!-- form-toolbar -->
        <div id="form-toolbar">
            <div id="form-indicators">
                <div v-if="isNewRecord || hasUnsavedChanges">Not Saved</div>
            </div>
            <div id="form-actions">
                <button v-if="isNewRecord || hasUnsavedChanges" class="btn btn-primary" @click="saveRecord">Save</button>
                <button v-if="isDraft && !hasUnsavedChanges && isSubmittable" class="btn btn-primary" @click="submitRecord">Submit</button>
                <button v-if="isSubmitted && !hasUnsavedChanges && isSubmittable" class="btn btn-primary" @click="cancelRecord">Cancel</button>
                <button v-if="isCancelled && !hasUnsavedChanges && isSubmittable" class="btn btn-danger" @click="deleteRecord">Delete</button>
            </div>
        </div>
        
        <!-- form-section -->
        <div id="form-body">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="customer">
                            Customer
                            <i class="bi bi-link-45deg ms-1"></i>
                        </label>
                        <input type="text" id="customer" class="form-control" 
                            v-model="recordData.customer"
                            @focus="fetchCustomers"
                            @input="fetchCustomers"
                            @blur="fetchOutstandingInvoices"
                            required
                        />
                    </div>
                    <div class="form-group">
                        <label for ="total_outstanding">Total Outstanding Amount</label>
                        <input type="number" id="total_outstanding" class="form-control"
                            :value="recordData.total_outstanding"
                            readonly
                        />
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="payment_date">
                            Payment Date
                        </label>
                        <input type="date" id="payment_date" class="form-control"
                            v-model="recordData.payment_date"
                        />
                    </div>
                    <div class="form-group">
                        <label for="mode_of_payment">Mode of Payment</label>
                        <select id="mode_of_payment" class="form-control" v-model="recordData.mode_of_payment">
                            <option value="Cash">Cash</option>
                            <option value="Bank">Bank</option>
                            <option value="Online">Online</option>
                            <option value="Credit Card">Credit Card</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="paid_amount">Paid Amount</label>
                        <input type="number" id="paid_amount" class="form-control"
                            v-model="recordData.paid_amount"
                            @blur="validatePaidAmount"
                        />
                        <div v-if="showAmountError" class="error-message">Paid amount cannot be more than total outstanding.</div>
                    </div>
                    <div class="form-group">
                        <label for="payment_reference">Payment Reference</label>
                        <input
                            type="text"
                            id="payment_reference"
                            class="form-control"
                            v-model="recordData.payment_reference"
                        />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                            <th>Select</th>
                            <th>Name</th>
                            <th>Subscription</th>
                            <th>Subscription Plan</th>
                            <th>Invoice Month</th>
                            <th>Amount</th>
                            <th>Outstanding</th>
                            <th>Created On</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="invoice in outstandingInvoices" :key="invoice.name">
                                <td>
                                    <input type="checkbox" 
                                        v-model="recordData.items" 
                                        :value="invoice" 
                                        @change="updateTotalOutstanding" 
                                    />
                                </td>
                                <td>{{ invoice.name }}</td>
                                <td>{{ invoice.subscription }}</td>
                                <td>{{ invoice.subscription_plan }}</td>
                                <td>{{ invoice.invoice_month }}</td>
                                <td>{{ invoice.amount }}</td>
                                <td>{{ invoice.outstanding }}</td>
                                <td>{{ invoice.created_on }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- form-comments -->
        <form-comments></form-comments>
    </div>
</template>

<script>
import axios from 'axios';
import Awesomplete from 'awesomplete';
import FormComments from '/src/components/form/FormComments.vue';


export default {
    name: "PaymentEntry",
    components: {
        'form-comments': FormComments,
    },
    data() {
        return {
            recordId: null,
            tableName: 'payment',
            recordData: {
                customer: '',
                payment_date: new Date().toISOString().slice(0, 10),
                mode_of_payment: 'Cash',
                total_outstanding: 0,
                paid_amount: 0,
                docstatus: 0,
                payment_reference: '',
                items: []
            },
            outstandingInvoices: [],
            // selectedInvoices: [],
            showAmountError: false,
            oldData: {},
            commentInput: '',
            comments: [],
            newRecord: true,
            isSubmittable: true,
        };
    },
    computed: {
        totalOutstanding() {
            console.log('computed:totalOutstanding...');
            // console.log('selectedInvoices2: ', this.selectedInvoices);
            console.log('selectedInvoices2: ', this.recordData.items);
            return this.recordData.items.reduce((total, invoice) => total + parseFloat(invoice.outstanding),0).toFixed(2);
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
        console.log('***PaymentFound.created: ', this.recordData);
        this.recordId = this.$route.params.recordId;
        // this.tableName = this.$route.params.tableName;
        this.fetchRecordData();
        // this.fetchComments();
    },
    methods: {
        fetchOutstandingInvoices() {
            console.log('methods: fetchOutstandingInvoices...', this.recordData.customer);
            if (!this.recordData.customer) {
                this.outstandingInvoices = [];
                return;
            }

            axios
                .get(`/api/fetchOutstandingInvoices.php?customer=${this.recordData.customer}`)
                .then((response) => {
                    console.log('xxxxx: ', response);
                    this.outstandingInvoices = response.data.rows;
                    // this.selectedInvoices = [];
                    this.recordData.items = [];
                })
                .catch((error) => {
                    console.error('Error fetching outstanding invoices: ', error);
                });
        },
        validatePaidAmount() {
            console.log('methods: validatePaidAmount...');
            this.showAmountError = this.recordData.paid_amount > parseFloat(this.totalOutstanding);
        },
        updateTotalOutstanding() {
            console.log('methods: updateTotalOutstanding...');
            // this.showAmountError = false;
            // this.recordData.paid_amount = 0;
            // console.log('selectedInvoices1: ', this.selectedInvoices);
            console.log('selectedInvoices1: ', this.recordData.items);
            this.recordData.total_outstanding = this.totalOutstanding;
        },
        fetchCustomers() {
            const inputValue = this.recordData['customer'];
            axios.get(`/api/fetchData.php?table=customers&query=${inputValue}&limit=20&offset=0`)
                .then((response) => {
                    const customers = response.data.rows;
                    this.showAwesompleteDropdown('customer', customers);
                })
                .catch((error) => {
                    console.error('Error fetching customers: ', error);
                })
                .finally(() => {
                    // this.isLoading = false;
                });
        },
        showAwesompleteDropdown(inputId, items) {
            // Get the input element
            const inputElement = document.getElementById(inputId);

            // Create the awesomplete instance if not already created
            if (!inputElement.awesomplete) {
                inputElement.awesomplete = new Awesomplete(inputElement, {
                    minChars: 0,
                    maxItems: 20, // Display 20 items by default
                    autoFirst: true,
                });
            }

            // Set the list of items and open the dropdown
            inputElement.awesomplete.list = items.map(item => item.name);
            inputElement.awesomplete.evaluate();
            inputElement.awesomplete.open();
        },
        fetchRecordData() {
            console.log('***PaymentForm.methods.fetchRecordData');
            // Make an API call to fetch record data based on recordId and tableName
            axios.get(`/api/paymentEntry.php?table=${this.tableName}&recordId=${this.recordId}`)
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
            console.log('***PaymentForm.methods.saveRecord: ', this.recordData);
            // Make an API call to save the record data
            axios.post(`/api/paymentEntry.php?table=${this.tableName}&recordId=${this.recordId}`, this.recordData)
                .then((response) => {
                    // this.fetchRecordData(); // Fetch the updated record data
                    console.log('abababababa: ', response);
                    this.$router.push(`/desk/Edit/${this.tableName}/${response.data.rows[0].name}`);
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
}
</script>

<style>
.error-message {
  color: red;
  font-size: 12px;
  margin-top: 4px;
}
</style>