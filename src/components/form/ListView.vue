<template>
    <div id="list-view">
        <header>
            <div class="d-flex justify-content-end">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Menu
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="#">Share</a></li>
                    </ul>
                </div>
                <button class="btn btn-primary ms-2" @click="refreshData">Refresh</button>
                <button class="btn btn-primary ms-2" @click="createNewRecord">New</button>
            </div>
        </header>

        <main>
            <div v-if="isLoading" class="loading-overlay">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <caption>List view results for table {{ tableName }}</caption>
                    <thead>
                        <tr>
                            <th v-for="column in columns" :key="column">{{ column }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="row in rows" :key="row.name" @click="editRecord(row)">
                            <td v-for="column in columns" :key="column">{{ row[column] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>

        <footer>
            <nav class="mt-3" aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item" :class="{ active: pageSize === 5 }" @click="changePageSize(5)">
                        <!-- <a class="page-link" href="#">5</a> -->
                        <span class="page-link">5</span>
                    </li>
                    <li class="page-item" :class="{ active: pageSize === 10 }" @click="changePageSize(10)">
                        <!-- <a class="page-link" href="#">10</a> -->
                        <span class="page-link">10</span>
                    </li>
                    <li class="page-item" :class="{ active: pageSize === 15 }" @click="changePageSize(15)">
                        <!-- <a class="page-link" href="#">15</a> -->
                        <span class="page-link">15</span>
                    </li>
                </ul>
            </nav>
        </footer>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "ListView",
    props: ['tableName'],
    data() {
        return {
            columns: [],
            rows: [],
            pageSize: 5,
            isLoading: false,
        }
    },
    created() {
        console.log('ListView.created.tableName', this.tableName);
        this.fetchData();
    },
    methods: {
        fetchData() {
            this.isLoading = true;
            // Make an API call to fetch the data from the specified table
            axios.get(`/api/fetchData.php?table=${this.tableName}&limit=${this.pageSize}`)
                .then((response) => {
                    this.columns = response.data.columns;
                    this.rows = response.data.rows;
                })
                .catch((error) => {
                    console.error('Error fetching data: ', error);
                })
                .finally(() => {
                    this.isLoading = false;
                })
        },
        refreshData() {
            // Refresh the data by making another API call
            this.fetchData();
        },
        createNewRecord() {
            // Handle creating a new record
            const newRecordId = `new-${this.tableName}`;
            this.$router.push(`/desk/Edit/${this.tableName}/${newRecordId}`);
        },
        editRecord(row) {
            const recordId = row.name;
            this.$router.push(`/desk/Edit/${this.tableName}/${recordId}`);
        },
        changePageSize(size) {
            console.log('changePagesize: ', size);
            this.pageSize = size;
            this.fetchData();
        },
    },
    watch: {
      tableName(newId) {
        console.log('newId', newId);
        this.fetchData();
      }
    }
};
</script>

<style scoped>
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9999;
}

.loading-overlay .spinner-border {
    color: #ffffff;
}

.page-link {
    cursor: pointer;
}

header {
    margin-bottom: 1rem;
}

table {
    white-space: nowrap;
}

thead {
    background-color: #b0c4de;
}
</style>