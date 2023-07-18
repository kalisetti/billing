<template>
    <div id="form-fields" class="row">
      <div class="col">
        <div class="form-group">
          <label for="plan-name">Plan Name</label>
          <input type="text" id="plan-name" data-fieldname="plan_name" class="form-control" 
            v-model="localRecordData.plan_name" 
            required
            >
        </div>
      </div>
      <div class="col">
        <div class="form-group">
          <label for="billing-interval">Billing Interval</label>
          <select id="billing-interval"  data-fieldname="billing_interval" class="form-select" aria-label="Billing Interval" 
            v-model="localRecordData.billing_interval"
            >
            <option selected>Month</option>
            <option>Year</option>
          </select>
        </div>
        <div class="form-group">
          <label for="cost">Cost</label>
          <input type="number" class="form-control" data-fieldname="cost" step="0.01" 
            v-model="localRecordData.cost"
            required
            >
        </div>
      </div>
    </div>
</template>

<script>
export default {
  name: 'SubscriptionPlan',
  props: ['recordData'],
  data() {
    return {
      localRecordData: { ...this.recordData },
    };
  },
  created() {
    console.log("> SubscriptionPlan.created: ", this.recordData);
  },
  // methods: {
  //   handleInputChange() {
  //     console.log('handleInputChange.formData: ', this.formData);
  //     this.$emit('input-change', this.recordData);
  //   }
  // }
  watch: {
    recordData(newData) {
      this.localRecordData = { ...newData };
    },
    localRecordData(newData) {
      this.$emit('input-change', { ...newData });
    },
  }
}
</script>
